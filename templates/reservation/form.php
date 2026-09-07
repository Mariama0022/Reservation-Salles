<?php

declare(strict_types=1);

$title = 'Créer une réservation';

ob_start();
?>

<h2>Créer une réservation</h2>

<form method="POST">

    <div>
        <label for="salle_id">Salle</label>

        <select id="salle_id" name="salle_id">

            <?php foreach ($salles as $salle): ?>

                <option
                    value="<?= (int) $salle->id ?>"
                    <?= (string) ($data['salle_id'] ?? '') === (string) $salle->id
                        ? 'selected'
                        : '' ?>
                >
                    <?= htmlspecialchars($salle->nom) ?>
                </option>

            <?php endforeach; ?>

        </select>

        <?php if (isset($errors['salle_id'])): ?>
            <p><?= htmlspecialchars($errors['salle_id']) ?></p>
        <?php endif; ?>
    </div>

    <div>
        <label for="responsable">Responsable</label>

        <input
            type="text"
            id="responsable"
            name="responsable"
            value="<?= htmlspecialchars($data['responsable'] ?? '') ?>"
        >

        <?php if (isset($errors['responsable'])): ?>
            <p><?= htmlspecialchars($errors['responsable']) ?></p>
        <?php endif; ?>
    </div>

    <div>
        <label for="email">Email</label>

        <input
            type="email"
            id="email"
            name="email"
            value="<?= htmlspecialchars($data['email'] ?? '') ?>"
        >

        <?php if (isset($errors['email'])): ?>
            <p><?= htmlspecialchars($errors['email']) ?></p>
        <?php endif; ?>
    </div>

    <div>
        <label for="motif">Motif</label>

        <textarea id="motif" name="motif"><?= htmlspecialchars(
            $data['motif'] ?? ''
        ) ?></textarea>

        <?php if (isset($errors['motif'])): ?>
            <p><?= htmlspecialchars($errors['motif']) ?></p>
        <?php endif; ?>
    </div>

    <div>
        <label for="date_debut">Date de début</label>

        <input
            type="datetime-local"
            id="date_debut"
            name="date_debut"
            value="<?= htmlspecialchars($data['date_debut'] ?? '') ?>"
        >

        <?php if (isset($errors['date_debut'])): ?>
            <p><?= htmlspecialchars($errors['date_debut']) ?></p>
        <?php endif; ?>
    </div>

    <div>
        <label for="date_fin">Date de fin</label>

        <input
            type="datetime-local"
            id="date_fin"
            name="date_fin"
            value="<?= htmlspecialchars($data['date_fin'] ?? '') ?>"
        >

        <?php if (isset($errors['date_fin'])): ?>
            <p><?= htmlspecialchars($errors['date_fin']) ?></p>
        <?php endif; ?>
    </div>

    <button type="submit">
        Enregistrer
    </button>

</form>

<?php
$content = ob_get_clean();
require dirname(__DIR__) . '/layout/base.php';
