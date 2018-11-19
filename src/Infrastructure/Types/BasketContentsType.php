<?php

namespace App\Infrastructure\Types;

use App\Domain\Basket\BasketContents;
use App\Domain\Basket\Item;
use App\Domain\Basket\ItemType;
use App\Domain\Basket\Weight;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class BasketContentsType extends Type
{
    const TYPE_NAME = 'basket_contents'; // modify to match your type name

    public function getSqlDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getJsonTypeDeclarationSQL($fieldDeclaration);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        $basketContents = new BasketContents;

        if ($value === null || $value === '') {
            return null;
        }

        if (is_resource($value)) {
            $value = stream_get_contents($value);
        }

        $val = json_decode($value, true);

        foreach ($val as $basketItem) {
            $item = new Item(
                new ItemType($basketItem['type']),
                new Weight($basketItem['weight'])
            );

            $basketContents->addItem($item);
        }

        return $basketContents;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {

        $items = [];
        /* @var $value BasketContents */
        foreach ($value->items() as $item) {
            /* @var $item Item */
            $items[] = [
                'type' => $item->type()->typeName(),
                'weight' => $item->weight()->weight()
            ];
        }

        return json_encode($items);
    }

    public function getName()
    {
        return self::TYPE_NAME;
    }
}