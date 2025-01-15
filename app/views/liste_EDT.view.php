<!-- inclusion du partie header -->
<?php $this->view("Partials/header") ?>

<body
    class="vertical-layout vertical-menu-modern boxicon-layout no-card-shadow 2-columns  navbar-sticky footer-static  "
    data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">

    <!-- inclusion du partie header -->
    <?php $this->view("Partials/navbar") ?>
    <!-- inclusion du partie header fin-->

    <!-- inclusion du partie seibar-->
    <?php $this->view("Partials/seibar") ?>
    <!-- inclusion du partie seibar fin-->

    <!--  Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
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
                                    <li class="breadcrumb-item"><a href="#">Gestion EDT</a>
                                    </li>
                                    <li class="breadcrumb-item active">Liste
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <!-- formulaire -->
                <section id="basic-datatable">

                    <div class="row">
                        <div class="col-12">
                            <div class="card card-animated-border-top ">

                                <div class="card-content row">
                                    <div class="col-12 row mt-1 px-2 ">

                                        <div
                                            class="card-body card-dashboard col-12 row d-flex justify-content-around align-items-center">
                                            <div
                                                class="col-12 col-md-10 row d-flex justify-content-start align-items-center mb-1 mb-md-0">
                                                <div class="col-1">
                                                    <i class="bx bx-dialpad text-primary"></i>
                                                </div>
                                                <div class="col-3 ">
                                                    <select class="select2 form-control text-center" id="filieres">
                                                        <option value="0" disabled selected>Filieres</option>
                                                        <?php foreach ($filieres as $filiere): ?>
                                                            <option value="<?php echo $filiere->id_filiere ?>">
                                                                <?php echo strtoupper($filiere->sigle_filiere) ?>
                                                            </option>
                                                        <?php endforeach ?>
                                                    </select>
                                                </div>
                                                <div class="col-4 col-md-3">
                                                    <select class="select2 form-control text-center" id="semestres">
                                                        <option value="0" disabled selected>Semestres</option>
                                                    </select>
                                                </div>
                                                <div class="col-4 col-md-3">
                                                    <select class="select2 form-control text-center"
                                                        id="anneeUniversitaires">
                                                        <option value="0" disabled selected>Annee universitaire</option>
                                                        <?php foreach ($filieres as $filiere): ?>
                                                            <option value="<?php echo $filiere->id_filiere ?>">
                                                                <?php echo strtoupper($filiere->nom_filiere . '(' . $filiere->sigle_filiere . ')') ?>
                                                            </option>
                                                        <?php endforeach ?>
                                                    </select>
                                                </div>


                                            </div>
                                            <a href="<?= ROOT ?>/Emploi_du_temps/ajouter_EDT" class="col-4 col-md-2">
                                                <button class="btn btn-primary" style="float:right;">
                                                    <i class="bx bx-plus"></i>&nbsp; Nouveau
                                                </button>
                                            </a>
                                        </div>


                                        <div class="table-responsive col-12">
                                            <table class="table zero-configuration">
                                                <thead>
                                                    <tr>
                                                        <th>Filière</th>
                                                        <th>Promotion</th>
                                                        <th>Module</th>
                                                        <th>Professeur</th>
                                                        <th>Salle</th>
                                                        <th>Date Debut</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($edts as $edt): ?>
                                                        <?php
                                                        $edtInfo = $edt->edt;
                                                        $promotion = $edt->promotion;
                                                        $module = $edt->module;
                                                        ?>
                                                        <tr style="font-size: 13px;">
                                                            <td class="h6 d-flex" style="font-weight: bolder;">
                                                                <?php if ($edtInfo->statut == 0): ?>
                                                                    <div class="badge badge-warning badge-icon">
                                                                        <span>x</span>
                                                                    </div>
                                                                <?php endif ?>
                                                                <?php if ($edtInfo->statut == 1): ?>
                                                                    <div class="badge badge-success badge-icon">
                                                                        <span>v</span>
                                                                    </div>
                                                                <?php endif ?>
                                                                <span
                                                                    class="px-1"><?php echo strtoupper($promotion->sigle_filiere) ?></span>
                                                            </td>
                                                            <td>
                                                                <?php echo strtoupper($promotion->sigle_filiere . '-' . $promotion->sigle_semestre . '( ' . $promotion->annee_universitaire . ' )') ?>
                                                            </td>
                                                            <td>
                                                                <?php echo strtoupper($module->nom_module) ?>
                                                            </td>
                                                            <td class="h6 text-bold-700 text-italic"
                                                                style="font-size: 13px;">
                                                                <?php echo strtoupper($edtInfo->enseignant_prenom . ' ' . $edtInfo->enseignant_nom) ?>
                                                            </td>

                                                            <td>
                                                                <?php echo strtoupper($edtInfo->nom_salle) ?>
                                                            </td>
                                                            <td>
                                                                <div class="badge badge-light-primary mr-1 mb-1">
                                                                    <?php echo strtoupper($edtInfo->date_debut) ?>
                                                                </div>
                                                            </td>
                                                            <td class="text-center dt-no-sorting">
                                                                <div class="dropdown">
                                                                    <span
                                                                        class="bx bx-dots-horizontal-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                                                        data-toggle="dropdown" aria-haspopup="true"
                                                                        aria-expanded="false" role="menu">
                                                                    </span>
                                                                    <div class="dropdown-menu dropdown-menu-right">
                                                                        <a class="dropdown-item"
                                                                            href="<?= ROOT ?>/Emploi_du_temps/apercu_EDT/<?php echo $edtInfo->id_edt ?>">
                                                                            <i class="bx bx-edit-alt mr-1"></i> Aperçu
                                                                        </a>
                                                                        <a class="dropdown-item" href="#"><i
                                                                                class="bx bx-edit-alt mr-1"></i> Editer</a>
                                                                        <a class="dropdown-item" href="#"><i
                                                                                class="bx bx-trash mr-1"></i> Supprimer</a>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach ?>
                                                </tbody>

                                            </table>
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
<script src="<?= ROOT ?>/assets/mon_js/edt.js"></script>
<script>
    var infoFiliere = [];
    $("#filieres").change(async function() {

        infoFiliere = await infosFiliere($(this).val());
        semestresFiliere(infoFiliere);

    })
</script>

</html>