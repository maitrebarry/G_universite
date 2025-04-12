<link rel="stylesheet" type="text/css" href="<?= ROOT ?>/assets/vendors/css/tables/datatable/datatables.min.css">


<!-- <div class="d-flex justify-content-around align-items-center row">

    <div class="col-6 col-md-3 mb-1 mb-md-0">
        <h6 class=" text-center text-uppercase">Semestre</h6>
        <h6 class=" text-center text-bold-600" id="nomSemestre"> </h6>
    </div>

    <div class="col-6 col-md-3 mb-1 mb-md-0">
        <h6 class=" text-center ">Moyenne General</h6>
        <h6 class=" text-center text-bold-600" id="moyenneTotalSemestre"> </h6>
    </div>

    <div class="col-6 col-md-3">
        <h6 class=" text-center ">Taux de reussite</h6>
        <h6 class=" text-center text-bold-600">
            <span class=" badge text-bold-600" id="tauxReussite"></span>
        </h6>
    </div>

    <div class="col-6 col-md-3">
        <h6 class=" text-center ">Credit Total</h6>
        <h6 class=" text-center  text-bold-600" id="creditTotal"><?= $creditTotal ?></h6>
    </div>

</div> -->
<div class="table-responsive">
    <table class="table table-striped table-bordered zero-configuration  w-100" id="notesTable">
        <thead>
            <tr>
                <th class="text-center d-lg-none">Etudiant</th>
                <th class="text-center d-none d-lg-table-cell">Matricule</th>
                <th class="text-center d-none d-lg-table-cell">Nom & Prenom</th>
                <th class="text-center  genre d-none d-lg-table-cell">Genre</th>
                <?php foreach ($infosLicence as $semestre): ?>
                    <th class="text-center moyenne  noteContainer text-capitalize">
                        <?= $semestre->sigle_semestre ?>
                    </th>
                <?php endforeach ?>
                <th class="text-center moyenne noteContainer">M/L</th>
                <th class="text-center moyenne noteContainer">Observation</th>
            </tr>
        </thead>
        <tbody id="tableBody">
            <!-- Affichage dynamique via PHP -->
            <?php foreach ($etudiants as $etudiant): ?>
                <tr>
                    <td class="text-bold-500 text-center d-lg-none etudiant" style="font-size: 14px;">
                        <div><?= strtoupper($etudiant->nom_prenom_etudiant) ?></div>
                        <div><a href=""><?= $etudiant->matricule_etudiant ?></a></div>
                    </td>
                    <td class="text-bold-500 text-left d-none d-lg-table-cell" style="font-size: 14px;">
                        <a href=""><?= $etudiant->matricule_etudiant ?></a>
                    </td>
                    <td class="text-bold-500 text-left d-none d-lg-table-cell" style="font-size: 14px;">
                        <?= strtoupper($etudiant->nom_prenom_etudiant) ?>
                    </td>
                    <td class="genre d-none d-lg-table-cell" style="font-size: 14px;">
                        <?= ($etudiant->genre_etudiant == "Féminin") ? 'F' : "M" ?>
                    </td>
                    <?php foreach ($infosLicence as $semestre): ?>
                        <td class=" noteContainer">
                            <input type="number" class="form-control moyenneUe note text-bold-600 text-center"
                                id="<?= 'e_' . $etudiant->id_etudiant . '_s_' . $semestre->id_parcours ?>" step="0.1" disabled>
                        </td>
                    <?php endforeach ?>
                    <td class="noteContainer">
                        <!-- Moyenne affichée dans un input readonly -->
                        <input type="number" class="form-control moyenneSemestre note text-bold-600 text-center" disabled>
                    </td>

                    <td>
                        <span class=" badge etatSemestre text-bold-600 text-center"></span>
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
    var moyennes = <?php echo json_encode($moyennesLicence); ?>;

    var nbrEtudiant = 0;
    var moyenneLicence = 0;
    var nbrSemestre = 0;
    var nbrValide = 0;
    var tauxReussite = 0;

    $("#notesTable").DataTable({
        "pageLength": 50
    })

    $.each(moyennes, function(index) {
        const moyennesSemestre = $(this)[0];
        var moyenneTotalSemestre = 0;
        var nombreUe = 0;
        nbrSemestre++;
        $.each(moyennesSemestre, function(index) {
            nombreUe++;
            const moyennesUe = $(this)[0].moyennesUe;

            $.each(moyennesUe, function(index2) {
                moyenneTotalSemestre += $(this)[0].moyenne_ue;
            });
        });

        moyenneTotalSemestre=moyennetotal
        
    });

    // $("#notesTable tbody tr").each(function(index) {
    //     nbrEtudiant++;
    //     var moyenneSemestre = 0;
    //     const row = $(this);

    //     $(this).find(".moyenneUe").each(function() {
    //         let moyenneUe = parseFloat($(this).val(), 10) || 0; // Convertir en nombre et éviter NaN
    //         moyenneSemestre += moyenneUe;
    //     });
    //     moyenneSemestre = (moyenneSemestre / nombreUe);
    //     if (moyenneSemestre < 10) {
    //         row.find('.etatSemestre').text("Ajourné");
    //         row.find('.etatSemestre').addClass('badge-light-danger');
    //         row.find(".moyenneSemestre").addClass('bg-rgba-danger')

    //     } else {
    //         row.find('.etatSemestre').text("Admis");
    //         row.find('.etatSemestre').addClass('badge-light-success');
    //         row.find(".moyenneSemestre").addClass('bg-rgba-success')
    //         nbrValide++;
    //     }
    //     row.find(".moyenneSemestre").val(moyenneSemestre.toFixed(2));

    //     moyenneTotalSemestre += moyenneSemestre;
    // });
    // moyenneTotalSemestre = (moyenneTotalSemestre / nbrEtudiant).toFixed(2);
    // $('#moyenneTotalSemestre').text(moyenneTotalSemestre);
    // if (moyenneTotalSemestre < 10) {
    //     $('#moyenneTotalSemestre').addClass('text-danger');
    // } else {
    //     $('#moyenneTotalSemestre').addClass('text-success');
    // }

    // tauxReussite = ((nbrValide * 100) / nbrEtudiant).toFixed(2);
    // $('#tauxReussite').text(tauxReussite + "%");
    // if (tauxReussite < 50) {
    //     $('#tauxReussite').addClass('badge-light-danger');
    // } else {
    //     $('#tauxReussite').addClass('badge-light-success');
    // }
</script>