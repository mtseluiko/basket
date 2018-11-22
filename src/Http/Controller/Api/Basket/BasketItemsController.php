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
use Symfony\Component\Routing\Annotation\Route;


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

    /**
     * @Route(
     *     "/baskets/{id}/items",
     *     name="add_items_basket",
     *     methods={"POST"}
     * )
     */
    public function addItemsToBasket(Request $request, string $id)
    {
        try {
            $items = $request->attributes->get('items');

            $response = $this->addItemsToBasketAction->execute(
                new AddItemsToBasketRequest($id, $items)
            );

            return $this->successResponse(
                $this->basketPresenter->presentBasket($response->basket())
            );
        } catch (DomainException $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @Route(
     *     "/baskets/{id}/items",
     *     name="remove_items_basket",
     *     methods={"DELETE"}
     * )
     */
    public function removeItemsFromBasket(Request $request, string $id)
    {
        try {
            $items = $request->attributes->get('items');

            $response = $this->removeItemsFromBasketAction->execute(
                new RemoveItemsFromBasketRequest($id, $items)
            );

            return $this->successResponse(
                $this->basketPresenter->presentBasket($response->basket())
            );
        } catch (DomainException $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }
}