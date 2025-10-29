<?php
declare(strict_types=1);

namespace App\DTO\Circle;


final class CirclePayloadDto
{
    public function __construct(
        public readonly string $type,
        public readonly int $id,
        public readonly int $v
    ) {}  
}