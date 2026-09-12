<?php

declare(strict_types=1);

namespace Tests\Unit\Validation;

use App\Validation\ReservationValidator;
use PHPUnit\Framework\TestCase;

class ReservationValidatorTest extends TestCase
{
    private function donneesValides(): array
    {
        return [
            'salle_id' => 1,
            'responsable' => 'Mariama Ba',
            'email' => 'mariama@example.com',
            'motif' => 'Réunion de travail',
            'date_debut' => '2099-10-10T10:00',
            'date_fin' => '2099-10-10T12:00',
        ];
    }

    public function testEmailInvalide(): void
    {
        $validator = new ReservationValidator();

        $data = $this->donneesValides();
        $data['email'] = 'email-invalide';

        $resultat = $validator->validate($data);

        $this->assertFalse($resultat->isValid());
        $this->assertArrayHasKey('email', $resultat->errors());
    }

    public function testResponsableVide(): void
    {
        $validator = new ReservationValidator();

        $data = $this->donneesValides();
        $data['responsable'] = '';

        $resultat = $validator->validate($data);

        $this->assertFalse($resultat->isValid());
        $this->assertArrayHasKey('responsable', $resultat->errors());
    }

    public function testDateIncorrecte(): void
    {
        $validator = new ReservationValidator();

        $data = $this->donneesValides();
        $data['date_debut'] = 'date-invalide';

        $resultat = $validator->validate($data);

        $this->assertFalse($resultat->isValid());
        $this->assertArrayHasKey('date_debut', $resultat->errors());
    }
}
