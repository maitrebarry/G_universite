<?php
function tronquerTexte($texte, $limite = 20)
{
    return (mb_strlen($texte) > $limite) ? mb_substr($texte, 0, $limite) . "…" : $texte;
}
$projetNames = ['GESTIONDEPROJET', 'PROJET', 'PROJETTUTORE'];
?>
<style>
    #notesTable { border-collapse: collapse; font-size: 12px; }
    #notesTable th, #notesTable td { border: 1px solid #9aa3b2; }

    /* Bandeau de groupe UE (en-tête niveau 1) */
    #notesTable .ue-group { color: #fff; text-align: center; font-weight: 700; font-size: 12px;
        padding: 5px 4px; letter-spacing: .2px; border: 1px solid #14346b; }
    #notesTable .ue-group.g0 { background: #1f4f9c; }
    #notesTable .ue-group.g1 { background: #3a72c4; }

    /* Bandes alternées pour distinguer les UE (modules + corps) */
    #notesTable .bnd0 { background: #ffffff; }
    #notesTable .bnd1 { background: #eef3fb; }

    /* Cellule moyenne d'UE (mise en valeur) */
    #notesTable .note_ue { background: #dbe5f5; color: #0f2a52; font-weight: 700; text-align: center; }

    /* En-tête vertical (noms de modules) */
    #notesTable .vertical-header { height: 138px; text-align: center; padding: 2px 0; vertical-align: middle; }
    #notesTable .vertical-header > div { display: inline-block; transform: rotate(-90deg); transform-origin: center center;
        font-size: 11px; font-weight: 600; white-space: nowrap; width: 22px; }

    #notesTable thead th { background: #e7ecf5; color: #14346b; vertical-align: middle; }
    #notesTable thead .ue-group, #notesTable thead .note_ue { background-clip: padding-box; }
    #notesTable .etudiantInfo { font-size: 12px; max-width: 110px; }
    #notesTable tbody td { padding: 4px 5px; }
    #notesTable .moyenneSemestre { background: #fff7e6; font-weight: 700; }

    table, tr, td, th { page-break-inside: avoid !important; }
    .no-break { page-break-inside: avoid !important; page-break-before: auto; page-break-after: auto; }
</style>
<?php
$creditTotal = 0;
foreach ($infosSemestre as $ue) {
    foreach ($ue as $module) { $creditTotal += $module->coeficient; }
}
?>

<!-- ==================== EN-TÊTE ==================== -->
<div class="text-center mb-3">
    <h5 style="margin:0;color:#14346b;font-weight:700;">Institut Universitaire de Formation Professionnelle (IUFP)</h5>
    <h6 style="margin:2px 0;">Année Universitaire <?= htmlspecialchars($promotion->annee_universitaire) ?></h6>
    <h6 style="margin:2px 0;"><strong>Résultats provisoires du <?= mb_strtoupper(htmlspecialchars($semestre->nom_semestre), 'UTF-8') ?>
            (<?= mb_strtoupper(htmlspecialchars($semestre->sigle_semestre), 'UTF-8') ?>)</strong></h6>
    <h6 style="margin:2px 0;">Mention : <?= htmlspecialchars($promotion->nom_filiere) ?> — <strong><?= (int) $creditTotal ?> crédits</strong></h6>
    <small>ECUE non acquis = <strong>X</strong> · Moyenne et validation par UE · Compensation intra-UE</small>
</div>

