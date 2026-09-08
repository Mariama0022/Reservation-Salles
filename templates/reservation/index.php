<?php

declare(strict_types=1);

$title = 'Liste des réservations';

ob_start();
?>

<h2>Liste des réservations</h2>

<a href="/reservations/create">Créer une réservation</a>

<?php if (empty($reservations)): ?>

    <p>Aucune réservation.</p>

<?php else: ?>

    <ul>
        <?php foreach ($reservations as $reservation): ?>
            <li>
                <a href="/reservations/<?= (int) $reservation->id ?>">
                    Réservation #<?= (int) $reservation->id ?>
                </a>

                -
                <?= htmlspecialchars($reservation->responsable) ?>
            </li>
        <?php endforeach; ?>
    </ul>

<?php endif; ?>

<?php
$content = ob_get_clean();
require dirname(__DIR__) . '/layout/base.php';
