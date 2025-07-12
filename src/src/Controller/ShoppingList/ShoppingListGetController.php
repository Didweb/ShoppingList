<?php
namespace App\Controller\ShoppingList;

use App\Utils\JsonResponseFactory;
use App\Utils\AuthenticatedUserInterface;
use App\DTO\ShoppingList\ShoppingListGetDto;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Request\ShoppingList\ShoppingListGetRequest;
use App\Service\ShoppingList\ShoppingListGetService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/shopping-list/{id}', name: 'api_shopping_list_get', methods: ['GET'])]
class ShoppingListGetController extends AbstractController
{
    public function __construct(
        private ShoppingListGetService $shoppingListGetService,
        private ShoppingListGetRequest $shoppingListGetRequest,
        private AuthenticatedUserInterface $authUser
    ) {}

    public function __invoke(int $id): JsonResponse
    {
        $validData = $this->shoppingListGetRequest->validate(['id' => $id]);

        $shoppingListGetDto = new ShoppingListGetDto($validData['id']);

        $shoppingListDto = $this->shoppingListGetService->get($shoppingListGetDto, $this->authUser);

        return JsonResponseFactory::success(['Shopping List' => $shoppingListDto]);
    }
}