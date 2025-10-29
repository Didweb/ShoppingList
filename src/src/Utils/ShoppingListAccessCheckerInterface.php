<?php
namespace App\Utils;

use App\Entity\ShoppingList;

interface ShoppingListAccessCheckerInterface
{
    public function userCanAccessShoppingList(ShoppingList $shoppingList, int $idUser): bool;
}