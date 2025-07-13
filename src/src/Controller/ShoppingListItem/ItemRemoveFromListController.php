<?php
namespace App\Controller\ShoppingListItem;

use App\Utils\JsonResponseFactory;
use App\Utils\AuthenticatedUserInterface;
use App\DTO\ShoppingList\ShoppingListGetDto;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\DTO\ShoppingListItem\ItemRemoveFromListDto;
use App\Service\ShoppingList\ShoppingListGetService;
use App\Request\ShoppingListItem\ItemRemoveFromListRequest;
use App\Service\ShoppingListItem\ItemRemoveFromListService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/shopping-list-items/delete-item', methods: ['DELETE'])]
class ItemRemoveFromListController extends AbstractController
{
        public function __construct(
        private ShoppingListGetService $shoppingListGetService,
        private ItemRemoveFromListService $itemRemoveFromListService,
        private ItemRemoveFromListRequest $itemRemoveFromListRequest,
        private AuthenticatedUserInterface $authUser
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $validData = $this->itemRemoveFromListRequest->validate($data);

        $itemRemoveFromListDto = new ItemRemoveFromListDto(
                                    $validData['id_item'], 
                                    $validData['id_shopping_list'],
                                    $this->authUser->getId()
                                );
        
        $shoppingListItem = $this->itemRemoveFromListService->delete($itemRemoveFromListDto);

        $shoppingListGetDto = new ShoppingListGetDto($validData['id_shopping_list'], $this->authUser->getId());
        $shoppingListDto = $this->shoppingListGetService->get($shoppingListGetDto);     

        return JsonResponseFactory::success($shoppingListDto);
    }
}