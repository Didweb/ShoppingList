<?php
namespace App\Request\Item;

use App\Request\AbstractRequestValidator;
use App\Request\RequestValidatorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;

class ItemAutocompleteRequest extends AbstractRequestValidator implements RequestValidatorInterface
{
    public function __construct(ValidatorInterface $validator) 
    {
        parent::__construct($validator);
    }

    public function validate(array $data): array
    {
        $constraints = new Assert\Collection([
            'q' => [
                new Assert\NotBlank(message: 'Por lo menos una palabara es obligatorio.'),
                new Assert\Type('string'),
            ]
        ]);

        $this->validateData($data, $constraints);

        return $data;
    }
}