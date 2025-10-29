<?php
namespace App\Utils;

use App\Entity\ShoppingList;

class ShoppingListAccessChecker implements ShoppingListAccessCheckerInterface
{
    public function userCanAccessShoppingList(ShoppingList $shoppingList, int $idUser): bool
    {
        $circle = $shoppingList->getCircle();

        if ($circle->getCreatedBy()->getId() === $idUser) {
            return true;
        }

        foreach ($circle->getUsers() as $user) {
            if ($user->getId() === $idUser) {
                return true;
            }
        }

        return false;
    }  
}