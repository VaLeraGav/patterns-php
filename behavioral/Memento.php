<?php

// Смотритель. Объект, который знает, почему и когда "Хозяин" должен сохранять и восстанавливать себя.
class Memento
{
    private State $state;

    public function __construct(State $state)
    {
        $this->state = $state;
    }

    public function getState(): State
    {
        return $this->state;
    }
}

// "Хранитель" сохраняет внутреннее состояние объекта "Хозяин".
class  State
{
    public const CREATED = 'created';
    public const PROCESS = 'process';
    public const TEST = 'test';
    public const DONE = 'done';

    private string $state;

    public function __construct(string $state)
    {
        $this->state = $state;
    }

    public function __toString(): string
    {
        return $this->state;
    }
}

// Хозяин. Объект, умеющий создавать "Хранителя", а также знающий, как восстановить свое внутреннее состояние из "Хранителя".
class Task
{
    private State $state;

    public function getState(): State
    {
        return $this->state;
    }

    public function create()
    {
        $this->state = new State(State::CREATED);
    }

    public function process()
    {
        $this->state = new State(State::PROCESS);
    }

    public function test()
    {
        $this->state = new State(State::TEST);
    }

    public function done()
    {
        $this->state = new State(State::DONE);
    }


    public function saveToMemento(): Memento
    {
        return new Memento($this->state);
    }

    public function restoreFromMemento(Memento $memento)
    {
        $this->state = $memento->getState();
    }
}

$task = new Task();
$task->create();

$memento = $task->saveToMemento();

print_r($task->getState() === $memento->getState()); // true

$task->test();
print_r($task->getState()); //     [state:State:private] => test

$task->restoreFromMemento($memento);
print_r($task->getState()); //     [state:State:private] => created
