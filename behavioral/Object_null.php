<?php

interface Worker
{
    public function work();
}

class ObjectManager
{
    private Worker $worker;

    public function __construct(Worker $worker)
    {
        $this->worker = $worker;
    }

    public function goWork()
    {
        $this->worker->work();
    }
}

class Developer implements Worker
{
    public function work()
    {
        echo 'Developer is working';
    }
}

class NullWorker implements Worker
{
    public function work()
    {
        // do nothing
    }
}

$developer = new Developer();
$nullableDeveloper = new NullWorker();

$objectManager = new ObjectManager($nullableDeveloper);
$objectManager->goWork(); //

$objectManager = new ObjectManager($developer);
$objectManager->goWork(); // Developer is working
