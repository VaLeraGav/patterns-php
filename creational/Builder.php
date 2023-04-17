<?php

class Operator
{
    public function make(Builder $builder): Message
    {
        $builder->makeHeader();
        $builder->makeBody();
        $builder->makeFooter();
        $builder->makeCustom();
        return $builder->getText();
    }
}

interface Builder
{
    public function makeHeader();

    public function makeBody();

    public function makeFooter();

    public function makeCustom();

    public function getText();
}

class TextBuilder implements Builder
{
    private Message $message;

    public function make()
    {
        $this->message = new Message();
    }

    public function makeHeader()
    {
        $this->message->setPart(new Header('Header line'));
    }

    public function makeBody()
    {
        $this->message->setPart(new Body('Body line'));
    }

    public function makeFooter()
    {
        $this->message->setPart(new Footer('Footer line'));
    }

    public function makeCustom()
    {
        $this->message->setPart(new Custom('Custom line'));
    }

    public function getText(): Message
    {
        return $this->message;
    }
}

class Section
{
    private string $text;

    public function __construct(string $text)
    {
        $this->text = $text;
    }

    public function __toString(): string
    {
        return $this->text;
    }
}

class Header extends Section
{

}

class Body extends Section
{

}

class Footer extends Section
{

}

class Custom extends Section
{

}

class Message
{
    private string $text = '';

    public function setPart($part)
    {
        $this->text .= $part . PHP_EOL;
    }

    public function getText(): string
    {
        return $this->text;
    }
}

$operator = new Operator();

$builder = new TextBuilder();
$builder->make();
$message = $operator->make($builder);

echo $message->getText();


// ----------- 2 -----------
class Burger
{
    protected $size;

    protected $cheese = false;
    protected $pepperoni = false;
    protected $lettuce = false;
    protected $tomato = false;

    public function __construct(BurgerBuilder $builder)
    {
        $this->size = $builder->size;
        $this->cheese = $builder->cheese;
        $this->pepperoni = $builder->pepperoni;
        $this->lettuce = $builder->lettuce;
        $this->tomato = $builder->tomato;
    }
}

class BurgerBuilder
{
    public $size;

    public $cheese = false;
    public $pepperoni = false;
    public $lettuce = false;
    public $tomato = false;

    public function __construct(int $size)
    {
        $this->size = $size;
    }

    public function addPepperoni()
    {
        $this->pepperoni = true;
        return $this;
    }

    public function addLettuce()
    {
        $this->lettuce = true;
        return $this;
    }

    public function addCheese()
    {
        $this->cheese = true;
        return $this;
    }

    public function addTomato()
    {
        $this->tomato = true;
        return $this;
    }

    public function build(): Burger
    {
        return new Burger($this);
    }
}

$burger = (new BurgerBuilder(14))
    ->addPepperoni()
    ->addLettuce()
    ->addTomato()
    ->build();

