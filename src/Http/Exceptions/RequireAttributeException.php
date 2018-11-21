<?php
/**
 * Created by PhpStorm.
 * User: mikhail
 * Date: 15.11.18
 * Time: 11:30
 */

namespace App\Http\Exceptions;


use Symfony\Component\HttpFoundation\Response;

class RequireAttributeException extends \DomainException
{

    public function __construct(string $attribute = '', $code = Response::HTTP_BAD_REQUEST)
    {
        $message = "Attribute '$attribute' is required";
        parent::__construct($message, $code);
    }

}