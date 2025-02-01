<style>
input {

    padding: 8px;
    font-size: 16px;
    text-align: center;
}

td {
    padding: 8px 5px !important;
}
</style>
<!-- inclusion du partie header -->
<?php $this->view("Partials/header") ?>

<body class="vertical-layout vertical-menu-modern boxicon-layout no-card-shadow 2-columns navbar-sticky footer-static"
    data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">

    <!-- inclusion du partie header -->
    <?php $this->view("Partials/navbar") ?>
    <!-- inclusion du partie header fin-->

    <!-- inclusion du partie seibar-->
    <?php $this->view("Partials/seibar") ?>
    <!-- inclusion du partie seibar fin-->

    <!-- Content-->
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-12 mb-2 mt-1">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h5 class="content-header-title float-left pr-1 mb-0">
                                <?php
                                echo (isset($_SESSION['nom_departement']))
                                    ? strtoupper($_SESSION['nom_departement'] . ' (' . $_SESSION['sigle_departement'] . ')')
                                    : "IUFP"
                                ?>
                            </h5>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb p-0 mb-0">
                                    <li class="breadcrumb-item"><a href="index.html"><i class="bx bx-home-alt"></i></a>
                                    </li>
                                    <li class="breadcrumb-item"><a
                                            href="<?php echo ROOT . '/Emploi_du_temps/' ?>">Gestion EDT</a>
                                    </li>
                                    <li class="breadcrumb-item active">Engistrements
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <!-- formulaire -->
                <section class="simple-validation">
                    <div class="row">
                        <div id="message" class="col-12"></div>
                        <div class="col-md-12">
                            <div class="card card-animated-border-top">
                                <div class="card-header">
                                    <h4 class="card-title text-center">Modification d'emploi du temps</h4>
                                </div>
                                <div class="card-content">
                                    <div class="card-body">
                                        <form method="POST" class="form-horizontal" novalidate id="edtForm">
                                            <div class="row d-flex justify-content-around align-items-center">
                                                <div class="col-sm-3">
                                                    <label class="form-label" for="single-select">Filiere</label>
                                                    <div class="form-group">
                                                        <select class="select2 form-control" id="filiere">
                                                            <option value="0" disabled selected>Selectionner une Filiere
                                                            </option>
                                                            <?php foreach ($filieres as $filiere): ?>
                                                            <option value="<?php echo $filiere->id_filiere ?>"
                                                                <?= ($infosEdt->promotion->id_filiere == $filiere->id_filiere) ? 'selected' : '' ?>>
                                                                <?php echo strtoupper($filiere->sigle_filiere) ?>
                                                            </option>
                                                            <?php endforeach ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <label class="form-label" for="single-select">Promotion</label>
                                                    <div class="form-group">
                                                        <select class="select2 form-control" id="promotions"
                                                            data-id="<?php echo $infosEdt->promotion->id_promotion ?>">
                                                            <option value="" disabled selected>Selectioner une Promotion
                                                            </option>

                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <label class="form-label" for="single-select">Modules</label>
                                                    <div class="form-group">
                                                        <select class="select2 form-control champ" id="modules"
                                                            name="modules"
                                                            data-id="<?php echo $infosEdt->module->id_ue_module ?>">
                                                            <option value="" disabled selected>Selectioner un Module
                                                            </option>

                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                                        data-target="#menuConfig"><i class="bx bxs-cog"></i>
                                                        Paramètrage</button>
                                                </div>
                                            </div>

                                            <div class="row d-flex justify-content-between align-items-center mb-1 ">
                                                <div
                                                    class="col-4 row d-flex justify-content-between align-items-center">
                                                    <div class=" col-12 m-0">
                                                        <!-- Bouton pour ajouter une nouvelle ligne -->
                                                        <i class="bx bx-plus btn btn-secondary" id="add-row"></i>
                                                        <!-- Bouton pour supprimer la dernière ligne -->
                                                        <i class="bx bx-minus btn btn-danger" id="remove-row"></i>
                                                    </div>

                                                </div>
                                                <div class=" col-6 row d-none float-right" id="infoModule">
                                                    <input type="hidden" id="vht" class="vht">
                                                    <!-- CM -->
                                                    <div class='col-6 col-lg-3'>
                                                        <label class="d-block text-center">CM</label>
                                                        <input type='number' class='form-control text-center cm'
                                                            disabled>
                                                    </div>
                                                    <!-- TD -->
                                                    <div class='col-6 col-lg-3'>
                                                        <label class="d-block text-center">TD</label>
                                                        <input type='number' class='form-control text-center td'
                                                            disabled>
                                                    </div>
                                                    <!-- TP -->
                                                    <div class='col-6 col-lg-3'>
                                                        <label class="d-block text-center">TP</label>
                                                        <input type='number' class='form-control text-center tp'
                                                            disabled>
                                                    </div>
                                                    <!-- TPE -->
                                                    <div class='col-6 col-lg-3'>
                                                        <label class="d-block text-center">TPE</label>
                                                        <input type='number' class='form-control text-center tpe'
                                                            disabled>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="table-responsive">
                                                <table id="table-extended-chechbox"
                                                    class="table table-striped table-bordered" style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center">Horaire</th>
                                                            <?php foreach ($jours as $jour): ?>
                                                            <th class="jour" data-id="<?php echo $jour->id_jour ?>">
                                                                <?php echo strtoupper($jour->nom_jour) ?></th>
                                                            <?php endforeach ?>

                                                        </tr>
                                                    </thead>
                                                    <tbody id="corpsEdt">
                                                        <?php foreach ($horairesEdt as $horaire): ?>
                                                        <tr>
                                                            <td>
                                                                <div class='row'>
                                                                    <div class='col-sm-6'>
                                                                        <input type='time'
                                                                            class='form-control horaireDebut'
                                                                            value="<?php echo substr($horaire->heure_debut, 0, 5) ?>">
                                                                    </div>
                                                                    <div class='col-sm-6'>
                                                                        <input type='time'
                                                                            class='form-control horaireFin'
                                                                            value="<?php echo substr($horaire->heure_fin, 0, 5) ?>">
                                                                    </div>
                                                                </div>
                                                            </td>

                                                            <?php foreach ($horaire->taches as $tache): ?>

                                                            <td>
                                                                <select class='form-control tache'>
                                                                    <option value='x' class='text-center'
                                                                        <?php echo (strtolower($tache->type_tache) == 'x') ? 'selected' : '' ?>>
                                                                        X</option>
                                                                    <option value='cm' class='text-center'
                                                                        <?php echo (strtolower($tache->type_tache) == 'cm') ? 'selected' : '' ?>>
                                                                        CM
                                                                    </option>
                                                                    <option value='td' class='text-center'
                                                                        <?php echo (strtolower($tache->type_tache) == 'td') ? 'selected' : '' ?>>
                                                                        TD
                                                                    </option>
                                                                    <option value='tp' class='text-center'
                                                                        <?php echo (strtolower($tache->type_tache) == 'tp') ? 'selected' : '' ?>>
                                                                        TP
                                                                    </option>
                                                                    <option value='tpe' class='text-center'
                                                                        <?php echo (strtolower($tache->type_tache) == 'tpe') ? 'selected' : '' ?>>
                                                                        TPE
                                                                    </option>
                                                                    <option value='examen' class='text-center'
                                                                        <?php echo (strtolower($tache->type_tache) == 'examen') ? 'selected' : '' ?>>
                                                                        EXAMEN
                                                                    </option>
                                                                </select>
                                                            </td>
                                                            <?php endforeach ?>
                                                        </tr>
                                                        <?php endforeach ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="row d-flex justify-content-between">
                                                <div class="col-sm-3">
                                                    <label class="form-label" for="enseignants">ENSEIGNANT :</label>
                                                    <div class="form-group">
                                                        <select class=" form-control champ" id="enseignants"
                                                            name="enseignants">
                                                            <option value="" disabled selected>Sélectionner un
                                                                enseignant</option>
                                                            <?php foreach ($enseignants as $enseignant): ?>
                                                            <option value="<?php echo $enseignant->enseignant_id ?>"
                                                                <?= ($infosEdt->edt->id_enseignant == $enseignant->enseignant_id) ? 'selected' : '' ?>>
                                                                <?php echo  strtoupper(
                                                                        $enseignant->enseignant_nom . "  "
                                                                            . $enseignant->enseignant_prenom . "("
                                                                            . $enseignant->enseignant_telephone . ")"
                                                                    )
                                                                    ?>
                                                            </option>
                                                            <?php endforeach ?>
                                                            <!-- Ajoutez ici les options des enseignants -->
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <label class="form-label" for="single-select">SALE DE COURS</label>
                                                    <div class="form-group">
                                                        <select class="form-control champ" name="salles" id="salles">
                                                            <option value="" disabled selected>Selectionner une Salle
                                                            </option>
                                                            <?php foreach ($salles as $salle): ?>
                                                            <option value="<?php echo $salle->id_salle ?>"
                                                                <?= ($infosEdt->edt->id_salle == $salle->id_salle) ? 'selected' : '' ?>>
                                                                <?php echo strtoupper($salle->nom_salle) . "(" . $salle->capacite_salle . ")" ?>
                                                            </option>
                                                            <?php endforeach ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3 ">
                                                    <label class="form-label" for="dateDebut champ">Date de Debut
                                                        :</label>
                                                    <div class="form-group w-100 d-flex justify-content-end ">
                                                        <input type="date" class="form-control" name="dateDebut"
                                                            id="dateDebut"
                                                            value="<?php echo date_format(new DateTime($infosEdt->edt->date_debut), "Y-m-d") ?>">
                                                    </div>
                                                </div>

                                            </div>
                                            <button type="submit" style="float: right;" class="btn btn-primary"
                                                data-id="<?php echo $infosEdt->edt->id_edt ?>"
                                                id="valider">Modifier</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- formulaire -->
            </div>
        </div>

        <div class="modal-primary mr-1 mb-1 d-inline-block ">
            <div class=" modal fade text-left" id="menuConfig" tabindex="-1" role="dialog"
                aria-labelledby="myModalLabel160" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class=" row">
                                <div class=" col-12 row mb-1">
                                    <h6 class=" col-12 text-center">Models Edt</h6>
                                    <div class="col-12 border p-2 d-flex justify-content-center ">

                                        <div style="width: 300px" class="cursor-pointer ">
                                            <span class=" text-center">Horizontal</span>
                                            <img class="img img-thumbnail d-block border border-primary"
                                                src="<?= ROOT ?>/assets/images/model-row.png" alt="model-row"
                                                id="model-row" style="border-width: 2px !important;"
                                                data-model="edt-row">
                                        </div>

                                        <div style=" width:300px; " class="ml-2 cursor-pointer">
                                            <span class=" text-center">Vertical</span>
                                            <img class="img img-thumbnail d-block border"
                                                src="<?= ROOT ?>/assets/images/model-column.png" alt="model-column"
                                                id="model-column" style="border-width: 2px !important;"
                                                data-model="edt-column">
                                        </div>
                                    </div>
                                </div>
                                <div class=" col-12 row">
                                    <h6 class=" col-12 text-center">Type de Cours</h6>
                                    <div class="col-12 border d-flex justify-content-center p-2">

                                        <div class=" radio radio-primary mr-4"> <input type="radio" name="type" id="cm"
                                                class="type" value="0">
                                            <label for="cm">CM</label>
                                        </div>

                                        <div class="radio radio-primary mr-4"> <input type="radio" name="type" id="td"
                                                class="type" value="1">
                                            <label for="td">TD</label>
                                        </div>

                                        <div class="radio radio-primary form-group mr-4"> <input type="radio"
                                                name="type" id="tp" class="type" value="2">
                                            <label for="tp">TP</label>
                                        </div>

                                        <div class="radio radio-primary form-group mr-4"> <input type="radio"
                                                name="type" id="all" class="type" value="3" checked>
                                            <label for="all">Mixe</label>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn  btn-link" data-dismiss="modal">
                                <i class="bx bx-x d-block d-sm-none"></i>
                                <span class="d-none d-sm-block">Fermer</span>
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- fin: Content-->



    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <!-- inclusion du partie foot-->
    <?php $this->view("Partials/foot") ?>
    <!-- inclusion du partie foot fin-->
    <!-- inclusion du partie footer-->
    <?php $this->view("Partials/footer") ?>
    <!-- inclusion du partie footer fin-->
