<?php
namespace App\DTO\Circle;

class CirclePayloadDto
{
    public function __construct(
        public readonly string $type,
        public readonly int $id,
        public readonly int $v
    ) {}  
}