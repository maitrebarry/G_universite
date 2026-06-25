<?php
$semestres = $infosLicence['semestres'];
$sum = 0; $n = 0; $nbValide = 0;
foreach ($moyennesLicence as $row) {
    $m = (float) $row['moyenne'];
    $sum += $m; $n++;
    if ($m >= 10) $nbValide++;
}
$moyGen = $n ? $sum / $n : 0;
$taux = $n ? ($nbValide * 100 / $n) : 0;
?>
<style>
    .gu-bulletin-head { display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 14px; }
    .gu-bulletin-head .gu-stat { flex: 1; min-width: 140px; border: 1px solid #c7d2e6; border-radius: 8px; padding: 8px 12px; background: #f4f7fc; text-align: center; }
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
    <h5 style="color:#14346b;font-weight:700;margin:0;">Résultats annuels — Récapitulatif des semestres</h5>
</div>

<div class="gu-bulletin-head">
    <div class="gu-stat"><div class="lab">Effectif</div><div class="val"><?= (int) $n ?></div></div>
    <div class="gu-stat"><div class="lab">Admis</div><div class="val ok"><?= (int) $nbValide ?></div></div>
    <div class="gu-stat"><div class="lab">Ajournés</div><div class="val ko"><?= (int) ($n - $nbValide) ?></div></div>
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
                <?php foreach ($semestres as $s): ?>
                    <th><?= mb_strtoupper(htmlspecialchars($s->sigle_semestre), 'UTF-8') ?></th>
                <?php endforeach ?>
                <th>Moy. Annuelle</th>
                <th>Observation</th>
            </tr>
        </thead>
        <tbody>
            <?php for ($i = 0; $i < count($moyennesLicence); $i++):
                $etudiant = $moyennesLicence[$i]['etudiant'];
                $note = (float) $moyennesLicence[$i]['moyenne'];
                $ok = $note >= 10;
                $sems = $moyennesSemestre[$i]['semestres'];
            ?>
                <tr>
                    <td class="num"><?= $i + 1 ?></td>
                    <td><?= htmlspecialchars($etudiant->matricule_etudiant) ?></td>
                    <td class="nom"><?= mb_strtoupper(htmlspecialchars($etudiant->nom_prenom_etudiant), 'UTF-8') ?><?= !empty($etudiant->prenom) ? ' ' . mb_convert_case(htmlspecialchars($etudiant->prenom), MB_CASE_TITLE, 'UTF-8') : '' ?></td>
                    <td class="num"><?= ($etudiant->genre_etudiant == "Féminin") ? 'F' : 'M' ?></td>
                    <?php foreach ($sems as $s): $sm = (float) $s['moyenne']; ?>
                        <td class="num <?= ($sm > 0 && $sm < 10) ? 'ko' : '' ?>"><?= ($sm == 0) ? 'X' : number_format($sm, 2) ?></td>
                    <?php endforeach ?>
                    <td class="moy-cell"><?= number_format($note, 2) ?></td>
                    <td class="obs <?= $ok ? 'ok' : 'ko' ?>"><?= $ok ? 'Admis' : 'Ajourné' ?></td>
                </tr>
            <?php endfor ?>
        </tbody>
    </table>
</div>
