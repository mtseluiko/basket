<?php
/**
 * Created by PhpStorm.
 * User: mikhail
 * Date: 15.11.18
 * Time: 11:30
 */

namespace App\Http\Exceptions;


use Symfony\Component\HttpFoundation\Response;

class ReadOnlyPropertyException extends \DomainException
{

    public function __construct(string $property = '', $code = Response::HTTP_BAD_REQUEST)
    {
        $message = "Property '$property' is read only";
        parent::__construct($message, $code);
    }

}