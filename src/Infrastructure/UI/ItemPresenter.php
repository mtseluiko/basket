<?php

namespace App\Infrastructure\UI;


use App\Domain\Basket\Item;

class ItemPresenter
{
    public function presentItem(Item $item): array
    {
        $result = [
            "type" => $item->type()->typeName(),
            "weight" => $item->weight()->weight()
        ];

        return $result;
    }

}