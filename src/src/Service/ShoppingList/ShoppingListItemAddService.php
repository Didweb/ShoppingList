<?php
namespace App\Service\ShoppingList;

use App\ValueObject\Status;
use App\Entity\ShoppingListItem;
use App\DTO\Item\ItemAddToListDto;
use App\Repository\ItemRepository;
use App\Repository\UserRepository;
use App\Exception\UnauthorizedException;
use Doctrine\ORM\EntityManagerInterface;
use App\DTO\ShoppingList\ShoppingListDto;
use App\Exception\EntityNotFoundException;
use App\Repository\ShoppingListRepository;
use Symfony\Bundle\SecurityBundle\Security;
use App\Repository\ShoppingListItemRepository;
use App\Exception\DuplicateNotAllowedException;
use App\Utils\ShoppingListAccessCheckerInterface;

class ShoppingListItemAddService
{

    public function __construct(
            private ShoppingListRepository $shoppingListRepository,
            private ShoppingListItemRepository $shoppingListItemRepository,
            private ItemRepository $itemRepository,
            private UserRepository $userRepository,
            private EntityManagerInterface $em,
            private ShoppingListAccessCheckerInterface $accessChecker,
            private Security $security)
    {}


    public function add(ItemAddToListDto $itemAddToListDto): ShoppingListDto
    {
        $shoppingList =  $this->shoppingListRepository->find($itemAddToListDto->idShoppingList);

        if(!$shoppingList) {
            throw new EntityNotFoundException('Shopping List no encontrada.');
        }

        if (!$this->accessChecker->userCanAccessShoppingList($shoppingList, $itemAddToListDto->idUser)) {
            throw new UnauthorizedException('No tienes acceso a esta Lista.');
        }

        $item = $this->itemRepository->find($itemAddToListDto->idItem);

        if(!$item) {
            throw new EntityNotFoundException('Item no encontrada.');
        }

        $user = $this->userRepository->find($itemAddToListDto->idUser);
        
        
        if(!$user) {
            throw new EntityNotFoundException('User no encontrada.');
        }

        $existItemInShoppingList = $this->shoppingListItemRepository->existsItemInShoppingList($shoppingList->getId(), $item->getId());

        if ($existItemInShoppingList) {
            $shoppingList = $this->shoppingListRepository->find($shoppingList->getId());
            $shoppingListDto = ShoppingListDto::fromEntity($shoppingList);
            throw new DuplicateNotAllowedException('Este item ya está en la lista.', (array)$shoppingListDto) ;
        }

        $shoppingListItms = new ShoppingListItem();
        $shoppingListItms->setShoppingList($shoppingList);
        $shoppingListItms->setItem($item);
        $shoppingListItms->setAddedBy($user);
        $shoppingListItms->setStatus(new Status(Status::PENDING));
        $shoppingListItms->setAddedAt(new \DateTimeImmutable('now'));

        $this->em->persist($shoppingListItms);
        $this->em->flush();

        $shoppingList = $this->shoppingListRepository->find($shoppingList->getId());

        return ShoppingListDto::fromEntity($shoppingList);

    }
}