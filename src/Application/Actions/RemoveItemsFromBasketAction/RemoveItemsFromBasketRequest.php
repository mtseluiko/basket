<?php
/**
 * Created by PhpStorm.
 * User: mikhail
 * Date: 16.11.18
 * Time: 10:39
 */

namespace App\Application\Actions\RemoveItemsFromBasketAction;


use App\Domain\Basket\BasketId;

class RemoveItemsFromBasketRequest
{
    private $basketId;
    private $items;

    public function __construct($params)
    {
        $this->basketId = BasketId::fromString($params->id);
        $items = [];
        foreach($params->items as $rawItem) {
            $items[] = new ItemRequestDto(
                $rawItem->type,
                $rawItem->weight
            );
        }

        $this->items = $items;
    }

    public function basketId(): BasketId
    {
        return $this->basketId;
    }

    public function items(): array
    {
        return $this->items;
    }
}