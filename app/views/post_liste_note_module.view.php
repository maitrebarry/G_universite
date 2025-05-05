<link rel="stylesheet" type="text/css" href="<?= ROOT ?>/assets/vendors/css/tables/datatable/datatables.min.css">


<div class="d-flex justify-content-around align-items-center row">

    <div class="col-6 col-md-3">
        <h6 class=" text-center ">Module</h6>
        <h6 class=" text-center text-bold-600 nomModule"> <?= $infosModule->nom_module; ?></h6>
    </div>

    <div class="col-6 col-md-3 mb-1 mb-md-0">
        <h6 class=" text-center ">Moyenne General</h6>
        <h6 class=" text-center text-bold-600" id="moyenneTotalModule"> </h6>
    </div>


    <div class="col-6 col-md-3">
        <h6 class=" text-center ">Taux de reussite</h6>
        <h6 class=" text-center text-bold-600">
            <span class=" badge text-bold-600" id="tauxReussite"></span>
        </h6>
    </div>


    <div class="col-6 col-md-3">
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
                <th class="text-center d-none d-lg-table-cell">Nom & Prenom</th>
                <th class="text-center  genre d-none d-md-table-cell">Genre</th>
                <th class="text-center note_devoir  noteContainer">Devoir</th>
                <th class="text-center note_evaluation  noteContainer">Éxamen</th>
                <th class="text-center noteContainer">Session</th>
                <th class="text-center moyenne noteContainer">M/Module</th>
                <th class="text-center moyenne noteContainer">Observation</th>
        </thead>
        <tbody id="tableBody">
            <!-- Affichage dynamique via PHP -->
            <?php foreach ($note_des_etudiants as $note): ?>
                <?php

                ?>
                <tr class="rowt" data-id="<?= $note->id_note ?>">

                    <td class="text-bold-500 text-center d-lg-none etudiant">
                        <div><?= strtoupper($note->nom_prenom_etudiant) ?></div>
                        <div><a href=""><?= $note->matricule_etudiant ?></a></div>
                    </td>
                    <td class="text-bold-500 text-left d-none d-lg-table-cell">
                        <a href=""><?= $note->matricule_etudiant ?></a>
                    </td>
                    <td class="text-bold-500 text-left d-none d-lg-table-cell">
                        <?= strtoupper($note->nom_prenom_etudiant) ?>
                    </td>
                    <td class="genre d-none d-md-table-cell"><?= ($note->genre_etudiant == "Féminin") ? 'F' : "M" ?></td>
                    <td class=" noteContainer">
                        <input type="number" class="form-control noteDevoir note text-bold-600 text-center"
                            value="<?= $note->note_devoir ?>" step="0.1" disabled>
                    </td>
                    <td class=" noteContainer">
                        <input type="number" class="form-control noteEvaluation note text-bold-600 text-center"
                            value="<?= $note->note_evaluation ?>" step="0.1" disabled>
                    </td>
                    <td class="noteContainer">
                        <input type="number" class="form-control noteSession note text-bold-600 text-center"
                            value="<?= $note->note_session ?>" step="0.1" disabled>
                    </td>
                    <td class="noteContainer" disabled>
                        <!-- Moyenne affichée dans un input readonly -->
                        <input type="number" class="form-control moyenne moyenneModule text-bold-600 text-center"
                            value="<?= $note->moyenne_module ?>" disabled>
                    </td>


                    <td>
                        <span class=" badge etatSemestre text-bold-600 text-center"></span>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>
</div>

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


    var nbrValide = 0;
    var nbrEtudiant = 0;
    var moyenneTotalModule = 0;
    $("#notesTable tbody tr").each(function() {
        nbrEtudiant++;
        const moyenneInput = $(this).find(".moyenne");
        const moyenne = moyenneInput.val()
        if (moyenne < 10) {
            moyenneInput.addClass("bg-rgba-danger");
            $(this).find('.etatSemestre').text("Non Validé");
            $(this).find('.etatSemestre').addClass('badge-light-danger');
        } else if (moyenne <= 20) {
            moyenneInput.addClass("bg-rgba-success");
            $(this).find('.etatSemestre').text("Validé");
            $(this).find('.etatSemestre').addClass('badge-light-success');
            nbrValide++;
        }

        moyenneTotalModule += parseFloat(moyenne, 10);
    })

    moyenneTotalModule = (moyenneTotalModule / nbrEtudiant).toFixed(2);
    $('#moyenneTotalModule').text(moyenneTotalModule);
    if (moyenneTotalModule < 10) {
        $('#moyenneTotalModule').addClass('text-danger');
    } else {
        $('#moyenneTotalModule').addClass('text-success');
    }

    tauxReussite = ((nbrValide * 100) / nbrEtudiant).toFixed(2);
    $('#tauxReussite').text(tauxReussite + "%");
    if (tauxReussite < 50) {
        $('#tauxReussite').addClass('badge-light-danger');
    } else {
        $('#tauxReussite').addClass('badge-light-success');
    }
</script>