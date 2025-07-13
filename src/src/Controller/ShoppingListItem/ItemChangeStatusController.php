<?php
namespace App\Controller\ShoppingListItem;

use App\Utils\JsonResponseFactory;
use App\Utils\AuthenticatedUserInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\DTO\ShoppingListItem\ItemChangeStatusDto;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Request\ShoppingListItem\ItemChangeStatusRequest;
use App\Service\ShoppingListItem\ItemChangeStatusService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/shopping-list-items/change-status', name: 'api_item_change_status', methods: ['PUT'])]
class ItemChangeStatusController extends AbstractController
{
    public function __construct(
        private ItemChangeStatusService $shoppingListGetService,
        private ItemChangeStatusRequest $itemChangeStatusRequest,
        private AuthenticatedUserInterface $authUser
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $validData = $this->itemChangeStatusRequest->validate($data);

        $itemChangeStatusDto = new ItemChangeStatusDto(
                                    $validData['id_item'], 
                                    $validData['id_shopping_list'],
                                    $validData['status'],
                                    $this->authUser->getId()
                                );
        
        $shoppingListItem = $this->shoppingListGetService->change($itemChangeStatusDto);

        return JsonResponseFactory::success($shoppingListItem);
    }
}