</body>
<!-- END: Body-->

</html>
<script src="<?= ROOT ?>/assets/mon_js/edt.js"></script>
<script src="<?= ROOT ?>/assets/mon_js/contrainte_date_edt.js"></script>
<script>
// la recuperation des liste de promotion d'une filière lors d'une selection de fiilière
var infoFiliere = [];
$("#filiere").change(async function() {
    infoFiliere = await infosFiliere($(this).val());
    promotionsFiliere(infoFiliere);
    idSemestre = $("#promotions option:selected").data("id");
    modulesSemestre(idSemestre, infoFiliere);
    infoModule($("#infoModule").val(), infoFiliere);


})

// la recuperation des modules d'une promotion lors d'une selection de promotion
$("#promotions").change(function() {
    idSemestre = $("#promotions option:selected").data("id");
    modulesSemestre(idSemestre, infoFiliere);
    infoModule($("#infoModule").val(), infoFiliere);
})


// la recuperation des heures d'un module lors d'une selection de module
$("#modules").change(function() {
    infoModule($(this).val(), infoFiliere);
    getDefaultEnseignantAndSalleModule($("#filiere").val(), $(this).val());


})

// les actions lors du rechargement de la page
$(document).ready(async function() {

    $('#edtForm').submit(function(event) {
        event.preventDefault();
        ajouterEdt("http://localhost/G_universite/public/Emploi_du_temps/editer_edt", "editer_edt");
    })

    // la recupeation des promotions de la filière selectionner après le rechargement
    infoFiliere = await infosFiliere($("#filiere").val());
    idPromotion = $("#promotions").data('id');
    promotionsFiliere(infoFiliere, idPromotion);
    idSemestre = $("#promotions option:selected").data("id");
    idModule = $("#modules").data("id");
    modulesSemestre(idSemestre, infoFiliere, idModule);
    infoModule($("#modules").val(), infoFiliere, false);
})


