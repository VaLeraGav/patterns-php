<?php

interface Worker
{
    public function countSalary(): int;
}

abstract class WorkerDecorator implements Worker
{
    public Worker $worker;

    public function __construct(Worker $worker)
    {
        $this->worker = $worker;
    }
}

class Developer implements Worker
{
    public function countSalary(): int
    {
        return 20 * 3000;
    }
}

class DeveloperOverTime extends WorkerDecorator
{
    public function countSalary(): int
    {
        return $this->worker->countSalary() + $this->worker->countSalary() * 0.2;
    }
}

$developer = new Developer();
$developerOverTime = new DeveloperOverTime($developer);

echo $developer->countSalary(); // 60000
echo $developerOverTime->countSalary(); // 72000

// ----------- 2 -----------

interface Coffee
{
    public function getCost();

    public function getDescription();
}

class SimpleCoffee implements Coffee
{
    public function getCost()
    {
        return 10;
    }

    public function getDescription()
    {
        return 'Simple coffee';
    }
}

class MilkCoffee implements Coffee
{
    protected $coffee;

    public function __construct(Coffee $coffee)
    {
        $this->coffee = $coffee;
    }

    public function getCost()
    {
        return $this->coffee->getCost() + 2;
    }

    public function getDescription()
    {
        return $this->coffee->getDescription() . ', milk';
    }
}

class WhipCoffee implements Coffee
{
    protected $coffee;

    public function __construct(Coffee $coffee)
    {
        $this->coffee = $coffee;
    }

    public function getCost()
    {
        return $this->coffee->getCost() + 5;
    }

    public function getDescription()
    {
        return $this->coffee->getDescription() . ', whip';
    }
}

$someCoffee = new SimpleCoffee();
echo $someCoffee->getCost(); // 10
echo $someCoffee->getDescription(); // Simple Coffee

$someCoffee = new MilkCoffee($someCoffee);
echo $someCoffee->getCost(); // 12
echo $someCoffee->getDescription(); // Simple Coffee, milk

$someCoffee = new WhipCoffee($someCoffee);
echo $someCoffee->getCost(); // 17
echo $someCoffee->getDescription(); // Simple Coffee, milk, whip


// ----------- 3 -----------

interface Booking
{
    public function calculatePrice(): int;

    public function getDescription(): string;
}

// Шаблон "Декоратор" хранит ссылку на объект (компонент), определяет его интерфейс и переадресует на него рабочие запросы.
abstract class BookingDecorator implements Booking
{
    public function __construct(protected Booking $booking)
    {
    }
}

// Системный компонент определяет интерфейс объекта, на который могут быть динамически возложены дополнительные обязанности
class DoubleRoomBooking implements Booking
{
    public function calculatePrice(): int
    {
        return 40;
    }

    public function getDescription(): string
    {
        return 'double room';
    }
}

// Конкретный компонент определяет объект, на который возлагаются дополнительные обязанности.
class ExtraBed extends BookingDecorator
{
    private const PRICE = 30;

    public function calculatePrice(): int
    {
        return $this->booking->calculatePrice() + self::PRICE;
    }

    public function getDescription(): string
    {
        return $this->booking->getDescription() . ' with extra bed';
    }
}

class WiFi extends BookingDecorator
{
    private const PRICE = 2;

    public function calculatePrice(): int
    {
        return $this->booking->calculatePrice() + self::PRICE;
    }

    public function getDescription(): string
    {
        return $this->booking->getDescription() . ' with wifi';
    }
}

$booking = new DoubleRoomBooking();

echo $booking->calculatePrice(); // 4
echo $booking->getDescription(); // double room

$booking2 = new DoubleRoomBooking();
$booking2 = new WiFi($booking2);

echo $booking2->calculatePrice(); // 42
echo $booking2->getDescription(); // double room with wifi

$booking3 = new DoubleRoomBooking();
$booking3 = new WiFi($booking);
$booking3 = new ExtraBed($booking);

echo $booking3->calculatePrice(); // 72
echo $booking3->getDescription(); // double room with wifi with extra bed