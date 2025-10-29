<?php
namespace App\DTO\ShoppingListItem;

use App\DTO\Item\ItemSimpleDto;
use App\Entity\ShoppingListItem;

final class ShoppingListItemDto
{
    public function __construct(
        public readonly int $id,
        public readonly int $shoppingList,
        public readonly ItemSimpleDto $item,
        public readonly string $status,
        public readonly int $addedBy,
        public readonly string $addedAt
    ) {} 

    public static function fromEntity(ShoppingListItem $item): self
    {
        return new self(
            $item->getId(),
            $item->getShoppingList()->getId(),
            $item->getItem() ? ItemSimpleDto::fromEntity($item->getItem()) : null,
            $item->getStatus()->value(),
            $item->getAddedBy()->getId(),
            $item->getAddedAt()->format('Y-m-d H:i:s')
        );
    }
}