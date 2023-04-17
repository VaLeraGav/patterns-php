<?php

interface Renderable
{
    public function render(): string;
}

// Составной узел ДОЛЖЕН продлить контракт компонента. Это обязательно для построения дерево компонентов.
class Mail implements Renderable
{
    private array $parts = [];

    public function render(): string
    {
        // print_r($this->parts);
        $result = '';
        foreach ($this->parts as $part) {
            $result .= $part->render();
        }
        return $result;
    }

    public function addPart(Renderable $part)
    {
        $this->parts[] = $part;
    }
}

abstract class Part
{
    private string $text;

    public function getText(): string
    {
        return $this->text . PHP_EOL;
    }

    public function __construct(string $text)
    {
        $this->text = $text;
    }
}

class Header extends Part implements Renderable
{
    public function render(): string
    {
        return $this->getText();
    }
}

class Body extends Part implements Renderable
{
    public function render(): string
    {
        return $this->getText();
    }
}

class Footer extends Part implements Renderable
{
    public function render(): string
    {
        return $this->getText();
    }
}

$mail = new Mail();

$mail->addPart(new Header('Header'));
$mail->addPart(new Body('Body'));
$mail->addPart(new Footer('Footer'));

echo $mail->render();
// Header
// Body
// Footer

// ----------- 2 -----------

interface Employee
{
    public function __construct(string $name, float $salary);

    public function getName(): string;

    public function setSalary(float $salary);

    public function getSalary(): float;

}

class Developer implements Employee
{
    protected $salary;
    protected $name;

    public function __construct(string $name, float $salary)
    {
        $this->name = $name;
        $this->salary = $salary;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setSalary(float $salary)
    {
        $this->salary = $salary;
    }

    public function getSalary(): float
    {
        return $this->salary;
    }
}

class Designer implements Employee
{
    protected $salary;
    protected $name;

    public function __construct(string $name, float $salary)
    {
        $this->name = $name;
        $this->salary = $salary;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setSalary(float $salary)
    {
        $this->salary = $salary;
    }

    public function getSalary(): float
    {
        return $this->salary;
    }
}

class Organization
{
    protected $employees;

    public function addEmployee(Employee $employee)
    {
        $this->employees[] = $employee;
    }

    public function getNetSalaries(): float
    {
        $netSalary = 0;

        foreach ($this->employees as $employee) {
            $netSalary += $employee->getSalary();
        }

        return $netSalary;
    }
}

$john = new Developer('John Doe', 12000);
$jane = new Designer('Jane', 10000);

$organization = new Organization();
$organization->addEmployee($john);
$organization->addEmployee($jane);

echo "Net salaries: " . $organization->getNetSalaries(); // Net Salaries: 22000
