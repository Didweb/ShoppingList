<?php
namespace App\Controller\Circle;

use App\DTO\Circle\CircleGetDto;
use App\Utils\JsonResponseFactory;
use App\Request\Circle\CircleGetRequest;
use App\Service\Circle\CircleGetService;
use App\Utils\AuthenticatedUserInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/circles/{id}', name: 'api_circle_get', methods: ['GET'])]
class CircleGetController extends AbstractController
{
    public function __construct(
        private CircleGetService $circleGetService,
        private CircleGetRequest $circleGetRequest,
        private AuthenticatedUserInterface $authUser
    ) {}
    
    public function __invoke(int $id): JsonResponse 
    {
        $validData = $this->circleGetRequest->validate(['id' => $id]);

        $circleGetDto = new CircleGetDto($validData['id']);
        
        $circleDto = $this->circleGetService->get($circleGetDto, $this->authUser);

        return JsonResponseFactory::success(['circle' => $circleDto]);
    }
}