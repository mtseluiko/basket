<?php
/**
 * Created by PhpStorm.
 * User: mikhail
 * Date: 13.11.18
 * Time: 15:45
 */

namespace App\Domain\Basket;


class Basket
{
    private $id;
    private $name;
    private $maxCapacity;
    /* @var $contents Item[] */
    private $contents;

    public function __construct(BasketId $id, BasketName $name, Weight $maxCapacity)
    {
        $this->id = $id;
        $this->name = $name;
        $this->maxCapacity = $maxCapacity;
    }

    public function id(): BasketId
    {
        return $this->id;
    }

    public function name(): BasketName
    {
        return $this->name;
    }

    public function maxCapacity(): Weight
    {
        return $this->maxCapacity;
    }

    public function contents(): array
    {
        return $this->contents;
    }

    public function currentWeight(): Weight
    {
        $totalWeight = new Weight;

        /* @var $item Item */
        foreach ($this->contents as $item) {
            $totalWeight = $totalWeight->add($item->weight());
        }

        return $totalWeight;
    }

    public function canAddItem(Item $item): bool
    {
        $weightWithItem = $this->currentWeight()->add($item->weight());

        return $weightWithItem->weight() <= $this->currentWeight()->weight();
    }

}