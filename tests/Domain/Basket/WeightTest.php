<?php
/**
 * Created by PhpStorm.
 * User: mikhail
 * Date: 15.11.18
 * Time: 11:02
 */

namespace App\Tests\Domain\Basket;


use App\Domain\Basket\Exceptions\NegativeWeightException;
use App\Domain\Basket\Weight;
use PHPUnit\Framework\TestCase;

class WeightTest extends TestCase
{
    public function testSameValueAs()
    {
        $weight = 5;

        $weight1 = new Weight($weight);
        $weight2 = new Weight($weight);

        $this->assertTrue($weight1->sameValueAs($weight1));
        $this->assertTrue($weight2->sameValueAs($weight2));
    }

    public function testBasketWeightCannotBeNegative()
    {
        $this->expectException(NegativeWeightException::class);

        $weight = -5;

        new Weight($weight);
    }

}