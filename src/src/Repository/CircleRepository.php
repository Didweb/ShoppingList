<?php

namespace App\Repository;

use App\Entity\Circle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Circle>
 */
class CircleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Circle::class);
    }

    public function findByOwnerOrMembership(int $userId): array
    {
        $qb = $this->createQueryBuilder('c')
            ->leftJoin('c.users', 'u')
            ->where('c.createdBy = :userId OR u.id = :userId')
            ->setParameter('userId', $userId)
            ->getQuery();

        return $qb->getResult();
    }

}
