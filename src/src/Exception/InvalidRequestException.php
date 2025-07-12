<?php
namespace App\Exception;

use RuntimeException;

final class InvalidRequestException extends RuntimeException
{
    private array $errors;

    public function __construct(array $errors)
    {
        parent::__construct('Errores de validación.');
        $this->errors = $errors;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}