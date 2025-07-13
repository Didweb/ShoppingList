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

    public function findBySlugLikeAndOwners(string $slugPartial, array $allowedOwnerIds): array
    {

        $qb = $this->createQueryBuilder('i');
        $qb->where('i.slug LIKE :slug')
        ->andWhere($qb->expr()->orX(
            'i.createdBy IS NULL',
            'i.createdBy IN (:owners)'
        ))
        ->setParameter('slug', '%' . $slugPartial . '%')
        ->setParameter('owners', $allowedOwnerIds)
        ->setMaxResults(20);

        return $qb->getQuery()->getResult();
    }

    public function findAllCanonicalItems(): array
    {
        return $this->createQueryBuilder('i')
            ->where('i.canonicalItem IS NULL')
            ->getQuery()
            ->getResult();
    }

    public function findBySlugLike(string $slugPartial): array
    {
        return $this->createQueryBuilder('i')
            ->where('i.slug LIKE :slug')
            ->setParameter('slug', '%' . $slugPartial . '%')
            ->getQuery()
            ->getResult();
    }

    public function findOneBySlugAndOwners(string $slug, array $ownerIds): ?Item
    {
        return $this->createQueryBuilder('i')
            ->where('i.slug = :slug')
            ->andWhere('i.createdBy IN (:owners)')
            ->setParameter('slug', $slug)
            ->setParameter('owners', $ownerIds)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
