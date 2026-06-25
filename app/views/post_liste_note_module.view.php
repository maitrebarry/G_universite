<?php
$sum = 0; $n = 0; $nbValide = 0;
foreach ($note_des_etudiants as $note) {
    $m = (float) $note->moyenne_module;
    $sum += $m; $n++;
    if ($m >= 10) $nbValide++;
}
$moyGen = $n ? $sum / $n : 0;
$taux = $n ? ($nbValide * 100 / $n) : 0;
if (!function_exists('rn_note_brute')) {
    function rn_note_brute($v) { return ($v === null || $v === '') ? '—' : rtrim(rtrim(number_format((float) $v, 2, '.', ''), '0'), '.'); }
}
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
    <h5 style="color:#14346b;font-weight:700;margin:0;">Résultats par module</h5>
    <?php if (!empty($infosModule->nom_ue)): ?><div style="font-size:12.5px;color:#555;">UE : <?= mb_strtoupper(htmlspecialchars($infosModule->nom_ue), 'UTF-8') ?></div><?php endif ?>
</div>

<div class="gu-bulletin-head">
    <div class="gu-stat"><div class="lab">Module</div><div class="val" style="font-size:13px;"><?= mb_strtoupper(htmlspecialchars($infosModule->nom_module), 'UTF-8') ?></div></div>
    <div class="gu-stat"><div class="lab">Crédit</div><div class="val"><?= (int) $infosModule->coeficient ?></div></div>
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
                <th>Devoir</th>
                <th>Examen</th>
                <th>Session</th>
                <th>Moy. Module</th>
                <th>Observation</th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1; foreach ($note_des_etudiants as $note): $m = (float) $note->moyenne_module; $ok = $m >= 10; ?>
                <tr>
                    <td class="num"><?= $i++ ?></td>
                    <td><?= htmlspecialchars($note->matricule_etudiant) ?></td>
                    <td class="nom"><?= mb_strtoupper(htmlspecialchars($note->nom_prenom_etudiant), 'UTF-8') ?><?= !empty($note->prenom) ? ' ' . mb_convert_case(htmlspecialchars($note->prenom), MB_CASE_TITLE, 'UTF-8') : '' ?></td>
                    <td class="num"><?= ($note->genre_etudiant == "Féminin") ? 'F' : 'M' ?></td>
                    <td class="num"><?= rn_note_brute($note->note_devoir) ?></td>
                    <td class="num"><?= rn_note_brute($note->note_evaluation) ?></td>
                    <td class="num"><?= rn_note_brute($note->note_session) ?></td>
                    <td class="moy-cell"><?= number_format($m, 2) ?></td>
                    <td class="obs <?= $ok ? 'ok' : 'ko' ?>"><?= $ok ? 'Validé' : 'Non validé' ?></td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>
