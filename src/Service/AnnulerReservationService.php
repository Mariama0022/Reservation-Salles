<?php

declare(strict_types=1);

namespace App\Service;

use App\Exception\ReservationIntrouvableException;
use App\Repository\ReservationRepositoryInterface;
use App\Model\Reservation;

class AnnulerReservationService
{
    public function __construct(
        private ReservationRepositoryInterface $reservationRepository
    ) {
    }

    public function executer(int $reservationId): Reservation
    {
        $reservation = $this->reservationRepository->trouver($reservationId);

        if ($reservation === null) {
            throw new ReservationIntrouvableException(
                'La réservation demandée est introuvable.'
            );
        }

        return $this->reservationRepository->annuler($reservation);
    }
}
