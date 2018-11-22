<?php
/**
 * Created by PhpStorm.
 * User: mikhail
 * Date: 22.11.18
 * Time: 17:16
 */

namespace App\Tests\Application;


use App\Application\Actions\AddBasketAction\AddBasketAction;
use App\Application\Actions\AddBasketAction\AddBasketRequest;
use App\Domain\Basket\BasketName;
use App\Domain\Basket\BasketRepositoryContract;
use App\Domain\Basket\Weight;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class AddBasketActionTest extends TestCase
{
    /** @var BasketRepositoryContract|MockObject */
    private $repositoryMock;

    /** @var AddBasketAction */
    private $action;

    public function setUp()
    {
        $this->repositoryMock = $this->getMockBuilder(BasketRepositoryContract::class)
            ->getMock();
        $this->action = new AddBasketAction($this->repositoryMock);
    }

    /**
     * @dataProvider requestsProvider
     */
    public function testExecuteCallsRepositoryStore($name, $maxCapacity)
    {
        $this->repositoryMock->expects($this->once())
            ->method('store');

        $request = new AddBasketRequest($name, $maxCapacity);
        $this->action->execute($request);
    }

    /**
     * @dataProvider requestsProvider
     */
    public function testExecuteReturnsCorrectBasket($name, $maxCapacity)
    {
        $request = new AddBasketRequest($name, $maxCapacity);

        $basket = $this->action->execute($request)->basket();

        $this->assertTrue($basket->name()->sameValueAs(new BasketName($name)));
        $this->assertTrue($basket->maxCapacity()->sameValueAs(new Weight($maxCapacity)));
    }

    public function requestsProvider()
    {
        return [
            ['test1', 5],
            ['test2', 0.5],
            ['test3', 100.15]
        ];
    }
}