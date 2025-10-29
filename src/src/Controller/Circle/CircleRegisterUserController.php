<?php
namespace App\Controller\Circle;

use App\Utils\JsonResponseFactory;
use App\DTO\Circle\CirclePayloadDto;
use App\Utils\AuthenticatedUserInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Request\Circle\CircleRegisterUserRequest;
use App\Service\Circle\CircleRegisterUserService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/circles/register-user', name: 'api_circle_register_user', methods: ['PATCH'])]
class CircleRegisterUserController extends AbstractController
{
    public function __construct(
        private CircleRegisterUserService $circleRegisterUserService,
        private CircleRegisterUserRequest $circleRegisterUserRequest,
        private AuthenticatedUserInterface $authUser
    ) {}
    
    public function __invoke(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $validData = $this->circleRegisterUserRequest->validate($data);
        
        $payloadDto = new CirclePayloadDto(
                        $validData['type'],
                        $validData['id'],
                        $validData['v'],
                    );

        $circleDto = $this->circleRegisterUserService->register($payloadDto, $this->authUser);

        return JsonResponseFactory::success(['circle' => $circleDto]);
    }
}