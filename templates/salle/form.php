<?php

declare(strict_types=1);

$title = isset($salle) ? 'Modifier une salle' : 'Créer une salle';

ob_start();
?>

<h2><?= htmlspecialchars($title) ?></h2>

<form method="POST">

    <div>
        <label for="nom">Nom</label>

        <input
            type="text"
            id="nom"
            name="nom"
            value="<?= htmlspecialchars($data['nom'] ?? '') ?>"
        >

        <?php if (isset($errors['nom'])): ?>
            <p><?= htmlspecialchars($errors['nom']) ?></p>
        <?php endif; ?>
    </div>

    <div>
        <label for="batiment">Bâtiment</label>

        <input
            type="text"
            id="batiment"
            name="batiment"
            value="<?= htmlspecialchars($data['batiment'] ?? '') ?>"
        >

        <?php if (isset($errors['batiment'])): ?>
            <p><?= htmlspecialchars($errors['batiment']) ?></p>
        <?php endif; ?>
    </div>

    <div>
        <label for="capacite">Capacité</label>

        <input
            type="number"
            id="capacite"
            name="capacite"
            value="<?= htmlspecialchars((string) ($data['capacite'] ?? '')) ?>"
        >

        <?php if (isset($errors['capacite'])): ?>
            <p><?= htmlspecialchars($errors['capacite']) ?></p>
        <?php endif; ?>
    </div>

    <div>
        <label for="type">Type</label>

        <select id="type" name="type">
            <?php
            $types = [
                'amphitheatre',
                'salle',
                'laboratoire',
                'informatique',
                'reunion',
            ];
            ?>

            <?php foreach ($types as $type): ?>
                <option
                    value="<?= htmlspecialchars($type) ?>"
                    <?= ($data['type'] ?? '') === $type ? 'selected' : '' ?>
                >
                    <?= htmlspecialchars($type) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <?php if (isset($errors['type'])): ?>
            <p><?= htmlspecialchars($errors['type']) ?></p>
        <?php endif; ?>
    </div>

    <div>
        <label>
            <input
                type="checkbox"
                name="active"
                <?= !empty($data['active']) ? 'checked' : '' ?>
            >
            Salle active
        </label>

        <?php if (isset($errors['active'])): ?>
            <p><?= htmlspecialchars($errors['active']) ?></p>
        <?php endif; ?>
    </div>

    <button type="submit">Enregistrer</button>

</form>

<?php
$content = ob_get_clean();
require dirname(__DIR__) . '/layout/base.php';
