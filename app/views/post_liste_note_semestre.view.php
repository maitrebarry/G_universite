<?php
function tronquerTexte($texte, $limite = 20)
{
    return (strlen($texte) > $limite) ? substr($texte, 0, $limite) . "…" : $texte;
}

?>
<link rel="stylesheet" type="text/css" href="<?= ROOT ?>/assets/vendors/css/tables/datatable/datatables.min.css">
<style>
.note_ue {
    background-color: #dad7cd;
    /* gris clair comme dans le PDF */
    color: #000;
    /* texte noir */
    font-weight: 600;
    text-align: center;
    border: 1px solid #999;
    /* fine bordure grise */
}

.vertical-header {
    height: 150px;
    /* hauteur de la cellule */
    text-align: center;
    padding: 0;
    white-space: wrap;
    /* pas de retour à la ligne */
}

.vertical-header>div {
    display: inline-block;
    transform: rotate(-90deg);
    /* rotation du texte */
    transform-origin: center center;
    font-size: 12px;
    font-weight: 600;
    overflow: hidden;

    width: clamp(40px, 40px, 100px);

}


.etudiantInfo {
    font-size: 14px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 100px;
    min-width: 100px;
}

/* Empêcher la coupure d'une ligne du tableau */
table,
tr,
td,
th {
    page-break-inside: avoid !important;
}

/* Pour éviter que des blocs comme le pied de page ou signature soient séparés */
.no-break {
    page-break-inside: avoid !important;

    page-break-inside: avoid !important;
    page-break-before: auto;
    page-break-after: auto;
}
</style>
<?php
// Calcul du total des crédits (si nécessaire)
$creditTotal = 0;
foreach ($infosSemestre as $ue) {
    foreach ($ue as $module) {
        $creditTotal += $module->coeficient;
    }
}
?>

<!-- ==================== EN-TÊTE ==================== -->
<div class="text-center mb-3">
    <h5>Institut Universitaire de Formation Professionnelle (IUFP)</h5>
    <h6>Année Universitaire <?= $promotion->annee_universitaire ?>
    </h6>
    <h6><strong>Résultats provisoires du <?= strtoupper($semestre->nom_semestre) ?>
            (<?= strtoupper($semestre->sigle_semestre) ?>)</strong></h6>
    <h6>Mention : <?= $promotion->nom_filiere ?></h6>
    <small>ECUE à reprendre (X) et moyenne par UE</small>
</div>

<!-- ==================== TABLEAU ==================== -->
<div class="table-responsive">
    <table class="table table-bordered table-striped w-100" id="notesTable">
        <thead>
            <tr class="no-break">
                <th class="text-center">N°</th>
                <th class="text-center etudiantInfo">Prénoms</th>
                <th class="text-center etudiantInfo">Nom</th>
                <th class="text-center etudiantInfo">Date de Naissance</th>
                <th class="text-center etudiantInfo">Lieu de Naissance</th>
                <?php foreach ($infosSemestre as $ue): ?>

                <?php foreach ($ue as $module): ?>
                <th class="vertical-header">
                    <div title="<?= $module->nom_module ?>">
                        <?php echo (strlen($module->nom_module) < 10) ? strtoupper($module->nom_module) : strtoupper($module->sigle_module) ?>

                </th>
                <?php endforeach ?>

                <th class="vertical-header">
                    <div><?= tronquerTexte($ue[0]->nom_ue, 8) ?></div>
                </th>

                <?php endforeach ?>

                <th class="text-center vertical-header">
                    <div>Moy. Gén.</div>
                </th>
                <th class="text-center vertical-header">
                    <div>Observation</div>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php for ($i = 0; $i < count($moyennesSemestre); $i++): ?>
            <?php
                $etudiant = $moyennesSemestre[$i]['etudiant'];
                $note = $moyennesSemestre[$i]['moyenne'];
                $ues = $moyennesUe[$i]['ues'];
                ?>
            <tr>
                <td class="text-center"><?= $i + 1 ?></td>
                <td class="text-center" style="font-size: 14px;">
                    <?= ucfirst(strtolower($etudiant->nom_prenom_etudiant)) ?>
                </td>
                <td class="text-center" style="font-size: 14px;"><?= strtoupper($etudiant->nom_prenom_etudiant) ?></td>
                <td class="text-center" style="font-size: 14px;"><?= $etudiant->date_naissance_etudiant ?></td>
                <td class="text-center" style="font-size: 14px;"><?= $etudiant->lieu_naissance_etudiant ?></td>

                <!-- Notes des UE -->
                <?php foreach ($ues as $ue): ?>

                <?php $modules = $ue['modules']; ?>
                <?php $number = 0 ?>

                <?php foreach ($modules as $module): ?>
                <td>
                    <?= ($module->moyenne_module == 0) ? "X" : number_format($module->moyenne_module, 2) ?>
                </td>
                <?php endforeach ?>

                <td class="text-center note_ue">
                    <?= ($ue['moyenne'] == 0) ? "X" : number_format($ue['moyenne'], 2) ?>
                </td>
                <?php endforeach ?>

                <!-- Moyenne Semestre -->
                <td class="text-center text-bold-600 moyenneSemestre"><?= number_format($note, 2) ?></td>

                <!-- Observation -->
                <td class="text-center">
                    <h6 class=" etatSemestre text-bold-600 fw-bold"></h6>
                </td>
            </tr>
            <?php endfor ?>
        </tbody>
    </table>
