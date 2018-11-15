<?php
/**
 * Created by PhpStorm.
 * User: mikhail
 * Date: 15.11.18
 * Time: 11:30
 */

namespace App\Domain\Basket\Exceptions;


class BasketNegativeCapacityException extends \DomainException
{
    private const ERROR_MSG = 'Basket capacity must be positive number';

    public function __construct(string $message = self::ERROR_MSG)
    {
        parent::__construct($message);
    }

}