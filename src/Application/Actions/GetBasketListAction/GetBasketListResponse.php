<?php
/**
 * Created by PhpStorm.
 * User: mikhail
 * Date: 16.11.18
 * Time: 10:39
 */

namespace App\Application\Actions\GetBasketListAction;


class GetBasketListResponse
{
    private $basketList;

    public function __construct(array $basketList)
    {
        $this->basketList = $basketList;
    }

    public function basketList(): array
    {
        return $this->basketList;
    }
}