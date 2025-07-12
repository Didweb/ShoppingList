<?php
namespace App\Request;

use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraint;
use App\Exception\InvalidRequestException;

abstract class AbstractRequestValidator
{
    public function __construct(
        protected readonly ValidatorInterface $validator
    ) {} 
    
    protected function validateData(array $data, Constraint $constraints): void
    {
        $violations = $this->validator->validate($data, $constraints);

        if (count($violations) > 0) {
            $errors = [];
            foreach ($violations as $violation) {
                $errors[] = $violation->getPropertyPath() . ': ' . $violation->getMessage();
            }

            throw new InvalidRequestException($errors);
        }
    }
}