<?php
/**
 * Created by PhpStorm.
 * User: mikhail
 * Date: 15.11.18
 * Time: 16:25
 */

namespace App\Domain\Basket;


use App\Domain\Basket\Exceptions\NegativeWeightException;

class Weight
{
    const EPSILON = 0.001; //float calculation precision

    private $value;

    public function __construct(float $value = 0)
    {
        if ($value < 0) {
            throw new NegativeWeightException;
        }

        $this->value = $value;
    }

    public function sameValueAs(self $otherWeight): bool
    {
        return $this->weight() === $otherWeight->weight();
    }

    public function weight(): float
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return strval($this->value);
    }

    public function add(self $weight): self
    {
        return new self($this->weight() + $weight->weight());
    }

    public function subtract(self $weight): self
    {
        if ($this->weight() < $weight->weight()) {
            throw new NegativeWeightException;
        }

        return new self($this->weight() - $weight->weight());
    }

    public function isZero(): bool
    {
        return !($this->weight() > self::EPSILON);
    }
}