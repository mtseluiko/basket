<?php
/**
 * Created by PhpStorm.
 * User: mikhail
 * Date: 23.11.18
 * Time: 14:31
 */

namespace App\Tests\Feature\Fixtures;


use App\Domain\Basket\Basket;
use App\Domain\Basket\BasketId;
use App\Domain\Basket\BasketName;
use App\Domain\Basket\Weight;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class BasketsFixture implements FixtureInterface
{

    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 20; $i++) {
            $product = new Basket(
                BasketId::generate(),
                new BasketName('test'),
                new Weight(10000)
            );
            $manager->persist($product);
        }

        $manager->flush();
    }
}