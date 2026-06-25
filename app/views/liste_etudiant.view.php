<?php foreach ($liste_etudiant as $etudiant):
    $nom = trim(($etudiant->nom_prenom_etudiant ?? '') . ' ' . ($etudiant->prenom ?? ''));
    $ini = '';
    foreach (preg_split('/\s+/', $nom) as $w) { if ($w !== '') $ini .= mb_strtoupper(mb_substr($w, 0, 1)); }
    $ini = mb_substr($ini, 0, 2) ?: 'E';
    $actif = ((int) ($etudiant->id_statut ?? 0) === 1);
?>
    <tr>
        <td data-label="Sélection" class="text-center">
            <input class="form-check-input" type="checkbox" name="paie[]" value="<?= $etudiant->id_etudiant ?>">
        </td>
        <td data-label="Nom">
            <div class="d-flex align-items-center" style="gap:10px;">
                <span class="gu-avatar"><?= htmlspecialchars($ini) ?></span>
                <span style="font-weight:500;"><?= htmlspecialchars($nom) ?></span>
            </div>
        </td>
        <td data-label="Matricule"><?= htmlspecialchars($etudiant->matricule_etudiant ?? '') ?></td>
        <td data-label="Statut">
            <span class="badge <?= $actif ? 'badge-soft-success' : 'badge-soft-info' ?>">
                <?= $actif ? 'Actif' : htmlspecialchars($etudiant->id_statut ?? '—') ?>
            </span>
        </td>
        <td data-label="Filière"><?= htmlspecialchars($etudiant->nom_filiere ?? '') ?></td>
        <td data-label="Diplôme"><?= htmlspecialchars($etudiant->diplome ?? '') ?></td>
        <td data-label="Actions" style="text-align:right;">
            <div class="gu-row-actions" style="justify-content:flex-end;">
                <a class="btn btn-ghost btn-icon btn-sm" title="Aperçu" href="<?= ROOT ?>/Etudiants/apercu_etudiant/<?= $etudiant->id_etudiant ?>"><i class="bx bx-show"></i></a>
                <a class="btn btn-ghost btn-icon btn-sm" title="Modifier" href="<?= ROOT ?>/Etudiants/modifier/<?= $etudiant->id_etudiant ?>"><i class="bx bx-edit"></i></a>
                <a class="btn btn-ghost btn-icon btn-sm" title="Paiement" href="<?= ROOT ?>/Etudiants/paiement_etudiant/<?= $etudiant->id_etudiant ?>"><i class="bx bx-wallet"></i></a>
            </div>
        </td>
    </tr>
<?php endforeach ?>
