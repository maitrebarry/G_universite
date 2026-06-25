<?php
$creditTotal = 0;
foreach ($infosUe as $mod) { $creditTotal += (float) $mod->coeficient; }

// Pivot : [id_etudiant][id_ue_module] = moyenne_module
$lookup = [];
foreach ($moyenne_des_etudiants as $modList) {
    foreach ($modList as $r) { $lookup[$r->id_etudiant][$r->id_module] = (float) $r->moyenne_module; }
}
$roster = isset($moyenne_des_etudiants[0]) ? $moyenne_des_etudiants[0] : [];

// Moyenne UE pondérée par crédit + stats
$rows = []; $sum = 0; $n = 0; $nbValide = 0;
foreach ($roster as $stu) {
    $sp = 0; $sc = 0;
    foreach ($infosUe as $mod) {
        $mv = isset($lookup[$stu->id_etudiant][$mod->id_ue_module]) ? $lookup[$stu->id_etudiant][$mod->id_ue_module] : 0;
        $sp += $mv * (float) $mod->coeficient; $sc += (float) $mod->coeficient;
    }
    $moyUe = $sc ? $sp / $sc : 0;
    $rows[] = ['stu' => $stu, 'moyUe' => $moyUe];
    $sum += $moyUe; $n++; if ($moyUe >= 10) $nbValide++;
}
$moyGen = $n ? $sum / $n : 0;
$taux = $n ? ($nbValide * 100 / $n) : 0;
?>
<style>
    .gu-bulletin-head { display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 14px; }
    .gu-bulletin-head .gu-stat { flex: 1; min-width: 150px; border: 1px solid #c7d2e6; border-radius: 8px; padding: 8px 12px; background: #f4f7fc; text-align: center; }
    .gu-bulletin-head .gu-stat .lab { font-size: 11px; color: #5a6b86; text-transform: uppercase; letter-spacing: .4px; }
    .gu-bulletin-head .gu-stat .val { font-size: 18px; font-weight: 700; color: #14346b; line-height: 1.2; }
    .gu-bulletin-head .gu-stat .val.ok { color: #15803d; } .gu-bulletin-head .gu-stat .val.ko { color: #b91c1c; }
    #notesTable { border-collapse: collapse; width: 100%; font-size: 12.5px; }
    #notesTable th, #notesTable td { border: 1px solid #9aa3b2; padding: 6px 8px; }
    #notesTable thead th { background: #e7ecf5; color: #14346b; font-weight: 700; text-align: center; vertical-align: middle; }
    #notesTable tbody tr:nth-child(even) { background: #f6f8fc; }
    #notesTable .num { text-align: center; }
    #notesTable .nom { font-weight: 600; }
    #notesTable .moy-cell { font-weight: 700; text-align: center; background: #eef3fb; color: #0f2a52; }
    #notesTable .obs { font-weight: 700; text-align: center; }
    #notesTable .ok { color: #15803d; } #notesTable .ko { color: #b91c1c; }
</style>

<div style="text-align:center;margin-bottom:10px;">
    <h5 style="color:#14346b;font-weight:700;margin:0;">Résultats par UE</h5>
    <div style="font-size:11px;color:#777;">Moyenne d'UE pondérée par les crédits (compensation intra-UE)</div>
</div>

<div class="gu-bulletin-head">
    <div class="gu-stat"><div class="lab">Unité d'Enseignement</div><div class="val" style="font-size:13px;"><?= mb_strtoupper(htmlspecialchars($infosUe[0]->nom_ue), 'UTF-8') ?></div></div>
    <div class="gu-stat"><div class="lab">Crédit total</div><div class="val"><?= (int) $creditTotal ?></div></div>
    <div class="gu-stat"><div class="lab">Moyenne générale</div><div class="val <?= $moyGen >= 10 ? 'ok' : 'ko' ?>"><?= number_format($moyGen, 2) ?></div></div>
    <div class="gu-stat"><div class="lab">Taux de réussite</div><div class="val <?= $taux >= 50 ? 'ok' : 'ko' ?>"><?= number_format($taux, 1) ?>%</div></div>
</div>

<div class="table-responsive">
    <table id="notesTable">
        <thead>
            <tr>
                <th style="width:42px;">N°</th>
                <th>Matricule</th>
                <th>Nom &amp; Prénom</th>
                <th style="width:54px;">Genre</th>
                <?php foreach ($infosUe as $mod): ?>
                    <th title="<?= htmlspecialchars($mod->nom_module) ?>"><?= (mb_strlen($mod->nom_module) < 16) ? mb_strtoupper(htmlspecialchars($mod->nom_module), 'UTF-8') : mb_strtoupper(htmlspecialchars($mod->sigle_module), 'UTF-8') ?></th>
                <?php endforeach ?>
                <th>Moy. UE</th>
                <th>Observation</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rows as $idx => $rw): $stu = $rw['stu']; $ok = $rw['moyUe'] >= 10; ?>
                <tr>
                    <td class="num"><?= $idx + 1 ?></td>
                    <td><?= htmlspecialchars($stu->matricule_etudiant) ?></td>
                    <td class="nom"><?= mb_strtoupper(htmlspecialchars($stu->nom_prenom_etudiant), 'UTF-8') ?><?= !empty($stu->prenom) ? ' ' . mb_convert_case(htmlspecialchars($stu->prenom), MB_CASE_TITLE, 'UTF-8') : '' ?></td>
                    <td class="num"><?= ($stu->genre_etudiant == "Féminin") ? 'F' : 'M' ?></td>
                    <?php foreach ($infosUe as $mod):
                        $mv = isset($lookup[$stu->id_etudiant][$mod->id_ue_module]) ? $lookup[$stu->id_etudiant][$mod->id_ue_module] : null;
                    ?>
                        <td class="num <?= ($mv !== null && $mv < 10) ? 'ko' : '' ?>"><?= ($mv === null || $mv == 0) ? 'X' : number_format($mv, 2) ?></td>
                    <?php endforeach ?>
                    <td class="moy-cell"><?= number_format($rw['moyUe'], 2) ?></td>
                    <td class="obs <?= $ok ? 'ok' : 'ko' ?>"><?= $ok ? 'Validée' : 'Non validée' ?></td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>
