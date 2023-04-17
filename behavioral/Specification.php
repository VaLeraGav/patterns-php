<?php

interface Specification
{
    public function isNormal(Pupil $pupil): bool;
}

class Pupil
{
    private int $rate = 0;

    public function getRate(): int
    {
        return $this->rate;
    }

    public function __construct(int $rate)
    {
        $this->rate = $rate;
    }
}

class PupilSpecification implements Specification
{
    private int $needRate = 0;

    public function __construct(int $needRate)
    {
        $this->needRate = $needRate;
    }

    public function isNormal(Pupil $pupil): bool
    {
        return $this->needRate < $pupil->getRate();
    }
}

class AndSpecification implements Specification
{
    private array $specification;

    public function __construct(Specification ...$specification)
    {
        $this->specification = $specification;
    }

    public function isNormal(Pupil $pupil): bool
    {
        foreach ($this->specification as $specification) {
            if (!$specification->isNormal($pupil)) {
                return false;
            }
        }
        return true;
    }
}

class OrSpecification implements Specification
{
    private array $specification;

    public function __construct(Specification ...$specification)
    {
        $this->specification = $specification;
    }

    public function isNormal(Pupil $pupil): bool
    {
        foreach ($this->specification as $specification) {
            if ($specification->isNormal($pupil)) {
                return true;
            }
        }
        return false;
    }
}

class NotSpecification implements Specification
{
    private Specification $specification;

    public function __construct(Specification $specification)
    {
        $this->specification = $specification;
    }

    public function isNormal(Pupil $pupil): bool
    {
        return !$this->specification->isNormal($pupil);
    }
}

$spec1 = new PupilSpecification(5);
$spec2 = new PupilSpecification(10);

$pupil = new Pupil(7);

var_dump($spec1->isNormal($pupil)); // true
var_dump($spec2->isNormal($pupil)); // false

$andSpecification = new AndSpecification($spec1, $spec2);
var_dump($andSpecification->isNormal($pupil)); //false

$orSpecification = new OrSpecification($spec1, $spec2);
var_dump($orSpecification->isNormal($pupil)); // true

$notSpecification = new NotSpecification($spec2);
var_dump($notSpecification->isNormal($pupil)); // true
