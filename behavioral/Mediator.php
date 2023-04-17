<?php

interface Mediator
{
    public function getWorker();
}

abstract class Worker
{
    private string $position;

    public function __construct(string $position)
    {
        $this->position = $position;
    }

    public function sayHello()
    {
        echo 'Hello';
    }

    public function work(): string
    {
        return $this->position . ' is working';
    }
}

class InfoBase
{
    public function printInfo(Worker $worker)
    {
        echo $worker->work();
    }
}

class WorkerInfoBaseMediator implements Mediator
{
    private Worker $worker;
    private InfoBase $infoBase;

    public function __construct(Worker $worker, InfoBase $infoBase)
    {
        $this->worker = $worker;
        $this->infoBase = $infoBase;
    }

    public function getWorker()
    {
        $this->infoBase->printInfo($this->worker);
    }
}

class Developer extends Worker
{

}

class Designer extends Worker
{

}

$developer = new Developer('developer middle');
$designer = new Designer('designer senior');
$infoBase = new InfoBase();
$workerInfoBaseMediator = new WorkerInfoBaseMediator($developer, $infoBase);
$workerInfoBaseMediator->getWorker(); // designer middle is working

$workerInfoBaseMediator2 = new WorkerInfoBaseMediator($designer, $infoBase);
$workerInfoBaseMediator2->getWorker(); // designer senior is working

// ----------- 2 -----------

interface ChatRoomMediator
{
    public function showMessage(User $user, string $message);
}

// Mediator
class ChatRoom implements ChatRoomMediator
{
    public function showMessage(User $user, string $message)
    {
        $time = date('M d, y H:i');
        $sender = $user->getName();

        echo $time . '[' . $sender . ']:' . $message;
    }
}

class User
{
    protected $name;
    protected $chatMediator;

    public function __construct(string $name, ChatRoomMediator $chatMediator)
    {
        $this->name = $name;
        $this->chatMediator = $chatMediator;
    }

    public function getName()
    {
        return $this->name;
    }

    public function send($message)
    {
        $this->chatMediator->showMessage($this, $message);
    }
}

$mediator = new ChatRoom();

$john = new User('John Doe', $mediator);
$jane = new User('Jane Doe', $mediator);

$john->send('Hi there!');
$jane->send('Hey!');

// Output will be
// Feb 14, 10:58 [John]: Hi there!
// Feb 14, 10:58 [Jane]: Hey!