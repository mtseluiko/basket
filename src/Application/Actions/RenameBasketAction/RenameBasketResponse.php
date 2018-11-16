<?php
/**
 * Created by PhpStorm.
 * User: mikhail
 * Date: 16.11.18
 * Time: 10:39
 */

namespace App\Application\Actions\RenameBasketAction;


use App\Domain\Basket\Basket;

class RenameBasketResponse
{
    private $basket;

    public function __construct(Basket $basket)
    {
        $this->basket = $basket;
    }

    public function basket(): Basket
    {
        return $this->basket;
    }
}