<?php
/**
 * Created by PhpStorm.
 * User: mikhail
 * Date: 15.11.18
 * Time: 16:25
 */

namespace App\Domain\Basket;


use App\Domain\Basket\Exceptions\ItemIncorrectTypeException;

final class ItemType
{
    private $typeName;

    const APPLE_ITEM_TYPE = 'apple';
    const ORANGE_ITEM_TYPE = 'orange';
    const WATERMELON_ITEM_TYPE = 'watermelon';

    public function __construct(string $typeName)
    {
        if (
            $typeName !== self::APPLE_ITEM_TYPE &&
            $typeName !== self::ORANGE_ITEM_TYPE &&
            $typeName !== self::WATERMELON_ITEM_TYPE
        ) {
            throw new ItemIncorrectTypeException;
        }


        $this->typeName = $typeName;
    }

    public function sameValueAs(self $otherItemType): bool
    {
        return $this->typeName() === $otherItemType->typeName();
    }

    public function typeName(): string
    {
        return $this->typeName;
    }

    public function __toString(): string
    {
        return $this->typeName;
    }
}