<?php
/**
 * Created by PhpStorm.
 * User: mikhail
 * Date: 16.11.18
 * Time: 15:34
 */

namespace App\Tests\Domain\Basket;


use App\Domain\Basket\BasketId;
use App\Domain\Basket\BasketName;
use App\Domain\Basket\Exceptions\BasketContentsRemoveMoreItemsThanExistsException;
use App\Domain\Basket\Exceptions\BasketOverflowException;
use App\Domain\Basket\Item;
use App\Domain\Basket\ItemType;
use App\Domain\Basket\Weight;
use PHPUnit\Framework\TestCase;
use App\Domain\Basket\Basket;

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

        $item1 = new Item(
            new ItemType('apple'),
            new Weight(5)
        );
        $items['apple'] = $item1;

        $basket->addItem($item1);

        $this->assertEquals(count($basket->contents()->items()), 1);
        $this->assertEquals($basket->contents()->items(), $items);

        $item2 = new Item(
            new ItemType('orange'),
            new Weight(1)
        );
        $items['orange'] = $item2;

        $basket->addItem($item2);

        $this->assertEquals(count($basket->contents()->items()), 2);
        $this->assertEquals($basket->contents()->items(), $items);

        /* @var $basketItems Item[] */
        $basketItems = $basket->contents()->items();
        $this->assertEquals($basketItems['apple']->weight()->weight(), 5);
        $this->assertEquals($basketItems['orange']->weight()->weight(), 1);
    }

    public function testAddSameItem()
    {
        $basket = new Basket(
            BasketId::generate(),
            $this->name,
            $this->maxCapacity
        );

        $items = [];

        $item1 = new Item(
            new ItemType('apple'),
            new Weight(5)
        );
        $items['apple'] = $item1;

        $basket->addItem($item1);

        $this->assertEquals(count($basket->contents()->items()), 1);
        $this->assertEquals($basket->contents()->items(), $items);

        $item2 = new Item(
            new ItemType('apple'),
            new Weight(1)
        );

        $basket->addItem($item2);

        $this->assertEquals(count($basket->contents()->items()), 1);

        /* @var $basketItems Item[] */
        $basketItems = $basket->contents()->items();
        $this->assertEquals($basketItems['apple']->weight()->weight(), 6);
    }

    public function testAddItemOverflow()
    {
        $this->expectException(BasketOverflowException::class);

        $basket = new Basket(
            BasketId::generate(),
            $this->name,
            $this->maxCapacity
        );

        $items = [];

        $item1 = new Item(
            new ItemType('apple'),
            new Weight(10.1)
        );
        $items['apple'] = $item1;

        $basket->addItem($item1);
    }

    public function testRemoveItem()
    {
        $basket = new Basket(
            BasketId::generate(),
            $this->name,
            $this->maxCapacity
        );

        $item1 = new Item(
            new ItemType('apple'),
            new Weight(5)
        );

        $basket->addItem($item1);

        $item2 = new Item(
            new ItemType('apple'),
            new Weight(2)
        );

        $basket->removeItem($item2);

        /* @var $basketItems Item[] */
        $basketItems = $basket->contents()->items();
        $this->assertEquals($basketItems['apple']->weight()->weight(), 3);
    }


    public function testCannotRemoveItemMoreThanExists()
    {
        $this->expectException(BasketContentsRemoveMoreItemsThanExistsException::class);

        $basket = new Basket(
            BasketId::generate(),
            $this->name,
            $this->maxCapacity
        );

        $item1 = new Item(
            new ItemType('apple'),
            new Weight(5)
        );

        $basket->addItem($item1);

        $item2 = new Item(
            new ItemType('apple'),
            new Weight(6)
        );

        $basket->removeItem($item2);
    }
}