<?php

declare(strict_types=1);

$title = 'Réservation';

ob_start();
?>

<h2>Réservation #<?= (int) $reservation->id ?></h2>

<p>
    Responsable :
    <?= htmlspecialchars((string) $reservation->nom_reservant) ?>
</p>

<p>
    Salle :
    <?= (int) $reservation->salle_id ?>
</p>

<p>
    Date :
    <?= htmlspecialchars((string) $reservation->date_reservation) ?>
</p>

<p>
    Heure de début :
    <?= htmlspecialchars((string) $reservation->heure_debut) ?>
</p>

<p>
    Heure de fin :
    <?= htmlspecialchars((string) $reservation->heure_fin) ?>
</p>

<form
    method="POST"
    action="/reservations/<?= (int) $reservation->id ?>/cancel"
>
    <button type="submit">
        Annuler la réservation
    </button>
</form>

<a href="/reservations">
    Retour
</a>

<?php
$content = ob_get_clean();
require dirname(__DIR__) . '/layout/base.php';

