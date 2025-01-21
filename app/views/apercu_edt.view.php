<?php $titre =  strtoupper(
    'edt-' . $infosEdt->promotion->sigle_filiere . '-' .
        $infosEdt->promotion->sigle_semestre .
        '-' . $infosEdt->promotion->annee_universitaire . '_'
        . $infosEdt->edt->date_debut . '_' .
        $infosEdt->edt->date_fin
)
?>
<style>
/* @media print {
    body * {
        visibility: hidden;
    }

    #edt,
    #edt * {
        visibility: visible;
    }

    #edt {

        position: absolute;
        left: 0;
        top: 0;
    }
} */

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
    <div class="app-content content ">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-12 mb-2 mt-1">
                    <div class="row breadcrumbs-top d-flex justify-content-between align-items-center">
                        <div class="col-10">
                            <h5 class="content-header-title float-left pr-1 mb-0">programmation des cours</h5>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb p-0 mb-0">
                                    <li class="breadcrumb-item"><a href="index.html"><i class="bx bx-home-alt"></i></a>
                                    </li>
                                    <li class="breadcrumb-item"><a
                                            href="<?php echo ROOT . '/Emploi_du_temps/' ?>">Gestion EDT</a>
                                    </li>
                                    <li class="breadcrumb-item active">Aperçu
                                    </li>
                                </ol>
                            </div>
                        </div>
                        <div class="col-2">
                            <button type="button" class=" btn btn-primary"
                                onclick="imprimerEdt('<?php echo $titre ?>')"><i class=" bx bx-printer"></i>
                                Imprimer</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body m-auto">
                <!-- formulaire -->
                <section class="simple-validation">
                    <div class="row">
                        <div id="message" class="col-12"></div>
                        <div class="col-12 w-100 m-auto">
                            <div class="card card-animated-border-top m-auto" id="edt">
                                <div class="card-header border-bottom-3 border-bottom-black mb-1 w-100 m-auto edt-header"
                                    style="min-width:75vw">
                                    <h4 class="card-title text-bold-700 mb-1 text-success d-flex justify-content-between align-items-center"
                                        style="min-width:75vw">
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
                                    <h5 class="text-center" style="min-width:75vw">
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
                                                        <?php echo strtoupper($infosEdt->edt->nom_salle) ?>
                                                    </h6>
                                                </div>
                                            </div>
                                        </div>

                                        <div class=" w-100">
                                            <table id="table-extended-chechbox"
                                                class="table table-striped table-bordered">
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
                                                    <?php foreach ($horairesEdt as $horaire): ?>
                                                    <tr>
                                                        <td>
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
                                                        <td>
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
                                                <?php if (strlen($infosEdt->module->nom_module) >= 20): ?>
                                                <caption class=" mt-1">
                                                    <h6>
                                                        <span
                                                            class=" text-bold-700"><?php echo  strtoupper($infosEdt->module->sigle_module) ?></span>
                                                        <span> =
                                                            <?php echo strtoupper($infosEdt->module->nom_module) ?></span>
                                                    </h6>
                                                </caption>
                                                <?php endif ?>
                                            </table>
                                        </div>

                                        <div>
                                            <h6 class="text-bold-600">
                                                <span><?php echo $infosEdt->promotion->sigle_filiere ?> - </span>
                                                <span>
                                                    <?php echo $infosEdt->edt->enseignant_prenom . ' ' . $infosEdt->edt->enseignant_nom ?>
                                                </span>
                                            </h6>
                                        </div>
                                        <div class=" mt-4 text-right mr-2" style="min-width:75vw">
                                            <div class=" text-center d-flex justify-content-end " style="
                                                min-width:75vw">
                                                <h6 class=" text-muted text-right" style="min-width:75vw">Segou, le
                                                    <?php echo $infosEdt->edt->date_creation ?></h6>
                                            </div>
                                            <div class="my-1" style="min-width:75vw">
                                                <h6 class=" text-bold-600 text-center d-flex justify-content-end w-100"
                                                    style="min-width:75vw">
                                                    Le
                                                    Chef de DER ST</h6>
                                                <h6 class="mt-1 d-flex text-center justify-content-end w-100"
                                                    style="min-width:75vw">
                                                    Signature
                                                </h6>
                                            </div>
                                            <div class=" d-flex justify-content-end" style="min-width:75vw">
                                                <h6 class=" text-right" style="min-width:75vw">Dr Amadou K dit Amadou Le
                                                    Grand <br> Maître
                                                    Assistant </h6>
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

</script>