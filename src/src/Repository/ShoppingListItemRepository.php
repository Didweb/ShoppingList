<?php

namespace App\Repository;

use App\Entity\ShoppingListItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ShoppingListItem>
 */
class ShoppingListItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShoppingListItem::class);
    }

    public function existsItemInShoppingList(int $shoppingListId, int $itemId): bool
    {
        return (bool) $this->createQueryBuilder('sli')
            ->select('1')
            ->where('sli.shoppingList = :shoppingListId')
            ->andWhere('sli.item = :itemId')
            ->setParameter('shoppingListId', $shoppingListId)
            ->setParameter('itemId', $itemId)
            ->setMaxResults(1) 
            ->getQuery()
            ->getOneOrNullResult();
    }
}
