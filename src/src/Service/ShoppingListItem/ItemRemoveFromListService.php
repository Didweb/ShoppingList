<?php
namespace App\Service\ShoppingListItem;

use App\Exception\UnauthorizedException;
use Doctrine\ORM\EntityManagerInterface;
use App\Exception\EntityNotFoundException;
use App\Repository\ShoppingListItemRepository;
use App\Utils\ShoppingListAccessCheckerInterface;
use App\DTO\ShoppingListItem\ItemRemoveFromListDto;
use App\Repository\ShoppingListRepository;

class ItemRemoveFromListService
{
    public function __construct(
        private ShoppingListItemRepository $shoppingListItemRepository,
        private ShoppingListRepository $shoppingListRepository,
        private ShoppingListAccessCheckerInterface $accessChecker,
        private EntityManagerInterface $em
        )
    {}


    public function delete(ItemRemoveFromListDto $itemRemoveFromListDto): void
    {
        $shoppingList = $this->shoppingListRepository->find($itemRemoveFromListDto->idShoppingList);

        if(!$shoppingList) {
            throw new EntityNotFoundException('Shopping List con id: ['.$itemRemoveFromListDto->idShoppingList.'] no encontrado.');
        }

        if (!$this->accessChecker->userCanAccessShoppingList($shoppingList, $itemRemoveFromListDto->idUser)) {
            throw new UnauthorizedException('No tienes acceso a esta Shopping Lista.');
        }

        $shoppingListItem = $this->shoppingListItemRepository->findOneBy([
                                'shoppingList' => $itemRemoveFromListDto->idShoppingList,
                                'item' => $itemRemoveFromListDto->idItem,
                            ]);

        if(!$shoppingListItem) {
            throw new EntityNotFoundException('Shopping List Item: [id Shopping List'.$itemRemoveFromListDto->idShoppingList.', Id Item'.$itemRemoveFromListDto->idItem.'] no encontrado.');
        }      
        
        $this->em->remove($shoppingListItem);
        $this->em->flush();

    }
}