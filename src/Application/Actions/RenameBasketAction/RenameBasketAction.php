<?php
/**
 * Created by PhpStorm.
 * User: mikhail
 * Date: 16.11.18
 * Time: 10:33
 */

namespace App\Application\Actions\RenameBasketAction;


use App\Application\Exceptions\BasketDoesNotExistsException;
use App\Domain\Basket\BasketRepositoryContract;

class RenameBasketAction
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

        if ($basket === null) {
            throw new BasketDoesNotExistsException;
        }

        $basket->rename($basketRequest->newName());

        $this->basketRepository->store($basket);

        return new RenameBasketResponse($basket);

    }
}