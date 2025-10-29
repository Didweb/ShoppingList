<?php
namespace App\DTO\Item;

use App\Entity\Item;

class ItemDto
{
    public function __construct(
        public readonly ?int $id,
        public readonly string $name,
        public readonly string $slug,
        public readonly ?int $createBy,
        public readonly int $canonicalItem
    ) {}

    public static function fromEntity(Item $item): self 
    {
        return new self(
            $item->getId() ?? null,
            $item->getName(),
            $item->getSlug(),
            $item->getCreatedBy() ? $item->getCreatedBy()->getId() :  null,
            $item->getCanonical()->getId()
        );
    }
}