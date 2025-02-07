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
                                    <li class="breadcrumb-item"><a href="#">Gestion des salles</a>
                                    </li>
                                    <li class="breadcrumb-item active">Liste
                                    </li>

                                </ol>
                                <div class="ms-auto">
                                    <button type="button" class="btn btn-primary" style="float: right;" data-toggle="modal" data-target="#primary">
                                        <i class="bx bx-plus"></i>Salle
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
            <div class="row g-4">
                <!-- Navigation -->
                <div class="col-12 col-lg-3">
                    <div class="card card-animated-border-top  ">
                        <div class="card-body">
                            <div class="d-flex justify-content-between flex-column mb-3 mb-md-0">
                                <ul class="nav nav-align-left nav-pills flex-column">
                              
                                    <li class="nav-item mb-1">
                                        <a class="nav-link radius-10 " href="<?= ROOT ?>/Modules/listeModule">
                                        <i class="fa-solid fa-book-open-reader me-2"></i>
                                            <span class="align-middle">Modules</span>
                                        </a>
                                    </li>
                                    <li class="nav-item mb-1">
                                        <a class="nav-link" href="<?= ROOT ?>/Semestre/Liste">
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
                                        <a class="nav-link  active" href="<?= ROOT ?>/Salles/Liste">
                                        <i class="fa-solid fa-door-open me-2"></i>
                                            <span class="align-middle">Salles</span>
                                        </a>
                                    </li>
                                    <li class="nav-item mb-1">
                                        <a class="nav-link   " href="<?= ROOT ?>/Departements/listeDepartements">
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
                        <!-- Store Details Tab -->
                        <div class="tab-pane fade show active" id="store_details" role="tabpanel">
                        <?php $this->view("set_flash") ?>
                            <div class="row mb-4">

                                <div class="col-12 ">

                                    <div class="card card-animated-border-top ">
                                        <div class="card-body">

                                            <!-- partie liste de l'école -->
                                            <table class="table zero-configuration table-bordered" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th>Nom salle</th>
                                                        <th>Capacité salle</th>
                                                        <th class="text-center dt-no-sorting">Action</th>
                                                    </tr>
                                                </thead>
                                                <?php foreach ($datas  as $data): ?>
                                                <tbody>
                                                        <tr>
                                                            <td><?= $data->nom_salle ?></td>
                                                            <td><?= $data->capacite_salle ?></td>
                                                            <td class="text-center py-1">
                                                                <div class="dropdown">
                                                                    <span class="bx bx-dots-horizontal-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
                                                                    <div class="dropdown-menu dropdown-menu-right">
                                                                        <a class="dropdown-item edit-btn" data-toggle="modal" data-target="#default"
                                                                            href="#"
                                                                            data-id_salle=<?= $data->id_salle ?>
                                                                            data-nom_salle=<?= $data->nom_salle ?>
                                                                            data-capacite_salle=<?= $data->capacite_salle ?>
                                                                            ><i class="bx bx-edit-alt mr-1"></i>Modifier</a>
                                                                        <a class="dropdown-item" href="<?= ROOT ?>/Salles/supprimer/<?= $data->id_salle ?>"><i class="bx bx-trash mr-1"></i>Supprimer</a>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                 
                                                </tbody>
                                                <?php endforeach ?>
                                            </table>
                                            <!-- fin de la  partie liste de salle -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- partie insertion des données -->
                            <form action="" method="post">
                                <div class="modal-primary mr-1 mb-1 d-inline-block modal-lg">
                                    <div class="modal fade text-left" id="primary" tabindex="-1" role="dialog" aria-labelledby="myModalLabel160" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document" >
                                            <div class="modal-content">
                                                <div class="modal-header bg-primary">
                                                    <h5 class="modal-title white" id="myModalLabel160"> Enregistrements de Salle</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <i class="bx bx-x"></i>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col mb-6">
                                                            <label for="nameBasic" class="form-label">Nom salle<span class="text-danger fs-6">*</span></label>
                                                            <input type="text" id="nameBasic" value="" class="form-control" name="nom_salle" placeholder="Nom salle" required />
                                                        </div>
                                                        <div class="col mb-6">
                                                            <label for="nameBasic" class="form-label">Capacité salle<span class="text-danger fs-6">*</span></label>
                                                            <input type="number" id="nameBasic" value="" class="form-control" name="capacite_salle" placeholder="Capacité salle" required />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                                                        <i class="bx bx-x d-block d-sm-none"></i>
                                                        <span class="d-none d-sm-block">Annuler</span>
                                                    </button>
                                                    <button type="submit" class="btn btn-primary ml-1" name="envoie">
                                                        <i class="bx bx-check d-block d-sm-none"></i>
                                                        <span class="d-none d-sm-block">Enregistre</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <!-- fin insertion des données -->
                            <!-- modification -->
                            <form  method="post"  action="<?= ROOT ?>/Salles/update">
                                <div class="modal-primary mr-1 mb-1 d-inline-block" id="">
                                    <div class="modal fade text-left" id="default" tabindex="-1" role="dialog" aria-labelledby="myModalLabel160" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header bg-primary">
                                                    <h5 class="modal-title white" id="myModalLabel160"> Modification de Salle</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <i class="bx bx-x"></i>
                                                    </button>
                                                    <input type="hidden" id="inputIdSalle" value="" name="id_salle"/>
                                                    <!-- <input type="hidden" id="inputIdmodule" name="id_module"> -->
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col mb-6">
                                                            <label for="inputnomSalle" class="form-label">Nom Salle<span class="text-danger fs-6">*</span></label>
                                                            <input type="text" id="inputnomSalle" value="" class="form-control" name="nom_salle" placeholder="Nom Salle" required />
                                                        </div>
                                                        <div class="col mb-6">
                                                            <label for="inputsigleModule" class="form-label">Cycle <span class="text-danger fs-6">*</span></label>
                                                            <input type="text" id="inputcapaciteSalle" value="" class="form-control" name="capacite_salle" placeholder="Capacite Salle" required />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                                                        <i class="bx bx-x d-block d-sm-none"></i>
                                                        <span class="d-none d-sm-block">Annuler</span>
                                                    </button>
                                                    <button type="submit" class="btn btn-primary ml-1" name="modifier">
                                                        <i class="bx bx-check d-block d-sm-none"></i>
                                                        <span class="d-none d-sm-block">Modifier</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <!-- modification -->

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
 <!-- Ajoutez un input caché pour la baseURL -->
 <script src="<?= ROOT ?>/assets/mon_js/modification_salle.js"></script>



</body>
<!-- END: Body-->

</html>