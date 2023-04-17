<?php

/**
 * Пользователь реализует наблюдаемый объект (называемый субъектом), он ведет список наблюдателей и отправляет уведомления
 * им в случае внесения изменений в пользовательский объект.
 */
class Worker implements SplSubject
{
    private SplObjectStorage $observers;
    private string $name = '';

    public function __construct()
    {
        $this->observers = new SplObjectStorage();
    }

    public function attach(SplObserver $observer): void
    {
        $this->observers->attach($observer);
    }

    public function detach(SplObserver $observer): void
    {
        $this->observers->detach($observer);
    }

    public function changeName($name)
    {
        $this->name = $name;
        $this->notify();
    }

    public function notify(): void
    {
        foreach ($this->observers as $observer) {
            $observer->update($this);
        }
    }
}

class WorkerObserver implements SplObserver
{
    private array $workers = [];

    public function getWorkers(): array
    {
        return $this->workers;
    }

    /**
     * Он вызывается субъектом, обычно с помощью SplSubject::notify()
     */
    public function update(SplSubject $subject): void
    {
        $this->workers[] = clone $subject;
    }
}

$observer = new WorkerObserver();

$worker = new Worker();

$worker->attach($observer);

$worker->changeName('Boris');
$worker->changeName('Bob');

var_dump(count($observer->getWorkers())); // 2

//$observer->update($worker);
// var_dump($observer->getWorkers()); // 3