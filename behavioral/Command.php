<?php

interface Command
{
    public function execute();
}

interface Undoable extends Command
{
    public function undo();
}

class Output
{
    private bool $isEnable = true;
    private string $body = '';

    public function getBody(): string
    {
        return $this->body;
    }

    public function enable(): void
    {
        $this->isEnable = true;
    }

    public function disable(): void
    {
        $this->isEnable = false;
    }

    public function write($str): void
    {
        if ($this->isEnable) {
            $this->body = $str;
        }
    }
}

class Invoker
{
    private Command $command;

    public function setCommand(Command $command): void
    {
        $this->command = $command;
    }

    public function run()
    {
        $this->command->execute();
    }
}

class  Message implements Command
{
    private Output $output;

    public function __construct(Output $output)
    {
        $this->output = $output;
    }

    public function execute()
    {
        $this->output->write('some string from execute');
    }
}

class ChangerStatus implements Undoable
{
    private Output $output;

    public function __construct(Output $output)
    {
        $this->output = $output;
    }

    public function execute()
    {
        $this->output->enable();
    }

    public function undo()
    {
        $this->output->disable();
    }
}

$output = new Output();
$invoker = new Invoker();

$message = new Message($output);

// выключатель
$changerStatus = new ChangerStatus($output);
//$changerStatus->undo(); // выключает
//$changerStatus->execute(); // выключает

$message->execute();
echo $output->getBody(); // some string from execute

// ----------- 2 -----------

class Bulb
{
    public function turnOn(): void
    {
        echo "Bulb has been lit";
    }

    public function turnOff(): void
    {
        echo "Darkness!";
    }
}

interface CommandNew
{
    public function execute();

    public function undo();

    public function redo();
}

// Command
class TurnOn implements CommandNew
{
    protected $bulb;

    public function __construct(Bulb $bulb)
    {
        $this->bulb = $bulb;
    }

    public function execute()
    {
        $this->bulb->turnOn();
    }

    public function undo()
    {
        $this->bulb->turnOff();
    }

    public function redo()
    {
        $this->execute();
    }
}

class TurnOff implements CommandNew
{
    protected $bulb;

    public function __construct(Bulb $bulb)
    {
        $this->bulb = $bulb;
    }

    public function execute()
    {
        $this->bulb->turnOff();
    }

    public function undo()
    {
        $this->bulb->turnOn();
    }

    public function redo()
    {
        $this->execute();
    }
}

// Invoker
class RemoteControl
{
    public function submit(CommandNew $command): void
    {
        $command->execute();
    }
}

$bulb = new Bulb();

$turnOn = new TurnOn($bulb);
$turnOff = new TurnOff($bulb);

$remote = new RemoteControl();
$remote->submit($turnOn); // Bulb has been lit!
$remote->submit($turnOff); // Darkness!

// ----------- 3 -----------

interface CommandThree
{
    /**
     * это самый важный метод в шаблоне команд,
     * Приемник передается в конструктор.
     */
    public function execute();
}

interface UndoableCommand extends CommandThree
{
    /**
     * Этот метод используется для отмены изменений, внесенных при выполнении команды
     */
    public function undo();
}

/**
 * Эта конкретная команда вызывает "печать" на приемнике, но внешний
 * вызывающий просто знает, что он может вызвать "execute"
 */
class HelloCommand implements CommandThree
{
    /**
     * Каждая конкретная команда создается с использованием разных приемников.
     * Приемников может быть один, много или полностью отсутствовать, но в параметрах могут быть и другие команды
     */
    public function __construct(private Receiver $output)
    {
    }

    /**
     * выполните и выведите "Hello World".
     */
    public function execute()
    {
        // иногда приемника нет, и это команда, которая выполняет всю работу
        $this->output->write('Hello World');
    }
}

/**
 * Эта конкретная команда настраивает приемник для добавления текущей даты в сообщения
 * вызывающий просто знает, что он может вызвать "execute"
 */
class AddMessageDateCommand implements UndoableCommand
{
    public function __construct(private Receiver $output)
    {
    }

    /**
     * Выполните и сделайте получателя, чтобы включить отображение даты сообщений.
     */
    public function execute()
    {
        // иногда приемника нет, и это команда, которая выполняет всю работу
        $this->output->enableDate();
    }

    /**
     * Отмените команду и сделайте так, чтобы получатель отключил отображение даты сообщений.
     */
    public function undo()
    {
        // иногда приемника нет, и это команда, которая выполняет всю работу
        $this->output->disableDate();
    }
}

/**
 * Получатель - это конкретная услуга со своим собственным контрактом, и она может быть только конкретной.
 */
class Receiver
{
    private bool $enableDate = false;

    /**
     * @var string[]
     */
    private array $output = [];

    public function write(string $str)
    {
        if ($this->enableDate) {
            $str .= ' [' . date('Y-m-d') . ']';
        }

        $this->output[] = $str;
    }

    public function getOutput(): string
    {
        return join("\n", $this->output);
    }

    /**
     * Включить отображение получателем даты сообщения
     */
    public function enableDate()
    {
        $this->enableDate = true;
    }

    /**
     * Отключите приемник для отображения даты сообщения
     */
    public function disableDate()
    {
        $this->enableDate = false;
    }
}

/**
 * Вызывающий использует данную ему команду.
 */
class InvokerThree
{
    private CommandThree $command;

    /**
     * в вызывающем устройстве мы находим такой метод для подписки на команду
     * Также может быть стек, список, фиксированный набор...
     */
    public function setCommand(CommandThree $cmd)
    {
        $this->command = $cmd;
    }

    /**
     * выполняет команду; вызывающий тот же самый, какой бы ни была команда
     */
    public function run()
    {
        $this->command->execute();
    }
}

$invoker = new InvokerThree();
$receiver = new Receiver();

$invoker->setCommand(new HelloCommand($receiver));
$invoker->run();
echo $receiver->getOutput(); // Hello World

$messageDateCommand = new AddMessageDateCommand($receiver);
$messageDateCommand->execute();

$invoker->run();
echo $receiver->getOutput(); // "Hello World Hello World [" . date('Y-m-d') . ']

$messageDateCommand->undo();

$invoker->run();
echo $receiver->getOutput(); // Hello World Hello World [" . date('Y-m-d') . "] Hello World