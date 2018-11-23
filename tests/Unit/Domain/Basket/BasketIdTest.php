<?php
/**
 * Created by PhpStorm.
 * User: mikhail
 * Date: 15.11.18
 * Time: 11:02
 */

namespace App\Tests\Unit\Domain\Basket;


use PHPUnit\Framework\TestCase;
use App\Domain\Basket\BasketId;
use Ramsey\Uuid\Uuid;

class BasketIdTest extends TestCase
{
    public function testSameValueAs()
    {
        $uuid = Uuid::uuid4();

        $basketId1 = BasketId::fromString($uuid);
        $basketId2 = BasketId::fromString($uuid);
        $this->assertTrue($basketId1->sameValueAs($basketId2));
        $this->assertTrue($basketId2->sameValueAs($basketId1));
    }
}
