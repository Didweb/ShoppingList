<?php
namespace App\DTO\ShoppingList;

final class ShoppingListGetDto
{
    public function __construct(
        public readonly string $id
    ) {} 
}