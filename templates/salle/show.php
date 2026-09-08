<?php

declare(strict_types=1);

$title = $salle->nom;

ob_start();
?>

<h2><?= htmlspecialchars($salle->nom) ?></h2>

<p>
    Capacité :
    <?= (int) $salle->capacite ?> places
</p>

<p>
    État :
    <?= $salle->active ? 'Active' : 'Inactive' ?>
</p>

<a href="/salles/<?= (int) $salle->id ?>/edit">
    Modifier
</a>

<a href="/salles">
    Retour
</a>

<?php
$content = ob_get_clean();
require dirname(__DIR__) . '/layout/base.php';
