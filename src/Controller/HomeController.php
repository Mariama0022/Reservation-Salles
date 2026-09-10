<?php

declare(strict_types=1);

namespace App\Controller;

class HomeController
{
    public function index(): void
    {
        $title = 'Accueil';

        ob_start();
        ?>
        <h2>Bienvenue</h2>
        <p>Application de gestion des réservations de salles.</p>
        <p>
            <a href="/salles">Gérer les salles</a>
            |
            <a href="/reservations">Gérer les réservations</a>
        </p>
        <?php
        $content = ob_get_clean();

        require dirname(__DIR__, 2) . '/templates/layout/base.php';
    }
}
