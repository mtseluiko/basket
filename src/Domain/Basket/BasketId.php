<?php
/**
 * Created by PhpStorm.
 * User: mikhail
 * Date: 15.11.18
 * Time: 10:00
 */

namespace App\Domain\Basket;


use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class BasketId
{
    protected $id;

    public function __construct(UuidInterface $uuid)
    {
        $this->id = $uuid;
    }

    public function toString(): string
    {
        return $this->id->toString();
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    public function id(): string
    {
        return $this->id;
    }

    public function sameValueAs(self $otherId): bool
    {
        return $this->id() === $otherId->id();
    }

    public static function generate(): BasketId
    {
        return new self(Uuid::uuid4());
    }

    public static function fromString(string $basketId): BasketId
    {
        return new self(Uuid::fromString($basketId));
    }
}

