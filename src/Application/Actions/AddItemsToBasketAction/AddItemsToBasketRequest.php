<?php
/**
 * Created by PhpStorm.
 * User: mikhail
 * Date: 16.11.18
 * Time: 10:39
 */

namespace App\Application\Actions\AddItemsToBasketAction;


use App\Domain\Basket\BasketId;
use App\Http\Exceptions\RequireAttributeException;

class AddItemsToBasketRequest
{
    private $basketId;
    private $items;

    public function __construct(string $basketId, array $itemsRaw)
    {

        $this->basketId = BasketId::fromString($basketId);

        $items = [];
        foreach($itemsRaw as $rawItem) {
            if(!isset($rawItem['type'])) {
                throw new RequireAttributeException('type');
            }
            if(!isset($rawItem['weight'])) {
                throw new RequireAttributeException('weight');
            }

            $items[] = new ItemRequestDto(
                $rawItem['type'],
                $rawItem['weight']
            );
        }

        $this->items = $items;
    }

    public function basketId(): BasketId
    {
        return $this->basketId;
    }

    public function items(): array
    {
        return $this->items;
    }
}