<?php
/**
 * Created by PhpStorm.
 * User: mikhail
 * Date: 15.11.18
 * Time: 11:30
 */

namespace App\Http\Exceptions;


use Symfony\Component\HttpFoundation\Response;

class RequestParsingException extends \DomainException
{
    private const ERROR_MSG = 'Unable to parse request';

    public function __construct(string $message = self::ERROR_MSG, $code = Response::HTTP_BAD_REQUEST)
    {
        parent::__construct($message, $code);
    }

}