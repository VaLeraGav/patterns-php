<?php

class Worker
{
    private string $name;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }
}

class WorkerFactory
{
    public static function make(): Worker
    {
        return new Worker();
    }
}

$worker = WorkerFactory::make();
$worker->setName('Jon');
var_dump($worker->getName());


// ----------- 2 -----------
interface Door
{
    public function getWidth(): float;

    public function getHeight(): float;
}

class WoodenDoor implements Door
{
    protected float $width;
    protected float $height;

    public function __construct(float $width, float $height)
    {
        $this->width = $width;
        $this->height = $height;
    }

    public function getWidth(): float
    {
        return $this->width;
    }

    public function getHeight(): float
    {
        return $this->height;
    }
}

class DoorFactory
{
    public static function makeDoor($width, $height): Door
    {
        return new WoodenDoor($width, $height);
    }
}


$door = DoorFactory::makeDoor(100, 200);
echo 'Width: ' . $door->getWidth(); // 100
echo 'Height: ' . $door->getHeight(); // 200