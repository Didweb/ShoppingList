<?php
namespace App\Controller\ShoppingList;

use App\Utils\JsonResponseFactory;
use App\Utils\AuthenticatedUserInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\ShoppingList\ShoppingListGetAllService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/all-shopping-list', name: 'api_shopping-list_get_all', methods: ['GET'])]
class ShoppingListGetAllController extends AbstractController
{
    public function __construct(
        private ShoppingListGetAllService $shoppingListGetAllService,
        private AuthenticatedUserInterface $authUser
    ) {}
    
    public function __invoke(): JsonResponse 
    {
        $shoppingListSimpleDto = $this->shoppingListGetAllService->get($this->authUser);

        return JsonResponseFactory::success(['shopping lists' => $shoppingListSimpleDto]);
    } 
}