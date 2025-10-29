<?php
namespace App\Service\ShoppingListItem;

use App\ValueObject\Status;
use App\Exception\UnauthorizedException;
use Doctrine\ORM\EntityManagerInterface;
use App\Exception\EntityNotFoundException;
use App\Repository\ShoppingListRepository;
use App\Repository\ShoppingListItemRepository;
use App\DTO\ShoppingListItem\ItemChangeStatusDto;
use App\DTO\ShoppingListItem\ShoppingListItemDto;
use App\Utils\ShoppingListAccessCheckerInterface;

class ItemChangeStatusService
{

    public function __construct(
            private ShoppingListItemRepository $shoppingListItemRepository,
            private ShoppingListRepository $shoppingListRepository,
            private ShoppingListAccessCheckerInterface $accessChecker,
            private EntityManagerInterface $em
            )
    {}


    public function change(ItemChangeStatusDto $itemChangeStatusDto): ShoppingListItemDto
    {

        $shoppingList = $this->shoppingListRepository->find($itemChangeStatusDto->idShoppingList);
        $item = $this->shoppingListItemRepository->find($itemChangeStatusDto->idItem);

        if(!$shoppingList) {
            throw new EntityNotFoundException('Shopping List con id: ['.$itemChangeStatusDto->idShoppingList.'] no encontrado.');
        }

        if(!$item) {
            throw new EntityNotFoundException('Item con id: ['.$itemChangeStatusDto->idItem.'] no encontrado.');
        }


        if (!$this->accessChecker->userCanAccessShoppingList($shoppingList, $itemChangeStatusDto->idUser)) {
            throw new UnauthorizedException('No tienes acceso a esta Shopping Lista.');
        }

        $shoppingListItem = $this->shoppingListItemRepository->findOneBy([
                                'shoppingList' => $shoppingList,
                                'item' => $item,
                            ]);

        if (!$shoppingListItem) {
            throw new EntityNotFoundException('Shopping Lista Item no encontrado.');
        }
        
        $status  = new Status($itemChangeStatusDto->status);
        $shoppingListItem->setStatus($status);

        $this->em->persist($shoppingListItem);
        $this->em->flush();
        
        return ShoppingListItemDto::fromEntity($shoppingListItem);
    }   
}