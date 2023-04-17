<?php

class WorkerFacade
{
    private Developer $developer;
    private Designer $designer;

    public function __construct(Developer $developer, Designer $designer)
    {
        $this->developer = $developer;
        $this->designer = $designer;
    }

    public function startWork()
    {
        $this->developer->startDevelop();
        $this->designer->startDesign();
    }

    public function stopWork()
    {
        $this->developer->stopDevelop();
        $this->designer->stopDesign();
    }
}

class Developer
{
    public function startDevelop()
    {
        echo 'Start develop' . PHP_EOL;
    }

    public function stopDevelop()
    {
        echo 'Stop develop' . PHP_EOL;
    }
}

class Designer
{
    public function startDesign()
    {
        echo 'Start design' . PHP_EOL;
    }

    public function stopDesign()
    {
        echo 'Stop design' . PHP_EOL;
    }
}

$developer = new Developer();
$designer = new Designer();

$workerFacade = new WorkerFacade($developer, $designer);

$workerFacade->startWork();
$workerFacade->stopWork();