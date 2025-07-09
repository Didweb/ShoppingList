<?php
namespace App\Controller;

use App\Utils\JsonResponseFactory;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HealthController extends AbstractController
{
    #[Route('/health', name: 'app_health', methods: ['GET'])]
    public function index(): JsonResponse
    {
        return JsonResponseFactory::success('API listas is running');
    }
}