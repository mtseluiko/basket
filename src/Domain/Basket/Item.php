<?php
/**
 * Created by PhpStorm.
 * User: mikhail
 * Date: 15.11.18
 * Time: 16:13
 */

namespace App\Domain\Basket;


class Item
{
    private $type;
    private $weight;

    public function __construct(ItemType $type, Weight $weight)
    {
        $this->type = $type;
        $this->weight = $weight;
    }

    public function type(): ItemType
    {
        return $this->type;
    }

    public function weight(): Weight
    {
        return $this->weight;
    }

}