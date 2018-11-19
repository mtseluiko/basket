<?php
/**
 * Created by PhpStorm.
 * User: mikhail
 * Date: 16.11.18
 * Time: 10:33
 */

namespace App\Application\Actions\AddItemsToBasketAction;


use App\Application\Dto\ItemRequestDto;
use App\Domain\Basket\BasketRepositoryContract;
use App\Domain\Basket\Item;
use App\Domain\Basket\Weight;

class AddItemsToBasketAction
{
    private $basketRepository;

    public function __construct(BasketRepositoryContract $basketRepository)
    {
        $this->basketRepository = $basketRepository;
    }

    public function execute(AddItemsToBasketRequest $basketRequest): AddItemsToBasketResponse
    {
        $basketId = $basketRequest->basketId();

        $basket = $this->basketRepository->get($basketId);

        $items = $basketRequest->items();

        if ($basket->canAddWeight($this->totalWeight($items))) {

            foreach ($items as $item) {
                /* @var $item ItemRequestDto */
                $itemType = $item->itemType();
                $weight = $item->weight();
                $basket->addItem(
                    new Item($itemType, $weight)
                );
            }
        }

        $this->basketRepository->store($basket);

        return new AddItemsToBasketResponse($basket);

    }

    private function totalWeight(array $items): Weight
    {
        $total = new Weight;
        foreach ($items as $item) {
            /* @var $item ItemRequestDto */
            $total->add($item->weight());
        }
        return $total;
    }
}