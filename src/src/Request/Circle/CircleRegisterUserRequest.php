<?php
namespace App\Request\Circle;

use App\Request\AbstractRequestValidator;
use App\Request\RequestValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CircleRegisterUserRequest extends AbstractRequestValidator implements RequestValidatorInterface
{
    public function __construct(ValidatorInterface $validator) 
    {
        parent::__construct($validator);
    } 

    public function validate(array $data): array
    {
        $constraints = new Assert\Collection([
                'type' => [
                    new Assert\NotBlank(message: 'El type ser obligatorio.'),
                    new Assert\Type('string'),
                ],
                'id' => [
                    new Assert\NotBlank(message: 'El id ser obligatorio.'),
                    new Assert\Type('integer'),
                ],
                'v' => [
                    new Assert\NotBlank(message: 'El v debe ser obligatorio.'),
                    new Assert\Type('integer'),
                ]
        ]);

        $this->validateData($data, $constraints);

        return $data;
    }
}