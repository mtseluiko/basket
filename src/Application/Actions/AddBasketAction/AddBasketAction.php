<?php
/**
 * Created by PhpStorm.
 * User: mikhail
 * Date: 16.11.18
 * Time: 10:33
 */

namespace App\Application\Actions\AddBasketAction;


use App\Domain\Basket\Basket;
use App\Domain\Basket\BasketId;
use App\Domain\Basket\BasketRepositoryContract;

class AddBasketAction
{
    private $basketRepository;

    public function __construct(BasketRepositoryContract $basketRepository)
    {
        $this->basketRepository = $basketRepository;
    }

    public function execute(AddBasketRequest $basketRequest): AddBasketResponse
    {
        $basketId = BasketId::generate();
        $basketName = $basketRequest->name();
        $maxCapacity = $basketRequest->maxCapacity();

        $basket = new Basket($basketId, $basketName, $maxCapacity);

        $this->basketRepository->store($basket);

        return new AddBasketResponse($basket);

    }
}