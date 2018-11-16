<?php
/**
 * Created by PhpStorm.
 * User: mikhail
 * Date: 16.11.18
 * Time: 16:24
 */

namespace App\Tests\Domain\Basket;


use App\Domain\Basket\Exceptions\ItemIncorrectTypeException;
use App\Domain\Basket\ItemType;
use PHPUnit\Framework\TestCase;

class ItemTypeTest extends TestCase
{
    public function testIncorrectTypeItem()
    {
        $this->expectException(ItemIncorrectTypeException::class);

        new ItemType('test');
    }
}