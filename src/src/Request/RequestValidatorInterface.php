<?php
namespace App\Request;

interface RequestValidatorInterface
{
    public function validate(array $data): array;
}