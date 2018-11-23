<?php
/**
 * Created by PhpStorm.
 * User: mikhail
 * Date: 22.11.18
 * Time: 17:16
 */

namespace App\Tests\Unit\Application;


use App\Application\Actions\AddItemsToBasketAction\AddItemsToBasketAction;
use App\Application\Actions\AddItemsToBasketAction\AddItemsToBasketRequest;
use App\Domain\Basket\Basket;
use App\Domain\Basket\BasketId;
use App\Domain\Basket\BasketName;
use App\Domain\Basket\BasketRepositoryContract;
use App\Domain\Basket\Exceptions\BasketDoesNotExistsException;
use App\Domain\Basket\Item;
use App\Domain\Basket\Weight;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class AddItemsToBasketActionTest extends TestCase
{
    /** @var BasketRepositoryContract|MockObject */
    private $repositoryMock;

    private $name;
    private $maxCapacity;
    private $basket;

    public function setUp()
    {
        $this->name = 'test';
        $this->maxCapacity = 100;

        $this->basket = new Basket(
            BasketId::generate(),
            new BasketName($this->name),
            new Weight($this->maxCapacity)
        );

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
                $this->basket
            );
        $action = new AddItemsToBasketAction($this->repositoryMock);

        $this->repositoryMock->expects($this->once())
            ->method('get');
        $this->repositoryMock->expects($this->once())
            ->method('store');

        $request = new AddItemsToBasketRequest(BasketId::generate(), $items);
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

        $action = new AddItemsToBasketAction($this->repositoryMock);

        $request = new AddItemsToBasketRequest(BasketId::generate(), $items);
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
                $this->basket
            );
        $action = new AddItemsToBasketAction($this->repositoryMock);

        $request = new AddItemsToBasketRequest(BasketId::generate(), $items);

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
            $this->assertEquals(
                $itemsByType[$item->type()->typeName()],
                $item->weight()->weight()
            );
        }
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
}