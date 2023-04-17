<?php

interface Worker
{
    public function work();
}

class Developer implements Worker
{
    public function work()
    {
        echo 'Im developing';
    }
}

class Designer implements Worker
{
    public function work()
    {
        echo 'Im designing';
    }
}

class WorkerFactory
{
    public static function make($workerTitle): ?Worker
    {
        $ClassName = strtoupper($workerTitle);

        if (class_exists($ClassName)) {
            return new $ClassName;
        }
        return null;
    }
}

$developer = WorkerFactory::make('developer');
$developer->work();

// ----------- 2 -----------

interface Formatter
{
    public function format(string $input): string;
}

class FormatString implements Formatter
{
    public function format(string $input): string
    {
        return $input;
    }
}

class FormatNumber implements Formatter
{
    public function format(string $input): string
    {
        return number_format((int)$input);
    }
}

final class StaticFactory
{
    public static function factory(string $type): FormatNumber|FormatString
    {
        return match ($type) {
            'number' => new FormatNumber(),
            'string' => new FormatString(),
            default => throw new InvalidArgumentException('Unknown format given'),
        };
    }
}

$example = StaticFactory::factory('number');
var_dump($example->format('it is string')); // 0