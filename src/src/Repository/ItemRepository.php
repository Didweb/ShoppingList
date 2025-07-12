<?php

namespace App\Repository;

use App\Entity\Item;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Item>
 */
class ItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Item::class);
    }

    public function findBySlugLike(string $slugPartial): array
    {
        return $this->createQueryBuilder('i')
            ->where('i.slug LIKE :partial')
            ->setParameter('partial', '%' . $slugPartial . '%')
            ->orderBy('i.name', 'ASC')
            ->setMaxResults(20) 
            ->getQuery()
            ->getResult();
    }

    public function findAllCanonicalItems(): array
    {
        return $this->createQueryBuilder('i')
            ->where('i.canonicalItem IS NULL')
            ->getQuery()
            ->getResult();
    }
}
