<?php
namespace App\Service\ShoppingList;

use App\Entity\User;
use App\Repository\ShoppingListRepository;
use Doctrine\ORM\EntityManagerInterface;

class ShoppingListDeleteByOwnerService
{
    public function __construct(
        private ShoppingListRepository $repo,
        private EntityManagerInterface $em
    ) {}

    public function deleteByOwner(User $user): void
    {
        $lists = $this->repo->findBy(['createdBy' => $user]);

        foreach ($lists as $list) {
            $this->em->remove($list);
        }
    }
}