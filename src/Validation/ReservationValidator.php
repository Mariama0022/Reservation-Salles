<?php

declare(strict_types=1);

namespace App\Validation;

use Respect\Validation\Validator as v;

class ReservationValidator implements ValidatorInterface
{
    public function validate(array $data): ValidationResult
    {
        $errors = [];

        if (!v::intType()->positive()->isValid($data['salle_id'] ?? null)) {
            $errors['salle_id'] = 'L\'identifiant de la salle doit être un entier positif.';
        }

        if (!v::stringType()->notEmpty()->length(2, 120)->isValid($data['responsable'] ?? null)) {
            $errors['responsable'] = 'Le responsable doit contenir entre 2 et 120 caractères.';
        }

        if (!v::email()->isValid($data['email'] ?? null)) {
            $errors['email'] = 'L\'adresse email est invalide.';
        }

        if (!v::stringType()->notEmpty()->length(5, 255)->isValid($data['motif'] ?? null)) {
            $errors['motif'] = 'Le motif doit contenir entre 5 et 255 caractères.';
        }

        if (!v::date('Y-m-d H:i:s')->isValid($data['date_debut'] ?? null)) {
            $errors['date_debut'] = 'La date de début est invalide.';
        }

        if (!v::date('Y-m-d H:i:s')->isValid($data['date_fin'] ?? null)) {
            $errors['date_fin'] = 'La date de fin est invalide.';
        }

        return new ValidationResult(
            empty($errors),
            $errors,
            $data
        );
    }
}
