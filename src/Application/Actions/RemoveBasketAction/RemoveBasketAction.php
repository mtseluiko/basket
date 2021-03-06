<?php

namespace App\Application\Actions\RemoveBasketAction;


use App\Domain\Basket\Exceptions\BasketDoesNotExistsException;
use App\Domain\Basket\BasketRepositoryContract;

class RemoveBasketAction
{
    private $basketRepository;

    public function __construct(BasketRepositoryContract $basketRepository)
    {
        $this->basketRepository = $basketRepository;
    }

    public function execute(RemoveBasketRequest $basketRequest): RemoveBasketResponse
    {
        $basketId = $basketRequest->basketId();
        $basket = $this->basketRepository->get($basketId);

        if($basket === null) {
            throw new BasketDoesNotExistsException;
        }

        $this->basketRepository->remove($basket);

        return new RemoveBasketResponse;

    }
}