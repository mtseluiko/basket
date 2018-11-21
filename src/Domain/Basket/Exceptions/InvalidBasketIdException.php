<?php
/**
 * Created by PhpStorm.
 * User: mikhail
 * Date: 15.11.18
 * Time: 11:30
 */

namespace App\Domain\Basket\Exceptions;


class InvalidBasketIdException extends \DomainException
{
    private const ERROR_MSG = 'Incorrect basket id';

    public function __construct(string $message = self::ERROR_MSG)
    {
        parent::__construct($message);
    }

}