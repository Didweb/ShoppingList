<?php
namespace App\Request\Circle;

use App\Request\AbstractRequestValidator;
use App\Request\RequestValidatorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;

class CircleDeleteRequest extends AbstractRequestValidator implements RequestValidatorInterface
{
    public function __construct(ValidatorInterface $validator) 
    {
        parent::__construct($validator);
    }

    public function validate(array $data): array
    {
        $constraints = new Assert\Collection([
            'id_circle' => [
                new Assert\NotBlank(message: 'El id_circle es obligatorio.'),
                new Assert\Type('integer'),
            ]
        ]);

        $this->validateData($data, $constraints);

        return $data;
    }
}