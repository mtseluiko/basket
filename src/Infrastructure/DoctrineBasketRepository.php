<?php
/**
 * Created by PhpStorm.
 * User: mikhail
 * Date: 16.11.18
 * Time: 10:10
 */

namespace App\Infrastructure;


use App\Domain\Basket\Basket;
use App\Domain\Basket\BasketId;
use App\Domain\Basket\BasketRepositoryContract;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class DoctrineBasketRepository extends ServiceEntityRepository
    implements BasketRepositoryContract
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Basket::class);
    }

    public function get(BasketId $basketId): ?Basket
    {
        return $this->find($basketId);
    }

    public function getAll(): array
    {
        return $this->findAll();
    }

    public function store(Basket $basket): void
    {
        $this->getEntityManager()->persist($basket);
        $this->getEntityManager()->flush($basket);
    }

    public function remove(BasketId $basketId): void
    {
        $basket = $this->get($basketId);
        $this->getEntityManager()->remove($basket);
        $this->getEntityManager()->flush();
    }

    public function getNextId(): BasketId
    {
        return BasketId::generate();
    }
}