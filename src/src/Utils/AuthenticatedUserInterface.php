<?php
namespace App\Utils;

interface AuthenticatedUserInterface
{
    public function getId(): int;
}