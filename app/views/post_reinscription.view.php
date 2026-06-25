<div class="d-flex align-items-center justify-content-between flex-wrap mb-2" style="gap:10px;">
    <div class="gu-field gu-search" style="position:relative;min-width:240px;">
        <i class="bx bx-search" style="position:absolute;left:.7rem;top:50%;transform:translateY(-50%);color:var(--text-tertiary);"></i>
        <input type="text" class="form-control" id="reinscSearch" style="padding-left:2rem;" placeholder="Rechercher un étudiant…">
    </div>
    <div class="d-flex align-items-center" style="gap:8px;">
        <span class="text-muted" style="font-size:.78rem;"><i class="bx bx-info-circle"></i> Seuls les étudiants <b>validés</b> peuvent être réinscrits</span>
        <span class="badge badge-soft-primary" id="selCount" style="font-size:.8rem;">0 sélectionné(s)</span>
    </div>
</div>

<div class="gu-table-wrap" style="max-height:calc(100vh - 430px);">
    <table class="gu-table" id="reinscriptionTable" style="width:100%;">
        <thead>
            <tr>
                <th style="width:42px;text-align:center;"><input type="checkbox" id="select-all" title="Tout sélectionner (validés)"></th>
                <th style="width:42px;text-align:center;">N°</th>
                <th>Nom &amp; Prénom</th>
                <th>Matricule</th>
                <th>Filière</th>
                <th class="text-center">Moyenne</th>
                <th class="text-center">Validation</th>
                <th class="text-center" style="width:120px;">État cible</th>
            </tr>
        </thead>
        <tbody>
            <?php for ($i = 0; $i < count($moyennesSemestre); $i++):
                $e = $moyennesSemestre[$i]['etudiant'];
                $valide = !empty($moyennesSemestre[$i]['isValidate']);
                $moy = (float) ($moyennesSemestre[$i]['moyenneReelle'] ?? 0);
                $nom = trim(($e->nom_prenom_etudiant ?? '') . ' ' . ($e->prenom ?? ''));
                $clsMoy = $moy < 10 ? 'danger' : ($moy < 14 ? 'warning' : 'success');
            ?>
                <tr data-id="<?= $e->id_etudiant ?>" data-nom="<?= htmlspecialchars(mb_strtolower($nom, 'UTF-8')) ?>" data-valide="<?= $valide ? 1 : 0 ?>" <?= $valide ? '' : 'style="opacity:.62;"' ?>>
                    <td class="text-center">
                        <input type="checkbox" class="chk-etudiant" value="<?= $e->id_etudiant ?>" <?= $valide ? '' : 'disabled title="Niveau non validé — réinscription impossible"' ?>>
                    </td>
                    <td class="text-center"><?= $i + 1 ?></td>
                    <td style="font-weight:var(--fw-medium);"><?= mb_strtoupper(htmlspecialchars($e->nom_prenom_etudiant ?? ''), 'UTF-8') ?><?= !empty($e->prenom) ? ' ' . mb_convert_case(htmlspecialchars($e->prenom), MB_CASE_TITLE, 'UTF-8') : '' ?></td>
                    <td><?= htmlspecialchars($e->matricule_etudiant ?? '—') ?></td>
                    <td><?= htmlspecialchars($e->nom_filiere ?? '') ?></td>
                    <td class="text-center"><span class="badge badge-soft-<?= $clsMoy ?>"><?= number_format($moy, 2) ?></span></td>
                    <td class="text-center"><?= $valide ? '<span class="badge badge-soft-success">Validé</span>' : '<span class="badge badge-soft-danger">Non validé</span>' ?></td>
                    <td class="text-center etat-cell">—</td>
                </tr>
            <?php endfor ?>
        </tbody>
    </table>
</div>
