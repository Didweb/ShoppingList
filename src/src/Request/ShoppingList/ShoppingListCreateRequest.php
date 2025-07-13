<?php
namespace App\Request\ShoppingList;

use App\Request\AbstractRequestValidator;
use App\Request\RequestValidatorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;

class ShoppingListCreateRequest extends AbstractRequestValidator implements RequestValidatorInterface
{
    public function __construct(ValidatorInterface $validator) 
    {
        parent::__construct($validator);
    }

    public function validate(array $data): array
    {
        $constraints = new Assert\Collection([
            'name' => [
                new Assert\NotBlank(message: 'El nombre es obligatorio.'),
                new Assert\Type('string'),
            ],
            'id_circle' => [
                new Assert\NotBlank(message: 'El ID Circle es obligatorio.'),
                new Assert\Type('integer'),
            ]
        ]);

        $this->validateData($data, $constraints);

        return $data;
    }
}