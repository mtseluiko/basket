<?php
/**
 * Created by PhpStorm.
 * User: mikhail
 * Date: 22.11.18
 * Time: 17:16
 */

namespace App\Tests\Unit\Application;


use App\Application\Actions\RemoveItemsFromBasketAction\RemoveItemsFromBasketAction;
use App\Application\Actions\RemoveItemsFromBasketAction\RemoveItemsFromBasketRequest;
use App\Domain\Basket\Basket;
use App\Domain\Basket\BasketId;
use App\Domain\Basket\BasketName;
use App\Domain\Basket\BasketRepositoryContract;
use App\Domain\Basket\Exceptions\BasketContentsRemoveMoreItemsThanExistsException;
use App\Domain\Basket\Exceptions\BasketDoesNotExistsException;
use App\Domain\Basket\Item;
use App\Domain\Basket\Weight;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class RemoveItemsFromBasketActionTest extends TestCase
{
    /** @var BasketRepositoryContract|MockObject */
    private $repositoryMock;

    /** @var Basket */
    private $basketWithItems;

    private $startQuantityOfEachItem;
    private $name;
    private $maxCapacity;

    public function setUp()
    {
        $this->startQuantityOfEachItem = 30;
        $this->name = 'test';
        $this->maxCapacity = 100;

        $this->basketWithItems = new Basket(
            BasketId::generate(),
            new BasketName($this->name),
            new Weight($this->maxCapacity)
        );
        $this->fillBasket();

        $this->repositoryMock = $this->getMockBuilder(BasketRepositoryContract::class)
            ->getMock();
    }

    /**
     * @dataProvider requestsProvider
     */
    public function testExecuteCallsRepositoryGetStore($items)
    {
        $this->repositoryMock
            ->method('get')
            ->willReturn(
                $this->basketWithItems
            );
        $action = new RemoveItemsFromBasketAction($this->repositoryMock);

        $this->repositoryMock->expects($this->once())
            ->method('get');
        $this->repositoryMock->expects($this->once())
            ->method('store');

        $request = new RemoveItemsFromBasketRequest(BasketId::generate(), $items);
        $action->execute($request);
    }

    /**
     * @dataProvider requestsProvider
     */
    public function testExecuteThrowsExceptionWhenBasketNotFound($items)
    {
        $this->expectException(BasketDoesNotExistsException::class);

        $this->repositoryMock
            ->method('get')
            ->willReturn(null);

        $action = new RemoveItemsFromBasketAction($this->repositoryMock);

        $request = new RemoveItemsFromBasketRequest(BasketId::generate(), $items);
        $action->execute($request);
    }

    /**
     * @dataProvider requestsProvider
     */
    public function testExecuteReturnsCorrectBasket($items)
    {
        $this->repositoryMock
            ->method('get')
            ->willReturn(
                $this->basketWithItems
            );
        $action = new RemoveItemsFromBasketAction($this->repositoryMock);

        $request = new RemoveItemsFromBasketRequest(BasketId::generate(), $items);

        $basket = $action->execute($request)->basket();

        $this->assertTrue($basket->name()->sameValueAs(new BasketName($this->name)));
        $this->assertTrue($basket->maxCapacity()->sameValueAs(new Weight($this->maxCapacity)));

        $itemsByType = [];

        foreach ($items as $item) { //calculate total weight sum
            $type = $item['type'];
            if (isset($itemsByType[$type])) {
                $itemsByType[$type] += $item['weight'];
            } else {
                $itemsByType[$type] = $item['weight'];
            }
        }

        foreach ($basket->contents() as $item) {
            /* @var $item Item */
            $type = $item->type()->typeName();
            if (!isset($itemsByType[$type])) {
                continue;
            }
            $this->assertEquals(
                $this->startQuantityOfEachItem - $itemsByType[$type],
                $item->weight()->weight()
            );
        }
    }

    /**
     * @dataProvider requestsProvider
     */
    public function testExecuteCannotRemoveFromEmptyBasket($items)
    {
        $this->expectException(BasketContentsRemoveMoreItemsThanExistsException::class);

        $emptyBasket = new Basket(
            BasketId::generate(),
            new BasketName($this->name),
            new Weight($this->maxCapacity)
        );

        $this->repositoryMock
            ->method('get')
            ->willReturn(
                $emptyBasket
            );

        $action = new RemoveItemsFromBasketAction($this->repositoryMock);

        $request = new RemoveItemsFromBasketRequest(BasketId::generate(), $items);

        $action->execute($request);
    }

    public function requestsProvider()
    {
        $items =
            [
                [
                    [
                        ['type' => 'apple', 'weight' => 5],
                        ['type' => 'orange', 'weight' => 10]
                    ],
                    [
                        ['type' => 'apple', 'weight' => 5],
                        ['type' => 'watermelon', 'weight' => 6.3],
                        ['type' => 'apple', 'weight' => 5.25]
                    ],
                    [
                        ['type' => 'orange', 'weight' => 10]
                    ],
                    [],
                ]
            ];
        return $items;
    }

    private function fillBasket()
    {
        $this->basketWithItems->addItem('apple', $this->startQuantityOfEachItem);
        $this->basketWithItems->addItem('watermelon', $this->startQuantityOfEachItem);
        $this->basketWithItems->addItem('orange', $this->startQuantityOfEachItem);
    }
}