<!-- ==================== TABLEAU ==================== -->
<div class="table-responsive">
    <table class="table table-bordered w-100" id="notesTable">
        <thead>
            <tr class="no-break">
                <th class="text-center" rowspan="2">N°</th>
                <th class="text-center etudiantInfo" rowspan="2">Prénoms</th>
                <th class="text-center etudiantInfo" rowspan="2">Nom</th>
                <th class="text-center etudiantInfo" rowspan="2">Date de Naissance</th>
                <th class="text-center etudiantInfo" rowspan="2">Lieu de Naissance</th>
                <?php $b = 0; foreach ($infosSemestre as $ue):
                    $nbm = max(1, count($ue));
                    $isProjet = in_array(mb_strtoupper(str_replace(' ', '', $ue[0]->nom_ue), 'UTF-8'), $projetNames, true);
                ?>
                    <th colspan="<?= $nbm + 1 ?>" class="ue-group g<?= $b % 2 ?>" title="<?= htmlspecialchars($ue[0]->nom_ue) ?>">
                        <?= mb_strtoupper(htmlspecialchars(tronquerTexte($ue[0]->nom_ue, 26)), 'UTF-8') ?><?= $isProjet ? ' ★' : '' ?>
                    </th>
                <?php $b++; endforeach ?>
                <th class="text-center vertical-header" rowspan="2"><div>MOY. GÉN.</div></th>
                <th class="text-center vertical-header" rowspan="2"><div>OBSERVATION</div></th>
            </tr>
            <tr class="no-break">
                <?php $b = 0; foreach ($infosSemestre as $ue): ?>
                    <?php foreach ($ue as $module): ?>
                        <th class="vertical-header bnd<?= $b % 2 ?>">
                            <div title="<?= htmlspecialchars($module->nom_module) ?>"><?= (mb_strlen($module->nom_module) < 10) ? mb_strtoupper(htmlspecialchars($module->nom_module), 'UTF-8') : mb_strtoupper(htmlspecialchars($module->sigle_module), 'UTF-8') ?></div>
                        </th>
                    <?php endforeach ?>
                    <th class="note_ue" style="font-size:10px;vertical-align:middle;">Moy.<br>UE</th>
                <?php $b++; endforeach ?>
            </tr>
        </thead>
        <tbody>
            <?php for ($i = 0; $i < count($moyennesSemestre); $i++):
                $etudiant = $moyennesSemestre[$i]['etudiant'];
                // Moyenne réelle pondérée pour l'affichage (le verdict reste piloté par isValidate / garde projet).
                $note = $moyennesSemestre[$i]['moyenneReelle'] ?? $moyennesSemestre[$i]['moyenne'];
                $admis = !empty($moyennesSemestre[$i]['isValidate']);
                $ues = $moyennesUe[$i]['ues'];
            ?>
                <tr data-valide="<?= $admis ? 1 : 0 ?>">
                    <td class="text-center"><?= $i + 1 ?></td>
                    <td style="font-size:12px;"><?= mb_convert_case(htmlspecialchars($etudiant->prenom ?? ''), MB_CASE_TITLE, 'UTF-8') ?></td>
                    <td style="font-size:12px;font-weight:600;"><?= mb_strtoupper(htmlspecialchars($etudiant->nom_prenom_etudiant), 'UTF-8') ?></td>
                    <td class="text-center" style="font-size:12px;"><?= htmlspecialchars($etudiant->date_naissance_etudiant) ?></td>
                    <td class="text-center" style="font-size:12px;"><?= htmlspecialchars($etudiant->lieu_naissance_etudiant) ?></td>

                    <?php $b = 0; foreach ($ues as $ue): $modules = $ue['modules']; ?>
                        <?php foreach ($modules as $module): ?>
                            <td class="text-center bnd<?= $b % 2 ?>">
                                <?= ($module->moyenne_module === null || $module->moyenne_module == 0) ? "X" : number_format($module->moyenne_module, 2) ?>
                            </td>
                        <?php endforeach ?>
                        <td class="text-center note_ue">
                            <?= ($ue['moyenne'] == 0) ? "X" : number_format($ue['moyenne'], 2) ?>
                        </td>
                    <?php $b++; endforeach ?>

                    <td class="text-center moyenneSemestre"><?= number_format($note, 2) ?></td>
                    <td class="text-center"><span class="etatSemestre text-bold-600 fw-bold"></span></td>
                </tr>
            <?php endfor ?>
        </tbody>
    </table>
</div>

<!-- ==================== STATISTIQUES ==================== -->
<div class="w-100 d-flex justify-content-between mt-3" style="gap:24px;">
    <div class="no-break">
        <p style="margin:2px 0;"><strong>Admis :</strong> <span id="nbAdmis"></span></p>
        <p style="margin:2px 0;"><strong>Ajourné :</strong> <span id="nbAjournes"></span></p>
        <p style="margin:2px 0;"><strong>Taux de réussite :</strong> <span id="tauxReussite"></span></p>
    </div>
    <div class="text-right mt-3 no-break" style="text-align:right;">
        <?php date_default_timezone_set("Africa/Bamako"); ?>
        <p style="margin:2px 0;">Ségou, le <?= date("d/m/Y") ?></p>
        <p style="margin:2px 0;">P/Le Directeur P.O</p>
        <p style="margin:2px 0;"><strong>Le <?= isset($_SESSION['role']) ? mb_strtoupper(htmlspecialchars($_SESSION['role']), 'UTF-8') : "" ?></strong></p>
        <p style="margin:2px 0;">Dr <?= isset($_SESSION['nom_prenom']) ? mb_strtoupper(htmlspecialchars($_SESSION['nom_prenom']), 'UTF-8') : "" ?>
            <?php if (!empty($_SESSION['nom_grade'])): ?><br><small><?= mb_strtoupper(htmlspecialchars($_SESSION['nom_grade']), 'UTF-8') ?></small><?php endif ?>
        </p>
    </div>
</div>

<script>
    $(document).ready(function () {
        var nbrEtudiant = 0, nbrValide = 0;
        $("#notesTable tbody tr").each(function () {
            nbrEtudiant++;
            var row = $(this);
            // Verdict autoritaire = isValidate (inclut la garde UE projet) ; repli sur la moyenne.
            var valide = row.attr("data-valide");
            var admis = (typeof valide !== "undefined") ? (valide === "1") : (parseFloat(row.find(".moyenneSemestre").text()) >= 10);
            if (admis) { row.find('.etatSemestre').text("Admis").css({ color: "#15803d" }); nbrValide++; }
            else { row.find('.etatSemestre').text("Ajourné").css({ color: "#b91c1c" }); }
        });
        var nbAj = nbrEtudiant - nbrValide;
        var taux = nbrEtudiant ? ((nbrValide * 100) / nbrEtudiant).toFixed(2) : "0.00";
        $("#nbAdmis").text(nbrValide);
        $("#nbAjournes").text(nbAj);
        $("#tauxReussite").text(taux + "%");
    });
</script>
