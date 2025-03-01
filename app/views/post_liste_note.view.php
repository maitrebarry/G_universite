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
<table class="table table-striped table-bordered zero-configuration " id="notesTable">
    <thead>
        <tr>
            <th class="text-center">Numero Matricule</th>
            <th class="text-center">Nom && Prénom</th>
            <th class="text-center">Genre</th>
            <th class="text-center note_devoir noteContainer">Note Devoir</th>
            <th class="text-center note_evaluation noteContainer">Note Évaluation</th>
            <th class="text-center note_session hidden noteContainer">Note Session</th>
            <th class="text-center moyenne noteContainer">Moyenne</th>
        </tr>
    </thead>
    <tbody>
        <!-- Affichage dynamique via PHP -->
        <?php foreach ($note_des_etudiants as $note): ?>
        <?php

            ?>
        <tr class="rowt" data-id="<?= $note->id_note ?>">
            <td class=" text-bold-500 text-success"><a href=""><?= strtoupper($note->matricule_etudiant) ?></a></td>
            <td class="text-bold-500"><?= strtoupper($note->nom_prenom_etudiant) ?></td>
            <td><?= ($note->genre_etudiant == "Féminin") ? 'F' : "M" ?></td>
            <td class=" noteContainer">
                <input type="number" class="form-control noteDevoir note" value="<?= $note->note_devoir ?>" step="0.1">
            </td>
            <td class=" noteContainer">
                <input type="number" class="form-control noteEvaluation note" value="<?= $note->note_evaluation ?>"
                    step="0.1">
            </td>
            <td class="note_session hidden noteContainer">
                <input type="number" class="form-control noteSession note" value="<?= $note->note_session ?>"
                    step="0.1">
            </td>
            <td class="noteContainer">
                <!-- Moyenne affichée dans un input readonly -->
                <input type="number" class="form-control moyenne" value="" readonly>
            </td>
        </tr>
        <?php endforeach ?>
    </tbody>
</table>
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