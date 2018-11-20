<?php

namespace App\Http\Controller;

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
use DomainException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BasketController extends ApiController
{
    private $addBasketAction;
    private $getBasketAction;
    private $getBasketListAction;
    private $removeBasketAction;
    private $renameBasketAction;

    public function __construct(
        AddBasketAction $addBasketAction,
        GetBasketAction $getBasketAction,
        GetBasketListAction $getBasketListAction,
        RemoveBasketAction $removeBasketAction,
        RenameBasketAction $renameBasketAction
    )
    {
        $this->addBasketAction = $addBasketAction;
        $this->getBasketAction = $getBasketAction;
        $this->getBasketListAction = $getBasketListAction;
        $this->removeBasketAction = $removeBasketAction;
        $this->renameBasketAction = $renameBasketAction;
    }

    public function addBasket(Request $request)
    {
        try {
            $requestParams = json_decode($request->getContent());
            $name = $requestParams->name;
            $maxCapacity = $requestParams->maxCapacity;

            $this->addBasketAction->execute(
                new AddBasketRequest($name, $maxCapacity)
            );

            return $this->emptyResponse(Response::HTTP_CREATED);
        } catch (DomainException $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function getBasket(Request $request)
    {
        try {
            $id = $request->get('id');

            $getBasketResponse = $this->getBasketAction->execute(
                new GetBasketRequest($id)
            );

            return $this->successResponse(['test' => 'Y']);
        } catch (DomainException $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function getBasketList()
    {
        try {
            $getBasketListResponse = $this->getBasketListAction->execute(
                new GetBasketListRequest
            );

            return $this->successResponse(['test' => 'Y']);
        } catch (DomainException $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function removeBasket(Request $request)
    {
        try {
            $requestParams = json_decode($request->getContent());
            $id = $requestParams->id;

            $this->removeBasketAction->execute(
                new RemoveBasketRequest($id)
            );

            return $this->emptyResponse(Response::HTTP_NO_CONTENT);
        } catch (DomainException $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function renameBasket(Request $request)
    {
        try {
            $requestParams = json_decode($request->getContent());
            $id = $requestParams->id;
            $name = $requestParams->name;

            $this->renameBasketAction->execute(
                new RenameBasketRequest($id, $name)
            );

            return $this->emptyResponse();
        } catch (DomainException $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}