<?php
namespace App\Controller\ShoppingList;

use App\Utils\JsonResponseFactory;
use App\Utils\AuthenticatedUserInterface;
use App\DTO\ShoppingList\ShoppingListEditDto;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Request\ShoppingList\ShoppingListEditRequest;
use App\Service\ShoppingList\ShoppingListEditService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/shopping-list/edit', name: 'api_shopping_list_edit', methods: ['PUT'])]
class ShoppingListEditController extends AbstractController
{
    public function __construct(
        private ShoppingListEditService $shoppingListEditService,
        private ShoppingListEditRequest $shoppingListEditRequest,
        private AuthenticatedUserInterface $authUser
    ) {}

    public function __invoke(Request $request): JsonResponse 
    {
        $data = json_decode($request->getContent(), true);
        $validData = $this->shoppingListEditRequest->validate($data);

        $shoppingListEditDto = new ShoppingListEditDto($validData['id_shopping_list'], $validData['name']);

        $shoppingListDto = $this->shoppingListEditService->edit($shoppingListEditDto, $this->authUser);

        return JsonResponseFactory::success(['shopping list' => $shoppingListDto], Response::HTTP_CREATED);
    }
}