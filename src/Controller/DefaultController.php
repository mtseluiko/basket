<?php

namespace App\Controller;

use App\Application\Actions\AddBasketAction\AddBasketAction;
use App\Application\Actions\AddBasketAction\AddBasketRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController
{
    private $addBasketAction;

    public function __construct(AddBasketAction $addBasketAction)
    {
        $this->addBasketAction = $addBasketAction;
    }

    public function index()
    {
        return new Response('Hello!');
    }

    public function exampleAdd(Request $request)
    {
        $name = $request->get('name');
        $maxCapacity = $request->get('maxCapacity');

        $response = $this->addBasketAction->execute(
            new AddBasketRequest($name, $maxCapacity)
        );

        return new Response($response->basket()->name());
    }
}