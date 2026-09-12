<?php

declare(strict_types=1);

namespace Tests\Integration\Repository;

use App\Model\Reservation;
use App\Model\Salle;
use App\Repository\EloquentReservationRepository;
use PHPUnit\Framework\TestCase;

class ReservationRepositoryTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        require_once __DIR__ . '/../../../config/database.php';
    }

    public function testRechercheConflitReservation(): void
    {
        $salle = new Salle();

        $salle->nom = 'Salle Conflit Test';
        $salle->batiment = 'Bâtiment A';
        $salle->capacite = 30;
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

        $repository = new EloquentReservationRepository();

        $conflit = $repository->rechercherConflit(
            $salle->id,
            new \DateTimeImmutable('2099-10-10 11:00:00'),
            new \DateTimeImmutable('2099-10-10 13:00:00')
        );

        $this->assertNotNull($conflit);
        $this->assertSame($reservation->id, $conflit->id);

        $reservation->delete();
        $salle->delete();
    }

    public function testReservationAdjacenteSansConflit(): void
    {
        $salle = new Salle();

        $salle->nom = 'Salle Adjacente Test';
        $salle->batiment = 'Bâtiment A';
        $salle->capacite = 30;
        $salle->type = 'reunion';
        $salle->active = true;

        $salle->save();

        $reservation = new Reservation();

        $reservation->nom_reservant = 'Mariama Ba';
        $reservation->date_reservation = '2099-10-11';
        $reservation->heure_debut = '10:00:00';
        $reservation->heure_fin = '12:00:00';
        $reservation->salle_id = $salle->id;

        $reservation->save();

        $repository = new EloquentReservationRepository();

        $conflit = $repository->rechercherConflit(
            $salle->id,
            new \DateTimeImmutable('2099-10-11 12:00:00'),
            new \DateTimeImmutable('2099-10-11 14:00:00')
        );

        $this->assertNull($conflit);

        $reservation->delete();
        $salle->delete();
    }

    public function testAnnulerReservation(): void
    {
        $salle = new Salle();

        $salle->nom = 'Salle Annulation Test';
        $salle->batiment = 'Bâtiment C';
        $salle->capacite = 20;
        $salle->type = 'reunion';
        $salle->active = true;

        $salle->save();

        $reservation = new Reservation();

        $reservation->nom_reservant = 'Mariama Ba';
        $reservation->date_reservation = '2099-10-12';
        $reservation->heure_debut = '10:00:00';
        $reservation->heure_fin = '12:00:00';
        $reservation->salle_id = $salle->id;

        $reservation->save();

        $repository = new EloquentReservationRepository();

        $repository->annuler($reservation);

        $reservationExiste = Reservation::find($reservation->id);

        $this->assertNull($reservationExiste);

        $salle->delete();
    }
}