<?php
namespace App\Controller\Circle;

use App\DTO\Circle\CircleEditDto;
use App\Utils\JsonResponseFactory;
use App\Request\Circle\CircleEditRequest;
use App\Service\Circle\CircleEditService;
use App\Utils\AuthenticatedUserInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
        

#[Route('/api/circles/edit', name: 'api_circle_edit', methods: ['PUT'])]
class CircleEditController extends AbstractController
{
    public function __construct(
        private CircleEditService $circleEditService,
        private CircleEditRequest $circleEditRequest,
        private AuthenticatedUserInterface $authUser
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $validData = $this->circleEditRequest->validate($data);

        $cirleEditDto = new CircleEditDto(
                                $validData['id'], 
                                $validData['name'], 
                                $validData['color'], 
                                $this->authUser->getId()
                        );

        $circleDto = $this->circleEditService->edit($cirleEditDto, $this->authUser);

        return JsonResponseFactory::success(['circel' => $circleDto], Response::HTTP_CREATED);
    }
}