<?php
namespace App\DTO\Circle;

use App\Entity\Circle;
use App\Entity\ShoppingList;
use App\DTO\ShoppingList\ShoppingListDto;

final class CircleDto
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $color,
        public readonly int $createdBy,
        public readonly array $users,
        public readonly array $shoppingLists,
        public readonly string $imageQrBase64,
    ) {} 

    public static function fromEntity(Circle $circle, string $qrBase64): self
    {
        return new self(
            $circle->getId(),
            $circle->getName(),
            $circle->getColor()->value(),
            $circle->getCreatedBy()->getId(),
            $circle->getUsers()->toArray(),
            $circle->getShoppingLists()->map(fn (ShoppingList $sl) => ShoppingListDto::fromEntity($sl))->toArray(),
            $qrBase64
        );
    }


}