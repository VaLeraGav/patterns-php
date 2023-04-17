<?php

class Connection
{
    private static ?self $instance = null;
    private static string $name;

    private function __construct()
    {
        // Hide the constructor
    }

    public static function getName(): string
    {
        return self::$name;
    }

    public static function setName(string $name): void
    {
        self::$name = $name;
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __clone(): void
    {
        throw new \Exception("Cannot clone a Singleton.");
    }

    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize a Singleton.");
    }
}

$connection1 = Connection::getInstance();
$connection1::setName('Laravel');

$connection2 = Connection::getInstance();
$connection2::setName('Symphony');

var_dump($connection1 === $connection2); // true
var_dump($connection1::getName()); // Symphony
var_dump($connection2::getName()); // Symphony