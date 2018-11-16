<?php
/**
 * Created by PhpStorm.
 * User: mikhail
 * Date: 16.11.18
 * Time: 9:51
 */

namespace App\Domain\Basket;


interface BasketRepositoryContract
{
    public function get(BasketId $basketId): ?Basket;

    public function getAll(): array;

    public function store(Basket $basket): void;

    public function getNextId(): BasketId;

}