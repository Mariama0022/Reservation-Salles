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
            $table->string('nom', 100);
            $table->string('batiment', 100);
            $table->unsignedInteger('capacite');
            $table->string('type', 30);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    if (!$schema->hasTable('reservations')) {
        $schema->create('reservations', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('salle_id')->constrained('salles');
            $table->string('responsable', 120);
            $table->string('email', 255);
            $table->string('motif', 255);
            $table->dateTime('date_debut');
            $table->dateTime('date_fin');
            $table->timestamps();
        });
    }

    echo "Tables créées avec succès !" . PHP_EOL;
} catch (Throwable $e) {
    echo "Erreur lors de la création des tables : " . $e->getMessage() . PHP_EOL;
}
