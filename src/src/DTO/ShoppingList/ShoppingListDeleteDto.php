<?php
namespace App\DTO\ShoppingList;

class ShoppingListDeleteDto
{
    public function __construct(
        public readonly string $id
    ) {} 
}