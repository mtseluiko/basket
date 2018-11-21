<?php

namespace App\Http\Controller\Api\Basket;

use App\Application\Actions\AddBasketAction\AddBasketAction;
use App\Application\Actions\AddBasketAction\AddBasketRequest;
use App\Application\Actions\GetBasketAction\GetBasketAction;
use App\Application\Actions\GetBasketAction\GetBasketRequest;
use App\Application\Actions\GetBasketListAction\GetBasketListAction;
use App\Application\Actions\GetBasketListAction\GetBasketListRequest;
use App\Application\Actions\RemoveBasketAction\RemoveBasketAction;
use App\Application\Actions\RemoveBasketAction\RemoveBasketRequest;
use App\Application\Actions\RenameBasketAction\RenameBasketAction;
use App\Application\Actions\RenameBasketAction\RenameBasketRequest;
use App\Http\Controller\Api\ApiController;
use App\Infrastructure\UI\BasketPresenter;
use DomainException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BasketController extends ApiController
{
    private $addBasketAction;
    private $getBasketAction;
    private $getBasketListAction;
    private $removeBasketAction;
    private $renameBasketAction;
    private $basketPresenter;

    public function __construct(
        AddBasketAction $addBasketAction,
        GetBasketAction $getBasketAction,
        GetBasketListAction $getBasketListAction,
        RemoveBasketAction $removeBasketAction,
        RenameBasketAction $renameBasketAction,
        BasketPresenter $basketPresenter
    )
    {
        $this->addBasketAction = $addBasketAction;
        $this->getBasketAction = $getBasketAction;
        $this->getBasketListAction = $getBasketListAction;
        $this->removeBasketAction = $removeBasketAction;
        $this->renameBasketAction = $renameBasketAction;
        $this->basketPresenter = $basketPresenter;
    }

    /**
     * @Route(
     *     "/baskets",
     *     name="add_basket",
     *     methods={"POST"}
     * )
     */
    public function addBasket(Request $request)
    {
        try {
            $requestParams = json_decode($request->getContent());
            $name = $requestParams->name;
            $maxCapacity = $requestParams->maxCapacity;

            $basketResponse = $this->addBasketAction->execute(
                new AddBasketRequest($name, $maxCapacity)
            );

            return $this->successResponse(
                $this->basketPresenter->presentBasket($basketResponse->basket()),
                Response::HTTP_CREATED
            );
        } catch (DomainException $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @Route(
     *     "/baskets/{id}",
     *     name="get_basket",
     *     methods={"GET"}
     * )
     */
    public function getBasket(string $id)
    {
        try {
            $basketResponse = $this->getBasketAction->execute(
                new GetBasketRequest($id)
            );

            return $this->successResponse(
                $this->basketPresenter->presentBasket($basketResponse->basket())
            );
        } catch (DomainException $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @Route(
     *     "/baskets",
     *     name="get_baskets",
     *     methods={"GET"},
     * )
     */
    public function getBasketList()
    {
        try {
            $getBasketListResponse = $this->getBasketListAction->execute(
                new GetBasketListRequest
            );

            return $this->successResponse(
                $this->basketPresenter->presentBasketList($getBasketListResponse->basketList())
            );
        } catch (DomainException $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @Route(
     *     "/baskets/{id}",
     *     name="remove_basket",
     *     methods={"DELETE"}
     * )
     */
    public function removeBasket(string $id)
    {
        try {
            $this->removeBasketAction->execute(
                new RemoveBasketRequest($id)
            );

            return $this->emptyResponse(Response::HTTP_NO_CONTENT);
        } catch (DomainException $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @Route(
     *     "/baskets/{id}/rename",
     *     name="rename_basket",
     *     methods={"POST"}
     * )
     */
    public function renameBasket(Request $request, string $id)
    {
        try {
            $requestParams = json_decode($request->getContent());

            $name = $requestParams->name;

            $basketResponse = $this->renameBasketAction->execute(
                new RenameBasketRequest($id, $name)
            );

            return $this->successResponse(
                $this->basketPresenter->presentBasket($basketResponse->basket())
            );
        } catch (DomainException $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }
}