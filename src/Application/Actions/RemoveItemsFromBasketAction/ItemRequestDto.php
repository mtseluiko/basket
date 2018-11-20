<?php
/**
 * Created by PhpStorm.
 * User: mikhail
 * Date: 19.11.18
 * Time: 15:46
 */

namespace App\Application\Actions\RemoveItemsFromBasketAction;


use App\Domain\Basket\ItemType;
use App\Domain\Basket\Weight;

class ItemRequestDto
{
    private $itemType;
    private $weight;

    public function __construct(string $itemType, ?float $weight)
    {
        $this->itemType = $itemType;
        $this->weight = $weight;
    }

    public function itemType(): string
    {
        return $this->itemType;
    }

    public function weight(): ?float
    {
        return $this->weight;
    }
}