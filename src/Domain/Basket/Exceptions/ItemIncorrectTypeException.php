<?php
/**
 * Created by PhpStorm.
 * User: mikhail
 * Date: 15.11.18
 * Time: 11:30
 */

namespace App\Domain\Basket\Exceptions;


class ItemIncorrectTypeException extends \DomainException
{
    private const ERROR_MSG = 'Incorrect item type';

    public function __construct(string $message = self::ERROR_MSG)
    {
        parent::__construct($message);
    }

}