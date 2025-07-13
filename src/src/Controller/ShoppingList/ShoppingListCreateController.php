<?php
namespace App\Controller\ShoppingList;

use App\Utils\JsonResponseFactory;
use App\Utils\AuthenticatedUserInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\DTO\ShoppingList\ShoppingListCreateDto;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Request\ShoppingList\ShoppingListCreateRequest;
use App\Service\ShoppingList\ShoppingListCreateService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/shopping-list', name: 'api_shopping_list_create', methods: ['POST'])]
class ShoppingListCreateController extends AbstractController
{
    public function __construct(
        private ShoppingListCreateService $shoppingListCreateService,
        private ShoppingListCreateRequest $shoppingListCreateRequest,
        private AuthenticatedUserInterface $authUser
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $validData = $this->shoppingListCreateRequest->validate($data);

        $shoppingListCreateDto = new ShoppingListCreateDto($validData['name'], $validData['id_circle']);

        $this->shoppingListCreateService->create($this->getUser(), $shoppingListCreateDto, $this->authUser);

        return JsonResponseFactory::success(['message' => 'Lista creada correctamente'], Response::HTTP_CREATED);
    }
}