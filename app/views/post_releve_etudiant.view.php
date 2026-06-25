<?php
// Relevé de notes — fragment AUTO-SUFFISANT (capturé par html2pdf : styles en ligne + couleurs concrètes).
$projetNames = ['GESTIONDEPROJET', 'PROJET', 'PROJETTUTORE'];
function rn_fmt($v) { return ($v === null || $v === '' || (float) $v == 0) ? 'X' : number_format((float) $v, 2); }
?>
<style>
    .releve { font-family: "Times New Roman", Georgia, serif; color: #111; width: 100%; box-sizing: border-box; padding: 4mm 7mm; }
    .releve * { box-sizing: border-box; }
    .releve .rv-official { display: flex; justify-content: space-between; align-items: flex-start; font-size: 10.5px; line-height: 1.35; }
    .releve .rv-official .c { width: 46%; }
    .releve .rv-official .r { width: 46%; text-align: right; }
    .releve .rv-official b { color: #14346b; }
    .releve .rv-official .devise { font-style: italic; }
    .releve .rv-title { text-align: center; margin: 8px 0 4px; }
    .releve .rv-title .t { font-size: 19px; font-weight: bold; letter-spacing: 3px; color: #14346b; text-transform: uppercase; }
    .releve .rv-title .s { font-size: 12px; color: #444; margin-top: 2px; }
    .releve .rv-rule { height: 2px; background: #14346b; margin: 6px 0 10px; }

    .releve .rv-infos { display: flex; justify-content: space-between; font-size: 12px; margin-bottom: 10px; }
    .releve .rv-infos p { margin: 1px 0; }

    .releve .rv-band { display: flex; gap: 8px; margin-bottom: 12px; }
    .releve .rv-band .box { flex: 1; border: 1px solid #c7d2e6; border-radius: 6px; padding: 6px 8px; text-align: center; background: #f4f7fc; }
    .releve .rv-band .box .lab { font-size: 9.5px; color: #5a6b86; text-transform: uppercase; letter-spacing: .4px; }
    .releve .rv-band .box .val { font-size: 17px; font-weight: bold; color: #14346b; line-height: 1.1; }
    .releve .rv-band .box.dec-ok { background: #e9f7ef; border-color: #9ed9b5; }
    .releve .rv-band .box.dec-ok .val { color: #15803d; }
    .releve .rv-band .box.dec-ko { background: #fdecec; border-color: #f0b4b4; }
    .releve .rv-band .box.dec-ko .val { color: #b91c1c; }

    .releve table.rv-tab { width: 100%; border-collapse: collapse; font-size: 11px; }
    .releve table.rv-tab th, .releve table.rv-tab td { border: 1px solid #8a93a3; padding: 3px 5px; text-align: center; vertical-align: middle; }
    .releve table.rv-tab thead th { background: #e7ecf5; color: #14346b; font-weight: bold; }
    .releve table.rv-tab td.ue { background: #f1f4fa; font-weight: bold; text-align: left; max-width: 150px; }
    .releve table.rv-tab td.ecue { text-align: left; }
    .releve .ok { color: #15803d; font-weight: bold; }
    .releve .ko { color: #b91c1c; font-weight: bold; }
    .releve .bdg-proj { background: #14346b; color: #fff; border-radius: 8px; padding: 0 5px; font-size: 9px; }

    .releve .rv-foot { display: flex; justify-content: space-between; margin-top: 12px; font-size: 12px; }
    .releve .rv-decision { font-size: 14px; font-weight: bold; }
    .releve .rv-decision.admis { color: #15803d; }
    .releve .rv-decision.ajourne { color: #b91c1c; }
    .releve .rv-legend { font-size: 9.5px; color: #6b7686; margin-top: 6px; }
    .releve .rv-sign { text-align: right; }
</style>

<?php for ($i = 0; $i < count($moyennesSemestre); $i++):
    $etudiant = $moyennesSemestre[$i]['etudiant'];
    $admis = !empty($moyennesSemestre[$i]['isValidate']);
    $rang = $moyennesSemestre[$i]['rang'] ?? null;
    $effectif = $moyennesSemestre[$i]['effectif'] ?? count($moyennesSemestre);
    $ues = $moyennesUe[$i]['ues'];

    // Agrégats (moyenne pondérée par les crédits + crédits validés + garde UE-projet)
    $totalCoef = 0; $sumPond = 0; $creditsValides = 0; $projetBloque = false;
    foreach ($ues as $idx => $u) {
        $coefUe = 0;
        foreach (($infosSemestre[$idx] ?? []) as $mi) { $coefUe += (float) ($mi->coeficient ?? 0); }
        $moyUe = (float) $u['moyenne']; $vu = !empty($u['isValidate']);
        $totalCoef += $coefUe; $sumPond += $moyUe * $coefUe;
        if ($vu) $creditsValides += $coefUe;
        if (in_array(strtoupper(str_replace(' ', '', $u['nom_ue'])), $projetNames, true) && !$vu) $projetBloque = true;
    }
    $moySem = $totalCoef ? $sumPond / $totalCoef : 0;
    $mention = $moySem >= 16 ? 'Très Bien' : ($moySem >= 14 ? 'Bien' : ($moySem >= 12 ? 'Assez Bien' : ($moySem >= 10 ? 'Passable' : '—')));
?>
    <div class="releve">
        <!-- En-tête officiel -->
        <div class="rv-official">
            <div class="c">
                <b>RÉPUBLIQUE DU MALI</b><br>
                <span class="devise">Un Peuple – Un But – Une Foi</span><br>
                Ministère de l'Enseignement Supérieur<br>et de la Recherche Scientifique
            </div>
            <div class="r">
                <b>UNIVERSITÉ DE SÉGOU</b><br>
                Institut Universitaire de Formation<br>Professionnelle (IUFP)
            </div>
        </div>

        <div class="rv-title">
            <div class="t">Relevé de notes</div>
            <div class="s">Année universitaire <?= htmlspecialchars($promotion->annee_universitaire) ?> — <?= mb_strtoupper(htmlspecialchars($semestre->nom_semestre), 'UTF-8') ?> (<?= mb_strtoupper(htmlspecialchars($semestre->sigle_semestre), 'UTF-8') ?>)</div>
        </div>
        <div class="rv-rule"></div>

        <!-- Informations étudiant -->
        <div class="rv-infos">
            <div>
                <p><strong>Nom :</strong> <?= mb_strtoupper(htmlspecialchars($etudiant->nom_prenom_etudiant), 'UTF-8') ?></p>
                <p><strong>Prénom(s) :</strong> <?= mb_convert_case(htmlspecialchars($etudiant->prenom ?? ''), MB_CASE_TITLE, 'UTF-8') ?></p>
                <p><strong>Filière :</strong> <?= htmlspecialchars($promotion->nom_filiere) ?></p>
            </div>
            <div style="text-align:right;">
                <p><strong>Né(e) le :</strong> <?= htmlspecialchars($etudiant->date_naissance_etudiant) ?> à <?= htmlspecialchars($etudiant->lieu_naissance_etudiant) ?></p>
                <p><strong>Matricule :</strong> <?= htmlspecialchars($etudiant->matricule_etudiant ?? '—') ?></p>
            </div>
        </div>

        <!-- Bandeau de synthèse -->
        <div class="rv-band">
            <div class="box">
                <div class="lab">Moyenne générale</div>
                <div class="val"><?= number_format($moySem, 2) ?><span style="font-size:11px;font-weight:normal;color:#5a6b86;"> /20</span></div>
            </div>
            <div class="box">
                <div class="lab">Crédits validés</div>
                <div class="val"><?= (int) $creditsValides ?><span style="font-size:11px;font-weight:normal;color:#5a6b86;"> /<?= (int) $totalCoef ?></span></div>
            </div>
            <div class="box">
                <div class="lab">Rang</div>
                <div class="val"><?= $rang ? $rang . '<span style="font-size:11px;font-weight:normal;color:#5a6b86;"> /' . (int) $effectif . '</span>' : '—' ?></div>
            </div>
            <div class="box <?= $admis ? 'dec-ok' : 'dec-ko' ?>">
                <div class="lab">Décision</div>
                <div class="val"><?= $admis ? 'ADMIS' : 'AJOURNÉ' ?></div>
            </div>
        </div>

        <!-- Tableau détaillé -->
        <table class="rv-tab">
            <thead>
                <tr>
                    <th style="width:18%;">Unité d'Enseignement</th>
                    <th>ECUE (Module)</th>
                    <th style="width:7%;">Crédit</th>
                    <th style="width:9%;">Moyenne</th>
                    <th style="width:13%;">Décision ECUE</th>
                    <th style="width:9%;">Moy. UE</th>
                    <th style="width:12%;">Décision UE</th>
                </tr>
            </thead>
            <tbody>
                <?php $num = 0; foreach ($infosSemestre as $ueModules):
                    $u = $ues[$num];
                    $vu = !empty($u['isValidate']);
                    $nb = max(1, count($ueModules));
                    $isProjet = in_array(strtoupper(str_replace(' ', '', $u['nom_ue'])), $projetNames, true);
                ?>
                    <?php foreach ($ueModules as $j => $modInfo):
                        $modNote = $u['modules'][$j] ?? null;
                        $moyMod = ($modNote && $modNote->moyenne_module !== null) ? (float) $modNote->moyenne_module : 0;
                        $decEcue = $moyMod >= 10 ? 'Acquis' : ($vu ? 'Compensé' : 'À reprendre');
                    ?>
                        <tr>
                            <?php if ($j === 0): ?>
                                <td class="ue" rowspan="<?= $nb ?>"><?= mb_strtoupper(htmlspecialchars($u['nom_ue']), 'UTF-8') ?><?= $isProjet ? ' <span class="bdg-proj">PROJET</span>' : '' ?></td>
                            <?php endif ?>
                            <td class="ecue"><?= mb_convert_case(htmlspecialchars($modInfo->nom_module), MB_CASE_TITLE, 'UTF-8') ?></td>
                            <td><?= (int) ($modInfo->coeficient ?? 0) ?></td>
                            <td><?= rn_fmt($moyMod) ?></td>
                            <td class="<?= $moyMod >= 10 ? 'ok' : ($vu ? '' : 'ko') ?>"><?= $decEcue ?></td>
                            <?php if ($j === 0): ?>
                                <td rowspan="<?= $nb ?>" class="<?= $vu ? 'ok' : 'ko' ?>"><?= rn_fmt($u['moyenne']) ?></td>
                                <td rowspan="<?= $nb ?>" class="<?= $vu ? 'ok' : 'ko' ?>"><?= $vu ? 'Validée' : 'Non validée' ?></td>
                            <?php endif ?>
                        </tr>
                    <?php endforeach ?>
                <?php $num++; endforeach ?>
            </tbody>
        </table>

        <div class="rv-legend">
            Légende : <strong>Acquis</strong> = moyenne ≥ 10 · <strong>Compensé</strong> = &lt; 10 mais UE validée (compensation intra-UE) · <strong>À reprendre</strong> = &lt; 10 et UE non validée · <strong>X</strong> = non noté.
        </div>

        <!-- Pied -->
        <div class="rv-foot">
            <div>
                <p class="rv-decision <?= $admis ? 'admis' : 'ajourne' ?>">
                    Décision finale : <?= $admis ? 'ADMIS' . ($mention !== '—' ? ' — Mention ' . $mention : '') : 'AJOURNÉ' ?>
                </p>
                <?php if (!$admis && $projetBloque): ?>
                    <p style="font-size:11px;color:#b91c1c;margin-top:2px;">Motif : UE « Projet » non validée — obligatoire pour valider le semestre.</p>
                <?php endif ?>
            </div>
            <div class="rv-sign">
                <?php date_default_timezone_set("Africa/Bamako"); ?>
                <p>Ségou, le <?= date("d/m/Y") ?></p>
                <p><strong>Le <?= isset($_SESSION['role']) ? mb_strtoupper(htmlspecialchars($_SESSION['role']), 'UTF-8') : '' ?></strong></p>
                <p>Dr <?= isset($_SESSION['nom_prenom']) ? mb_strtoupper(htmlspecialchars($_SESSION['nom_prenom']), 'UTF-8') : '' ?>
                    <?php if (!empty($_SESSION['nom_grade'])): ?><br><small><?= mb_strtoupper(htmlspecialchars($_SESSION['nom_grade']), 'UTF-8') ?></small><?php endif ?>
                </p>
            </div>
        </div>
    </div>
    <div style="page-break-after: always;"></div>
<?php endfor ?>
