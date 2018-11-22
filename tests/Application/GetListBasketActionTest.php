<?php
/**
 * Created by PhpStorm.
 * User: mikhail
 * Date: 22.11.18
 * Time: 17:16
 */

namespace App\Tests\Application;


use App\Application\Actions\GetBasketListAction\GetBasketListAction;
use App\Application\Actions\GetBasketListAction\GetBasketListRequest;
use App\Domain\Basket\Basket;
use App\Domain\Basket\BasketId;
use App\Domain\Basket\BasketName;
use App\Domain\Basket\BasketRepositoryContract;
use App\Domain\Basket\Weight;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class GetBasketListActionTest extends TestCase
{
    /** @var BasketRepositoryContract|MockObject */
    private $repositoryMock;

    public function setUp()
    {
        $this->repositoryMock = $this->getMockBuilder(BasketRepositoryContract::class)
            ->getMock();
    }

    public function testExecuteCallsRepositoryGetAll()
    {
        $basketList =
            [
                new Basket(
                    BasketId::generate(),
                    new BasketName('test'),
                    new Weight(1)
                )
            ];

        $this->repositoryMock
            ->method('getAll')
            ->willReturn($basketList);

        $action = new GetBasketListAction($this->repositoryMock);

        $this->repositoryMock->expects($this->once())
            ->method('getAll');

        $action->execute(new GetBasketListRequest);
    }
}