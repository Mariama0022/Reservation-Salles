<?php

declare(strict_types=1);

namespace Tests\Integration\Repository;

use App\Model\Salle;
use App\Model\Reservation;
use App\Repository\EloquentSalleRepository;
use PHPUnit\Framework\TestCase;

class SalleRepositoryTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        require_once __DIR__ . '/../../../config/database.php';
    }

    public function testCreationSalleAvecEloquent(): void
    {
        $repository = new EloquentSalleRepository();

        $salle = new Salle();

        $salle->nom = 'Salle Test';
        $salle->batiment = 'Bâtiment A';
        $salle->capacite = 30;
        $salle->type = 'salle';
        $salle->active = true;

        $resultat = $repository->enregistrer($salle);

        $this->assertNotNull($resultat->id);
        $this->assertSame('Salle Test', $resultat->nom);
        $this->assertSame('Bâtiment A', $resultat->batiment);
        $this->assertSame(30, $resultat->capacite);
        $this->assertSame('salle', $resultat->type);
        $this->assertTrue($resultat->active);

        $resultat->delete();
    }

    public function testRelationSalleReservations(): void
    {
        $salle = new Salle();

        $salle->nom = 'Salle Relation Test';
        $salle->batiment = 'Bâtiment B';
        $salle->capacite = 40;
        $salle->type = 'reunion';
        $salle->active = true;

        $salle->save();

        $reservation = new Reservation();

        $reservation->nom_reservant = 'Mariama Ba';
        $reservation->date_reservation = '2099-10-10';
        $reservation->heure_debut = '10:00:00';
        $reservation->heure_fin = '12:00:00';
        $reservation->salle_id = $salle->id;

        $reservation->save();

        $salle->load('reservations');

        $this->assertCount(1, $salle->reservations);

        $this->assertSame(
            'Mariama Ba',
            $salle->reservations->first()->nom_reservant
        );

        $reservation->delete();
        $salle->delete();
    }
}