<?php

namespace App\Infrastructure\Persistence\MongoDBDoctrine\Types;

use Doctrine\ODM\MongoDB\Types\Type;

class BasketIdMongoType extends Type
{
    const TYPE_NAME = 'basket_id_mongo';

    public function closureToPHP()
    {
        return '$return = \App\Domain\Basket\BasketId::fromString($value);';
    }

    public function convertToDatabaseValue($value)
    {
        if (gettype($value) === 'string') {
            return $value;
        }
        return $value->id();
    }

    public function getName()
    {
        return self::TYPE_NAME;
    }
}