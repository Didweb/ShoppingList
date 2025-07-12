<?php
namespace App\DTO\Circle;

use App\Entity\Circle;

class CircleSimpleDto
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $color,
        public readonly int $createdBy
    ) {} 


    public static function fromEntity(Circle $circle): self
    {
        return new self(
            $circle->getId(),
            $circle->getName(),
            $circle->getColor()->value(),
            $circle->getCreatedBy()->getId()
        );
    }
}