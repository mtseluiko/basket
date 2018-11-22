<?php
/**
 * Created by PhpStorm.
 * User: mikhail
 * Date: 22.11.18
 * Time: 17:16
 */

namespace App\Tests\Application;


use App\Application\Actions\GetBasketAction\GetBasketAction;
use App\Application\Actions\GetBasketAction\GetBasketRequest;
use App\Domain\Basket\Basket;
use App\Domain\Basket\BasketId;
use App\Domain\Basket\BasketName;
use App\Domain\Basket\BasketRepositoryContract;
use App\Domain\Basket\Exceptions\BasketDoesNotExistsException;
use App\Domain\Basket\Weight;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class GetBasketActionTest extends TestCase
{
    /** @var BasketRepositoryContract|MockObject */
    private $repositoryMock;

    public function setUp()
    {
        $this->repositoryMock = $this->getMockBuilder(BasketRepositoryContract::class)
            ->getMock();
    }

    public function testExecuteCallsRepositoryGet()
    {
        $basket = new Basket(
            BasketId::generate(),
            new BasketName('test'),
            new Weight(1)
        );

        $this->repositoryMock
            ->method('get')
            ->willReturn($basket);

        $action = new GetBasketAction($this->repositoryMock);

        $this->repositoryMock->expects($this->once())
            ->method('get');

        $request = new GetBasketRequest(BasketId::generate());
        $action->execute($request);
    }

    public function testExecuteThrowsExceptionWhenBasketNotFound()
    {
        $this->expectException(BasketDoesNotExistsException::class);

        $this->repositoryMock
            ->method('get')
            ->willReturn(null);

        $action = new GetBasketAction($this->repositoryMock);

        $request = new GetBasketRequest(BasketId::generate());
        $action->execute($request);
    }
}