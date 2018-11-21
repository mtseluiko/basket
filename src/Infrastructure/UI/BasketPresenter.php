<?php

namespace App\Infrastructure\UI;


use App\Domain\Basket\Basket;
use App\Domain\Basket\Item;

class BasketPresenter
{
    private $itemPresenter;

    public function __construct(ItemPresenter $itemPresenter)
    {
        $this->itemPresenter = $itemPresenter;
    }

    public function presentBasket(Basket $basket): array
    {
        $result = [
            "id" => $basket->id()->id(),
            "name" => $basket->name()->name(),
            "maxCapacity" => $basket->maxCapacity()->weight(),
            "contents" => $this->presentBasketContent($basket->contents())
        ];

        return $result;
    }

    public function presentBasketList(array $basketList): array
    {
        $result = [];

        foreach ($basketList as $basket) {
            /* @var $basket Basket */
            $result[$basket->id()->id()] = $this->presentBasket($basket);
        }

        return $result;
    }

    private function presentBasketContent(array $basketContent): array
    {
        $result = [];

        foreach ($basketContent as $item) {
            /* @var $item Item */
            $result[] = $this->itemPresenter->presentItem($item);
        }

        return $result;
    }
}