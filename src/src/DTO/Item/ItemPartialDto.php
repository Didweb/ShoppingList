<?php
namespace App\DTO\Item;

final class ItemPartialDto
{
    public function __construct(
        public readonly string $partial
    ) {}
}