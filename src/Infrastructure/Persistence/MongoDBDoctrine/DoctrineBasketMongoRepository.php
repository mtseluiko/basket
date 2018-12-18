<?php
/**
 * Created by PhpStorm.
 * User: mikhail
 * Date: 16.11.18
 * Time: 10:10
 */

namespace App\Infrastructure\Persistence\MongoDBDoctrine;


use App\Domain\Basket\Basket;
use App\Domain\Basket\BasketId;
use App\Domain\Basket\BasketRepositoryContract;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;
use Doctrine\Bundle\MongoDBBundle\Repository\ServiceDocumentRepository;

class DoctrineBasketMongoRepository extends ServiceDocumentRepository
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
        $this->getDocumentManager()->persist($basket);
        $this->getDocumentManager()->flush($basket);
    }

    public function remove(Basket $basket): void
    {
        $this->getDocumentManager()->remove($basket);
        $this->getDocumentManager()->flush($basket);
    }

    public function getNextId(): BasketId
    {
        return BasketId::generate();
    }
}