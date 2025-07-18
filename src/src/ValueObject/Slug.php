<?php
namespace App\ValueObject;

use InvalidArgumentException;

final class Slug
{
    
    private string $value;

    public function __construct(string $text)
    {
        $slug = self::slugify($text);
        if (empty($slug)) {
            throw new InvalidArgumentException('Slug cannot be empty.');
        }
        $this->value = $slug;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function equals(Slug $other): bool
    {
        return $this->value === $other->value;
    }

    public function levenshteinDistance(Slug $other): int
    {
        return levenshtein($this->value, $other->value);
    }

    public static function slugify(string $text): string
    {
        $slug = iconv('UTF-8', 'ASCII//TRANSLIT', $text);
        $slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug); 
        $slug = trim($slug, '-');
        return strtolower($slug);
    }

    public function value(): string
    {
        return $this->value;
    }
}