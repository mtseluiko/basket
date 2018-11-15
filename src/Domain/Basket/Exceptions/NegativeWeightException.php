<?php
/**
 * Created by PhpStorm.
 * User: mikhail
 * Date: 15.11.18
 * Time: 11:30
 */

namespace App\Domain\Basket\Exceptions;


class NegativeWeightException extends \DomainException
{
    private const ERROR_MSG = 'Weight can\'t be negative';

    public function __construct(string $message = self::ERROR_MSG)
    {
        parent::__construct($message);
    }

}