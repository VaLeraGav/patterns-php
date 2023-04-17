<?php

class WorkerPool
{
    private array $freeWorkers = [];
    private array $busyWorkers = [];

    public function getFreeWorkers(): array
    {
        return $this->freeWorkers;
    }

    public function setFreeWorkers(array $freeWorkers): void
    {
        $this->freeWorkers = $freeWorkers;
    }

    public function getBusyWorkers(): array
    {
        return $this->busyWorkers;
    }

    public function setBusyWorkers(array $busyWorkers): void
    {
        $this->busyWorkers = $busyWorkers;
    }

    public function getWorker(): Worker
    {
        if (count($this->freeWorkers) === 0) {
            $worker = new Worker();
        } else {
            $worker = array_pop($this->freeWorkers);
        }

        $this->busyWorkers[spl_object_hash($worker)] = $worker;

        return $worker;
    }

    public function release(Worker $worker)
    {
        $key = spl_object_hash($worker);

        if (isset($this->busyWorkers[$key])) {
            unset($this->busyWorkers[$key]);
            $this->freeWorkers[$key] = $worker;
        }
    }
}

class Worker
{
    public function work()
    {
        printf('im working');
    }
}


$pool = new WorkerPool();

$worker1 = $pool->getWorker();
$worker2 = $pool->getWorker();
$worker3 = $pool->getWorker();

$pool->release($worker2);

//var_dump($pool);

var_dump($pool->getBusyWorkers());
//  array(2) {
//      '00000000000000020000000000000000' =>
//    class Worker#2 (0) {
//    }
//    '00000000000000040000000000000000' =>
//    class Worker#4 (0) {
//    }
//  }

var_dump($pool->getFreeWorkers());
//  array(1) {
//      '00000000000000030000000000000000' =>
//    class Worker#3 (0) {
//    }
//  }