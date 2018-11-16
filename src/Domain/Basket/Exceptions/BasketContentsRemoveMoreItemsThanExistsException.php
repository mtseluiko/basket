<?php
/**
 * Created by PhpStorm.
 * User: mikhail
 * Date: 16.11.18
 * Time: 16:20
 */

namespace App\Domain\Basket\Exceptions;


class BasketContentsRemoveMoreItemsThanExistsException extends \DomainException
{

    private const ERROR_MSG = 'Can\'t remove more items than exists';

    public function __construct(string $message = self::ERROR_MSG)
    {
        parent::__construct($message);
    }
}