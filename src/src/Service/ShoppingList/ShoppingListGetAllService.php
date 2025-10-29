<?php
namespace App\Service\ShoppingList;

use Doctrine\ORM\EntityManagerInterface;
use App\Utils\AuthenticatedUserInterface;
use App\DTO\Circle\CircleSimpleWithListsDto;
use App\Repository\CircleRepository;

class ShoppingListGetAllService
{
    public function __construct(
        private CircleRepository $circleRepository,
        private EntityManagerInterface $em)
    {}

    public function get(AuthenticatedUserInterface $authUser): array
    {

        $circleShoppingLists = $this->circleRepository->findByOwnerOrMembership($authUser->getId());

        $circleShoppingListArr = [];

        foreach ($circleShoppingLists as $circleShoppingList) {
            
            $circleShoppingListArr[] = CircleSimpleWithListsDto::fromEntity($circleShoppingList);
        }

        return $circleShoppingListArr;

    }
}