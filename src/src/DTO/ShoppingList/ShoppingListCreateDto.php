<?php
namespace App\DTO\ShoppingList;

final class ShoppingListCreateDto
{
        public function __construct(
        public readonly string $name,
        public readonly string $idCircle
    ) {} 
}