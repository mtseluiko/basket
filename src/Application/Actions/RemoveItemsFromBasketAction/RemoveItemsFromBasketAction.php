<?php
/**
 * Created by PhpStorm.
 * User: mikhail
 * Date: 16.11.18
 * Time: 10:33
 */

namespace App\Application\Actions\RemoveItemsFromBasketAction;


use App\Domain\Basket\Exceptions\BasketDoesNotExistsException;
use App\Domain\Basket\BasketRepositoryContract;

class RemoveItemsFromBasketAction
{
    private $basketRepository;

    public function __construct(BasketRepositoryContract $basketRepository)
    {
        $this->basketRepository = $basketRepository;
    }

    public function execute(RemoveItemsFromBasketRequest $basketRequest): RemoveItemsFromBasketResponse
    {
        $basketId = $basketRequest->basketId();

        $basket = $this->basketRepository->get($basketId);

        if ($basket === null) {
            throw new BasketDoesNotExistsException;
        }

        $items = $basketRequest->items();

        foreach ($items as $item) {
            /* @var $item ItemRequestDto */
            $itemType = $item->itemType();
            $weight = $item->weight();
            if($weight === null) {
                $basket->removeAllItemsByType($itemType);
            } else {
                $basket->removeItem($itemType, $weight);
            }

        }

        $this->basketRepository->store($basket);

        return new RemoveItemsFromBasketResponse($basket);

    }
}