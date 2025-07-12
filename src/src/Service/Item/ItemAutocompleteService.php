<?php
namespace App\Service\Item;

use App\DTO\Item\ItemPartialDto;
use App\Entity\Item;
use App\ValueObject\Slug;
use App\Repository\ItemRepository;
use App\DTO\Item\ItemSuggestionDto;

class ItemAutocompleteService
{
    public function __construct(private ItemRepository $itemRepository) 
    {}

    public function suggest(ItemPartialDto $itemPartialDto): array
    {
        $searchTerm = mb_strtolower(trim($itemPartialDto->partial));
        $slugPartial = (new Slug($searchTerm))->value();

        $candidates = $this->itemRepository->findBySlugLike($slugPartial);

        $canonicalCandidates = [];

        foreach ($candidates as $item) {
            $canonical = $item->getCanonical() ?? $item;
            $slugCanonical = $canonical->getSlug()->value();

            if (str_starts_with($slugCanonical, $slugPartial) ||
                str_contains($slugCanonical, '-' . $slugPartial)) {
                $canonicalCandidates[$canonical->getId()] = $canonical;
                continue;
            }

          
            $tokens = explode('-', $slugCanonical);
            foreach ($tokens as $token) {
                if (levenshtein($slugPartial, $token) <= 1) {
                    $canonicalCandidates[$canonical->getId()] = $canonical;
                    continue 2; 
                }
            }

           
            if (levenshtein($slugPartial, $slugCanonical) <= 2) {
                $canonicalCandidates[$canonical->getId()] = $canonical;
            }
        }

        if (empty($canonicalCandidates)) {
            
            $allItems = $this->itemRepository->findAll();

            $allCanonicalItems = [];

            foreach ($allItems as $item) {
                $canonical = $item->getCanonical();
                if ($canonical === null || $canonical->getId() === $item->getId()) {
                    $allCanonicalItems[] = $item;
                }
            }
    

            $closestItem = null;
            $shortestDistance = 4;

            foreach ($allCanonicalItems as $item) {
                $slug = $item->getSlug()->value();
                $distance = levenshtein($slugPartial, $slug);

                if ($distance < $shortestDistance) {
                    $shortestDistance = $distance;
                    $closestItem = $item;
                }
            }

            if ($closestItem !== null) {
                $canonicalCandidates[$closestItem->getId()] = $closestItem;
            }
        }

    
        return array_map(
            fn (Item $item) => new ItemSuggestionDto($item->getName()),
            array_values($canonicalCandidates)
        );
    }


}