<?php $titre =
    'edt-' . $infosEdt->module->sigle_module . '-' . $infosEdt->promotion->sigle_filiere . '-' .
    $infosEdt->promotion->sigle_semestre .
    '-' . $infosEdt->promotion->annee_universitaire . '_'
    . strtoupper($infosEdt->edt->enseignant_nom);


?>
<style>
input {
    padding: 8px;
    font-size: 16px;
    text-align: center;
}

td {
    padding: 10px 5px !important;
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
    <div class="app-content content ">
        <div id="loader" class="w-100 position-absolute d-none justify-content-center align-items-center"
            style="height:100vh;z-index:100">
            <div class="spinner-border  " role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
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
                                    <li class="breadcrumb-item"><a href="#">Gestion EDT</a>
                                    </li>
                                    <li class="breadcrumb-item active">Liste
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-1 col-6  d-flex justify-content-end">
                    <a type="button" class=" btn btn-primary"
                        href="<?= ROOT ?>/Emploi_du_temps/editer_edt/<?php echo $infosEdt->edt->id_edt ?>"><i
                            class="bx bx-edit-alt mr-1"></i>
                        Editer</a>
                </div>
                <div class="mb-1 col-6 ">
                    <button type="button" class=" btn btn-primary" id="print" data-nom="<?php echo $titre ?>"><i
                            class=" bx bx-printer"></i>
                        Imprimer</button>
                </div>
            </div>
            <div class="content-body m-auto">
                <!-- formulaire -->
                <section class="simple-validation">
                    <div class="row">
                        <div id="message" class="col-12"></div>
                        <div class="col-12 w-100 m-auto">
                            <div class="card card-animated-border-top m-auto" id="edt">
                                <div class="card-header border-bottom-3 border-bottom-black  w-100 m-auto edt-header"
                                    style="min-width:100%">
                                    <h4 class="card-title text-bold-700  text-success d-flex justify-content-between align-items-center"
                                        style="min-width:100%">
                                        <div class=" d-flex align-items-lg-center">
                                            <img src="<?= ROOT ?>/assets/images/logo.jpg" alt=""
                                                class=" img-thumbnail mr-1 d-block" style="width: 100px;">
                                            <span>Instut Universitaire de
                                                Formation Professionnel</span>
                                        </div>
                                        <span class=" d-block text-right text-dark">
                                            Formation Initiale
                                        </span>
                                    </h4>
                                    <h5 class="text-center text-uppercase" style="min-width:100%">
                                        Edt du
                                        <span class=" h6 au "><?php echo $infosEdt->edt->date_debut ?> au
                                            <?php echo $infosEdt->edt->date_fin ?></span>
                                    </h5>
                                </div>
                                <div class="card-content w-100">
                                    <div class="card-body w-100">
                                        <div class="d-flex justify-content-between align-items-center w-100"
                                            style="width: 100%;">
                                            <div class="col-3">
                                                <label class="form-label d-block text-center text-bold-600 mb-1"
                                                    for="single-select">Filiere</label>
                                                <div class="form-group">
                                                    <h6 class="text-center text-bold-500 text-body">
                                                        <?php echo strtoupper($infosEdt->promotion->sigle_filiere) ?>
                                                    </h6>
                                                </div>
                                            </div>
                                            <div class="col-3 d-flex justify-content-around flex-column">
                                                <label class="form-label d-block text-center text-bold-600 mb-1"
                                                    for="anneeUniversitaire">Année
                                                    universitaire</label>
                                                <div class="form-group">
                                                    <h6 class="text-center text-bold-500 text-body">
                                                        <?php echo $infosEdt->promotion->annee_universitaire ?>
                                                    </h6>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <label class="form-label d-block text-center text-bold-600 mb-1"
                                                    for="single-select">Niveau</label>
                                                <div class="form-group">
                                                    <h6 class="text-center text-bold-500 text-body">
                                                        <?php echo strtoupper($infosEdt->promotion->sigle_semestre) ?>
                                                    </h6>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <label class="form-label d-block text-center text-bold-600 mb-1"
                                                    for="single-select">Salle de Cours</label>
                                                <div class="form-group">
                                                    <h6 class="text-center text-bold-500 text-body">
                                                        <?php if (trim(strtoupper($enseignants[0]->groupe)) == "GP" || trim(strtoupper($enseignants[0]->groupe)) == trim("GROUPE PRINCIPAL")): ?>

                                                        <?php echo strtoupper($enseignants[0]->nom_salle) ?>
                                                        <?php else : ?>
                                                        <?php foreach ($enseignants as $enseignant): ?>

                                                        <span class="mr-1">
                                                            <?php echo  $enseignant->groupe . "(" . strtoupper($enseignant->nom_salle) . ")" ?>
                                                        </span>
                                                        <?php endforeach ?>

                                                        <?php endif ?>
                                                    </h6>
                                                </div>
                                            </div>
                                        </div>

                                        <div class=" w-100">
                                            <table id="table-extended-chechbox"
                                                class="table table-striped table-bordered table-responsive w-100">
                                                <thead class="w-100">
                                                    <tr>
                                                        <th class="text-center">Horaire</th>
                                                        <?php foreach ($jours as $jour): ?>
                                                        <?php if (!empty(trim($jour->nom_jour))): ?>
                                                        <th class="jour" data-id="<?php echo $jour->id_jour ?>">
                                                            <?php echo strtoupper($jour->nom_jour) ?></th>
                                                        <?php endif ?>
                                                        <?php endforeach ?>

                                                    </tr>
                                                </thead>
                                                <tbody class="w-100">
                                                    <?php foreach ($horairesEdt as $horaire): ?>

                                                    <tr>
                                                        <td style='width:auto !important; min-width: 160px !important;'>
                                                            <div class='row m-auto'>
                                                                <div class='col-sm-6'>
                                                                    <h6><?php echo substr($horaire->heure_debut, 0, 5) ?>
                                                                    </h6>
                                                                </div>
                                                                <div class='col-sm-6'>
                                                                    <h6><?php echo substr($horaire->heure_fin, 0, 5) ?>
                                                                    </h6>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <?php foreach ($horaire->taches as $tache): ?>
                                                        <td style="font-size:14px">
                                                            <?php if (strtoupper($tache->type_tache) != "X"): ?>
                                                            <span class=" text-center d-block text-bold-6 00">
                                                                <?php echo (strlen($infosEdt->module->nom_module) < 20) ? strtoupper($infosEdt->module->nom_module) : strtoupper($infosEdt->module->sigle_module) ?>
                                                            </span>
                                                            <span style="font-size: 11px;"
                                                                class=" text-muted text-body text-center text-italic d-block">
                                                                <?php echo strtoupper($tache->type_tache) ?>
                                                            </span>
                                                            <?php endif ?>
                                                        </td>
                                                        <?php endforeach ?>
                                                    </tr>
                                                    <?php endforeach ?>
                                                </tbody>

                                            </table>
                                        </div>
                                        <?php if (strlen($infosEdt->module->nom_module) >= 20): ?>
                                        <div class=" mt-1">
                                            <h6>
                                                <span class=" text-bold-700 "
                                                    style="font-size: 14px;"><?php echo  strtoupper($infosEdt->module->sigle_module) ?></span>
                                                <span style="font-size: 14px;"> =
                                                    <?php echo strtoupper($infosEdt->module->nom_module) ?></span>
                                            </h6>
                                        </div>
                                        <?php endif ?>
                                        <div>
                                            <h6 class="text-bold-200">
                                                <div class="d-flex justify-content-arround mb-1">
                                                    <?php foreach ($enseignants as $enseignant): ?>
                                                    <span class=" text-capitalize d-block mr-1">

                                                        <?php echo $enseignant->enseignant_prenom . ' ' . $enseignant->enseignant_nom ?>
                                                        <?php if (!empty(trim($enseignant->groupe))): ?>
                                                        (
                                                        <b> <?php echo $enseignant->groupe ?></b>
                                                        )
                                                        <?php endif ?>
                                                    </span>
                                                    <?php endforeach ?>
                                                </div>
                                            </h6>
                                        </div>
                                        <div class=" text-right mr-2" style="min-width:100%">
                                            <div class="d-flex justify-content-end " style="min-width:100%">
                                                <h6 class=" text-muted text-right" style="min-width:100%">Segou, le
                                                    <?php echo date('d-m-Y') ?></h6>
                                            </div>
                                            <div class="" style="min-width:100%">
                                                <h6 class=" text-bold-600 text-center d-flex justify-content-end w-100"
                                                    style="min-width:100%; font-size: 14px !important">
                                                    Le
                                                    Chef de DER
                                                    <?php echo (isset($_SESSION['sigle_departement'])) ? strtoupper($_SESSION['sigle_departement']) : "" ?>
                                                </h6>
                                                <h6 class=" d-flex text-center justify-content-end w-100"
                                                    style="min-width:100%">
                                                    <img src="<?= ROOT ?><?= $_SESSION['signature'] ?>" alt="user image"
                                                        class="d-block rounded  "
                                                        style="width: 150px; max-height: 60px;" />
                                                </h6>
                                            </div>
                                            <div class=" d-flex justify-content-end" style="min-width:100%">
                                                <h6 class=" text-right"
                                                    style="min-width:100%; font-size: 14px !important">Dr
                                                    <?php echo (isset($_SESSION['nom_prenom'])) ? strtoupper($_SESSION['nom_prenom']) : "" ?>
                                                    <br>
                                                    <i style="font-size:11px">
                                                        <?php echo (isset($_SESSION['nom_grade'])) ? strtoupper($_SESSION['nom_grade']) : "" ?>
                                                    </i>
                                                </h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- formulaire -->
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
$('#print').click(function() {
    window.scrollTo({
        top: 0,
        behavior: "smooth",
    });
    const nomEdt = $(this).data('nom');
    setTimeout(function() {
        imprimer(nomEdt);
    }, 500)
})
</script>