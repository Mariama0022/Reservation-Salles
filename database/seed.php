<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Model\Salle;

require __DIR__ . '/../config/database.php';

$salles = [
    [
        'nom' => 'Amphithéâtre A',
        'capacite' => 250,
        'active' => true,
    ],
    [
        'nom' => 'Salle B12',
        'capacite' => 40,
        'active' => true,
    ],
    [
        'nom' => 'Laboratoire Chimie',
        'capacite' => 24,
        'active' => true,
    ],
    [
        'nom' => 'Salle Informatique 1',
        'capacite' => 30,
        'active' => true,
    ],
    [
        'nom' => 'Salle de réunion',
        'capacite' => 12,
        'active' => true,
    ],
];

foreach ($salles as $donnees) {
    Salle::firstOrCreate(
        [
            'nom' => $donnees['nom'],
        ],
        $donnees
    );
}

echo "Données initiales ajoutées avec succès !" . PHP_EOL;

