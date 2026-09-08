<?php

declare(strict_types=1);

$title = 'Méthode non autorisée';

ob_start();
?>

<h2>405 - Méthode non autorisée</h2>

<p>La méthode HTTP utilisée n'est pas autorisée.</p>

<a href="/salles">Retour</a>

<?php
$content = ob_get_clean();
require dirname(__DIR__) . '/layout/base.php';
