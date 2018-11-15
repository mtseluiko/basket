<?php
/**
 * Created by PhpStorm.
 * User: mikhail
 * Date: 15.11.18
 * Time: 11:30
 */

namespace App\Domain\Basket\Exceptions;


class BasketNameIncorrectLengthException extends \DomainException
{
    private const ERROR_MSG = 'Basket name has incorrect length';

    public function __construct(string $message = self::ERROR_MSG)
    {
        parent::__construct($message);
    }

}