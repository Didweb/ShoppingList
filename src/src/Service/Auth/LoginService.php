<?php
namespace App\Service\Auth;

use App\Entity\User;
use App\DTO\Auth\LoginDto;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Exception\InvalidCredentialsException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

class LoginService
{
    public function __construct(
            private UserRepository $userRepository,
            private EntityManagerInterface $em,
            private UserPasswordHasherInterface $passwordHasher,
            private JWTTokenManagerInterface $JWTManager)
    {}

    public function login(LoginDto $loginDto): string
    {

        $user = $this->em->getRepository(User::class)->findOneBy(['email' => $loginDto->email]);

        if (!$user) {
            throw new InvalidCredentialsException('Invalid credentials. Email not found.');
        }

        if (!$this->passwordHasher->isPasswordValid($user, $loginDto->password)) {
            throw new InvalidCredentialsException('Invalid credentials. Password Error.');
        }

        $token = $this->JWTManager->create($user);

        return $token;
    }
}