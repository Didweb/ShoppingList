<?php
namespace App\Utils;

use App\Entity\User;

interface AuthenticatedUserInterface
{
    public function getId(): int;
}