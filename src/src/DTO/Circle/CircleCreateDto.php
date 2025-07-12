<?php
namespace App\DTO\Circle;

final class CircleCreateDto
{
    public function __construct(
        public readonly string $name,
        public readonly string $color,
    ) {} 
}