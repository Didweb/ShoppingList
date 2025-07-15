<?php
namespace App\Controller\Circle;


use App\Utils\JsonResponseFactory;
use App\DTO\Circle\CircleUnregisterDto;
use App\Utils\AuthenticatedUserInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Request\Circle\CircleUnregisterUserRequest;
use App\Service\Circle\CircleUnregisterUserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/circles/unregister-user', name: 'api_circle_unregister_user', methods: ['PATCH'])]
class CircleUnregisterUserController extends AbstractController
{
    public function __construct(
        private CircleUnregisterUserService $circleUnregisterUserService,
        private CircleUnregisterUserRequest $circleUnregisterUserRequest,
        private AuthenticatedUserInterface $authUser
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $validData = $this->circleUnregisterUserRequest->validate($data);

        $unregisterUserDto = new CircleUnregisterDto($validData['id_circle']);

        $this->circleUnregisterUserService->unregister($unregisterUserDto, $this->authUser);

        return JsonResponseFactory::success('El usuario ha sido borrado del círculo.');
    }
}