<?php
namespace App\Service\Auth;

use App\Entity\User;
use App\DTO\Auth\RegisterDto;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\Circle\CircleCreateService;
use App\Exception\EmailAlreadyInUseException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterService
{

    public function __construct(
            private UserRepository $userRepository,
            private EntityManagerInterface $em,
            private UserPasswordHasherInterface $passwordHasher,
            private CircleCreateService $circleCreateService)
    {}

    public function register(RegisterDto $registerDto): void
    {
        $existingUser = $this->em->getRepository(User::class)->findOneBy(['email' => $registerDto->email]);
        
        if ($existingUser) {
            throw new EmailAlreadyInUseException();
        }

        $user = new User();
        $user->setEmail($registerDto->email);

        $hashedPassword = $this->passwordHasher->hashPassword($user, $registerDto->password);
        $user->setPassword($hashedPassword);
        $user->setName($registerDto->name);
        $user->setRoles(['ROLE_USER']);

        $this->em->persist($user);
        $this->em->flush();

        $this->circleCreateService->create(null, true);
        
    }
}