<?php
namespace App\Request\ShoppingList;

use App\Request\AbstractRequestValidator;
use App\Request\RequestValidatorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;

class ShoppingListEditRequest extends AbstractRequestValidator implements RequestValidatorInterface
{
    public function __construct(ValidatorInterface $validator) 
    {
        parent::__construct($validator);
    }

    public function validate(array $data): array
    {
        $constraints = new Assert\Collection([
            'id_shopping_list' => [
                new Assert\NotBlank(message: 'El Id Shoopping List es obligatorio.'),
                new Assert\Type('integer'),
            ],
            'name' => [
                new Assert\NotBlank(message: 'El Name es obligatorio.'),
                new Assert\Type('string'),
            ]
        ]);

        $this->validateData($data, $constraints);

        return $data;
    }

}