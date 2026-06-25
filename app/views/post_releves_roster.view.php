<?php $projetNames = ['GESTIONDEPROJET', 'PROJET', 'PROJETTUTORE']; ?>
<div class="d-flex align-items-center justify-content-between flex-wrap mb-2" style="gap:10px;">
    <div class="gu-field gu-search" style="position:relative;min-width:240px;">
        <i class="bx bx-search gu-ico" style="position:absolute;left:.7rem;top:50%;transform:translateY(-50%);color:var(--text-tertiary);"></i>
        <input type="text" class="form-control" id="rosterSearch" style="padding-left:2rem;" placeholder="Rechercher un étudiant…">
    </div>
    <button type="button" class="btn btn-primary" id="printAllReleves"><i class="bx bx-printer"></i> Imprimer tous les relevés</button>
</div>

<div class="gu-table-wrap" style="max-height:calc(100vh - 360px);">
    <table class="gu-table gu-card-mobile" id="rosterTable" style="width:100%;">
        <thead>
            <tr>
                <th style="width:46px;text-align:center;">N°</th>
                <th>Nom &amp; prénom</th>
                <th class="text-center">Moyenne</th>
                <th class="text-center">Crédits</th>
                <th class="text-center">Décision</th>
                <th class="text-center" style="width:120px;">Relevé</th>
            </tr>
        </thead>
        <tbody>
            <?php for ($i = 0; $i < count($moyennesSemestre); $i++):
                $et = $moyennesSemestre[$i]['etudiant'];
                $admis = !empty($moyennesSemestre[$i]['isValidate']);
                $ues = $moyennesUe[$i]['ues'];
                $totalCoef = 0; $sumPond = 0; $cred = 0;
                foreach ($ues as $idx => $u) {
                    $cu = 0;
                    foreach (($infosSemestre[$idx] ?? []) as $mi) { $cu += (float) ($mi->coeficient ?? 0); }
                    $totalCoef += $cu; $sumPond += (float) $u['moyenne'] * $cu;
                    if (!empty($u['isValidate'])) $cred += $cu;
                }
                $moy = $totalCoef ? $sumPond / $totalCoef : 0;
                $nom = trim(($et->nom_prenom_etudiant ?? '') . ' ' . ($et->prenom ?? ''));
                $cls = $moy < 10 ? 'danger' : ($moy < 14 ? 'warning' : 'success');
            ?>
                <tr data-nom="<?= htmlspecialchars(mb_strtolower($nom, 'UTF-8')) ?>">
                    <td data-label="N°" class="text-center"><?= $i + 1 ?></td>
                    <td data-label="Nom" style="font-weight:var(--fw-medium);"><?= mb_convert_case(htmlspecialchars($nom), MB_CASE_TITLE, 'UTF-8') ?></td>
                    <td data-label="Moyenne" class="text-center"><span class="badge badge-soft-<?= $cls ?>"><?= number_format($moy, 2) ?></span></td>
                    <td data-label="Crédits" class="text-center"><?= (int) $cred ?>/<?= (int) $totalCoef ?></td>
                    <td data-label="Décision" class="text-center"><?= $admis ? '<span class="badge badge-soft-success">Admis</span>' : '<span class="badge badge-soft-danger">Ajourné</span>' ?></td>
                    <td data-label="Relevé" class="text-center" style="white-space:nowrap;">
                        <button type="button" class="gu-icon-act btn-apercu-releve" data-id="<?= $et->id_etudiant ?>" title="Aperçu du relevé"><i class="bx bx-show"></i></button>
                        <button type="button" class="gu-icon-act btn-pdf-releve" data-id="<?= $et->id_etudiant ?>" data-nom="<?= htmlspecialchars($nom) ?>" title="Télécharger le PDF"><i class="bx bx-download"></i></button>
                    </td>
                </tr>
            <?php endfor ?>
        </tbody>
    </table>
</div>
