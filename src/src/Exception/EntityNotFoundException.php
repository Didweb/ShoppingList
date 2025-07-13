<?php
namespace App\Exception;

use DomainException;

class EntityNotFoundException extends DomainException
{
    private int $statusCode;

    public function __construct(
        string $message = 'Entidad no encontrada.',
        int $statusCode = 409
    ) {
        parent::__construct($message);
        $this->statusCode = $statusCode;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}