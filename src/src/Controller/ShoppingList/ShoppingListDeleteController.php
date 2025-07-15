<?php
namespace App\Controller\ShoppingList;

use App\Utils\JsonResponseFactory;
use App\Utils\AuthenticatedUserInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\DTO\ShoppingList\ShoppingListDeleteDto;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Request\ShoppingList\ShoppingListDeleteRequest;
use App\Service\ShoppingList\ShoppingListDeleteService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/shopping-list/delete', name: 'api_shopping_list_delete', methods: ['DELETE'])]
class ShoppingListDeleteController extends AbstractController
{
    public function __construct(
        private ShoppingListDeleteService $shoppingListDeleteService,
        private ShoppingListDeleteRequest $shoppingListDeleteRequest,
        private AuthenticatedUserInterface $authUser
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $validData = $this->shoppingListDeleteRequest->validate($data);

        $shoppingListDeleteDto = new ShoppingListDeleteDto($validData['id_shopping_list']);

        $this->shoppingListDeleteService->delete($shoppingListDeleteDto, $this->authUser);

        return JsonResponseFactory::success(['message' => 'Lista eliminada correctamente'], Response::HTTP_CREATED);
    }
}