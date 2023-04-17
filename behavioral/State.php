<?php

interface State
{
    public function toNext(Task $task);

    public function getStatus();
}

class Task
{
    private State $state;

    public function getState(): State
    {
        return $this->state;
    }

    public function setState(State $state): void
    {
        $this->state = $state;
    }

    public static function make(): Task
    {
        $self = new self();
        $self->setState(new Created());
        return $self;
    }

    public function proceedToNext(): void
    {
        $this->state->toNext($this);
    }

    public function toString()
    {
        return $this->state->getStatus();
    }

}

class Created implements State
{

    public function toNext(Task $task)
    {
        $task->setState(new Process());
    }

    public function getStatus(): string
    {
        return 'Created';
    }
}

class Process implements State
{

    public function toNext(Task $task)
    {
        $task->setState(new Test());
    }

    public function getStatus(): string
    {
        return 'Process';
    }
}

class Test implements State
{

    public function toNext(Task $task)
    {
        $task->setState(new Done());
    }

    public function getStatus(): string
    {
        return 'Test';
    }
}

class Done implements State
{

    public function toNext(Task $task)
    {
    }

    public function getStatus(): string
    {
        return 'Done';
    }
}

$task = Task::make();

$task->proceedToNext();
$task->proceedToNext();

//echo $task->getState()->getStatus(); // Test
echo $task->toString(); // Test

// ----------- 2 -----------

interface WritingState
{
    public function write(string $words);
}

class UpperCase implements WritingState
{
    public function write(string $words)
    {
        echo strtoupper($words);
    }
}

class LowerCase implements WritingState
{
    public function write(string $words)
    {
        echo strtolower($words);
    }
}

class Defaulted implements WritingState
{
    public function write(string $words)
    {
        echo $words;
    }
}

class TextEditor
{
    protected $state;

    public function __construct(WritingState $state)
    {
        $this->state = $state;
    }

    public function setState(WritingState $state)
    {
        $this->state = $state;
    }

    public function type(string $words)
    {
        $this->state->write($words);
    }
}

$editor = new TextEditor(new Defaulted());

$editor->type('First line');

$editor->setState(new UpperCase());

$editor->type('Second line');
$editor->type('Third line');

$editor->setState(new LowerCase());

$editor->type('Fourth line');
$editor->type('Fifth line');

// Output:
// First line
// SECOND LINE
// THIRD LINE
// fourth line
// fifth line