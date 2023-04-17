<?php

interface WorkerVisitor
{
    public function visitDeveloper(Worker $worker);

    public function visitDesigner(Worker $worker);
}

class RecorderVisitor implements WorkerVisitor
{
    private array $visited = [];

    public function getVisited(): array
    {
        return $this->visited;
    }

    public function visitDeveloper(Worker $developer)
    {
        $this->visited[] = $developer;
    }

    public function visitDesigner(Worker $designer)
    {
        $this->visited[] = $designer;
    }
}

interface Worker
{
    public function work();

    public function accept(WorkerVisitor $visitor);
}

class Developer implements Worker
{

    public function work()
    {
        echo 'developer is working';
    }

    public function accept(WorkerVisitor $visitor)
    {
        $visitor->visitDeveloper($this);
    }
}

class Designer implements Worker
{

    public function work()
    {
        echo 'designer is working';
    }

    public function accept(WorkerVisitor $visitor)
    {
        $visitor->visitDesigner($this);
    }
}

$visitor = new RecorderVisitor();
$developer = new Developer();
$designer = new Designer();

$developer->accept($visitor);
$designer->accept($visitor);

foreach ($visitor->getVisited() as $worker) {
    $worker->work();
    // developer is workingde
    // signer is working
}