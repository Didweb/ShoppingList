<?php
namespace App\DTO\ShoppingList;

use App\Entity\ShoppingList;
use App\Entity\ShoppingListItem;
use App\DTO\ShoppingListItem\ShoppingListItemDto;

final class ShoppingListDto
{
        public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly int $createdBy,
        public readonly string $createAt,
        public readonly int $circle,
        public readonly array $shoppingListItems
    ) {} 

    public static function fromEntity(ShoppingList $sl): self
    {
        return new self(
            $sl->getId(),
            $sl->getName(),
            $sl->getCreatedBy()->getId(),
            $sl->getCreateAt()->format('Y-m-d H:i:s'),
            $sl->getCircle()->getId(),
            $sl->getShoppingListItems()->map(fn (ShoppingListItem $item) => ShoppingListItemDto::fromEntity($item))->toArray()
        );
    }
}