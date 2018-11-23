<?php
/**
 * Created by PhpStorm.
 * User: mikhail
 * Date: 22.11.18
 * Time: 17:16
 */

namespace App\Tests\Unit\Application;


use App\Application\Actions\RemoveBasketAction\RemoveBasketAction;
use App\Application\Actions\RemoveBasketAction\RemoveBasketRequest;
use App\Domain\Basket\Basket;
use App\Domain\Basket\BasketId;
use App\Domain\Basket\BasketName;
use App\Domain\Basket\BasketRepositoryContract;
use App\Domain\Basket\Exceptions\BasketDoesNotExistsException;
use App\Domain\Basket\Weight;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class RemoveBasketActionTest extends TestCase
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

    public function testExecuteCallsRepositoryGetRemove()
    {
        $this->repositoryMock
            ->method('get')
            ->willReturn(
                $this->basket
            );
        $action = new RemoveBasketAction($this->repositoryMock);

        $this->repositoryMock->expects($this->once())
            ->method('get');
        $this->repositoryMock->expects($this->once())
            ->method('remove');

        $request = new RemoveBasketRequest(BasketId::generate());
        $action->execute($request);
    }

    public function testExecuteThrowsExceptionWhenBasketNotFound()
    {
        $this->expectException(BasketDoesNotExistsException::class);

        $this->repositoryMock
            ->method('get')
            ->willReturn(null);
        $action = new RemoveBasketAction($this->repositoryMock);

        $request = new RemoveBasketRequest(BasketId::generate());
        $action->execute($request);
    }
}