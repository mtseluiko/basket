<?php

namespace App\Infrastructure;

use App\Domain\Basket\BasketId;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Id\AbstractIdGenerator;

class BasketIdGenerator extends AbstractIdGenerator
{
    public function generate(EntityManager $em, $entity)
    {
        return BasketId::generate();
    }
}
