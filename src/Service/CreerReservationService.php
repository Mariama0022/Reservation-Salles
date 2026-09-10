<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\CreerReservationDTO;
use App\Exception\SalleIndisponibleException;
use App\Repository\ReservationRepositoryInterface;
use App\Repository\SalleRepositoryInterface;
use App\Model\Reservation;
use DateTimeImmutable;
use DomainException;

class CreerReservationService
{
    public function __construct(
        private SalleRepositoryInterface $salleRepository,
        private ReservationRepositoryInterface $reservationRepository
    ) {
    }

    public function executer(CreerReservationDTO $dto): Reservation
    {
        // 1. Retrouver la salle
        $salle = $this->salleRepository->trouver($dto->salleId);

        if ($salle === null) {
            throw new DomainException('Salle introuvable.');
        }

        // 2. Vérifier que la salle est active
        if (!$salle->active) {
            throw new DomainException('La salle est inactive.');
        }

        // 3. Vérifier que le début précède la fin
        if ($dto->dateDebut >= $dto->dateFin) {
            throw new DomainException(
                'La date de début doit précéder la date de fin.'
            );
        }

        // 4. Vérifier que la réservation est sur une seule journée
        if (
            $dto->dateDebut->format('Y-m-d')
            !==
            $dto->dateFin->format('Y-m-d')
        ) {
            throw new DomainException(
                'Une réservation doit commencer et finir le même jour.'
            );
        }

        // 5. Vérifier que la durée ne dépasse pas quatre heures
        $duree = $dto->dateFin->getTimestamp()
            - $dto->dateDebut->getTimestamp();

        if ($duree > 4 * 60 * 60) {
            throw new DomainException(
                'La durée de réservation ne doit pas dépasser quatre heures.'
            );
        }

        // 6. Vérifier que la date est future
        if ($dto->dateDebut <= new DateTimeImmutable()) {
            throw new DomainException(
                'La réservation doit commencer dans le futur.'
            );
        }

        // 7. Rechercher les chevauchements
        $conflit = $this->reservationRepository->rechercherConflit(
            $dto->salleId,
            $dto->dateDebut,
            $dto->dateFin
        );

        if ($conflit !== null) {
            throw new SalleIndisponibleException(
                'La salle est déjà réservée sur cette période.'
            );
        }

        // 8. Créer la réservation
        $reservation = new Reservation();

        $reservation->salle_id = $dto->salleId;

        $reservation->nom_reservant = $dto->responsable;

        $reservation->date_reservation =
            $dto->dateDebut->format('Y-m-d');

        $reservation->heure_debut =
            $dto->dateDebut->format('H:i:s');

        $reservation->heure_fin =
            $dto->dateFin->format('H:i:s');

        // 9. Enregistrer
        return $this->reservationRepository->enregistrer($reservation);
    }
}