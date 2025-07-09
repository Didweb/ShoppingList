<?php
namespace App\ValueObject;

use InvalidArgumentException;

final class Status
{
    public const PENDING = 'pending';
    public const PURCHASED = 'purchased';

    private string $value;

    private static array $allowed = [
        self::PENDING,
        self::PURCHASED
    ];

    public function __construct(string $value)
    {
        if (!in_array($value, self::$allowed, true)) {
            throw new InvalidArgumentException("Invalid status: $value");
        }

        $this->value = $value;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public static function pending(): self
    {
        return new self(self::PENDING);
    }

    public static function purchased(): self
    {
        return new self(self::PURCHASED);
    }
}