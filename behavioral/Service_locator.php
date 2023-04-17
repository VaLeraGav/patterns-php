<?php

interface Service
{

}

class ServiceLocator
{
    /**
     * @var string[][]
     */
    private array $services = [];

    /**
     * @var Service[]
     */
    private array $instantiated = [];

    public function addInstance(string $class, Service $service)
    {
        $this->instantiated[$class] = $service;
    }

    public function addClass(string $class, array $params)
    {
        $this->services[$class] = $params;
    }

    public function has(string $interface): bool
    {
        return isset($this->services[$interface]) || isset($this->instantiated[$interface]);
    }

    public function get(string $class): Service
    {
        if (isset($this->instantiated[$class])) {
            return $this->instantiated[$class];
        }

        $object = new $class(...$this->services[$class]);

        if (!$object instanceof Service) {
            throw new InvalidArgumentException('Could not register service: is no instance of Service');
        }

        $this->instantiated[$class] = $object;

        return $object;
    }
}

class LogService implements Service
{
    public function getName() {
        echo 'LogService';
    }

}

class Cache implements Service
{

}


$serviceLocator = new ServiceLocator();
$serviceLocator->addInstance(LogService::class, new LogService());

var_dump($serviceLocator->has(LogService::class)); // True
var_dump($serviceLocator->has(Cache::class)); // False

$logger = $serviceLocator->get('LogService');
$logger->getName(); // LogService