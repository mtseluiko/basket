<?php
/**
 * Created by PhpStorm.
 * User: mikhail
 * Date: 16.11.18
 * Time: 10:39
 */

namespace App\Application\Actions\RemoveItemsFromBasketAction;


use App\Domain\Basket\BasketId;
use App\Http\Exceptions\RequireAttributeException;

class RemoveItemsFromBasketRequest
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

            $items[] = new ItemRequestDto(
                $rawItem['type'],
                $rawItem['weight'] ?? null
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