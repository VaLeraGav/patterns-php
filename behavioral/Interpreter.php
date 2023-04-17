<?php

abstract class Expression
{
    abstract public function interpret(Context $context): bool;
}

class Context
{
    private array $workers = [];

    public function setWorkers(string $worker): void
    {
        $this->workers[] = $worker;
    }

    public function lookUp($key): string|bool
    {
        if (isset($this->workers[$key])) {
            return $this->workers[$key];
        }
        return false;
    }
}

class VariableExp extends Expression
{
    private int $key;

    public function __construct(int $key)
    {
        $this->key = $key;
    }

    public function interpret(Context $context): bool
    {
        return $context->lookUp($this->key);
    }
}

class AndExp extends Expression
{
    private int $keyOne;
    private int $keyTwo;

    public function __construct(int $keyOne, int $keyTwo)
    {
        $this->keyOne = $keyOne;
        $this->keyTwo = $keyTwo;
    }

    public function interpret(Context $context): bool
    {
        return $context->lookUp($this->keyOne) && $context->lookUp($this->keyTwo);
    }
}

class OrExp extends Expression
{
    private int $keyOne;
    private int $keyTwo;

    public function __construct(int $keyOne, int $keyTwo)
    {
        $this->keyOne = $keyOne;
        $this->keyTwo = $keyTwo;
    }

    public function interpret(Context $context): bool
    {
        return $context->lookUp($this->keyOne) || $context->lookUp($this->keyTwo);
    }
}

$context = new Context();
$context->setWorkers('Bob');
$context->setWorkers('Jon');

$varExp = new VariableExp(0);
$andExp = new AndExp(1, 3);
$orExp = new OrExp(1, 3);

var_dump($varExp->interpret($context)); // true
var_dump($andExp->interpret($context)); // false
var_dump($orExp->interpret($context)); // true
