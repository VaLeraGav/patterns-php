<?php

// 1. Необходимо унифицировать интерфейсы всех создаваемых объектов.
interface Interviewer
{
    public function askQuestions();
}

// Классы, которые порождаются от Interviewer
class Developer implements Interviewer
{
    public function askQuestions()
    {
        echo 'Спрашивает про шаблоны проектирования!';
    }
}

class CommunityExecutive implements Interviewer
{
    public function askQuestions()
    {
        echo 'Спрашивает о работе с сообществом';
    }
}

// 2. В классе, который производит продукты, создайте пустой "Фабричный метод".
// В качестве возвращаемого типа укажите общий интерфейс продукта.
abstract class HiringManager
{
    // Фабричный метод
    abstract public function makeInterviewer(): Interviewer;

    public function takeInterview(): void
    {
        $interviewer = $this->makeInterviewer();
        $interviewer->askQuestions();
    }
}

// 3. Переопределите "Фабричный метод" в подклассах, перемещая туда создание соответствующих продуктов.
// Теперь любой дочерний класс может расширять его и предоставлять необходимого интервьюера
class DevelopmentManager extends HiringManager
{
    public function makeInterviewer(): Interviewer
    {
        return new Developer();
    }
}

class MarketingManager extends HiringManager
{
    public function makeInterviewer(): Interviewer
    {
        return new CommunityExecutive();
    }
}

$devManager = new DevelopmentManager();
$devManager->takeInterview(); // Вывод: Спрашивает о шаблонах проектирования!

$marketingManager = new MarketingManager();
$marketingManager->takeInterview(); // Вывод: Спрашивает о работе с сообществом