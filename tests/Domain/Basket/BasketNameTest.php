<?php
/**
 * Created by PhpStorm.
 * User: mikhail
 * Date: 15.11.18
 * Time: 11:02
 */

namespace App\Tests\Domain\Basket;


use App\Domain\Basket\Exceptions\BasketEmptyNameException;
use App\Domain\Basket\Exceptions\BasketNameIncorrectLengthException;
use PHPUnit\Framework\TestCase;
use App\Domain\Basket\BasketName;

class BasketNameTest extends TestCase
{
    public function testSameValueAs()
    {
        $name = 'test';

        $basketName1 = new BasketName($name);
        $basketName2 = new BasketName($name);

        $this->assertTrue($basketName1->sameValueAs($basketName2));
        $this->assertTrue($basketName2->sameValueAs($basketName1));
    }

    public function testBasketNameCannotBeEmpty()
    {
        $this->expectException(BasketEmptyNameException::class);

        $name = '';

        new BasketName($name);
    }

    public function testBasketNameTooLong()
    {
        $this->expectException(BasketNameIncorrectLengthException::class);

        $name = str_repeat('t',BasketName::BASKET_NAME_MAX_LENGTH + 5);

        new BasketName($name);
    }

    public function testNameTooShort()
    {
        $this->expectException(BasketNameIncorrectLengthException::class);

        $name =  str_repeat('t',BasketName::BASKET_NAME_MIN_LENGTH - 1);

        new BasketName($name);
    }
}