<?php
namespace App\Controller\Item;

use App\DTO\Item\ItemPartialDto;
use App\Utils\JsonResponseFactory;
use App\Request\Item\ItemAutocompleteRequest;
use App\Service\Item\ItemAutocompleteService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/items/autocomplete', methods: ['POST'])]
class ItemAutocompleteController  extends AbstractController
{
    public function __construct(
        private ItemAutocompleteService $itemAutocompleteService,
        private ItemAutocompleteRequest $itemAutocompleteRequest
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        
        $validData = $this->itemAutocompleteRequest->validate($data);
        $itemPartialDto = new ItemPartialDto($validData['q']);

        $suggestions = $this->itemAutocompleteService->suggest($itemPartialDto);

  
        return JsonResponseFactory::success(['sugesstions' => $suggestions]);
    }
}