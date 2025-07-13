<?php
namespace App\DTO\Item;

final class ItemAddDto
{
    public function __construct(
        public readonly int $idShoppingList,
        public readonly string $name,
        public readonly ?int $idSelectedItem,
        public readonly int $idUser
    ) {}
}