<?php
/**
 * Created by PhpStorm.
 * User: mikhail
 * Date: 16.11.18
 * Time: 15:34
 */

namespace App\Tests\Domain\Basket;


use App\Domain\Basket\Basket;
use App\Domain\Basket\BasketId;
use App\Domain\Basket\BasketName;
use App\Domain\Basket\Exceptions\BasketContentsRemoveMoreItemsThanExistsException;
use App\Domain\Basket\Exceptions\BasketOverflowException;
use App\Domain\Basket\Item;
use App\Domain\Basket\ItemType;
use App\Domain\Basket\Weight;
use PHPUnit\Framework\TestCase;

class BasketTest extends TestCase
{
    private $name;
    private $maxCapacity;

    protected function setUp()
    {
        $this->name = new BasketName('test');
        $this->maxCapacity = new Weight(10);
    }

    public function testRename()
    {
        $basket = new Basket(
            BasketId::generate(),
            $this->name,
            $this->maxCapacity
        );

        $newNameRaw = 'test1';
        $newName = new BasketName($newNameRaw);

        $basket->rename($newName);

        $this->assertEquals($newNameRaw, $basket->name()->name());

    }

    public function testAddItem()
    {
        $basket = new Basket(
            BasketId::generate(),
            $this->name,
            $this->maxCapacity
        );

        $items = [];

        $item1Type = 'apple';
        $item1Weight = 5;

        $item1 = new Item(
            new ItemType($item1Type),
            new Weight($item1Weight)
        );
        $items['apple'] = $item1;

        $basket->addItem($item1Type, $item1Weight);
        $this->assertEquals(count($basket->contents()), 1);
        $this->assertEquals($basket->contents(), $items);

        $item2Type = 'orange';
        $item2Weight = 1;

        $item2 = new Item(
            new ItemType($item2Type),
            new Weight($item2Weight)
        );
        $items['orange'] = $item2;

        $basket->addItem($item2Type, $item2Weight);

        $this->assertEquals(count($basket->contents()), 2);
        $this->assertEquals($basket->contents(), $items);

        /* @var $basketItems Item[] */
        $basketItems = $basket->contents();
        $this->assertEquals($basketItems[$item1Type]->weight()->weight(), $item1Weight);
        $this->assertEquals($basketItems[$item2Type]->weight()->weight(), $item2Weight);
    }

    public function testAddSameItem()
    {
        $basket = new Basket(
            BasketId::generate(),
            $this->name,
            $this->maxCapacity
        );

        $items = [];

        $item1Type = 'apple';
        $item1Weight = 5;

        $item1 = new Item(
            new ItemType($item1Type),
            new Weight($item1Weight)
        );
        $items['apple'] = $item1;

        $basket->addItem($item1Type, $item1Weight);

        $this->assertEquals(count($basket->contents()), 1);
        $this->assertEquals($basket->contents(), $items);

        $item2Weight = 1;

        $basket->addItem($item1Type, $item2Weight);

        $this->assertEquals(count($basket->contents()), 1);

        /* @var $basketItems Item[] */
        $basketItems = $basket->contents();
        $this->assertEquals(
            $basketItems[$item1Type]->weight()->weight(),
            $item1Weight + $item2Weight
        );
    }

    public function testAddItemOverflow()
    {
        $this->expectException(BasketOverflowException::class);

        $basket = new Basket(
            BasketId::generate(),
            $this->name,
            $this->maxCapacity
        );

        $item1Type = 'apple';
        $item1Weight = 10.1;

        $basket->addItem($item1Type, $item1Weight);
    }

    public function testRemoveItem()
    {
        $basket = new Basket(
            BasketId::generate(),
            $this->name,
            $this->maxCapacity
        );

        $item1Type = 'apple';
        $item1Weight = 5;

        $basket->addItem($item1Type, $item1Weight);

        $item2Weight = 2;

        $basket->removeItem($item1Type, $item2Weight);

        /* @var $basketItems Item[] */
        $basketItems = $basket->contents();
        $this->assertEquals(
            $basketItems['apple']->weight()->weight(),
            $item1Weight - $item2Weight
        );
    }


    public function testCannotRemoveItemMoreThanExists()
    {
        $this->expectException(BasketContentsRemoveMoreItemsThanExistsException::class);

        $basket = new Basket(
            BasketId::generate(),
            $this->name,
            $this->maxCapacity
        );

        $item1Type = 'apple';
        $item1Weight = 5;

        $basket->addItem($item1Type, $item1Weight);

        $item2Type = 'apple';
        $item2Weight = 6;

        $basket->removeItem($item2Type, $item2Weight);
    }
}