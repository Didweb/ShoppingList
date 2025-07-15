<?php
namespace App\Service\ShoppingList;

use App\Exception\UnauthorizedException;
use Doctrine\ORM\EntityManagerInterface;
use App\Utils\AuthenticatedUserInterface;
use App\Exception\EntityNotFoundException;
use App\Repository\ShoppingListRepository;
use App\DTO\ShoppingList\ShoppingListDeleteDto;
use App\Utils\ShoppingListAccessCheckerInterface;

class ShoppingListDeleteService
{
    public function __construct(
        private ShoppingListRepository $shoppingListRepository,
        private EntityManagerInterface $em,
        private ShoppingListAccessCheckerInterface $accessChecker
    )
    {} 

    public function delete(ShoppingListDeleteDto $shoppingListDeleteDto, AuthenticatedUserInterface $authUser): void
    {
        $shoppingList = $this->shoppingListRepository->find($shoppingListDeleteDto->id);

        if(!$shoppingList) {
            throw new EntityNotFoundException('Shopping List con id: ['.$shoppingListDeleteDto->id.'] no encontrado.');
        }

        if (!$this->accessChecker->userCanAccessShoppingList($shoppingList, $authUser->getId())) {
            throw new UnauthorizedException('No tienes acceso a este círculo.');
        }

        $this->em->remove($shoppingList);
        $this->em->flush();
        
    }
}