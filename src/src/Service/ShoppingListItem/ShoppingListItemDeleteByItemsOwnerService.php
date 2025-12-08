<?php

namespace App\Service\ShoppingListItem;

use App\Entity\User;
use App\Repository\ShoppingListItemRepository;

class ShoppingListItemDeleteByItemsOwnerService
{
    public function __construct(
        private ShoppingListItemRepository $repo
    ) {}

    public function deleteWhereItemOwner(User $user): void
    {
        $this->repo->deleteAllByItemsCreatedBy($user);
    }
}