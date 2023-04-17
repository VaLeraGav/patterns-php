<?php

abstract class Registry
{
    private static $properties = [];

    public static function setProperty($name, $value)
    {
        self::$properties[$name] = $value;
    }

    public static function getProperty($name)
    {
        if (isset(self::$properties[$name])) {
            return self::$properties[$name];
        }
        return null;
    }

    public static function getProperties()
    {
        return self::$properties;
    }
}


class Service
{
    public function getGroup()
    {
        echo 'Service';
    }
}

class Service2
{
    public function getName()
    {
        echo 'Service2';
    }
}

$service = new Service();
Registry::setProperty('test', $service);
$testService = Registry::getProperty('test');
$testService->getGroup(); // Service

$service2 = new Service2();
Registry::setProperty('test2', $service2);
$testService2 = Registry::getProperty('test2');
$testService2->getName(); // Service2
