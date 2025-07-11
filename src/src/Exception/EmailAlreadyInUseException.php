<?php
namespace App\Exception;

use DomainException;

class EmailAlreadyInUseException extends DomainException
{
    private int $statusCode;

    public function __construct(
        string $message = 'Email ya existe.',
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