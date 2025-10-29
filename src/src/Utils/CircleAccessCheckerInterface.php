<?php
namespace App\Utils;

use App\Entity\Circle;

interface CircleAccessCheckerInterface
{
    public function userCanAccessCircle(Circle $circle, AuthenticatedUserInterface $authUser): bool;
}