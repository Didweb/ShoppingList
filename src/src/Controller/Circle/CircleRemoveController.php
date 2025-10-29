<?php
namespace App\Controller\Circle;

use App\Utils\JsonResponseFactory;
use App\DTO\Circle\CircleDeleteDto;
use App\Service\Circle\CircleGetService;
use App\Utils\AuthenticatedUserInterface;
use App\Request\Circle\CircleDeleteRequest;
use App\Service\Circle\CircleDeleteService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/circles/delete-circle', name: 'api_item_remove_circle', methods: ['DELETE'])]
class CircleRemoveController extends AbstractController
{
    public function __construct(
        private CircleGetService $circleGetService,
        private CircleDeleteRequest $circleDeleteRequest,
        private CircleDeleteService $circleDeleteService,
        private AuthenticatedUserInterface $authUser
    )
    {}

    public function __invoke(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $validData = $this->circleDeleteRequest->validate($data);
        
        $payloadDto = new CircleDeleteDto($validData['id_circle']);

        $circleDto = $this->circleDeleteService->delete($payloadDto, $this->authUser);

        return JsonResponseFactory::success(['circle' => $circleDto]);
    }
}