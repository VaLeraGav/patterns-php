<?php

// Определите абстрактный класс с интерфейсом, включающим отдельный метод для каждого из классов, экземпляры которых
//   должны быть созданы.
interface Worker
{
    public function work();
}

// Для каждого семейства создайте конкретные классы, производные отданного абстрактного класса.
interface DeveloperWorker extends Worker
{

}

interface DesignerWorker extends Worker
{

}

class NativeDeveloperWorker implements DeveloperWorker
{
    public function work()
    {
        echo 'Im developing as native';
    }
}

class OutsourceDeveloperWorker implements DeveloperWorker
{
    public function work()
    {
        echo 'Im developing as outsource';
    }
}

class NativeDesignerWorker implements DesignerWorker
{
    public function work()
    {
        echo 'Im designer as native';
    }
}

class OutsourceDesignerWorker implements DesignerWorker
{
    public function work()
    {
        echo 'Im designer as outsource';
    }
}

// Однозначно идентифицируйте правила создания экземпляров.
interface AbstractFactory
{
    public static function makeDeveloperWorker(): DeveloperWorker;

    public static function makeDesignerWorker(): DesignerWorker;
}

// Использующий экземпляры объект должен обращаться к "Абстрактной фабрике" для создания требуемых экземпляров.
class OutsourceWorkerFactory implements AbstractFactory
{
    public static function makeDeveloperWorker(): DeveloperWorker
    {
        return new OutsourceDeveloperWorker();
    }

    public static function makeDesignerWorker(): DesignerWorker
    {
        return new OutsourceDesignerWorker();
    }
}

class NativeWorkerFactory implements AbstractFactory
{
    public static function makeDeveloperWorker(): DeveloperWorker
    {
        return new NativeDeveloperWorker();
    }

    public static function makeDesignerWorker(): DesignerWorker
    {
        return new NativeDesignerWorker();
    }
}

$nativeDeveloper = NativeWorkerFactory::makeDeveloperWorker();
$outsourceDeveloper = OutsourceWorkerFactory::makeDeveloperWorker();
$nativeDesigner = NativeWorkerFactory::makeDesignerWorker();
$outsourceDesigner = OutsourceWorkerFactory::makeDesignerWorker();

$nativeDesigner->work(); // Im designer as native
$outsourceDeveloper->work(); // Im developing as outsource

// ----------- 2 -----------

interface Door
{
    public function getDescription();
}

class WoodenDoor implements Door
{
    public function getDescription()
    {
        echo 'Я деревянная дверь';
    }
}

class IronDoor implements Door
{
    public function getDescription()
    {
        echo 'Я железная дверь';
    }
}

interface DoorFittingExpert
{
    public function getDescription();
}

class Welder implements DoorFittingExpert
{
    public function getDescription()
    {
        echo 'Я работаю только с железными дверьми';
    }
}

class Carpenter implements DoorFittingExpert
{
    public function getDescription()
    {
        echo 'Я работаю только с деревянными дверьми';
    }
}

interface DoorFactory
{
    public function makeDoor(): Door;

    public function makeFittingExpert(): DoorFittingExpert;
}

// Деревянная фабрика вернет деревянную дверь и столяра
class WoodenDoorFactory implements DoorFactory
{
    public function makeDoor(): Door
    {
        return new WoodenDoor();
    }

    public function makeFittingExpert(): DoorFittingExpert
    {
        return new Carpenter();
    }
}

// Железная фабрика вернет железную дверь и сварщика
class IronDoorFactory implements DoorFactory
{
    public function makeDoor(): Door
    {
        return new IronDoor();
    }

    public function makeFittingExpert(): DoorFittingExpert
    {
        return new Welder();
    }
}

$woodenFactory = new WoodenDoorFactory();

$door = $woodenFactory->makeDoor();
$expert = $woodenFactory->makeFittingExpert();

$door->getDescription();  // Вывод: Я деревянная дверь
$expert->getDescription(); // Вывод: Я работаю только с деревянными дверями

// Аналогично для железной двери
$ironFactory = new IronDoorFactory();

$door = $ironFactory->makeDoor();
$expert = $ironFactory->makeFittingExpert();

$door->getDescription();  // Вывод: Я железная дверь
$expert->getDescription(); // Вывод: Я работаю только с железными дверями