<?php
namespace App\DTO\ShoppingListItem;

final class ItemChangeStatusDto
{
    public function __construct(
        public readonly int $idItem,
        public readonly int $idShoppingList,
        public readonly string $status,
        public readonly int $idUser
    ) {} 
}