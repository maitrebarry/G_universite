<style>
input {

    padding: 8px;
    font-size: 16px;
    text-align: center;
}

td {
    padding: 15px 5px !important;
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
                            <h5 class="content-header-title float-left pr-1 mb-0">programmation des cours</h5>
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
                                    <h4 class="card-title text-center">Création d'emploi du temps</h4>
                                </div>
                                <div class="card-content">
                                    <div class="card-body">
                                        <form method="POST" class="form-horizontal" novalidate id="edtForm">
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <label class="form-label" for="single-select">Filiere</label>
                                                    <div class="form-group">
                                                        <select class="select2 form-control" id="filiere">
                                                            <option value="0" disabled selected>Selectionner une Filiere
                                                            </option>
                                                            <?php foreach ($filieres as $filiere): ?>
                                                            <option value="<?php echo $filiere->id_filiere ?>"
                                                                <?= ($idFiliere != null && $idFiliere == $filiere->id_filiere) ? 'selected' : '' ?>>
                                                                <?php echo strtoupper($filiere->sigle_filiere) ?>
                                                            </option>
                                                            <?php endforeach ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <label class="form-label" for="single-select">Promotion</label>
                                                    <div class="form-group">
                                                        <select class="select2 form-control" id="promotions">
                                                            <option value="" disabled selected>Selectioner une Promotion
                                                            </option>

                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <label class="form-label" for="single-select">Modules</label>
                                                    <div class="form-group">
                                                        <select class="select2 form-control" id="modules"
                                                            name="modules">
                                                            <option value="" disabled selected>Selectioner un Module
                                                            </option>

                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row d-flex justify-content-between align-items-center p-1 ">
                                                <div
                                                    class="col-12 row d-flex justify-content-between align-items-center">
                                                    <div class=" col-4 m-0">
                                                        <!-- Bouton pour ajouter une nouvelle ligne -->
                                                        <i class="bx bx-plus btn btn-secondary" id="add-row"></i>
                                                        <!-- Bouton pour supprimer la dernière ligne -->
                                                        <i class="bx bx-minus btn btn-danger" id="remove-row"></i>
                                                    </div>
                                                    <div class="col-2">
                                                        <button type="button" class="btn btn-primary"
                                                            data-toggle="modal" data-target="#menuConfig"><i
                                                                class="bx bxs-cog"></i>
                                                            Paramètrage</button>
                                                    </div>
                                                </div>
                                                <div class=" offset-6 col-6 row d-none float-right" id="infoModule">
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
                                                    <tbody>

                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="row d-flex justify-content-between">
                                                <div class="col-sm-3">
                                                    <label class="form-label" for="enseignants">ENSEIGNANT :</label>
                                                    <div class="form-group">
                                                        <select class="select2 form-control" id="enseignants"
                                                            name="enseignants">
                                                            <option value="" disabled selected>Sélectionner un
                                                                enseignant</option>
                                                            <?php foreach ($enseignants as $enseignant): ?>
                                                            <option value="<?php echo $enseignant->enseignant_id ?>">
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
                                                        <select class="select2 form-control" name="salles" id="salles">
                                                            <option value="" disabled selected>Selectionner une Salle
                                                            </option>
                                                            <?php foreach ($salles as $salle): ?>
                                                            <option value="<?php echo $salle->id_salle ?>">
                                                                <?php echo strtoupper($salle->nom_salle) . "(" . $salle->capacite_salle . ")" ?>
                                                            </option>
                                                            <?php endforeach ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3 ">
                                                    <label class="form-label" for="dateDebut">Date de Debut :</label>
                                                    <div class="form-group w-100 d-flex justify-content-end ">
                                                        <input type="date" class="form-control" name="dateDebut"
                                                            id="dateDebut" value="<?php echo date("d/m/Y") ?>">
                                                    </div>
                                                </div>

                                            </div>
                                            <button type="submit" style="float: right;"
                                                class="btn btn-primary">Enregistrer</button><br>
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

        <div class="modal-primary mr-1 mb-1 d-inline-block">
            <div class="modal fade text-left" id="menuConfig" tabindex="-1" role="dialog"
                aria-labelledby="myModalLabel160" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                    <div class="modal-content">

                        <div class="modal-body">
                            <div class="row">
                                <div class=" col-12 row">
                                    <h6 class=" col-12 text-center">Models Edt</h6>
                                    <div class="col-12 border p-2 d-flex justify-content-center">

                                        <div style="width: 200px" class="cursor-pointer">
                                            <span class=" text-center">Horizontal</span>
                                            <img class="img img-thumbnail d-block"
                                                src="<?= ROOT ?>/assets/images/model-row.png" alt="model-row">
                                        </div>

                                        <div style=" width:200px; " class="ml-2 cursor-pointer">
                                            <span class=" text-center">Vertical</span>
                                            <img class="img img-thumbnail d-block"
                                                src="<?= ROOT ?>/assets/images/model-column.png" alt="model-column">
                                        </div>
                                    </div>
                                </div>
                                <div class=" col-12 row">
                                    <h6 class=" col-12 text-center">Type de Cours</h6>
                                    <div class="col-12 border p-2 d-flex justify-content-center">

                                        <div class="checkbox mr-4"> <input type="checkbox" name="" id="cm"
                                                class=" checkbox__input" checked>
                                            <label for="cm"></label>CM
                                        </div>

                                        <div class="checkbox mr-4"> <input type="checkbox" name="" id="td"
                                                class=" checkbox__input">
                                            <label for="td"></label>TD
                                        </div>

                                        <div class="checkbox mr-4"> <input type="checkbox" name="" id="tp"
                                                class=" checkbox__input">
                                            <label for="tp"></label>TP
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn  btn-link" data-dismiss="modal">
                                <i class="bx bx-x d-block d-sm-none"></i>
                                <span class="d-none d-sm-block">Annuler</span>
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
<script>
var infoFiliere = [];
$("#filiere").change(async function() {

    infoFiliere = await infosFiliere($(this).val());
    promotionsFiliere(infoFiliere);
    idSemestre = $("#promotions option:selected").data("id");
    modulesSemestre(idSemestre, infoFiliere);
    infoModule($("#infoModule").val(), infoFiliere);


})

$("#promotions").change(function() {
    idSemestre = $("#promotions option:selected").data("id");
    modulesSemestre(idSemestre, infoFiliere);
    infoModule($("#infoModule").val(), infoFiliere);
})


$("#modules").change(function() {
    infoModule($(this).val(), infoFiliere);

})

// JavaScript pour ajouter une ligne à la table
document.getElementById('add-row').addEventListener('click', function() {
    addHeure();
});

// JavaScript pour supprimer la dernière ligne de la table
document.getElementById('remove-row').addEventListener('click', function() {
    removeHeure();
});

$(document).ready(async function() {

    $('#edtForm').submit(function(event) {
        event.preventDefault();
        ajouterEdt();

    })

    infoFiliere = await infosFiliere($("#filiere").val());
    promotionsFiliere(infoFiliere);
    idSemestre = $("#promotions option:selected").data("id");
    modulesSemestre(idSemestre, infoFiliere);
    infoModule($("#infoModule").val(), infoFiliere);
})
</script>