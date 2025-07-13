<?php
namespace App\DTO\Item;

use App\Entity\Item;

final class ItemSimpleDto
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly ?int $createdBy
    ) {}

    public static function fromEntity(Item $item): self 
    {
        return new self(
            $item->getId(),
            $item->getName(),
            $item->getCreatedBy() ? $item->getCreatedBy()->getId() :  null,
        );
    }
}