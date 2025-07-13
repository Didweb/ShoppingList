<?php
namespace App\Exception;

use DomainException;

class DuplicateNotAllowedException extends DomainException
{
    private int $statusCode;
    private array $data;

    public function __construct(
        string $message = 'Duplicado no permitido.',
        array $data = [],
        int $statusCode = 409
    ) {
        
        parent::__construct($message);
        $this->statusCode = $statusCode;
        $this->data = $data;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getData(): array
    {
        return $this->data;
    }
}