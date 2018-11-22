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
    /* @var $basket Basket */
    private $basket;
    private $weight;
    private $basketName;

    protected function setUp()
    {
        $this->basketName = 'test';
        $this->weight = 10;
        $this->basket = new Basket(
            BasketId::generate(),
            new BasketName($this->basketName),
            new Weight($this->weight)
        );
    }

    public function testRename()
    {
        $newNameRaw = 'test1';
        $newName = new BasketName($newNameRaw);

        $this->basket->rename($newName);

        $this->assertEquals($newNameRaw, $this->basket->name()->name());

    }

    /**
     * @dataProvider providerItems
     */
    public function testAddItem($item1Type, $item1Weight, $item2Type, $item2Weight)
    {
        $items = [];

        $item1 = new Item(
            new ItemType($item1Type),
            new Weight($item1Weight)
        );
        $items[$item1Type] = $item1;

        $this->basket->addItem($item1Type, $item1Weight);
        $this->assertEquals(count($this->basket->contents()), 1);
        $this->assertEquals($this->basket->contents(), $items);

        $item2 = new Item(
            new ItemType($item2Type),
            new Weight($item2Weight)
        );
        $items[$item2Type] = $item2;

        $this->basket->addItem($item2Type, $item2Weight);

        $this->assertEquals(count($this->basket->contents()), 2);
        $this->assertEquals($this->basket->contents(), $items);

        /* @var $basketItems Item[] */
        $basketItems = $this->basket->contents();
        $this->assertEquals($basketItems[$item1Type]->weight()->weight(), $item1Weight);
        $this->assertEquals($basketItems[$item2Type]->weight()->weight(), $item2Weight);
    }

    /**
     * @dataProvider providerItems
     */
    public function testAddSameItem($item1Type, $item1Weight, $item2Type, $item2Weight)
    {
        $items = [];

        $item1 = new Item(
            new ItemType($item1Type),
            new Weight($item1Weight)
        );
        $items[$item1Type] = $item1;

        $this->basket->addItem($item1Type, $item1Weight);

        $this->assertEquals(count($this->basket->contents()), 1);
        $this->assertEquals($this->basket->contents(), $items);

        $this->basket->addItem($item1Type, $item2Weight);

        $this->assertEquals(count($this->basket->contents()), 1);

        /* @var $basketItems Item[] */
        $basketItems = $this->basket->contents();
        $this->assertEquals(
            $basketItems[$item1Type]->weight()->weight(),
            $item1Weight + $item2Weight
        );
    }

    /**
     * @dataProvider providerItems
     */
    public function testAddItemOverflow($item1Type)
    {
        $this->expectException(BasketOverflowException::class);

        $item1Weight = $this->weight + 0.1;

        $this->basket->addItem($item1Type, $item1Weight);
    }

    /**
     * @dataProvider providerItems
     */
    public function testRemoveItem($item1Type, $item1Weight)
    {
        $this->basket->addItem($item1Type, $item1Weight);

        $item2Weight = $item1Weight - 1;

        $this->basket->removeItem($item1Type, $item2Weight);

        /* @var $basketItems Item[] */
        $basketItems = $this->basket->contents();
        $this->assertEquals(
            $basketItems[$item1Type]->weight()->weight(),
            $item1Weight - $item2Weight
        );
    }

    /**
     * @dataProvider providerItems
     */
    public function testCannotRemoveItemMoreThanExists($item1Type, $item1Weight)
    {
        $this->expectException(BasketContentsRemoveMoreItemsThanExistsException::class);

        $item2Weight = $item1Weight + 1;

        $this->basket->addItem($item1Type, $item1Weight);

        $this->basket->removeItem($item1Type, $item2Weight);
    }

    public function providerItems()
    {
        return [
            ['apple', 5, 'orange', 3.25],
            ['orange', 3.1, 'apple', 2],
            ['watermelon', 1, 'apple', 1],
            ['orange', 5, 'watermelon', 1]
        ];
    }
}