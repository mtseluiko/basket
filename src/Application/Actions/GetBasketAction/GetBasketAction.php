<?php
/**
 * Created by PhpStorm.
 * User: mikhail
 * Date: 16.11.18
 * Time: 10:33
 */

namespace App\Application\Actions\GetBasketAction;


use App\Domain\Basket\BasketRepositoryContract;
use App\Domain\Basket\Exceptions\BasketDoesNotExistsException;

class GetBasketAction
{
    private $basketRepository;

    public function __construct(BasketRepositoryContract $basketRepository)
    {
        $this->basketRepository = $basketRepository;
    }

    public function execute(RenameBasketRequest $basketRequest): RenameBasketResponse
    {
        $basketId = $basketRequest->basketId();
        $basket = $this->basketRepository->get($basketId);

        if($basket === null) {
            throw new BasketDoesNotExistsException;
        }

        return new RenameBasketResponse($basket);

    }
}