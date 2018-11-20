<?php
/**
 * Created by PhpStorm.
 * User: mikhail
 * Date: 16.11.18
 * Time: 10:39
 */

namespace App\Application\Actions\RemoveBasketAction;


use App\Domain\Basket\BasketId;

class RemoveBasketRequest
{
    private $basketId;

    public function __construct(string $id)
    {
        $this->basketId = BasketId::fromString($id);
    }

    public function basketId(): BasketId
    {
        return $this->basketId;
    }
}