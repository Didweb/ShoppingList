<?php
namespace App\Controller\Item;


use App\DTO\Item\ItemAddDto;
use App\DTO\Item\ItemAddToListDto;
use App\Utils\JsonResponseFactory;
use App\Request\Item\ItemAddRequest;
use App\Service\Item\ItemCreateService;
use App\Utils\AuthenticatedUserInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\ShoppingList\ShoppingListItemAddService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

 #[Route('/api/items/add', name: 'api_items_create_add', methods: ['POST'])]
class ItemAsignateToListController  extends AbstractController
{
    public function __construct(
        private ItemCreateService $itemCreateService,
        private ShoppingListItemAddService $shoppingListItemAddService,
        private ItemAddRequest $itemAddRequest,
        private AuthenticatedUserInterface $authUser
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $validData = $this->itemAddRequest->validate($data);

        $itemAddDto = new ItemAddDto(
                    $validData['id_shopping_list'], 
                    $validData['name'], 
                    $validData['id_selected_item'],
                    $this->authUser->getId()
                );

        $itemDto = $this->itemCreateService->createOrGet($itemAddDto);
        $itemAddToListDto = new ItemAddToListDto(
                                $validData['id_shopping_list'],
                                $validData['id_selected_item'],
                                $itemDto->id,
                                $this->authUser->getId()
                            );        
        $shoppingList = $this->shoppingListItemAddService->add($itemAddToListDto);
        return JsonResponseFactory::success(['Shopping List' => $shoppingList]);
    }
}