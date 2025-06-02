
<h2>Aperçu Emploi du Temps Individuel</h2>
<?php if ($enseignant): ?>
    <p><strong>Nom :</strong> <?= htmlspecialchars($enseignant->enseignant_prenom . ' ' . $enseignant->enseignant_nom) ?></p>
    <p><strong>Période :</strong> <?= htmlspecialchars($date_debut) ?> au <?= htmlspecialchars($date_fin) ?></p>
    <p><strong>Statut :</strong> <?= htmlspecialchars($enseignant->enseignant_statut) ?></p>
    <p><strong>Heures totales :</strong> <?= $heures_totales ?></p>
    <p><strong>Heures dues :</strong> <?= $heures_dues ?></p>
    <p><strong>Heures supp :</strong> <?= $heures_supp ?></p>
    <p><strong>Semestres/Promotions :</strong> <?= implode(', ', $semestres_promotions) ?></p>
    <h3>Détail :</h3>
    <table border="1" cellpadding="5">
        <tr>
            <th>Date</th>
            <th>Heure</th>
            <th>Matière</th>
            <th>Semestre</th>
            <th>Promotion</th>
        </tr>
        <?php foreach ($emplois_du_temps as $edt): ?>
                      
            <tr>
                <td><?= htmlspecialchars($edt->date_debut) ?> - <?= htmlspecialchars($edt->date_fin) ?></td>
                <td><?= htmlspecialchars($edt->heure_total) ?></td>
                <td><?= htmlspecialchars($edt->nom_module) ?></td>
                <td><?= htmlspecialchars($edt->nom_semestre) ?></td>
                <td><?= htmlspecialchars($edt->annee_universitaire) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
    <?php if (empty($mode_groupe)): // Affiche le bouton PDF seulement en mode individuel ?>
    <form method="post" action="/Enseignants/telechargerPDF">
        <input type="hidden" name="enseignant_id" value="<?= $enseignant->enseignant_id ?>">
        <input type="hidden" name="date_debut" value="<?= htmlspecialchars($date_debut) ?>">
        <input type="hidden" name="date_fin" value="<?= htmlspecialchars($date_fin) ?>">
        <input type="hidden" name="status" value="<?= htmlspecialchars($status) ?>">
        <button type="submit">Télécharger le PDF</button>
    </form>
    <?php endif; ?>
<?php else: ?>
    <p>Aucun emploi du temps trouvé pour cette période.</p>
<?php endif; ?>