<?php

abstract class WorkerPrototype
{
    protected string $name;
    protected string $position;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getPosition(): string
    {
        return $this->position;
    }

    public function setPosition(string $position): void
    {
        $this->position = $position;
    }
}

class Developer extends WorkerPrototype
{
    protected string $position = 'Developer';
}

$developer = new Developer();
echo $developer->getPosition(); // Developer

$developer2 = clone $developer;
$developer2->setName('Boris');
echo $developer2->getName(); // Boris
echo $developer2->getPosition(); // Developer

$developer3 = clone $developer;
$developer3->setName('Anton');
echo $developer3->getName(); // Anton

