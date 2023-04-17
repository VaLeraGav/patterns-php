<?php

interface Mail
{
    public function render(): string;
}

abstract class TypeMail
{
    private string $text;

    public function getText(): string
    {
        return $this->text;
    }

    public function __construct(string $text)
    {
        $this->text = $text;
    }
}

class BusinessMail extends TypeMail implements Mail
{
    public function render(): string
    {
        return $this->getText() . ' from business mail';
    }
}

class JobMail extends TypeMail implements Mail
{
    public function render(): string
    {
        return $this->getText() . ' from job mail';
    }
}

// Распределение
class MailFactory
{
    private array $pool = [];

    public function getMail($id, $typeMail): Mail
    {
        if (!isset($this->pool[$id])) {
            $this->pool[$id] = $this->make($typeMail);
        }

        return $this->pool[$id];
    }

    private function make($typeMail): Mail
    {
        if ($typeMail === 'business') {
            return new BusinessMail('Business text');
        }

        return new JobMail('Job text');
    }
}

$mailFactory = new MailFactory();
$mail = $mailFactory->getMail(10, 'business');
echo $mail->render(); // Business text from business mail

$mail2 = $mailFactory->getMail(11, 'business12');
echo $mail2->render(); // Job text from job mail

echo "\n";

// ----------- 2 -----------

// Все, что будет кэшировано, имеет минимальный вес.
// Сорта чая здесь будут невесомыми.
class KarakTea
{
}

// Действует как фабрика и спасает команду
class TeaMaker
{
    protected $availableTea = [];

    public function make($preference)
    {
        if (empty($this->availableTea[$preference])) {
            $this->availableTea[$preference] = new KarakTea();
        }

        return $this->availableTea[$preference];
    }
}

class TeaShop
{
    protected $orders;
    protected $teaMaker;

    public function __construct(TeaMaker $teaMaker)
    {
        $this->teaMaker = $teaMaker;
    }

    public function takeOrder(string $teaType, int $table)
    {
        $this->orders[$table] = $this->teaMaker->make($teaType);
    }

    public function serve()
    {
        foreach ($this->orders as $table => $tea) {
            echo "Serving tea to table# " . $table;
        }
    }
}

$teaMaker = new TeaMaker();
$shop = new TeaShop($teaMaker);

$shop->takeOrder('less sugar', 1);
$shop->takeOrder('more milk', 2);
$shop->takeOrder('without sugar', 5);

$shop->serve();
// Serving tea to table# 1
// Serving tea to table# 2
// Serving tea to table# 5
