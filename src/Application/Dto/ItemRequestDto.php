<?php
/**
 * Created by PhpStorm.
 * User: mikhail
 * Date: 19.11.18
 * Time: 15:46
 */

namespace App\Application\Dto;


use App\Domain\Basket\ItemType;
use App\Domain\Basket\Weight;

class ItemRequestDto
{
    private $itemType;
    private $weight;

    public function __construct(ItemType $itemType, Weight $weight)
    {
        $this->itemType = $itemType;
        $this->weight = $weight;
    }

    public function itemType(): ItemType
    {
        return $this->itemType;
    }

    public function weight(): Weight
    {
        return $this->weight;
    }
}