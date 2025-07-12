<?php

namespace App\Controller;

use App\Entity\User;
use App\DTO\Auth\LoginDto;
use App\DTO\Auth\RegisterDto;
use App\Request\Auth\LoginRequest;
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
        private LoginService $loginService,
        private RegisterRequest $registerRequest,
        private LoginRequest $loginRequest,
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
    public function login(Request $request): JsonResponse 
    {

        $data = json_decode($request->getContent(), true);

        $validData = $this->loginRequest->validate($data);

        $loginDto = new LoginDto($validData['email'], $validData['password']);

        $token = $this->loginService->login($loginDto);

        return JsonResponseFactory::success([
            'token' => $token
        ]);
    }
}
