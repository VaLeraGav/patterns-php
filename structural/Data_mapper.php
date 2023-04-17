<?php

class Worker
{
    private string $name;

    public function getName(): string
    {
        return $this->name;
    }

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public static function make($args): Worker
    {
        return new self($args['name']);
    }
}

// Вытаскивать данные
class WorkerMapper
{
    private WorkerStorageAdapter $workerStorageAdapter;

    public function __construct(WorkerStorageAdapter $workerStorageAdapter)
    {
        $this->workerStorageAdapter = $workerStorageAdapter;
    }

    public function findById($id): string|Worker
    {
        $res = $this->workerStorageAdapter->find($id);
        if ($res === null) {
            return 'Worker with this id doesnt exists';
        }
        return $this->make($res);
    }

    private function make($args): Worker
    {
        return Worker::make($args);
    }
}

// Хранит данные
class WorkerStorageAdapter
{
    private array $data = [];

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function find($id)
    {
        if (isset($this->data[$id])) {
            return $this->data[$id];
        }
        return null;
    }
}

$data = [
    1 => [
        'name' => 'Boris'
    ],
    'test' => [
        'name' => 'Anton'
    ],
];

$workerStorageAdapter = new WorkerStorageAdapter($data);
$workerMapper = new WorkerMapper($workerStorageAdapter);

$worker = $workerMapper->findById(1);

echo $worker->getName(); // Boris