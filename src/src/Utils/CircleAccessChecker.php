<?php
namespace App\Utils;

use App\Entity\Circle;
use App\Utils\AuthenticatedUserInterface;

class CircleAccessChecker implements CircleAccessCheckerInterface
{
    public function userCanAccessCircle(Circle $circle, AuthenticatedUserInterface $authUser): bool
    {
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