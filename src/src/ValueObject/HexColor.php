<?php
namespace App\ValueObject;

use InvalidArgumentException;

class HexColor
{
    private string $value;

    public function __construct(string $value)
    {
        $value = strtoupper($value);
        $this->ensureIsValid($value);
        $this->value = $value;
    }

    private function ensureIsValid(string $value): void
    {
        if (!preg_match('/^#[0-9A-F]{8}$/', $value)) {
            throw new InvalidArgumentException(
                sprintf('Hex color must be 9 characters long including "#", got "%s"', $value)
            );
        }
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(HexColor $other): bool
    {
        return $this->value === $other->value();
    }
}