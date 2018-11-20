<?php
/**
 * Created by PhpStorm.
 * User: mikhail
 * Date: 16.11.18
 * Time: 10:39
 */

namespace App\Application\Actions\AddBasketAction;


use App\Domain\Basket\BasketName;
use App\Domain\Basket\Weight;

class AddBasketRequest
{
    private $name;
    private $maxCapacity;

    public function __construct(string $name, float $maxCapacity)
    {
        $this->name = new BasketName($name);
        $this->maxCapacity = new Weight($maxCapacity);
    }

    public function name(): BasketName
    {
        return $this->name;
    }

    public function maxCapacity(): Weight
    {
        return $this->maxCapacity;
    }
}