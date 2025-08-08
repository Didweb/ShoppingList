<?php
namespace App\Controller\Circle;

use App\DTO\Circle\CircleGetDto;
use App\Utils\JsonResponseFactory;
use App\DTO\Circle\CircleCreateDto;
use App\Service\Circle\CircleGetService;
use App\Utils\AuthenticatedUserInterface;
use App\Request\Circle\CircleCreateRequest;
use App\Service\Circle\CircleCreateService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/circles', name: 'api_circle_create', methods: ['POST'])]
class CircleCreateController extends AbstractController
{
    public function __construct(
        private CircleCreateService $createCircleService,
        private CircleCreateRequest $circleCreateRequest,
        private CircleGetService $circleGetService,
        private AuthenticatedUserInterface $authUser
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $validData = $this->circleCreateRequest->validate($data);

        $cirleCreateDto = new CircleCreateDto($validData['name'],$validData['color']);

        $idCircle = $this->createCircleService->create($this->getUser(), $cirleCreateDto);

        $circleGetDto = new CircleGetDto($idCircle);
        $circle = $this->circleGetService->get($circleGetDto, $this->authUser);


        return JsonResponseFactory::success([
            'message' => 'Círculo creado correctamente', 
            'circle'=> $circle], Response::HTTP_CREATED);
    }
}