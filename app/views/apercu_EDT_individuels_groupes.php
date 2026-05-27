
<h2>Aperçu des EDT individuels sélectionnés</h2>exit;
<form method="post" action="<?= ROOT ?>/Enseignants/telechargerPDFGroupes" target="_blank">
    <?php foreach ($apercus as $apercu): ?>
        <input type="hidden" name="enseignants[]" value="<?= $apercu['enseignant']->enseignant_id ?>">
        <input type="hidden" name="date_debut" value="<?= htmlspecialchars($apercu['date_debut']) ?>">
        <input type="hidden" name="date_fin" value="<?= htmlspecialchars($apercu['date_fin']) ?>">
        <input type="hidden" name="status" value="<?= htmlspecialchars($apercu['status']) ?>">
    <?php endforeach; ?>
    <button type="submit" class="btn btn-primary mb-3">Télécharger tous les PDF (ZIP)</button>
</form>
<?php foreach ($apercus as $apercu): ?>
    <div style="border:1px solid #ccc; margin-bottom:30px; padding:15px;">
        <?php
        $mode_groupe = true;
        extract($apercu);
        include(__DIR__ . '/apercu_EDT_individuel.php');
        ?>
    </div>
<?php endforeach; ?>