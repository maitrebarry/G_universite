<link rel="stylesheet" type="text/css" href="<?= ROOT ?>/assets/vendors/css/tables/datatable/datatables.min.css">


<div class="d-flex justify-content-around align-items-center">

    <div>
        <h6 class=" text-center   text-uppercase"> UE</h6>
        <h6 class=" text-center text-bold-600 nomUe text-uppercase"> <?= $infosModule->nom_ue ?></h6>
    </div>

    <div>
        <h6 class=" text-center ">Module</h6>
        <h6 class=" text-center text-bold-600 nomModule"> <?= $infosModule->nom_module; ?></h6>
    </div>
    <div>
        <h6 class=" text-center ">Credit</h6>
        <h6 class=" text-center  text-bold-600 nomModule coeficient"><?= $infosModule->coeficient ?></h6>
    </div>


</div>
<div class="table-responsive">
    <table class="table table-striped table-bordered zero-configuration  w-100" id="notesTable">
        <thead>
            <tr>
                <th class="text-center d-lg-none">Etudiant</th>
                <th class="text-center d-none d-lg-table-cell">Matricule</th>
                <th class="text-center d-none d-lg-table-cell text-captilize">Nom & Prenom</th>
                <th class="text-center  genre d-none d-md-table-cell">Genre</th>
                <th class="text-center note_devoir  noteContainer">Note Devoir</th>
                <th class="text-center note_evaluation  noteContainer">Note Évaluation</th>
                <th class="text-center noteContainer">Note Session</th>
                <th class="text-center moyenne  noteContainer">Moyenne/Module</th>
        </thead>
        <tbody id="tableBody">

            <!-- Affichage dynamique via PHP -->
            <?php foreach ($note_des_etudiants as $note): ?>
            <?php

                ?>
            <tr class="rowt" data-id="<?= $note->id_note ?>">

                <td class="text-bold-500 text-center d-lg-none etudiant">
                    <div><?= ucwords(strtolower($note->prenom . ' ' . $note->nom_prenom_etudiant)) ?></div>
                    <div><a href=""><?= $note->matricule_etudiant ?></a></div>
                </td>
                <td class="text-bold-500 text-left d-none d-lg-table-cell">
                    <a href=""><?= $note->matricule_etudiant ?></a>
                </td>
                <td class="text-bold-500 text-left d-none d-lg-table-cell">
                    <?= ucwords(strtolower($note->prenom . ' ' . $note->nom_prenom_etudiant))
                        ?>
                </td>
                <td class="genre d-none d-md-table-cell"><?= ($note->genre_etudiant == "Féminin") ? 'F' : "M" ?></td>
                <td class=" noteContainer">
                    <input type="number" class="form-control noteDevoir note" value="<?= $note->note_devoir ?>"
                        step="0.1">
                </td>
                <td class=" noteContainer">
                    <input type="number" class="form-control noteEvaluation note" value="<?= $note->note_evaluation ?>"
                        step="0.1">
                </td>
                <td class="noteContainer">
                    <input type="number" class="form-control noteSession note" value="<?= $note->note_session ?>"
                        step="0.1">
                </td>
                <td class="noteContainer">
                    <!-- Moyenne affichée dans un input readonly -->
                    <input type="number" class="form-control moyenne moyenneModule" value="<?= $note->moyenne_module ?>"
                        disabled>
                </td>
            </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>
</div>

<!-- Zones pour le spinner et les messages -->
<div id="loadingSpinner" style="display:none;">Chargement...</div>
<div id="message"></div>
<div id="alerte"></div>

<!-- BEGIN Vendor JS-->
<script src="<?= ROOT ?>/assets/vendors/js/tables/datatable/datatables.min.js"></script>
<script src="<?= ROOT ?>/assets/vendors/js/tables/datatable/dataTables.bootstrap4.min.js"></script>
<script src="<?= ROOT ?>/assets/vendors/js/tables/datatable/dataTables.buttons.min.js"></script>
<script src="<?= ROOT ?>/assets/vendors/js/tables/datatable/datatables.checkboxes.min.js"></script>
<script src="<?= ROOT ?>/assets/js/scripts/datatables/datatable.js"></script>
<!-- BEGIN: Page Vendor JS-->

<script>
$("#notesTable").DataTable({
    "pageLength": 50
})
</script>