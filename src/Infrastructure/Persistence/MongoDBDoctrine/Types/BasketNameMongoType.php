<?php

namespace App\Infrastructure\Persistence\MongoDBDoctrine\Types;

use App\Domain\Basket\BasketName;
use Doctrine\ODM\MongoDB\Types\Type;

class BasketNameMongoType extends Type
{
    const TYPE_NAME = 'basket_name_mongo';

    public function closureToPHP()
    {
        return '$return = new \App\Domain\Basket\BasketName($value);';
    }

    public function convertToDatabaseValue($value)
    {
        /** @var $value BasketName */
        return $value->name();
    }

    public function getName()
    {
        return self::TYPE_NAME;
    }
}