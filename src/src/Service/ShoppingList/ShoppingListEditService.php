<?php
namespace App\Service\ShoppingList;

use App\Exception\UnauthorizedException;
use Doctrine\ORM\EntityManagerInterface;
use App\DTO\ShoppingList\ShoppingListDto;
use App\Utils\AuthenticatedUserInterface;
use App\Exception\EntityNotFoundException;
use App\Repository\ShoppingListRepository;
use App\DTO\ShoppingList\ShoppingListEditDto;
use App\Utils\ShoppingListAccessCheckerInterface;

class ShoppingListEditService
{
    public function __construct(
        private ShoppingListRepository $shoppingListRepository,
        private EntityManagerInterface $em,
        private ShoppingListAccessCheckerInterface $accessChecker
    )
    {}

    public function edit(ShoppingListEditDto $shoppingListEditDto, AuthenticatedUserInterface $authUser): ShoppingListDto
    {
        $shoppingList = $this->shoppingListRepository->find($shoppingListEditDto->id);

        if (!$shoppingList) {
            throw new EntityNotFoundException('Shopping List con id: ['.$shoppingListEditDto->id.'] no encontrado.');
        }

        if (!$this->accessChecker->userCanAccessShoppingList($shoppingList, $authUser->getId())) {
            throw new UnauthorizedException('No tienes acceso a esta Lista.');
        }

        $shoppingList->setName($shoppingListEditDto->name);

        $this->em->persist($shoppingList);
        $this->em->flush();

        return ShoppingListDto::fromEntity($shoppingList);
    }
}