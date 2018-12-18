<?php
/**
 * Created by PhpStorm.
 * User: mikhail
 * Date: 13.11.18
 * Time: 15:45
 */

namespace App\Domain\Basket;


use App\Domain\Basket\Exceptions\BasketContentsRemoveMoreItemsThanExistsException;
use App\Domain\Basket\Exceptions\BasketOverflowException;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document(collection="baskets", repositoryClass="App\Infrastructure\Persistence\MongoDBDoctrine\DoctrineBasketMongoRepository")
 */
class Basket
{
    /** @MongoDB\Id(strategy="NONE", type="basket_id_mongo") */
    private $id;
    /** @MongoDB\Field(type="basket_name_mongo") */
    private $name;
    /** @MongoDB\Field(type="basket_capacity_mongo") */
    private $maxCapacity;
    /** @MongoDB\Field(type="basket_contents_mongo") */
    private $contents;

    public function __construct(BasketId $id, BasketName $name, Weight $maxCapacity)
    {
        $this->id = $id;
        $this->name = $name;
        $this->maxCapacity = $maxCapacity;
        $this->contents = [];
    }

    public function id(): BasketId
    {
        return $this->id;
    }

    public function name(): BasketName
    {
        return $this->name;
    }

    public function maxCapacity(): Weight
    {
        return $this->maxCapacity;
    }

    public function contents(): array
    {
        return $this->contents;
    }

    public function currentWeight(): Weight
    {
        $totalWeight = new Weight;

        /* @var $item Item */
        foreach ($this->contents() as $item) {
            $totalWeight = $totalWeight->add($item->weight());
        }

        return $totalWeight;
    }

    public function rename(BasketName $name): void
    {
        $this->name = $name;
    }

    private function canAddWeight(Weight $weight): bool
    {
        $weightWithItem = $this->currentWeight()->add($weight);

        return $weightWithItem->weight() <= $this->maxCapacity()->weight();
    }

    private function hasItemWithType(ItemType $type): bool
    {
        $typeName = $type->typeName();

        if (isset($this->contents()[$typeName])) {
            $item = $this->contents()[$typeName];
            return !$item->weight()->isZero();
        }

        return false;
    }

    public function addItem(string $itemTypeName, float $weightValue): void
    {
        $itemType = new ItemType($itemTypeName);
        $weight = new Weight($weightValue);

        if (!$this->canAddWeight($weight)) {
            throw new BasketOverflowException;
        }

        $currentItems = $this->contents();

        if ($this->hasItemWithType($itemType)) {
            $currentItems[$itemTypeName] = $currentItems[$itemTypeName]->addWeight($weight);
        } else {

            $currentItems[$itemTypeName] = new Item($itemType, $weight);
        }

        $this->contents = $currentItems;
    }

    public function removeItem(string $itemTypeName, float $weightValue): void
    {
        $currentItems = $this->contents();
        $itemType = new ItemType($itemTypeName);
        $weight = new Weight($weightValue);
        if (
            $this->hasItemWithType($itemType) &&
            $currentItems[$itemTypeName]->weight()->weight() > $weightValue
        ) {
            $currentItems[$itemTypeName] = $currentItems[$itemTypeName]->subtractWeight($weight);
        } else {
            throw new BasketContentsRemoveMoreItemsThanExistsException;
        }

        $this->contents = $currentItems;
    }

    public function removeAllItemsByType(string $itemTypeName): void
    {
        $currentItems = $this->contents();
        unset($currentItems[$itemTypeName]);
        $this->contents = $currentItems;
    }

    public function getContentsJson(): string
    {
        $resultItems = [];
        foreach ($this->contents() as $item) {
            /* @var $item Item */
            $type = $item->type()->typeName();
            $weight = $item->weight()->weight();

            if(isset($resultItems[$type])) {
                $weight = $resultItems[$type]['weight'] += $weight;
            }

            $resultItems[$item->type()->typeName()] = [
                'type' => $type,
                'weight' => $weight
            ];
        }

        return json_encode($resultItems);
    }

}