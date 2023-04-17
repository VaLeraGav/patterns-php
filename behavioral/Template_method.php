<?php

abstract class Task
{
    public function printSections() // build
    {
        $this->printHeader();
        $this->printBody();
        $this->printFooter();
        $this->printCustom();
    }

    private function printHeader()
    {
        echo 'Header' . PHP_EOL;
    }

    private function printBody()
    {
        echo 'Body' . PHP_EOL;
    }

    private function printFooter()
    {
        echo 'Footer' . PHP_EOL;
    }

    abstract protected function printCustom();
}

class DeveloperTask extends Task
{
    protected function printCustom()
    {
        echo 'for developer' . PHP_EOL;
    }
}

class DesignerTask extends Task
{
    protected function printCustom()
    {
        echo 'for designer' . PHP_EOL;
    }
}

$developerTask = new DeveloperTask();
$designerTask = new DesignerTask();

$designerTask->printSections();
// Header
// Body
// Footer
// for designer

// ----------- 2 -----------

abstract class Journey
{
    /**
     * @var string[]
     */
    private array $thingsToDo = [];

    /**
     * Это общедоступная услуга, предоставляемая этим классом и его подклассами.
     * Обратите внимание, что окончательно "заморозить" глобальное поведение алгоритма невозможно.
     * Если вы хотите переопределить этот контракт, создайте интерфейс только с takeATrip()
     * и отнесите его к подклассу.
     *
     * Вызываем именно родительский метод
     */
    final public function takeATrip(): void
    {
        $this->thingsToDo[] = $this->buyAFlight();
        $this->thingsToDo[] = $this->takePlane();
        $this->thingsToDo[] = $this->enjoyVacation();
        $buyGift = $this->buyGift();

        if ($buyGift !== null) {
            $this->thingsToDo[] = $buyGift;
        }

        $this->thingsToDo[] = $this->takePlane();
    }

    /**
     * Этот метод должен быть реализован, это ключевая особенность этого шаблона
     */
    abstract protected function enjoyVacation(): string;

    /**
     * Этот метод также является частью алгоритма, но он необязателен.
     * Вы можете переопределить его, только если вам нужно
     */
    protected function buyGift(): ?string
    {
        return null;
    }

    private function buyAFlight(): string
    {
        return 'Buy a flight ticket';
    }

    private function takePlane(): string
    {
        return 'Taking the plane';
    }

    /**
     * @return string[]
     */
    final public function getThingsToDo(): array
    {
        return $this->thingsToDo;
    }
}

class BeachJourney extends Journey
{
    protected function enjoyVacation(): string
    {
        return "Swimming and sun-bathing";
    }
}

class CityJourney extends Journey
{
    protected function enjoyVacation(): string
    {
        return "Eat, drink, take photos and sleep";
    }

    protected function buyGift(): ?string
    {
        return "Buy a gift";
    }
}

$beachJourney = new BeachJourney();
$beachJourney->takeATrip();

print_r($beachJourney->getThingsToDo());
//    [0] => Buy a flight ticket
//    [1] => Taking the plane
//    [2] => Swimming and sun-bathing
//    [3] => Taking the plane

$cityJourney = new CityJourney();
$cityJourney->takeATrip();

print_r($cityJourney->getThingsToDo());
//    [0] => Buy a flight ticket
//    [1] => Taking the plane
//    [2] => Eat, drink, take photos and sleep
//    [3] => Buy a gift
//    [4] => Taking the plane
