<?php
/**
 * Created by PhpStorm.
 * User: mikhail
 * Date: 16.11.18
 * Time: 10:39
 */

namespace App\Application\Actions\RenameBasketAction;


use App\Domain\Basket\BasketId;
use App\Domain\Basket\BasketName;

class RenameBasketRequest
{
    private $basketId;
    private $newName;

    public function __construct(string $id, string $newName)
    {
        $this->basketId = BasketId::fromString($id);
        $this->newName = new BasketName($newName);
    }

    public function basketId(): BasketId
    {
        return $this->basketId;
    }

    public function newName(): BasketName
    {
        return $this->newName;
    }
}