<?php
/**
 * Created by PhpStorm.
 * User: mikhail
 * Date: 15.11.18
 * Time: 10:01
 */

namespace App\Domain\Basket;


use App\Domain\Basket\Exceptions\BasketContentsRemoveMoreItemsThanExistsException;

class BasketContents
{
    /* @var $items Item[] */
    private $items = [];

    public function sameValueAs(self $otherItems): bool
    {
        return $this->items() == $otherItems->items();
    }

    public function items(): array
    {
        return $this->items;
    }

    public function hasItemWithType(ItemType $type): bool
    {
        $typeName = $type->typeName();

        if (isset($this->items()[$typeName])) {
            $item = $this->items()[$typeName];
            return !$item->weight()->isZero();
        }

        return false;
    }

    public function addItem(Item $item): self
    {
        $currentItems = $this->items();
        $itemTypeName = $item->type()->typeName();

        if ($this->hasItemWithType($item->type())) {
            $currentItems[$itemTypeName] = $currentItems[$itemTypeName]->addWeight($item->weight());
        } else {
            $currentItems[$itemTypeName] = $item;
        }


        $newContents = new self;
        $newContents->items = $currentItems;

        return $newContents;
    }

    public function removeItem(Item $item): self
    {
        $currentItems = $this->items();
        $itemTypeName = $item->type()->typeName();

        if (
            $this->hasItemWithType($item->type()) &&
            $currentItems[$itemTypeName]->weight()->weight() > $item->weight()->weight()
        ) {
            $currentItems[$itemTypeName] = $currentItems[$itemTypeName]->subtractWeight($item->weight());
        } else {
            throw new BasketContentsRemoveMoreItemsThanExistsException;
        }

        $newContents = new self;
        $newContents->items = $currentItems;
        return $newContents;
    }
}