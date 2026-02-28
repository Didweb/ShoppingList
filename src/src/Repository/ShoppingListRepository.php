<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\ShoppingList;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<ShoppingList>
 */
class ShoppingListRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShoppingList::class);
    }

    public function findByUserAccess(int $userId): array
    {
        return $this->createQueryBuilder('sl')
            ->join('sl.circle', 'c')
            ->leftJoin('c.users', 'u')
            ->where('c.createdBy = :userId')
            ->orWhere('u.id = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getResult();
    }


    public function deleteAllItemsByOwner(User  $user): void
    {
        $lists = $this->shoppingListRepository->findBy(['createdBy' => $user]);
                    foreach ($lists as $list) {
                        $this->em->remove($list);
                    }
    }

}
