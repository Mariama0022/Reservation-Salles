<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\CreerReservationDTO;
use App\Exception\ReservationIntrouvableException;
use App\Exception\SalleIndisponibleException;
use App\Repository\ReservationRepositoryInterface;
use App\Repository\SalleRepositoryInterface;
use App\Service\AnnulerReservationService;
use App\Service\CreerReservationService;
use App\Validation\ReservationValidator;
use DateTimeImmutable;
use Throwable;

class ReservationController
{
    public function __construct(
        private ReservationRepositoryInterface $reservationRepository,
        private SalleRepositoryInterface $salleRepository,
        private ReservationValidator $validator,
        private CreerReservationService $creerReservationService,
        private AnnulerReservationService $annulerReservationService
    ) {
    }

    public function index(): void
    {
        $reservations = $this->reservationRepository->lister();

        require dirname(__DIR__, 2) . '/templates/reservation/index.php';
    }

    public function show(int $id): void
    {
        $reservation = $this->reservationRepository->trouver($id);

        if ($reservation === null) {
            http_response_code(404);
            require dirname(__DIR__, 2) . '/templates/error/404.php';
            return;
        }

        require dirname(__DIR__, 2) . '/templates/reservation/show.php';
    }

    public function create(): void
    {
        $salles = $this->salleRepository->lister();

        $errors = [];
        $data = [];

        require dirname(__DIR__, 2) . '/templates/reservation/form.php';
    }

    public function store(): void
    {
        $data = [
            'salle_id' => isset($_POST['salle_id'])
                ? (int) $_POST['salle_id']
                : 0,

            'responsable' => $_POST['responsable'] ?? '',

            'email' => $_POST['email'] ?? '',

            'motif' => $_POST['motif'] ?? '',

            'date_debut' => $_POST['date_debut'] ?? '',

            'date_fin' => $_POST['date_fin'] ?? '',
        ];

        $resultat = $this->validator->validate($data);

        if (!$resultat->isValid()) {
            $errors = $resultat->errors();
            $salles = $this->salleRepository->lister();

            require dirname(__DIR__, 2) . '/templates/reservation/form.php';
            return;
        }

        try {
            $dto = new CreerReservationDTO(
                $data['salle_id'],
                $data['responsable'],
                $data['email'],
                $data['motif'],
                new DateTimeImmutable($data['date_debut']),
                new DateTimeImmutable($data['date_fin'])
            );

            $reservation = $this->creerReservationService->executer($dto);

            header('Location: /reservations/' . $reservation->id);
            exit;

        } catch (SalleIndisponibleException $e) {
            $errors = [
                'date_debut' => $e->getMessage(),
            ];

            $salles = $this->salleRepository->lister();

            require dirname(__DIR__, 2) . '/templates/reservation/form.php';

        } catch (Throwable $e) {
            $errors = [
                'date_debut' => $e->getMessage(),
            ];

            $salles = $this->salleRepository->lister();

            require dirname(__DIR__, 2) . '/templates/reservation/form.php';
        }
    }

    public function cancel(int $id): void
    {
        try {
            $this->annulerReservationService->executer($id);

            header('Location: /reservations');
            exit;

        } catch (ReservationIntrouvableException) {
            http_response_code(404);

            require dirname(__DIR__, 2) . '/templates/error/404.php';
        }
    }
}