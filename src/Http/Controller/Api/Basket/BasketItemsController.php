<?php

namespace App\Http\Controller\Api\Basket;

use App\Application\Actions\AddItemsToBasketAction\AddItemsToBasketAction;
use App\Application\Actions\AddItemsToBasketAction\AddItemsToBasketRequest;
use App\Application\Actions\RemoveItemsFromBasketAction\RemoveItemsFromBasketAction;
use App\Application\Actions\RemoveItemsFromBasketAction\RemoveItemsFromBasketRequest;
use App\Http\Controller\Api\ApiController;
use App\Infrastructure\UI\BasketPresenter;
use DomainException;
use Symfony\Component\HttpFoundation\Request;

class BasketItemsController extends ApiController
{
    private $addItemsToBasketAction;
    private $removeItemsFromBasketAction;
    private $basketPresenter;

    public function __construct(
        AddItemsToBasketAction $addItemsToBasketAction,
        RemoveItemsFromBasketAction $removeItemsFromBasketAction,
        BasketPresenter $basketPresenter
    )
    {
        $this->addItemsToBasketAction = $addItemsToBasketAction;
        $this->removeItemsFromBasketAction = $removeItemsFromBasketAction;
        $this->basketPresenter = $basketPresenter;
    }

    public function addItemsToBasket(Request $request)
    {
        try {
            $requestParams = json_decode($request->getContent());

            $response = $this->addItemsToBasketAction->execute(
                new AddItemsToBasketRequest($requestParams)
            );

            return $this->successResponse(
                $this->basketPresenter->presentBasket($response->basket())
            );
        } catch (DomainException $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function removeItemsFromBasket(Request $request)
    {
        try {
            $requestParams = json_decode($request->getContent());

            $response = $this->removeItemsFromBasketAction->execute(
                new RemoveItemsFromBasketRequest($requestParams)
            );

            return $this->successResponse(
                $this->basketPresenter->presentBasket($response->basket())
            );
        } catch (DomainException $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }
}