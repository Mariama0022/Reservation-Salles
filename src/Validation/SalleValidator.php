<?php

declare(strict_types=1);

namespace App\Validation;

use Respect\Validation\Validator as v;

class SalleValidator implements ValidatorInterface
{
    public function validate(array $data): ValidationResult
    {
        $errors = [];

        if (!v::stringType()->notEmpty()->length(2, 100)->isValid($data['nom'] ?? null)) {
            $errors['nom'] = 'Le nom est obligatoire et doit contenir entre 2 et 100 caractères.';
        }

        if (!v::stringType()->notEmpty()->length(2, 100)->isValid($data['batiment'] ?? null)) {
            $errors['batiment'] = 'Le bâtiment est obligatoire et doit contenir entre 2 et 100 caractères.';
        }

        if (!v::intType()->between(1, 1000)->isValid($data['capacite'] ?? null)) {
            $errors['capacite'] = 'La capacité doit être un entier compris entre 1 et 1000.';
        }

        if (!v::in([
            'amphitheatre',
            'salle',
            'laboratoire',
            'informatique',
            'reunion',
        ])->isValid($data['type'] ?? null)) {
            $errors['type'] = 'Le type de salle est invalide.';
        }

        if (!v::boolType()->isValid($data['active'] ?? null)) {
            $errors['active'] = 'Le champ active doit être un booléen.';
        }

        return new ValidationResult(
            empty($errors),
            $errors,
            $data
        );
    }
}
