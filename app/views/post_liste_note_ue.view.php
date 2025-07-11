<link rel="stylesheet" type="text/css" href="<?= ROOT ?>/assets/vendors/css/tables/datatable/datatables.min.css">

<?php foreach ($infosUe as $module): ?>
    <?php @$creditTotal += $module->coeficient ?>
<?php endforeach ?>

<div class="d-flex justify-content-around align-items-center row">

    <div class="col-6 col-md-3 mb-1 mb-md-0">
        <h6 class=" text-center   text-uppercase"> UE</h6>
        <h6 class=" text-center text-bold-600 nomUe text-uppercase"> <?= $infosUe[0]->nom_ue ?></h6>
    </div>

    <div class="col-6 col-md-3 mb-1 mb-md-0">
        <h6 class=" text-center ">Moyenne General</h6>
        <h6 class=" text-center text-bold-600" id="moyenneTotalUe"> </h6>
    </div>

    <div class="col-6 col-md-3">
        <h6 class=" text-center ">Taux de reussite</h6>
        <h6 class=" text-center text-bold-600">
            <span class=" badge text-bold-600" id="tauxReussite"></span>
        </h6>
    </div>

    <div class="col-6 col-md-3">
        <h6 class=" text-center ">Credit Total</h6>
        <h6 class=" text-center  text-bold-600 nomModule coeficient"><?= $creditTotal ?></h6>
    </div>

</div>
<div class="table-responsive">
    <table class="table table-striped table-bordered zero-configuration  w-100" id="notesTable">
        <thead>
            <tr>
                <th class="text-center d-lg-none">Etudiant</th>
                <th class="text-center d-none d-lg-table-cell">Matricule</th>
                <th class="text-center d-none d-lg-table-cell">Nom & Prenom</th>
                <th class="text-center  genre d-none d-lg-table-cell">Genre</th>
                <?php foreach ($infosUe as $module): ?>
                    <th class="text-center moyenne  noteContainer text-capitalize">
                        M/<?= (strlen($module->nom_module) < 20) ? $module->nom_module : $module->sigle_module ?>
                    </th>
                <?php endforeach ?>
                <th class="text-center moyenne  noteContainer">M/UE</th>
                <th class="text-center moyenne  noteContainer">Observation</th>
            </tr>
        </thead>
        <tbody id="tableBody">
            <!-- Affichage dynamique via PHP -->
            <?php foreach ($moyenne_des_etudiants[0] as $note): ?>
                <tr>

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
                    <td class="genre d-none d-lg-table-cell"><?= ($note->genre_etudiant == "Féminin") ? 'F' : "M" ?></td>
                    <?php foreach ($infosUe as $module): ?>
                        <td class=" noteContainer">
                            <input type="number" class="form-control moyenneModule note text-bold-600 text-center"
                                id="<?= 'e_' . $note->id_etudiant . '_m_' . $module->id_ue_module ?>" step="0.1" disabled
                                value="10">
                        </td>
                    <?php endforeach ?>

                    <td class="noteContainer">
                        <!-- Moyenne affichée dans un input readonly -->
                        <input type="number" class="form-control  moyenneUe note text-bold-600 text-center" disabled>
                    </td>

                    <td>
                        <span class=" badge etatUe text-bold-600 text-center"></span>
                    </td>

                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>

<!-- BEGIN Vendor JS-->
<script src="<?= ROOT ?>/assets/vendors/js/tables/datatable/datatables.min.js"></script>
<script src="<?= ROOT ?>/assets/vendors/js/tables/datatable/dataTables.bootstrap4.min.js"></script>
<script src="<?= ROOT ?>/assets/vendors/js/tables/datatable/dataTables.buttons.min.js"></script>
<script src="<?= ROOT ?>/assets/vendors/js/tables/datatable/datatables.checkboxes.min.js"></script>
<script src="<?= ROOT ?>/assets/js/scripts/datatables/datatable.js"></script>

<script>
    $("#notesTable").DataTable({
        "pageLength": 100
    })

    var moyennes = <?php echo json_encode($moyenne_des_etudiants); ?>;
    var nombreModule = 0;
    var nbrEtudiant = 0;
    var moyenneTotalUe = 0;
    var nbrValide = 0;
    var tauxReussite = 0;
    $.each(moyennes, function(index) {
        const moyennesModule = moyennes[index];
        nombreModule++;
        $.each(moyennesModule, function(index2) {
            const idMoyenne =
                '#e_' + moyennesModule[index2].id_etudiant + "_m_" + moyennesModule[index2].id_module;
            $(idMoyenne).val(moyennesModule[index2].moyenne_module)
        });
    });

    $("#notesTable tbody tr").each(function(index) {
        nbrEtudiant++;
        var moyenneUe = 0;
        const row = $(this);

        $(this).find(".moyenneModule").each(function() {
            let moyenneModule = parseFloat($(this).val(), 10) || 0; // Convertir en nombre et éviter NaN
            moyenneUe += moyenneModule;
        });
        moyenneUe = (moyenneUe / nombreModule);
        if (moyenneUe < 10) {
            row.find('.etatUe').text("Non Validé");
            row.find('.etatUe').addClass('badge-light-danger');
        } else {
            row.find('.etatUe').text("Validé");
            row.find('.etatUe').addClass('badge-light-success');
            nbrValide++;
        }
        row.find(".moyenneUe").val(moyenneUe.toFixed(2));

        moyenneTotalUe += moyenneUe;
    });
    moyenneTotalUe = (moyenneTotalUe / nbrEtudiant).toFixed(2);
    $('#moyenneTotalUe').text(moyenneTotalUe);
    if (moyenneTotalUe < 10) {
        $('#moyenneTotalUe').addClass('text-danger');
    } else {
        $('#moyenneTotalUe').addClass('text-success');
    }

    tauxReussite = ((nbrValide * 100) / nbrEtudiant).toFixed(2);
    $('#tauxReussite').text(tauxReussite + "%");
    if (tauxReussite < 50) {
        $('#tauxReussite').addClass('badge-light-danger');
    } else {
        $('#tauxReussite').addClass('badge-light-success');
    }
</script>
<!-- BEGIN: Page Vendor JS-->