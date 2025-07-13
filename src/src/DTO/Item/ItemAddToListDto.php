<?php
namespace App\DTO\Item;

class ItemAddToListDto
{
    public function __construct(
        public readonly int $idShoppingList,
        public readonly ?int $idSelectedItem,
        public readonly int $idItem,
        public readonly int $idUser
    ) {}
}