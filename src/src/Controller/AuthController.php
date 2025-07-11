<?php

namespace App\Controller;

use App\Entity\User;
use App\DTO\Auth\RegisterDto;
use App\Service\Auth\LoginService;
use App\Utils\JsonResponseFactory;
use App\Request\Auth\RegisterRequest;
use App\Service\Auth\RegisterService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

final class AuthController extends AbstractController
{

    public function __construct(
        private RegisterService $registerService,
        private RegisterRequest $registerRequest
    ) {}

    #[Route('/auth', name: 'app_auth')]
    public function index(): JsonResponse
    {
        return JsonResponseFactory::success('Welcome to your new controller!');
    }

    #[Route('/register', name: 'api_register', methods: ['POST'])]
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        
        $validData = $this->registerRequest->validate($data);

        $registerDto = new RegisterDto($validData['email'], $validData['password'], $validData['name']);

        $this->registerService->register($registerDto);

        return JsonResponseFactory::success('User created');
    }

    #[Route('/login', name: 'api_login', methods: ['POST'])]
    public function login(
                        Request $request,
                        EntityManagerInterface $em,
                        UserPasswordHasherInterface $passwordHasher,
                        JWTTokenManagerInterface $JWTManager
                    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        if (empty($data['email']) || empty($data['password'])) {
            return JsonResponseFactory::error('Missing credentials', 400);
        }

        $user = $em->getRepository(User::class)->findOneBy(['email' => $data['email']]);
        if (!$user) {
            return JsonResponseFactory::error('Invalid credentials. Email not found.', 401);
        }

        if (!$passwordHasher->isPasswordValid($user, $data['password'])) {
            return JsonResponseFactory::error('Invalid credentials. Password Error.', 401);
        }

        $token = $JWTManager->create($user);

        return JsonResponseFactory::success([
            'token' => $token
        ]);
    }
}
