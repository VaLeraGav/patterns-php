<?php

class Entity implements \Stringable
{
    /**
     * @var SplObjectStorage<Value,Value>
     */
    private $values;

    /**
     * @param Value[] $values
     */
    public function __construct(private string $name, array $values)
    {
        $this->values = new SplObjectStorage();

        foreach ($values as $value) {
            $this->values->attach($value);
        }
    }

    public function __toString(): string
    {
        $text = [$this->name];

        foreach ($this->values as $value) {
            $text[] = (string)$value;
        }

        return join(', ', $text);
    }
}

class AttributeEAV implements \Stringable
{
    private SplObjectStorage $values;

    public function __construct(private string $name)
    {
        $this->values = new SplObjectStorage();
    }

    public function addValue(Value $value): void
    {
        $this->values->attach($value);
    }

    public function getValues(): SplObjectStorage
    {
        return $this->values;
    }

    public function __toString(): string
    {
        return $this->name;
    }
}

class Value implements \Stringable
{
    public function __construct(private AttributeEAV $attribute, private string $name)
    {
        $attribute->addValue($this);
    }

    public function __toString(): string
    {
        return sprintf('%s: %s', (string)$this->attribute, $this->name);
    }
}

$colorAttribute = new AttributeEAV('color');
$colorSilver = new Value($colorAttribute, 'silver');
$colorBlack = new Value($colorAttribute, 'black');

$memoryAttribute = new AttributeEAV('memory');
$memory8Gb = new Value($memoryAttribute, '8GB');

$entity = new Entity('MacBook Pro', [$colorSilver, $colorBlack, $memory8Gb]);

echo $entity; //'MacBook Pro, color: silver, color: black, memory: 8GB'