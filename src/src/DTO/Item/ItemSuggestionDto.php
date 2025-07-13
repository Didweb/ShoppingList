<?php
namespace App\DTO\Item;

final class ItemSuggestionDto
{
    public function __construct(
        public readonly string $suggestion
    ) {}
}