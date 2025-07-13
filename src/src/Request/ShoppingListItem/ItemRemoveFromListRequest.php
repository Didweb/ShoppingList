<?php
namespace App\Request\ShoppingListItem;

use App\Request\AbstractRequestValidator;
use App\Request\RequestValidatorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;

class ItemRemoveFromListRequest extends AbstractRequestValidator implements RequestValidatorInterface
{
    public function __construct(ValidatorInterface $validator) 
    {
        parent::__construct($validator);
    }

    public function validate(array $data): array
    {
        $constraints = new Assert\Collection([
            'id_item' => [
                new Assert\NotBlank(message: 'El id Item es obligatorio.'),
                new Assert\Type('integer'),
            ],
            'id_shopping_list' => [
                new Assert\NotBlank(message: 'El id Shopping Lista es obligatorio.'),
                new Assert\Type('integer'),
            ]
        ]);

        $this->validateData($data, $constraints);

        return $data;
    }
}