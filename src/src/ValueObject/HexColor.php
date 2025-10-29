<?php
namespace App\ValueObject;

use App\Exception\InvalidOptionValueObjectException;

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
        if (!preg_match('/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{8})$/', $value)) {
            throw new InvalidOptionValueObjectException('Formato color inválido: debe ser hexadecimal con o sin transparencia.');
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