<?php
function tronquerTexte($texte, $limite = 8)
{
    return (strlen($texte) > $limite) ? substr($texte, 0, $limite) . "…" : $texte;
}
?>
<link rel="stylesheet" type="text/css" href="<?= ROOT ?>/assets/vendors/css/tables/datatable/datatables.min.css">
<style>
    .vertical-header {
        writing-mode: vertical-rl;
        /* Texte vertical */
        transform: rotate(180deg);
        /* Redresse le texte */
        text-align: center;
        vertical-align: middle;
        white-space: nowrap;
        /* Pas de retour à la ligne */
        overflow: hidden;
        /* Cache le surplus */
        text-overflow: ellipsis;
        /* Ajoute ... */
        max-height: 120px;
        /* Hauteur max (ajuste selon besoin) */
        font-size: 12px;
        padding: 5px;
    }

    .etudiantInfo {
        font-size: 14px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 120px;
        min-width: 120px;

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
    <h6>Année Universitaire 2022-2023</h6>
    <h6><strong>Résultats provisoires du troisième semestre (S3)</strong></h6>
    <h6>Mention : Génie Informatique</h6>
    <small>ECUE à reprendre (X) et moyenne par UE</small>
</div>

<!-- ==================== TABLEAU ==================== -->
<div class="table-responsive">
    <table class="table table-bordered table-striped w-100" id="notesTable">
        <thead>
            <tr>
                <th class="text-center">N°</th>
                <th class="text-center etudiantInfo">Prénoms</th>
                <th class="text-center etudiantInfo">Nom</th>
                <th class="text-center etudiantInfo">Date de Naissance</th>
                <th class="text-center etudiantInfo">Lieu de Naissance</th>
                <?php foreach ($infosSemestre as $ue): ?>
                    <th class="vertical-header"><?= tronquerTexte($ue[0]->nom_ue, 8) ?></th>
                <?php endforeach ?>

                <th class="text-center vertical-header">Moy. Gén.</th>
                <th class="text-center vertical-header">Observation</th>
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
                        <td class="text-center">
                            <?= ($ue['moyenne'] == 0) ? "X" : number_format($ue['moyenne'], 2) ?>
                        </td>
                    <?php endforeach ?>

                    <!-- Moyenne Semestre -->
                    <td class="text-center text-bold-600 moyenneSemestre"><?= number_format($note, 2) ?></td>

                    <!-- Observation -->
                    <td class="text-center">
                        <span class="badge etatSemestre text-bold-600"></span>
                    </td>
                </tr>
            <?php endfor ?>
        </tbody>
    </table>
</div>

<!-- ==================== STATISTIQUES ==================== -->
<div class="mt-3">
    <p><strong>Admis :</strong> <span id="nbAdmis"></span></p>
    <p><strong>Ajourné :</strong> <span id="nbAjournes"></span></p>
    <p><strong>Taux de réussite :</strong> <span id="tauxReussite"></span></p>
</div>

<!-- ==================== SIGNATURE ==================== -->
<div class="text-right mt-5 mr-5">
    <p>Ségou, le <?= date("d F Y") ?></p>
    <p>P/Le Directeur P.O</p>
    <p><strong>Le Directeur Adjoint</strong></p>
    <p>Dr Mahamet KOÏTA<br><small>Maître Assistant</small></p>
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

<!-- Impression en paysage
<style>
    @media print {
        @page {
            size: A4 landscape;
        }
    }
</style> -->