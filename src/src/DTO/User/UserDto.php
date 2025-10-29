<?php
namespace App\DTO\User;

use App\Entity\User;

class UserDto
{
    public function __construct(
        public readonly int $id,
        public readonly string $name
    ) {} 

    public static function fromEntity(User $user): self
    {
        return new self(
            $user->getId(),
            $user->getName()
        );
    }
}