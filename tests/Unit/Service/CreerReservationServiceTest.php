<?php

declare(strict_types=1);

namespace Tests\Unit\Service;

use App\DTO\CreerReservationDTO;
use App\Exception\SalleIndisponibleException;
use App\Model\Reservation;
use App\Model\Salle;
use App\Repository\ReservationRepositoryInterface;
use App\Repository\SalleRepositoryInterface;
use App\Service\CreerReservationService;
use DateTimeImmutable;
use DomainException;
use PHPUnit\Framework\TestCase;

class CreerReservationServiceTest extends TestCase
{
    private function creerSalle(
        int $id = 1,
        bool $active = true
    ): Salle {
        $salle = new Salle();

        $salle->id = $id;
        $salle->nom = 'Salle A';
        $salle->capacite = 30;
        $salle->active = $active;

        return $salle;
    }

    private function creerDTO(
        string $debut = '2099-10-10 10:00:00',
        string $fin = '2099-10-10 12:00:00',
        int $salleId = 1
    ): CreerReservationDTO {
        return new CreerReservationDTO(
            $salleId,
            'Mariama Ba',
            'mariama@example.com',
            'Réunion de travail',
            new DateTimeImmutable($debut),
            new DateTimeImmutable($fin)
        );
    }

    private function creerService(
        SalleRepositoryMemoire $salleRepository,
        ReservationRepositoryMemoire $reservationRepository
    ): CreerReservationService {
        return new CreerReservationService(
            $salleRepository,
            $reservationRepository
        );
    }

    public function testReservationValide(): void
    {
        $salleRepository = new SalleRepositoryMemoire();
        $reservationRepository = new ReservationRepositoryMemoire();

        $salleRepository->salle = $this->creerSalle();

        $service = $this->creerService(
            $salleRepository,
            $reservationRepository
        );

        $reservation = $service->executer(
            $this->creerDTO()
        );

        $this->assertInstanceOf(Reservation::class, $reservation);
        $this->assertSame(1, $reservation->salle_id);
        $this->assertSame('Mariama Ba', $reservation->getAttribute('nom_reservant'));    
        $this->assertCount(1, $reservationRepository->reservations);
    }

    public function testSalleInexistante(): void
    {
        $salleRepository = new SalleRepositoryMemoire();
        $reservationRepository = new ReservationRepositoryMemoire();

        $service = $this->creerService(
            $salleRepository,
            $reservationRepository
        );

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Salle introuvable.');

        $service->executer(
            $this->creerDTO()
        );
    }

    public function testSalleInactive(): void
    {
        $salleRepository = new SalleRepositoryMemoire();
        $reservationRepository = new ReservationRepositoryMemoire();

        $salleRepository->salle = $this->creerSalle(
            active: false
        );

        $service = $this->creerService(
            $salleRepository,
            $reservationRepository
        );

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('La salle est inactive.');

        $service->executer(
            $this->creerDTO()
        );
    }

    public function testDateFinAnterieureAuDebut(): void
    {
        $salleRepository = new SalleRepositoryMemoire();
        $reservationRepository = new ReservationRepositoryMemoire();

        $salleRepository->salle = $this->creerSalle();

        $service = $this->creerService(
            $salleRepository,
            $reservationRepository
        );

        $dto = $this->creerDTO(
            '2099-10-10 14:00:00',
            '2099-10-10 12:00:00'
        );

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage(
            'La date de début doit précéder la date de fin.'
        );

        $service->executer($dto);
    }

    public function testDureeSuperieureAQuatreHeures(): void
    {
        $salleRepository = new SalleRepositoryMemoire();
        $reservationRepository = new ReservationRepositoryMemoire();

        $salleRepository->salle = $this->creerSalle();

        $service = $this->creerService(
            $salleRepository,
            $reservationRepository
        );

        $dto = $this->creerDTO(
            '2099-10-10 10:00:00',
            '2099-10-10 15:00:00'
        );

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage(
            'La durée de réservation ne doit pas dépasser quatre heures.'
        );

        $service->executer($dto);
    }

    public function testDatePassee(): void
    {
        $salleRepository = new SalleRepositoryMemoire();
        $reservationRepository = new ReservationRepositoryMemoire();

        $salleRepository->salle = $this->creerSalle();

        $service = $this->creerService(
            $salleRepository,
            $reservationRepository
        );

        $dto = $this->creerDTO(
            '2020-10-10 10:00:00',
            '2020-10-10 12:00:00'
        );

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage(
            'La réservation doit commencer dans le futur.'
        );

        $service->executer($dto);
    }

    public function testConflitAvecUneReservation(): void
    {
        $salleRepository = new SalleRepositoryMemoire();
        $reservationRepository = new ReservationRepositoryMemoire();

        $salleRepository->salle = $this->creerSalle();

        $reservationExistante = new Reservation();
        $reservationExistante->id = 10;

        $reservationRepository->conflit = $reservationExistante;

        $service = $this->creerService(
            $salleRepository,
            $reservationRepository
        );

        $this->expectException(SalleIndisponibleException::class);
        $this->expectExceptionMessage(
            'La salle est déjà réservée sur cette période.'
        );

        $service->executer(
            $this->creerDTO()
        );
    }

    public function testReservationVoisineSansChevauchement(): void
    {
        $salleRepository = new SalleRepositoryMemoire();
        $reservationRepository = new ReservationRepositoryMemoire();

        $salleRepository->salle = $this->creerSalle();

        // Une réservation se termine à 10h.
        // La nouvelle réservation commence à 10h.
        // Elles sont donc voisines et ne se chevauchent pas.
        $reservationRepository->conflit = null;

        $service = $this->creerService(
            $salleRepository,
            $reservationRepository
        );

        $dto = $this->creerDTO(
            '2099-10-10 10:00:00',
            '2099-10-10 12:00:00'
        );

        $reservation = $service->executer($dto);

        $this->assertInstanceOf(
            Reservation::class,
            $reservation
        );

        $this->assertCount(
            1,
            $reservationRepository->reservations
        );
    }
}


/**
 * Faux repository de salles utilisé uniquement pour les tests.
 * Aucune connexion MySQL.
 */
class SalleRepositoryMemoire implements SalleRepositoryInterface
{
    public ?Salle $salle = null;

    public function lister(): array
    {
        return $this->salle === null
            ? []
            : [$this->salle];
    }

    public function trouver(int $id): ?Salle
    {
        if ($this->salle === null) {
            return null;
        }

        return $this->salle->id === $id
            ? $this->salle
            : null;
    }

    public function enregistrer(Salle $salle): Salle
    {
        $this->salle = $salle;

        return $salle;
    }
}


/**
 * Faux repository de réservations utilisé uniquement pour les tests.
 * Aucune connexion MySQL.
 */
class ReservationRepositoryMemoire
    implements ReservationRepositoryInterface
{
    /** @var Reservation[] */
    public array $reservations = [];

    public ?Reservation $conflit = null;

    public function lister(): array
    {
        return $this->reservations;
    }

    public function trouver(int $id): ?Reservation
    {
        foreach ($this->reservations as $reservation) {
            if ($reservation->id === $id) {
                return $reservation;
            }
        }

        return null;
    }

    public function rechercherConflit(
        int $salleId,
        DateTimeImmutable $dateDebut,
        DateTimeImmutable $dateFin
    ): ?Reservation {
        return $this->conflit;
    }

    public function enregistrer(
        Reservation $reservation
    ): Reservation {
        $this->reservations[] = $reservation;

        return $reservation;
    }

    public function annuler(
        Reservation $reservation
    ): Reservation {
        return $reservation;
    }
}
