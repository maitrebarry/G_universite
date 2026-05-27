<link rel="stylesheet" type="text/css" href="<?= ROOT ?>/assets/vendors/css/tables/datatable/datatables.min.css">


<div class="d-flex justify-content-around align-items-center row">

    <div class="col-6 col-md-3 mb-1 mb-md-0">
        <h6 class=" text-center text-uppercase">Classe</h6>
        <h6 class=" text-center text-bold-600" id="nomClasse"> </h6>
    </div>

    <div class="col-6 col-md-3 mb-1 mb-md-0">
        <h6 class=" text-center ">Moyenne General</h6>
        <h6 class=" text-center text-bold-600" id="moyenneTotalLicence"> </h6>
    </div>

    <div class="col-6 col-md-3">
        <h6 class=" text-center ">Taux de reussite</h6>
        <h6 class=" text-center text-bold-600">
            <span class=" badge text-bold-600" id="tauxReussite"></span>
        </h6>
    </div>

    <div class="col-6 col-md-3">
        <h6 class=" text-center ">Credit Total</h6>
        <h6 class=" text-center  text-bold-600" id="creditTotal"><?= @$creditTotal ?></h6>
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
                <?php $semestres = $infosLicence['semestres'] ?>
                <?php foreach ($semestres as $semestre): ?>
                <th class="text-center moyenne  noteContainer text-capitalize">
                    <?= strtoupper($semestre->sigle_semestre) ?>
                </th>
                <?php endforeach ?>
                <th class="text-center moyenne noteContainer">M/L</th>
                <th class="text-center moyenne noteContainer">Observation</th>
            </tr>
        </thead>
        <tbody id="tableBody">
            <!-- Affichage dynamique via PHP -->
            <?php for ($i = 0; $i < count($moyennesLicence); $i++) : ?>
            <?php $etudiant = $moyennesLicence[$i]['etudiant'];
                $note = $moyennesLicence[$i]['moyenne'] ?>
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


                <?php $semestres = $moyennesSemestre[$i]['semestres']; ?>
                <?php foreach ($semestres as $semestre): ?>
                <td class=" noteContainer">
                    <input type="number" class="form-control moyenneUe note text-bold-600 text-center" step="0.1"
                        disabled value="<?php echo  $semestre['moyenne'] ?>">
                </td>
                <?php endforeach ?>
                <td class="noteContainer">
                    <!-- Moyenne affichée dans un input readonly -->
                    <input type="number" class="form-control moyenneLicence note text-bold-600 text-center" disabled
                        value="<?php echo $note ?>">
                </td>

                <td>
                    <span class=" badge etatLicence text-bold-600 text-center"></span>
                </td>

            </tr>
            <?php endfor ?>
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
var nbrEtudiant = 0;
var nbrSemestre = 0;
var nbrValide = 0;
var tauxReussite = 0;
moyenneTotalLicence = 0;

$("#notesTable").DataTable({
    "pageLength": 100
})
$('#nomClasse').text($("#promotions option:selected").text())


$("#notesTable tbody tr").each(function(index) {
    row = $(this);
    nbrEtudiant++;
    moyenneLicence = parseInt(row.find('.moyenneLicence').val(), 10);
    if (moyenneLicence < 10) {
        row.find('.etatLicence').text("Ajourné");
        row.find('.etatLicence').addClass('badge-light-danger');
        row.find(".moyenneLicence").addClass('bg-rgba-danger')

    } else {
        row.find('.etatLicence').text("Admis");
        row.find('.etatLicence').addClass('badge-light-success');
        row.find(".moyenneLicence").addClass('bg-rgba-success')
        nbrValide++;
    }


    moyenneTotalLicence += moyenneLicence;
});
moyenneTotalLicence = (moyenneTotalLicence / nbrEtudiant).toFixed(2);
$('#moyenneTotalLicence').text(moyenneTotalLicence);
if (moyenneTotalLicence < 10) {
    $('#moyenneTotalLicence').addClass('text-danger');
} else {
    $('#moyenneTotalLicence').addClass('text-success');
}

tauxReussite = ((nbrValide * 100) / nbrEtudiant).toFixed(2);
$('#tauxReussite').text(tauxReussite + "%");
if (tauxReussite < 50) {
    $('#tauxReussite').addClass('badge-light-danger');
} else {
    $('#tauxReussite').addClass('badge-light-success');
}
</script>