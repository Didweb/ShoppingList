<?php
namespace App\Exception;

use DomainException;

class InvalidOptionValueObjectException extends DomainException
{
    private int $statusCode;

    public function __construct(
        string $message = 'Valor no permitido.',
        int $statusCode = 422
    ) {
        parent::__construct($message);
        $this->statusCode = $statusCode;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}