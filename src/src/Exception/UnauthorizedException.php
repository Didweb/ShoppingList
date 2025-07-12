<?php
namespace App\Exception;

use DomainException;

class UnauthorizedException extends DomainException
{
    private int $statusCode;

    public function __construct(
        string $message = 'Sin permiso para acceder a este recurso.',
        int $statusCode = 403
    ) {
        parent::__construct($message);
        $this->statusCode = $statusCode;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}