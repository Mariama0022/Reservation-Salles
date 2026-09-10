<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\CreerSalleDTO;
use App\Model\Salle;
use App\Repository\SalleRepositoryInterface;
use App\Validation\SalleValidator;

class SalleController
{
    public function __construct(
        private SalleRepositoryInterface $salleRepository,
        private SalleValidator $validator
    ) {
    }

    public function index(): void
    {
        $salles = $this->salleRepository->lister();

        require dirname(__DIR__, 2) . '/templates/salle/index.php';
    }

    public function show(int $id): void
    {
        $salle = $this->salleRepository->trouver($id);

        if ($salle === null) {
            http_response_code(404);
            require dirname(__DIR__, 2) . '/templates/error/404.php';
            return;
        }

        require dirname(__DIR__, 2) . '/templates/salle/show.php';
    }

    public function create(): void
    {
        $errors = [];
        $data = [];

        require dirname(__DIR__, 2) . '/templates/salle/form.php';
    }

    public function store(): void
    {
        $data = [
            'nom' => $_POST['nom'] ?? '',
            'batiment' => $_POST['batiment'] ?? '',
            'capacite' => isset($_POST['capacite'])
                ? (int) $_POST['capacite']
                : 0,
            'type' => $_POST['type'] ?? '',
            'active' => isset($_POST['active']),
        ];

        $resultat = $this->validator->validate($data);

        if (!$resultat->isValid()) {
            $errors = $resultat->errors();

            require dirname(__DIR__, 2) . '/templates/salle/form.php';
            return;
        }

        $dto = new CreerSalleDTO(
            $data['nom'],
            $data['batiment'],
            $data['capacite'],
            $data['type'],
            $data['active']
        );

        $salle = new Salle();

        $salle->nom = $dto->nom;
        $salle->batiment = $dto->batiment;
        $salle->capacite = $dto->capacite;
        $salle->type = $dto->type;
        $salle->active = $dto->active;

        $this->salleRepository->enregistrer($salle);

        header('Location: /salles');
        exit;
    }

    public function edit(int $id): void
    {
        $salle = $this->salleRepository->trouver($id);

        if ($salle === null) {
            http_response_code(404);
            require dirname(__DIR__, 2) . '/templates/error/404.php';
            return;
        }

        $errors = [];
        $data = [
            'nom' => $salle->nom,
            'batiment' => $salle->batiment,
            'capacite' => $salle->capacite,
            'type' => $salle->type,
            'active' => $salle->active,
        ];

        require dirname(__DIR__, 2) . '/templates/salle/form.php';
    }

    public function update(int $id): void
    {
        $salle = $this->salleRepository->trouver($id);

        if ($salle === null) {
            http_response_code(404);
            require dirname(__DIR__, 2) . '/templates/error/404.php';
            return;
        }

        $data = [
            'nom' => $_POST['nom'] ?? '',
            'batiment' => $_POST['batiment'] ?? '',
            'capacite' => isset($_POST['capacite'])
                ? (int) $_POST['capacite']
                : 0,
            'type' => $_POST['type'] ?? '',
            'active' => isset($_POST['active']),
        ];

        $resultat = $this->validator->validate($data);

        if (!$resultat->isValid()) {
            $errors = $resultat->errors();

            require dirname(__DIR__, 2) . '/templates/salle/form.php';
            return;
        }

        $salle->nom = $data['nom'];
        $salle->capacite = $data['capacite'];
        $salle->active = $data['active'];

        $this->salleRepository->enregistrer($salle);

        header('Location: /salles/' . $id);
        exit;
    }
}
