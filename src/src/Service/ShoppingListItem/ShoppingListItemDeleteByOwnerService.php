<?php

namespace App\Service\ShoppingListItem;

use App\Entity\User;
use App\Repository\ShoppingListItemRepository;

class ShoppingListItemDeleteByOwnerService
{
    public function __construct(
        private ShoppingListItemRepository $repo
    ) {}

    public function deleteByOwner(User $user): void
    {
        $this->repo->deleteAllByAddedBy($user);
    }
}