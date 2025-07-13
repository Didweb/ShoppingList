<?php
namespace App\Request\Item;

use App\Request\AbstractRequestValidator;
use App\Request\RequestValidatorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;

class ItemAddRequest extends AbstractRequestValidator implements RequestValidatorInterface
{
    public function __construct(ValidatorInterface $validator) 
    {
        parent::__construct($validator);
    }

    public function validate(array $data): array
    {
        $constraints = new Assert\Collection([
            'id_shopping_list' => [
                new Assert\NotBlank(message: 'El ID ShopingLis es obligatorio.'),
                new Assert\Type('int'),
            ],
            'name' => [
                new Assert\NotBlank(message: 'El nombre es obligatorio.'),
                new Assert\Type('string'),
            ],
            'id_selected_item' => new Assert\Optional([
                new Assert\Type('int'),
            ]),
        ]);

        $this->validateData($data, $constraints);

        return $data;
    }
}