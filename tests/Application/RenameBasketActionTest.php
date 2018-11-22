<?php
/**
 * Created by PhpStorm.
 * User: mikhail
 * Date: 22.11.18
 * Time: 17:16
 */

namespace App\Tests\Application;


use App\Application\Actions\RenameBasketAction\RenameBasketAction;
use App\Application\Actions\RenameBasketAction\RenameBasketRequest;
use App\Domain\Basket\Basket;
use App\Domain\Basket\BasketId;
use App\Domain\Basket\BasketName;
use App\Domain\Basket\BasketRepositoryContract;
use App\Domain\Basket\Exceptions\BasketDoesNotExistsException;
use App\Domain\Basket\Weight;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class RenameBasketActionTest extends TestCase
{
    /** @var BasketRepositoryContract|MockObject */
    private $repositoryMock;

    private $oldName;
    private $maxCapacity;
    private $basket;

    public function setUp()
    {
        $this->oldName = 'test';
        $this->maxCapacity = 100;

        $this->basket = new Basket(
            BasketId::generate(),
            new BasketName($this->oldName),
            new Weight($this->maxCapacity)
        );

        $this->repositoryMock = $this->getMockBuilder(BasketRepositoryContract::class)
            ->getMock();
    }

    /**
     * @dataProvider requestsProvider
     */
    public function testExecuteCallsRepositoryGetStore($newName)
    {
        $this->repositoryMock
            ->method('get')
            ->willReturn(
                $this->basket
            );
        $action = new RenameBasketAction($this->repositoryMock);

        $this->repositoryMock->expects($this->once())
            ->method('get');
        $this->repositoryMock->expects($this->once())
            ->method('store');

        $request = new RenameBasketRequest(BasketId::generate(), $newName);
        $action->execute($request);
    }

    /**
     * @dataProvider requestsProvider
     */
    public function testExecuteThrowsExceptionWhenBasketNotFound($newName)
    {
        $this->expectException(BasketDoesNotExistsException::class);

        $this->repositoryMock
            ->method('get')
            ->willReturn(null);

        $action = new RenameBasketAction($this->repositoryMock);

        $request = new RenameBasketRequest(BasketId::generate(), $newName);
        $action->execute($request);
    }

    /**
     * @dataProvider requestsProvider
     */
    public function testExecuteReturnsCorrectBasket($newName)
    {
        $this->repositoryMock
            ->method('get')
            ->willReturn(
                $this->basket
            );
        $action = new RenameBasketAction($this->repositoryMock);

        $request = new RenameBasketRequest(BasketId::generate(), $newName);

        $basket = $action->execute($request)->basket();

        $this->assertTrue($basket->name()->sameValueAs(new BasketName($newName)));
        $this->assertTrue($basket->maxCapacity()->sameValueAs(new Weight($this->maxCapacity)));
    }

    public function requestsProvider()
    {
        return [
            ['test1'],
            ['test2'],
            ['test3']
        ];
    }
}