<?php

namespace App\Infrastructure\Types;

use App\Domain\Basket\Item;
use App\Domain\Basket\ItemType;
use App\Domain\Basket\Weight;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class BasketContentsType extends Type
{
    const TYPE_NAME = 'basket_contents';

    public function getSqlDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getJsonTypeDeclarationSQL($fieldDeclaration);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        $basketContents = [];

        if ($value === null || $value === '') {
            return null;
        }

        if (is_resource($value)) {
            $value = stream_get_contents($value);
        }

        $val = json_decode($value, true);

        foreach ($val as $basketItem) {
            if(count($basketItem) === 0) {
                continue;
            }
            $item = new Item(
                new ItemType($basketItem['type']),
                new Weight($basketItem['weight'])
            );

            $basketContents[$basketItem['type']] = $item;
        }

        return $basketContents;
    }

    public function convertToDatabaseValue($items, AbstractPlatform $platform)
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

        return json_encode($resultItems);
    }

    public function getName()
    {
        return self::TYPE_NAME;
    }
}