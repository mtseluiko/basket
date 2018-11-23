<?php
/**
 * Created by PhpStorm.
 * User: mikhail
 * Date: 15.11.18
 * Time: 16:25
 */

namespace App\Domain\Basket;


use App\Domain\Basket\Exceptions\NegativeWeightException;

final class Weight
{
    const EPSILON = 0.001; //float calculation precision

    private $weight;

    public function __construct(float $value = 0)
    {
        if ($value < 0) {
            throw new NegativeWeightException;
        }

        $this->weight = $value;
    }

    public function sameValueAs(self $otherWeight): bool
    {
        return $this->weight() === $otherWeight->weight();
    }

    public function weight(): float
    {
        return $this->weight;
    }

    public function __toString(): string
    {
        return strval($this->weight);
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