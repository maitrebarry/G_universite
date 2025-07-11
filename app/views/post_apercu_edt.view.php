<?php $this->view("Partials/header") ?>
<style>
input {

    padding: 8px;
    font-size: 16px;
    text-align: center;
}

div {
    margin: 0;
    overflow: visible !important;
    padding: 0;
}

td {
    padding: 10px 5px !important;
}
</style>
<div class="card card-animated-border-top m-auto mt-0" id="edt" style="height:100%; overflow:visible"
    style="min-width:100%">
    <div class="card-header border-bottom-3 border-bottom-black  w-100 m-auto edt-header"
        style="min-width:100%; margin:0 !important">
        <h4 class="card-title text-bold-700  text-success d-flex justify-content-between align-items-center">
            <div class=" d-flex align-items-lg-center">
                <img src="<?= ROOT ?>/assets/images/logo.jpg" alt="" class=" img-thumbnail mr-1 d-block"
                    style="width: 100px;">
                <span>Instut Universitaire de
                    Formation Professionnel</span>
            </div>
            <span class=" d-block text-right text-dark">
                Formation Initiale
            </span>
        </h4>
        <h5 class="text-center text-uppercase">
            Edt du
            <span class=" h6 au "><?php echo $infosEdt->edt->date_debut ?> au
                <?php echo $infosEdt->edt->date_fin ?></span>
        </h5>
    </div>
    <div class="card-content w-100">
        <div class="card-body w-100">
            <div class="d-flex justify-content-between align-items-center w-100" style="width: 100%;">
                <div class="col-3">
                    <label class="form-label d-block text-center text-bold-600 mb-1" for="single-select">Filiere</label>
                    <div class="form-group">
                        <h6 class="text-center text-bold-500 text-body">
                            <?php echo strtoupper($infosEdt->promotion->sigle_filiere) ?>
                        </h6>
                    </div>
                </div>
                <div class="col-3 d-flex justify-content-around flex-column">
                    <label class="form-label d-block text-center text-bold-600 mb-1" for="anneeUniversitaire">Année
                        universitaire</label>
                    <div class="form-group">
                        <h6 class="text-center text-bold-500 text-body">
                            <?php echo $infosEdt->promotion->annee_universitaire ?>
                        </h6>
                    </div>
                </div>
                <div class="col-3">
                    <label class="form-label d-block text-center text-bold-600 mb-1" for="single-select">Niveau</label>
                    <div class="form-group">
                        <h6 class="text-center text-bold-500 text-body">
                            <?php echo strtoupper($infosEdt->promotion->sigle_semestre) ?>
                        </h6>
                    </div>
                </div>
                <div class="col-3">
                    <label class="form-label d-block text-center text-bold-600 mb-1" for="single-select">Salle de
                        Cours</label>
                    <div class="form-group">
                        <h6 class="text-center text-bold-500 text-body">
                            <?php echo strtoupper($infosEdt->edt->nom_salle) ?>
                        </h6>
                    </div>
                </div>
            </div>

            <div class=" w-100">
                <table id="table-extended-chechbox" class="table table-striped table-bordered table-responsive-md">
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
                            <td style='min-width: 160px !important;'>
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
                            <td style="font-size:13px">
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
                    <span class=" text-bold-700"><?php echo  strtoupper($infosEdt->module->sigle_module) ?></span>
                    <span> =
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
                                    <?php if(!empty(trim($enseignant->groupe))): ?>
                                    (
                                    <b> <?php echo $enseignant->groupe ?></b>
                                    )
                                    <?php endif ?>
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
                            style="min-width:100%">
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
                        <h6 class=" text-right" style="min-width:100%">Dr
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

<?php $this->view("Partials/footer") ?>