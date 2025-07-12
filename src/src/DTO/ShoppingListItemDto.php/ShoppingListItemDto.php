<?php
namespace App\DTO\ShoppingListItem;

use App\Entity\ShoppingListItem;

final class ShoppingListItemDto
{
    public function __construct(
        public readonly int $id,
        public readonly int $shoppingList,
        public readonly int $item,
        public readonly string $status,
        public readonly int $addedBy,
        public readonly string $addedAt
    ) {} 

    public static function fromEntity(ShoppingListItem $item): self
    {
        return new self(
            $item->getId(),
            $item->getShoppingList()->getId(),
            $item->getItem()->getId(),
            $item->getStatus()->value(),
            $item->getAddedBy()->getId(),
            $item->getAddedAt()->format('Y-m-d H:i:s')
        );
    }
}