<?php
/**
 * Created by PhpStorm.
 * User: mikhail
 * Date: 15.11.18
 * Time: 16:25
 */

namespace App\Domain\Basket;


use App\Domain\Basket\Exceptions\ItemIncorrectTypeException;

class ItemType
{
    private $type;

    private const APPLE_ITEM_TYPE = 'apple';
    private const ORANGE_ITEM_TYPE = 'orange';
    private const WATERMELON_ITEM_TYPE = 'watermelon';

    public function __construct(string $type)
    {
        if(
            $type !== self::APPLE_ITEM_TYPE &&
            $type !== self::ORANGE_ITEM_TYPE &&
            $type !== self::WATERMELON_ITEM_TYPE
        )
        {
            throw new ItemIncorrectTypeException;
        }


        $this->type = $type;
    }

    public function sameValueAs(self $otherItemType): bool
    {
        return $this->type() === $otherItemType->type();
    }

    public function type()
    {
        return $this->type;
    }

    public function __toString()
    {
        return $this->type;
    }
}