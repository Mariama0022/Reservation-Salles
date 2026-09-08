<?php

declare(strict_types=1);

$title = 'Page introuvable';

ob_start();
?>

<h2>404 - Page introuvable</h2>

<p>La ressource demandée n'existe pas.</p>

<a href="/salles">Retour à l'accueil</a>

<?php
$content = ob_get_clean();
require dirname(__DIR__) . '/layout/base.php';
