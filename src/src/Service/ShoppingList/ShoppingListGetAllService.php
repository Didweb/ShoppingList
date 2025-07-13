<?php
namespace App\Service\ShoppingList;

use Doctrine\ORM\EntityManagerInterface;
use App\Utils\AuthenticatedUserInterface;
use App\Repository\ShoppingListRepository;
use App\DTO\ShoppingList\ShoppingListSimpleDto;

class ShoppingListGetAllService
{
    public function __construct(
        private ShoppingListRepository $shoppingListRepository,
        private EntityManagerInterface $em)
    {}

    public function get(AuthenticatedUserInterface $authUser): array
    {

        $shoppingLists = $this->shoppingListRepository->findByUserAccess($authUser->getId());

        $shoppingListArr = [];

        foreach ($shoppingLists as $shoppingList) {
            
            $shoppingListArr[] = ShoppingListSimpleDto::fromEntity($shoppingList);
        }

        return $shoppingListArr;

    }
}