<?php

namespace App\Infrastructure\Persistence\MongoDBDoctrine\Types;

use App\Domain\Basket\Weight;
use Doctrine\ODM\MongoDB\Types\Type;

class BasketCapacityMongoType extends Type
{
    const TYPE_NAME = 'basket_capacity_mongo';

    public function closureToPHP()
    {
        return '$return = new \App\Domain\Basket\Weight($value);';
    }

    public function convertToDatabaseValue($value)
    {
        /** @var $value Weight */
        return $value->weight();
    }

    public function getName()
    {
        return self::TYPE_NAME;
    }
}