<?php
namespace App\Request\Circle;

use App\Request\AbstractRequestValidator;
use App\Request\RequestValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CircleEditRequest extends AbstractRequestValidator implements RequestValidatorInterface
{
    public function __construct(ValidatorInterface $validator) 
    {
        parent::__construct($validator);
    }

    public function validate(array $data): array
    {
        $constraints = new Assert\Collection([
            'id' => [
                new Assert\NotBlank(message: 'El Id es obligatorio.'),
                new Assert\Type('integer'),
            ],
            'name' => [
                new Assert\Type('string'),
            ],
            'color' => [
                new Assert\Regex([
                    'pattern' => '/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{8})$/',
                    'message' => 'Formato color inválido: debe ser hexadecimal con o sin transparencia.',
                ])
            ],
        ]);

        $this->validateData($data, $constraints);

        return $data;
    }
}