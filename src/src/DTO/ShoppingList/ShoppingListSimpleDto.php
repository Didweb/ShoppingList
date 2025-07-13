<?php
namespace App\DTO\ShoppingList;

use App\Entity\ShoppingList;
use App\DTO\Circle\CircleSimpleDto;

class ShoppingListSimpleDto
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly int $createdBy,
        public readonly CircleSimpleDto $circle
    ) {} 


    public static function fromEntity(ShoppingList $shoppingList): self
    {
        return new self(
            $shoppingList->getId(),
            $shoppingList->getName(),
            $shoppingList->getCreatedBy()->getId(),
            CircleSimpleDto::fromEntity($shoppingList->getCircle())
        );
    }
}