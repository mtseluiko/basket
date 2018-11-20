<?php

namespace App\Http\Controller;

use App\Application\Actions\AddItemsToBasketAction\AddItemsToBasketAction;
use App\Application\Actions\AddItemsToBasketAction\AddItemsToBasketRequest;
use App\Application\Actions\RemoveItemsFromBasketAction\RemoveItemsFromBasketAction;
use App\Application\Actions\RemoveItemsFromBasketAction\RemoveItemsFromBasketRequest;
use App\Http\Controller\Api\ApiController;
use DomainException;
use Symfony\Component\HttpFoundation\Request;

class BasketItemsController extends ApiController
{
    private $addItemsToBasketAction;
    private $removeItemsFromBasketAction;

    public function __construct(
        AddItemsToBasketAction $addItemsToBasketAction,
        RemoveItemsFromBasketAction $removeItemsFromBasketAction
    )
    {
        $this->addItemsToBasketAction = $addItemsToBasketAction;
        $this->removeItemsFromBasketAction = $removeItemsFromBasketAction;
    }

    public function addItemsToBasket(Request $request)
    {
        try {
            $requestParams = json_decode($request->getContent());

            $response = $this->addItemsToBasketAction->execute(
                new AddItemsToBasketRequest($requestParams)
            );

            return $this->emptyResponse();
        } catch (DomainException $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function removeItemsFromBasket(Request $request)
    {
        try {
            $requestParams = json_decode($request->getContent());

            $response = $this->removeItemsFromBasketAction->execute(
                new RemoveItemsFromBasketRequest($requestParams)
            );

            return $this->successResponse(['test' => 'Y']);
        } catch (DomainException $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}