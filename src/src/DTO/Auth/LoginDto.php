<?php
namespace App\DTO\Auth;

class LoginDto
{
    public function __construct(
        public readonly string $email,
        public readonly string $password
    ) {} 
}