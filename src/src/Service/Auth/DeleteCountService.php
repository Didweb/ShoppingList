<?php
namespace App\Service\Auth;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Exception\InvalidCredentialsException;
use App\Service\Item\ItemDeleteByOwnerService;
use App\Service\Circle\CircleDeleteByOwnerService;
use App\Service\ShoppingList\ShoppingListDeleteByOwnerService;
use App\Service\ShoppingListItem\ShoppingListItemDeleteByOwnerService;
use App\Service\ShoppingListItem\ShoppingListItemDeleteByItemsOwnerService;

class DeleteCountService
{
    public function __construct(
        private UserRepository $userRepository,
        private CircleDeleteByOwnerService $circleDeletion,
        private ShoppingListDeleteByOwnerService $listDeletion,
        private ShoppingListItemDeleteByOwnerService $listItemsDeletionByOwner,
        private ShoppingListItemDeleteByItemsOwnerService $listItemsDeletionByItemsOwner,
        private ItemDeleteByOwnerService $itemDeletion,
        private EntityManagerInterface $em
    ) {}


    public function deleteAccount(int $userId): void
    {
        $user = $this->userRepository->find($userId);

        if (!$user) {
            throw new InvalidCredentialsException("Usuario [$userId] no encontrado.");
        }

        // Transacción para no dejar datos corruptos
        $this->em->wrapInTransaction(function () use ($user) {

            // 1. Borrar TODOS los círculos creados por el usuario
            $this->circleDeletion->deleteByOwner($user);

            // 2. Borrar TODAS las listas creadas por el usuario
            $this->listDeletion->deleteByOwner($user);

            // 3. Borrar listItems donde addedBy == usuario
            $this->listItemsDeletionByOwner->deleteByOwner($user);

            // 4. Borrar listItems cuyo Item pertenece al usuario
            $this->listItemsDeletionByItemsOwner->deleteWhereItemOwner($user);

            // 5. Borrar TODOS los items creados por el usuario
            $this->itemDeletion->deleteByOwner($user);

            // 6. Borrar finalmente el usuario
            $this->em->remove($user);
        });
    }
}