// Mettre un edt en model horizontal
$('#model-row').click(function() {
    $('#model-column').removeClass('border-primary');
    $(this).addClass("border-primary");
    $(this).css('transition', 'all 0.5s');
    const heuresModule = calculerHeuresModuleEdt();
    const model = $(this).data('model');
    const type = parseInt($('input[name="type"]:checked').val(), 10);
    genererEdt(heuresModule, model, type);


})

// Mettre un edt en model vertical
$('#model-column').click(function() {
    $('#model-row').removeClass('border-primary');
    $(this).addClass("border-primary");
    $(this).css('transition', 'all 0.5s');
    const heuresModule = calculerHeuresModuleEdt();
    const model = $(this).data('model');
    const type = parseInt($('input[name="type"]:checked').val(), 10);

    genererEdt(heuresModule, model, type);
})

// le changement du type de cours d'un edt
$('.type').click(function() {
    const heuresModule = calculerHeuresModuleEdt();
    const model = ($('#model-row').hasClass("border-primary")) ? $('#model-row').data('model') : $(
        '#model-column').data('model')
    const type = parseInt($('input[name="type"]:checked').val(), 10);
    genererEdt(heuresModule, model, type);
})

// Ajouter une ligne à un edt
document.getElementById('add-row').addEventListener('click', function() {
    $('#table-extended-chechbox tbody tr').each(function(index) {
        if (index == $('#table-extended-chechbox tbody tr').length - 1) {
            horaireDebut = $(this).find('.horaireFin').val()
        }
    })
    heure = horaireDebut.split(':');
    horaireFin = (parseInt(heure[0], 10) + 2) + ':' + heure[1];
    const type = parseInt($('input[name="type"]:checked').val(), 10);
    genererCoursEdt(typeEdt[type]);
    addHeure(horaireDebut, horaireFin, coursJour);

});

// Supprimer une ligne d'un edt
document.getElementById('remove-row').addEventListener('click', function() {
    removeHeure();
});
</script>