<?php

interface Definer
{
    public function define($arg): string;
}

class Data
{
    private Definer $definer;
    private int|string|bool $arg;

    public function setArg(bool|int|string $arg): void
    {
        $this->arg = $arg;
    }

    public function __construct(Definer $definer)
    {
        $this->definer = $definer;
    }

    public function executeStrategy(): string
    {
        return $this->definer->define($this->arg);
    }
}

class StringDefiner implements Definer
{
    public function define($arg): string
    {
        return $arg . ' | from string strategy';
    }
}

class BoolDefiner implements Definer
{
    public function define($arg): string
    {
        return $arg . ' _ from bool strategy';
    }
}

$data = new Data(new BoolDefiner());
$data->setArg('Some arg from first');
echo $data->executeStrategy(); // Some arg from first _ from string strategy

$data2 = new Data(new StringDefiner());
$data2->setArg('Some arg from first');
echo $data2->executeStrategy(); // Some arg from first | from string strategy

// ----------- 2 -----------

class Context
{
    public function __construct(private Comparator $comparator)
    {
    }

    public function executeStrategy(array $elements): array
    {
        uasort($elements, [$this->comparator, 'compare']);
        return $elements;
    }
}

// Создать интерфейс (стратегию), описывающий этот алгоритм.
interface Comparator
{
    public function compare($a, $b): int;
}

// Поместить каждый алгоритм в собственный класс.
class DateComparator implements Comparator
{
    // Определить алгоритмы, который подвержены частым изменениям
    public function compare($a, $b): int
    {
        $aDate = new DateTime($a['date']);
        $bDate = new DateTime($b['date']);

        return $aDate <=> $bDate;
    }
}

class IdComparator implements Comparator
{
    public function compare($a, $b): int
    {
        return $a['id'] <=> $b['id'];
    }
}

$collection = [
    ['id' => 2],
    ['id' => 1],
    ['id' => 3]
];
$obj = new Context(new IdComparator());
$elements = $obj->executeStrategy($collection);

$firstElement = array_shift($elements);
print_r($firstElement); // [id] => 1

$collection2 = [
    ['date' => '2014-03-03'],
    ['date' => '2015-03-02'],
    ['date' => '2013-03-01']
];
$obj = new Context(new DateComparator());
$elements = $obj->executeStrategy($collection2);

$firstElement = array_shift($elements);
print_r($firstElement); //  ['date' => '2013-03-01'],