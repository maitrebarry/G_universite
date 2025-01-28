<!-- inclusion du partie header -->
<?php $this->view("Partials/header") ?>

<body class="vertical-layout vertical-menu-modern boxicon-layout no-card-shadow 2-columns  navbar-sticky footer-static  " data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">
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
                            <h5 class="content-header-title float-left pr-1 mb-0">Configuration</h5>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb p-0 mb-0">
                                    <li class="breadcrumb-item"><a href="index.html"><i class="bx bx-home-alt"></i></a>
                                    </li>
                                    <li class="breadcrumb-item"><a href="#">Gestion des departements</a>
                                    </li>
                                    <li class="breadcrumb-item active">Liste
                                    </li>
                                </ol>
                                <div class="ms-auto">
                                    <button type="button" class="btn btn-primary" style="float: right;" data-toggle="modal" data-target="#primary">
                                        <i class="bx bx-plus"></i>Departements
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
            <div class="row g-4">
                <?php $this->view("set_flash") ?>
                <!-- Navigation -->
                <div class="col-12 col-lg-3">
                    <div class="card card-animated-border-top ">
                        <div class="card-body">
                            <div class="d-flex justify-content-between flex-column mb-3 mb-md-0">
                                <ul class="nav nav-align-left nav-pills flex-column">
                               
                                    <li class="nav-item mb-1">
                                        <a class="nav-link   " href="<?= ROOT ?>/Modules/listeModule">
                                        <i class="fa-solid fa-book-open-reader me-2"></i>
                                            <span class="align-middle">Modules</span>
                                        </a>
                                    </li>
                                    
                                    <li class="nav-item mb-1">
                                        <a class="nav-link " href="<?= ROOT ?>/Semestres/Liste">
                                        <i class="fa-solid fa-calendar-day me-2"></i>
                                            <span class="align-middle">Semestre</span>
                                        </a>
                                    </li>
                                    <li class="nav-item mb-1">
                                        <a class="nav-link " href="<?= ROOT ?>/Periodes/Liste">
                                        <i class="fa-solid fa-calendar-alt me-2"></i>
                                            <span class="align-middle">Période</span>
                                        </a>
                                    </li>
                                    <li class="nav-item mb-1">
                                        <a class="nav-link " href="<?= ROOT ?>/Utilisateurs/liste_utilisateur">
                                        <i class="fa-solid fa-users me-2"></i>
                                            <span class="align-middle">Utilisateur</span>
                                        </a>
                                    </li>
                                    <li class="nav-item mb-1">
                                        <a class="nav-link " href="<?= ROOT ?>/Salles/Liste">
                                        <i class="fa-solid fa-door-open me-2"></i>
                                            <span class="align-middle">Salles</span>
                                        </a>
                                    </li>
                                    <li class="nav-item mb-1">
                                        <a class="nav-link active   " href="<?= ROOT ?>/Departements/listeDepartements">
                                        <i class="fa-solid fa-building me-2"></i>
                                            <span class="align-middle">departements</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Navigation -->
                <!-- Options -->
                <div class="col-12 col-lg-9 pt-4 pt-lg-0">
                    <div class="tab-content p-0">
                        <div class="tab-pane fade show active" id="store_details" role="tabpanel">

                            <div class="row mb-4">

                                <div class="col-12 ">

                                    <div class="card card-animated-border-top ">
                                        <div class="card-body">

                                            <!-- partie liste de modules -->
                                            <table class="table zero-configuration table-bordered" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th>Nom departements </th>
                                                        <th>code departement</th>
                                                        <th class="text-center dt-no-sorting">Action</th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    <?php foreach ($liste as $listes): ?>
                                                        <tr>
                                                            <td><?= $listes->nom_departement ?></td>
                                                            <td><?= $listes->sigle_departement ?></td>
                                                            <td class="text-center ">
                                                                <div class=" dropdown">
                                                                    <span class="bx bx-dots-horizontal-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu">
                                                                    </span>
                                                                    <div class="dropdown-menu dropdown-menu-right">
                                                                        <a class="dropdown-item edit-btn" data-toggle="modal" data-target="#default"
                                                                            href="#"
                                                                            data-id="<?= $listes->id_departement ?>"
                                                                            data-nom_departement="<?= $listes->nom_departement ?>"
                                                                            data-sigle_departement="<?= $listes->sigle_departement  ?>"><i class="bx bx-edit-alt mr-1"></i> edit</a>
                                                                        <a class="dropdown-item" href="<?= ROOT ?>/Departements/delete/<?= $listes->id_departement ?>"><i class="bx bx-trash mr-1"></i> delete</a>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach ?>
                                                </tbody>
                                            </table>
                                            <!-- fin de la  partie liste de modules -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- partie insertion des données -->
                            <div class="modal-primary mr-1 mb-1 d-inline-block modal-lg">
                                <div class="modal fade text-left" id="primary" tabindex="-1" role="dialog" aria-labelledby="myModalLabel160" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary">
                                                <h5 class="modal-title white" id="myModalLabel160"> Enregistrements de departement</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <i class="bx bx-x"></i>
                                                </button>
                                            </div>
                                            <form action="" method="post">
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col mb-6">
                                                            <label for="nameBasic" class="form-label">Nom departements <span class="text-danger fs-6">*</span></label>
                                                            <input type="text" id="nameBasic" value="" class="form-control" name="nom_departement" placeholder="Nom departements" required />
                                                        </div>
                                                        <div class="col mb-6">
                                                            <label for="nameBasic" class="form-label">code departement <span class="text-danger fs-6">*</span></label>
                                                            <input type="text" id="nameBasic" value="" class="form-control" name="sigle_departement" placeholder="code departement" required />
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                                                        <i class="bx bx-x d-block d-sm-none"></i>
                                                        <span class="d-none d-sm-block">Annuler</span>
                                                    </button>
                                                    <button type="submit" class="btn btn-primary ml-1 d-none d-sm-block" name="envoyer">
                                                        <i class="bx bx-check d-block d-sm-none"></i>
                                                        Enregistrer
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- fin insertion des données -->
                            <!-- partie modification des données -->
                            <div class="modal-primary mr-1 mb-1 d-inline-block">
                                <div class="modal fade text-left" id="default" tabindex="-1" aria-labelledby="myModalLabel160" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary">
                                                <h5 class="modal-title white" id="myModalLabel160">Modifier le Module</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form method="post" action="<?= ROOT ?>/Modules/editFiliere">
                                                <div class="modal-body">
                                                    <input type="hidden" id="inputIdmodule" name="id_departement">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="inputnomModule">Nom du Module</label>
                                                                <input type="text" class="form-control" id="inputnomModule" name="nom_departement" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="inputsigleModule">Code du Module</label>
                                                                <input type="text" class="form-control" id="inputsigleModule" name="sigle_departement" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                                                            <i class="bx bx-x d-block d-sm-none"></i>
                                                            <span class="d-none d-sm-block">Annuler</span>
                                                        </button>
                                                        <button type="submit" class="btn btn-primary" name="editmodule">Modifier</button>
                                                    </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- partie fin modification des données -->

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
    <script src="<?= ROOT ?>/assets/mon_js/modification_module.js"></script>
</body>
<!-- END: Body-->

</html>