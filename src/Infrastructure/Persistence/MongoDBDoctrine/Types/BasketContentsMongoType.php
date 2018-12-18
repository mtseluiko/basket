<?php

namespace App\Infrastructure\Persistence\MongoDBDoctrine\Types;

use App\Domain\Basket\Item;
use Doctrine\ODM\MongoDB\Types\Type;

class BasketContentsMongoType extends Type
{
    const TYPE_NAME = 'basket_contents_mongo';

    public function closureToPHP()
    {
        return '$return = \App\Infrastructure\Helpers\ItemsHelper::getItemsFromArray($value);';
    }

    public function convertToDatabaseValue($items)
    {
        $resultItems = [];
        foreach ($items as $item) {
            /* @var $item Item */
            $type = $item->type()->typeName();
            $weight = $item->weight()->weight();

            if(isset($resultItems[$type])) {
                $weight = $resultItems[$type]['weight'] += $weight;
            }

            $resultItems[$item->type()->typeName()] = [
                'type' => $type,
                'weight' => $weight
            ];
        }
        return $resultItems;
    }


    public function getName()
    {
        return self::TYPE_NAME;
    }
}