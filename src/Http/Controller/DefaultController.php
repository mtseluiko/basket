<?php

namespace App\Http\Controller;

use App\Application\Actions\AddItemsToBasketAction\AddItemsToBasketAction;
use App\Application\Actions\AddItemsToBasketAction\AddItemsToBasketRequest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController
{
    private $addItemsToBasketAction;

    public function __construct(AddItemsToBasketAction $addItemsToBasketAction)
    {
        $this->addItemsToBasketAction = $addItemsToBasketAction;
    }

    public function index()
    {
        return new Response('Hello!');
    }

    public function exampleAdd(Request $request)
    {
        $requestParams = json_decode($request->getContent());

        $response = $this->addItemsToBasketAction->execute(
            new AddItemsToBasketRequest($requestParams)
        );

        return new JsonResponse($response->basket()->name());
    }
}