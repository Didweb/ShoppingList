<?php
namespace App\Service\ShoppingList;

use App\DTO\ShoppingList\ShoppingListDto;
use App\Exception\UnauthorizedException;
use Doctrine\ORM\EntityManagerInterface;
use App\Utils\AuthenticatedUserInterface;
use App\Exception\EntityNotFoundException;
use App\Repository\ShoppingListRepository;
use App\DTO\ShoppingList\ShoppingListGetDto;
use App\Utils\ShoppingListAccessCheckerInterface;

class ShoppingListGetService
{
    public function __construct(
            private ShoppingListRepository $shoppingListRepository,
            private EntityManagerInterface $em,
            private ShoppingListAccessCheckerInterface $accessChecker)
    {}

    public function get(ShoppingListGetDto $shoppingListGetDto, AuthenticatedUserInterface $authUser): ShoppingListDto
    {

        $shoppingList = $this->shoppingListRepository->find($shoppingListGetDto->id);

        if(!$shoppingList) {
            throw new EntityNotFoundException('Shopping List con id: ['.$shoppingListGetDto->id.'] no encontrado.');
        }

        if (!$this->accessChecker->userCanAccessShoppingList($shoppingList, $authUser)) {
            throw new UnauthorizedException('No tienes acceso a esta Lista.');
        }

        return ShoppingListDto::fromEntity($shoppingList);

    }
}