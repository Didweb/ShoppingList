<?php
namespace App\DTO\Circle;

final class CircleEditDto
{
        public function __construct(
        public readonly string $id,
        public readonly ?string $name,
        public readonly ?string $color,
        public readonly int $idUser
    ) {} 
}