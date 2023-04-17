<?php

abstract class Handler
{
    private ?Handler $successor;

    public function __construct(?Handler $successor)
    {
        $this->successor = $successor;
    }

    /**
     * Этот подход с использованием шаблонного метода pattern гарантирует вам, что
     * каждый подкласс не забудет вызвать преемника
     */
    final public function handle(TaskInterface $task): ?array
    {
        // запрос не был обработан этим обработчиком => смотрите следующий
        $proceed = $this->processing($task);
        if ($proceed === null && $this->successor) {
            $proceed = $this->successor->handle($task);
        }
        return $proceed;
    }

    abstract public function processing(TaskInterface $task): ?array;
}

interface TaskInterface
{
    public function getArray(): array;
}

class DevTask implements TaskInterface
{
    private array $arr = [1, 2, 3,];
    public function getArray(): array
    {
        return $this->arr;
    }
}

class Senior extends Handler
{
    public function processing(TaskInterface $task): ?array
    {
        return $task->getArray();
    }
}

class Middle extends Handler
{
    public function processing(TaskInterface $task): ?array
    {
        return null;
    }
}

class Jun extends Handler
{
    public function processing(TaskInterface $task): ?array
    {
        return null;
    }
}

// 1
$task = new DevTask();

$senior = new Senior(null);
$middle = new Middle($senior);
$jun = new Jun($middle);
print_r($jun->handle($task)); // вернет массив

// 2
$task2 = new DevTask();

$jun = new Jun(null);
print_r($jun->handle($task2)); // НЕ вернет массив