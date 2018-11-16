<?php
/**
 * Created by PhpStorm.
 * User: mikhail
 * Date: 15.11.18
 * Time: 11:30
 */

namespace App\Application\Exceptions;


class BasketDoesNotExistsException extends \LogicException
{
    private const ERROR_MSG = 'Basket does not exists';

    public function __construct(string $message = self::ERROR_MSG)
    {
        parent::__construct($message);
    }

}