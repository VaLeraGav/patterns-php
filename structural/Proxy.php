<?php

interface Worker
{
    public function closedHours($hours);

    public function countSalary(): int;
}

class WorkerOutsource implements Worker
{
    private array $hours = [];

    public function closedHours($hours)
    {
        $this->hours[] = $hours;
    }

    public function countSalary(): int
    {
        return array_sum($this->hours) * 500;
    }
}

class WorkerProxy extends WorkerOutsource implements Worker
{
    private int $salary = 0;

    public function countSalary(): int
    {
        // единожды обратимся к своему родителю и потом больше не запускается, а сразу возвращает результат
        if ($this->salary === 0) {
            $this->salary = parent::countSalary();
        }
        return $this->salary;
    }
}

$workerProxy = new WorkerProxy();
$workerProxy->closedHours(10);
$workerProxy->closedHours(10);
echo $workerProxy->countSalary(); // 10000

// игнорируется
$workerProxy->closedHours(20);
$workerProxy->closedHours(20);
echo $workerProxy->countSalary(); // 10000

// ----------- 2 -----------

interface BankAccount
{
    public function deposit(int $amount);

    public function getBalance(): int;
}


class HeavyBankAccount implements BankAccount
{
    private array $transactions = [];

    public function deposit(int $amount)
    {
        $this->transactions[] = $amount;
    }

    public function getBalance(): int
    {
        // это тяжелая часть, представьте себе все транзакции даже из
        // данные многолетней и десятилетней давности должны быть извлечены из базы данных или веб-службы
        // и баланс должен быть рассчитан исходя из этого

        return array_sum($this->transactions);
    }
}

class BankAccountProxy extends HeavyBankAccount implements BankAccount
{
    private ?int $balance = null;

    public function getBalance(): int
    {
        // поскольку вычисление баланса обходится очень дорого,
        // использование функции Bank Account::getBalance() откладывается до тех пор, пока это действительно не понадобится
        // и не будет вычислен повторно для этого экземпляра

        if ($this->balance === null) {
            $this->balance = parent::getBalance();
        }

        return $this->balance;
    }
}

$bankAccount = new BankAccountProxy();
$bankAccount->deposit(30);

// на этот раз рассчитывается баланс времени
echo $bankAccount->getBalance(); // 30

// наследование позволяет прокси-серверу банковского счета вести себя по отношению к постороннему лицу точно так же,
// как банковский счет сервера
$bankAccount->deposit(50);

// на этот раз ранее рассчитанный баланс возвращается снова без его повторного расчета
echo $bankAccount->getBalance(); // 30