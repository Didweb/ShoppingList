<?php

namespace App\Service\Item;

use App\Entity\User;
use App\Repository\ItemRepository;

class ItemDeleteByOwnerService
{
    public function __construct(
        private ItemRepository $itemRepository
    ) {}

    public function deleteByOwner(User $user): void
    {
        $this->itemRepository->deleteAllItemsByOwner($user);
    }
}