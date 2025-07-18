<?php
namespace App\DTO\ShoppingListItem;

final class ItemRemoveFromListDto
{
    public function __construct(
        public readonly int $idItem,
        public readonly int $idShoppingList,
        public readonly int $idUser
    ) {} 
}