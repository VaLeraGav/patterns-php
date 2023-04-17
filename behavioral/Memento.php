<?php

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

// ----------- 2 -----------

class EditorMemento
{
    protected $content;

    public function __construct(string $content)
    {
        $this->content = $content;
    }

    public function getContent()
    {
        return $this->content;
    }
}

class Editor
{
    protected $content = '';

    public function type(string $words)
    {
        $this->content = $this->content . ' ' . $words;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function save()
    {
        return new EditorMemento($this->content);
    }

    public function restore(EditorMemento $memento)
    {
        $this->content = $memento->getContent();
    }
}

$editor = new Editor();

// Type some stuff
$editor->type('This is the first sentence.');
$editor->type('This is second.');

// Save the state to restore to : This is the first sentence. This is second.
$saved = $editor->save();

// Type some more
$editor->type('And this is third.');

// Output: Content before Saving
echo $editor->getContent(); // This is the first sentence. This is second. And this is third.

// Restoring to last saved state
$editor->restore($saved);

//$editor->getContent(); // This is the first sentence. This is second.