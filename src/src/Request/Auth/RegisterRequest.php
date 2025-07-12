<?php
namespace App\Request\Auth;

use App\Request\AbstractRequestValidator;
use App\Request\RequestValidatorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;

class RegisterRequest extends AbstractRequestValidator implements RequestValidatorInterface
{
    public function __construct(ValidatorInterface $validator) 
    {
        parent::__construct($validator);
    }

    public function validate(array $data): array
    {
        $constraints = new Assert\Collection([
            'email' => [
                new Assert\NotBlank(message: 'El email es obligatorio.'),
                new Assert\Type('string'),
                new Assert\Email(message: 'El email no es válido.'),
            ],
            'password' => [
                new Assert\NotBlank(message: 'La contraseña es obligatoria.'),
                new Assert\Type('string'),
                new Assert\Length([
                    'min' => 8,
                    'minMessage' => 'La contraseña debe tener al menos {{ limit }} caracteres.',
                ]),
            ],
            'name' => [
                new Assert\NotBlank(message: 'El nombre es obligatorio.'),
                new Assert\Type('string'),
            ],
        ]);

        $this->validateData($data, $constraints);

        return $data;
    }
}