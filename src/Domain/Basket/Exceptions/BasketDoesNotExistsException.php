<?php
/**
 * Created by PhpStorm.
 * User: mikhail
 * Date: 15.11.18
 * Time: 11:30
 */

namespace App\Domain\Basket\Exceptions;


use Symfony\Component\HttpFoundation\Response;

class BasketDoesNotExistsException extends \DomainException
{
    private const ERROR_MSG = 'Basket does not exists';

    public function __construct(string $message = self::ERROR_MSG, $code = Response::HTTP_NOT_FOUND)
    {
        parent::__construct($message, $code);
    }

}