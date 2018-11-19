<?php
/**
 * Created by PhpStorm.
 * User: mikhail
 * Date: 15.11.18
 * Time: 10:00
 */

namespace App\Domain\Basket;


use App\Domain\Basket\Exceptions\BasketEmptyNameException;
use App\Domain\Basket\Exceptions\BasketNameIncorrectLengthException;

class BasketName
{
    const BASKET_NAME_MIN_LENGTH = 3;
    const BASKET_NAME_MAX_LENGTH = 40;

    private $name;

    public function __construct(string $name)
    {
        $nameLength = strlen($name);

        if ($nameLength === 0) {
            throw new BasketEmptyNameException;
        }

        if (
            $nameLength < self::BASKET_NAME_MIN_LENGTH ||
            $nameLength > self::BASKET_NAME_MAX_LENGTH
        ) {
            throw new BasketNameIncorrectLengthException;
        }
        $this->name = $name;
    }

    public function sameValueAs(self $otherName): bool
    {
        return $this->name() === $otherName->name();
    }

    public function name(): string
    {
        return $this->name;
    }

    public function __toString(): string
    {
        return $this->name;
    }
}