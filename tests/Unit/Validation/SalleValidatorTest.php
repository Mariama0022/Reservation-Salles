<?php

declare(strict_types=1);

namespace Tests\Unit\Validation;

use App\Validation\SalleValidator;
use PHPUnit\Framework\TestCase;

class SalleValidatorTest extends TestCase
{
    public function testCapaciteNegative(): void
    {
        $validator = new SalleValidator();

        $resultat = $validator->validate([
            'nom' => 'Salle A',
            'batiment' => 'Batiment principal',
            'capacite' => -10,
            'type' => 'salle',
            'active' => true,
        ]);

        $this->assertFalse($resultat->isValid());
        $this->assertArrayHasKey('capacite', $resultat->errors());
    }

    public function testTypeDeSalleInconnu(): void
    {
        $validator = new SalleValidator();

        $resultat = $validator->validate([
            'nom' => 'Salle A',
            'batiment' => 'Batiment principal',
            'capacite' => 30,
            'type' => 'garage',
            'active' => true,
        ]);

        $this->assertFalse($resultat->isValid());
        $this->assertArrayHasKey('type', $resultat->errors());
    }
}
