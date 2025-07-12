<?php
namespace App\Utils;

use Symfony\Bundle\SecurityBundle\Security;

class AuthenticatedUser implements AuthenticatedUserInterface
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function getId(): int
    {
        $user = $this->security->getUser();
        if (!$user) {
            throw new \LogicException('No user logged in.');
        }
        return $user->getId();
    }
}