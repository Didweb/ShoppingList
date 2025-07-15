<?php
namespace App\DTO\ShoppingList;

class ShoppingListEditDto
{
    public function __construct(
        public readonly int $id,
        public readonly string $name
    ) {} 
}