</div>

<!-- ==================== STATISTIQUES ==================== -->
<div class="w-100 d-flex justify-content-arround mx-4" style="width: 100vw !important;">
    <div class="mt-3 no-break">
        <p><strong>Admis :</strong> <span id="nbAdmis"></span></p>
        <p><strong>Ajourné :</strong> <span id="nbAjournes"></span></p>
        <p><strong>Taux de réussite :</strong> <span id="tauxReussite"></span></p>
    </div>

    <!-- ==================== SIGNATURE ==================== -->
    <div class="text-right mt-5 mr-5 no-break">
        <p>
            <?php
            date_default_timezone_set("Africa/Bamako"); // ou Africa/Abidjan, qui est aussi UTC+0

            echo "Ségou, le " . date("d F Y");
            ?>
        </p>
        <p>P/Le Directeur P.O</p>
        <p><strong>Le <?php echo (isset($_SESSION['role'])) ? strtoupper($_SESSION['role']) : "" ?>
            </strong></p>
        <p>Dr <?php echo (isset($_SESSION['nom_prenom'])) ? strtoupper($_SESSION['nom_prenom']) : "" ?>
            <br><small> <?php echo (isset($_SESSION['nom_grade'])) ? strtoupper($_SESSION['nom_grade']) : "" ?>
            </small>
        </p>
    </div>
</div>
<!-- ==================== JS ==================== -->
<script src="<?= ROOT ?>/assets/vendors/js/tables/datatable/datatables.min.js"></script>
<script src="<?= ROOT ?>/assets/vendors/js/tables/datatable/dataTables.bootstrap4.min.js"></script>
<script src="<?= ROOT ?>/assets/vendors/js/tables/datatable/dataTables.buttons.min.js"></script>
<script src="<?= ROOT ?>/assets/vendors/js/tables/datatable/datatables.checkboxes.min.js"></script>
<script src="<?= ROOT ?>/assets/js/scripts/datatables/datatable.js"></script>

<script>
$(document).ready(function() {
    var nbrEtudiant = 0;
    var nbrValide = 0;

    $("#notesTable tbody tr").each(function() {
        nbrEtudiant++;
        var row = $(this);
        var moyenneSemestre = parseFloat(row.find(".moyenneSemestre").text());

        if (moyenneSemestre < 10) {
            row.find('.etatSemestre').text("Ajourné").addClass('badge-light-danger');
        } else {
            row.find('.etatSemestre').text("Admis").addClass('badge-light-success');
            nbrValide++;
        }
    });

    // Statistiques
    var nbAj = nbrEtudiant - nbrValide;
    var taux = ((nbrValide * 100) / nbrEtudiant).toFixed(2);

    $("#nbAdmis").text(nbrValide);
    $("#nbAjournes").text(nbAj);
    $("#tauxReussite").text(taux + "%");
});
</script>

<!-- Impression en paysage -->
<style>

</style>