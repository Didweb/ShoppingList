<?php
namespace App\Controller\Circle;

use App\Utils\JsonResponseFactory;
use App\Utils\AuthenticatedUserInterface;
use App\Service\Circle\CircleGetAllService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/all-circles', name: 'api_circle_get_all', methods: ['GET'])]
class CircleGetAllController extends AbstractController
{
    public function __construct(
        private CircleGetAllService $circleGetAllService,
        private AuthenticatedUserInterface $authUser
    ) {}
    
    public function __invoke(): JsonResponse 
    {
        
        $circleDto = $this->circleGetAllService->get($this->authUser);

        return JsonResponseFactory::success(['circles' => $circleDto]);
    } 
}