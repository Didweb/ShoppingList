<?php
namespace App\DTO\Circle;

use App\Entity\User;
use App\Entity\Circle;
use App\DTO\User\UserDto;
use App\Entity\ShoppingList;
use App\DTO\ShoppingList\ShoppingListDto;

final class CircleSimpleWithListsDto
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $color,
        public readonly int $createdBy,
        public readonly array $users,
        public readonly array $shoppingLists
    ) {} 

    public static function fromEntity(Circle $circle): self
    {
        return new self(
            $circle->getId(),
            $circle->getName(),
            $circle->getColor()->value(),
            $circle->getCreatedBy()->getId(),
            $circle->getUsers()->map(fn (User $u) => UserDto::fromEntity($u))->toArray(),
            $circle->getShoppingLists()->map(fn (ShoppingList $sl) => ShoppingListDto::fromEntity($sl))->toArray()
        );
    }
}