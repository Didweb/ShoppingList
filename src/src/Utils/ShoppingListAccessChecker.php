<?php
namespace App\Utils;

use App\Entity\ShoppingList;

class ShoppingListAccessChecker implements ShoppingListAccessCheckerInterface
{
      public function userCanAccessShoppingList(ShoppingList $shoppingList, AuthenticatedUserInterface $authUser): bool
    {
        $circle = $shoppingList->getCircle();

        if ($circle->getCreatedBy()->getId() === $authUser->getId()) {
            return true;
        }

        foreach ($circle->getUsers() as $user) {
            if ($user->getId() === $authUser->getId()) {
                return true;
            }
        }

        return false;
    }  
}