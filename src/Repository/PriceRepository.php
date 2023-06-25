<?php

namespace App\Repository;

use App\Entity\Price;
use App\Entity\Service;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class PriceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Price::class);
    }

    public function findByPrice($serviceId)
    {
        return $this->createQueryBuilder('p')
            ->select('MIN(p.price) AS lowestPrice')
            ->andWhere('p.service = :serviceId')
            ->setParameter('serviceId', $serviceId)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
