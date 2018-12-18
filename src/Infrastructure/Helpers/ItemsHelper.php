<?php

namespace App\Infrastructure\Helpers;


use App\Domain\Basket\Item;
use App\Domain\Basket\ItemType;
use App\Domain\Basket\Weight;

class ItemsHelper
{
    private static function getSingleItemFromArray(array $item): Item
    {
        return new Item(
            new ItemType($item['type']),
            new Weight($item['weight'])
        );
    }

    public static function getItemsFromArray(array $itemsRaw): array
    {
        $items = [];

        foreach ($itemsRaw as $basketItem) {
            if(count($basketItem) === 0) {
                continue;
            }

            $item = self::getSingleItemFromArray($basketItem);

            $items[$basketItem['type']] = $item;
        }
        return $items;
    }
}