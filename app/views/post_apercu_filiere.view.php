<?php
// Fragment maquette pédagogique — AUTO-SUFFISANT (écran + impression html2pdf). Couleurs concrètes.
$filiere = $infoFiliere['filiere'];
$semestres = $infoFiliere['semestres'];
$ues = $infoFiliere['ues'];
$modules = $infoFiliere['modules'];

$modByUe = [];
foreach ($modules as $m) { $modByUe[$m->id_ue][] = $m; }
$ueByParcours = [];
foreach ($ues as $u) { $ueByParcours[$u->id_parcours][] = $u; }
$hh = function ($v) { return (int) $v; };
$totalCreditFiliere = 0;
foreach ($ues as $u) { $totalCreditFiliere += (int) $u->ue_credit; }
?>
<div id="semestresTable" style="font-family: Arial, Helvetica, sans-serif; color:#111; background:#fff;">
    <!-- En-tête officiel -->
    <div style="text-align:center; margin-bottom:16px;">
        <img src="<?= ROOT ?>/assets/images/logo.jpg" alt="" style="height:62px;"><br>
        <div style="font-size:19px;font-weight:bold;color:#14346b;letter-spacing:2px;margin-top:4px;">MAQUETTE PÉDAGOGIQUE</div>
        <div style="font-size:14px;color:#1f4f9c;font-weight:bold;"><?= strtoupper(htmlspecialchars($filiere->nom_filiere)) ?> · <?= strtoupper(htmlspecialchars($filiere->sigle_filiere)) ?></div>
        <div style="font-size:11px;color:#555;margin-top:2px;">Institut Universitaire de Formation Professionnelle (IUFP) — Université de Ségou · <?= count($semestres) ?> semestres · <?= (int) $totalCreditFiliere ?> crédits</div>
    </div>

    <?php foreach ($semestres as $semestre):
        $sUes = $ueByParcours[$semestre->id_parcours] ?? [];
        $nbUe = count($sUes); $nbMod = 0; $tCred = 0; $tVht = 0;
    ?>
        <div class="semestre" id="s_<?= $semestre->id_parcours ?>" style="margin-bottom:18px; page-break-inside:auto;">
            <div style="background:#14346b;color:#fff;font-weight:bold;padding:7px 12px;border-radius:6px 6px 0 0;font-size:13px;letter-spacing:.5px;">
                <?= mb_strtoupper(htmlspecialchars($semestre->nom_semestre), 'UTF-8') ?> (<?= htmlspecialchars($semestre->sigle_semestre) ?>)
            </div>
            <table style="width:100%;border-collapse:collapse;font-size:11px;">
                <thead>
                    <tr style="background:#e7ecf5;color:#14346b;">
                        <th style="border:1px solid #9aa3b2;padding:5px;text-align:left;width:20%;">Unité d'Enseignement</th>
                        <th style="border:1px solid #9aa3b2;padding:5px;text-align:left;">ECUE (Module)</th>
                        <th style="border:1px solid #9aa3b2;padding:5px;width:9%;">Code</th>
                        <th style="border:1px solid #9aa3b2;padding:5px;width:7%;">Crédit</th>
                        <th style="border:1px solid #9aa3b2;padding:5px;width:6%;">CM</th>
                        <th style="border:1px solid #9aa3b2;padding:5px;width:6%;">TD</th>
                        <th style="border:1px solid #9aa3b2;padding:5px;width:6%;">TP</th>
                        <th style="border:1px solid #9aa3b2;padding:5px;width:6%;">TPE</th>
                        <th style="border:1px solid #9aa3b2;padding:5px;width:7%;">VHT</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($sUes as $ue):
                        $uMods = $modByUe[$ue->id_ue] ?? [];
                        $rs = max(1, count($uMods));
                        $nbMod += count($uMods); $tCred += (int) $ue->ue_credit;
                    ?>
                        <?php if (empty($uMods)): ?>
                            <tr>
                                <td style="border:1px solid #9aa3b2;padding:5px;background:#f1f4fa;font-weight:bold;"><?= mb_strtoupper(htmlspecialchars($ue->nom_ue), 'UTF-8') ?><br><small style="color:#1f4f9c;font-weight:normal;"><?= (int) $ue->ue_credit ?> crédits</small></td>
                                <td colspan="8" style="border:1px solid #9aa3b2;padding:5px;color:#999;text-align:center;">Aucun module</td>
                            </tr>
                        <?php else: foreach ($uMods as $j => $m):
                            $vht = $hh($m->cm) + $hh($m->td) + $hh($m->tp) + $hh($m->tpe); $tVht += $vht;
                        ?>
                            <tr>
                                <?php if ($j === 0): ?>
                                    <td rowspan="<?= $rs ?>" style="border:1px solid #9aa3b2;padding:5px;background:#f1f4fa;font-weight:bold;vertical-align:middle;"><?= mb_strtoupper(htmlspecialchars($ue->nom_ue), 'UTF-8') ?><br><small style="color:#1f4f9c;font-weight:normal;"><?= (int) $ue->ue_credit ?> crédits</small></td>
                                <?php endif ?>
                                <td style="border:1px solid #9aa3b2;padding:5px;text-align:left;"><?= mb_convert_case(htmlspecialchars($m->nom_module), MB_CASE_TITLE, 'UTF-8') ?></td>
                                <td style="border:1px solid #9aa3b2;padding:5px;text-align:center;"><?= htmlspecialchars($m->code_module) ?></td>
                                <td style="border:1px solid #9aa3b2;padding:5px;text-align:center;font-weight:bold;color:#0f2a52;"><?= (int) $m->coeficient ?></td>
                                <td style="border:1px solid #9aa3b2;padding:5px;text-align:center;"><?= $hh($m->cm) ?></td>
                                <td style="border:1px solid #9aa3b2;padding:5px;text-align:center;"><?= $hh($m->td) ?></td>
                                <td style="border:1px solid #9aa3b2;padding:5px;text-align:center;"><?= $hh($m->tp) ?></td>
                                <td style="border:1px solid #9aa3b2;padding:5px;text-align:center;"><?= $hh($m->tpe) ?></td>
                                <td style="border:1px solid #9aa3b2;padding:5px;text-align:center;font-weight:bold;"><?= $vht ?></td>
                            </tr>
                        <?php endforeach; endif ?>
                    <?php endforeach ?>
                </tbody>
                <tfoot>
                    <tr style="background:#dbe5f5;color:#0f2a52;font-weight:bold;">
                        <td style="border:1px solid #9aa3b2;padding:5px;"><?= $nbUe ?> UE</td>
                        <td style="border:1px solid #9aa3b2;padding:5px;"><?= $nbMod ?> module(s)</td>
                        <td colspan="2" style="border:1px solid #9aa3b2;padding:5px;text-align:center;"><?= $tCred ?> crédits</td>
                        <td colspan="5" style="border:1px solid #9aa3b2;padding:5px;text-align:center;">Volume horaire total : <?= $tVht ?> h</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    <?php endforeach ?>
</div>
