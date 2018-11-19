<?php
/**
 * Created by PhpStorm.
 * User: mikhail
 * Date: 13.11.18
 * Time: 15:45
 */

namespace App\Domain\Basket;


use App\Domain\Basket\Exceptions\BasketOverflowException;


class Basket
{
    private $id;
    private $name;
    private $maxCapacity;
    private $contents;

    public function __construct(BasketId $id, BasketName $name, Weight $maxCapacity)
    {
        $this->id = $id;
        $this->name = $name;
        $this->maxCapacity = $maxCapacity;
        $this->contents = new BasketContents;
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

    public function contents(): BasketContents
    {
        return $this->contents;
    }

    public function currentWeight(): Weight
    {
        $totalWeight = new Weight;

        /* @var $item Item */
        foreach ($this->contents()->items() as $item) {
            $totalWeight = $totalWeight->add($item->weight());
        }

        return $totalWeight;
    }

    public function rename(BasketName $name): void
    {
        $this->name = $name;
    }

    public function canAddItem(Item $item): bool
    {
        return $this->canAddWeight($item->weight());
    }

    public function canAddWeight(Weight $weight): bool
    {
        $weightWithItem = $this->currentWeight()->add($weight);

        return $weightWithItem->weight() <= $this->maxCapacity()->weight();
    }

    public function addItem(Item $item): void
    {
        if (!$this->canAddItem($item)) {
            throw new BasketOverflowException;
        }

        $this->contents = $this->contents()->addItem($item);
    }

    public function removeItem(Item $item): void
    {
        $this->contents = $this->contents()->removeItem($item);
    }


}