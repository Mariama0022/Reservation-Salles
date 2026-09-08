<?php

declare(strict_types=1);

$title = 'Liste des salles';

ob_start();
?>

<h2>Liste des salles</h2>

<a href="/salles/create">Créer une salle</a>

<?php if (empty($salles)): ?>
    <p>Aucune salle disponible.</p>
<?php else: ?>

    <ul>
        <?php foreach ($salles as $salle): ?>
            <li>
                <a href="/salles/<?= (int) $salle->id ?>">
                    <?= htmlspecialchars($salle->nom) ?>
                </a>

                - <?= (int) $salle->capacite ?> places

                <?php if ($salle->active): ?>
                    - Active
                <?php else: ?>
                    - Inactive
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>

<?php endif; ?>

<?php
$content = ob_get_clean();
require dirname(__DIR__) . '/layout/base.php';

