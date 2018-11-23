<?php
/**
 * Created by PhpStorm.
 * User: mikhail
 * Date: 15.11.18
 * Time: 16:13
 */

namespace App\Domain\Basket;


final class Item
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

    public function addWeight(Weight $weight): Item
    {
        return new self($this->type, $this->weight()->add($weight));
    }

    public function subtractWeight(Weight $weight): Item
    {
        return new self($this->type, $this->weight()->subtract($weight));
    }

}