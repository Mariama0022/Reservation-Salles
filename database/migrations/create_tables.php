<?php

declare(strict_types=1);

require __DIR__ . '/../../vendor/autoload.php';

use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Schema\Blueprint;

$capsule = require __DIR__ . '/../../config/database.php';

try {
    $schema = $capsule->schema();

    if (!$schema->hasTable('salles')) {
        $schema->create('salles', function (Blueprint $table): void {
            $table->id();
            $table->string('nom');
            $table->integer('capacite');
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    if (!$schema->hasTable('reservations')) {
        $schema->create('reservations', function (Blueprint $table): void {
            $table->id();
            $table->string('nom_reservant');
            $table->date('date_reservation');
            $table->time('heure_debut');
            $table->time('heure_fin');
            $table->foreignId('salle_id')->constrained('salles');
            $table->timestamps();
        });
    }

    echo "Tables créées avec succès !" . PHP_EOL;
} catch (Throwable $e) {
    echo "Erreur lors de la création des tables : " . $e->getMessage() . PHP_EOL;
}
