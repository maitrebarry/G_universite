<!-- inclusion du partie header -->

<?php
$this->view("Partials/header") ?>

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
                                    <li class="breadcrumb-item"><a href="#">Gestion des periode</a>
                                    </li>
                                    <li class="breadcrumb-item active">Liste
                                    </li>

                                </ol>

                                <div class="ms-auto">
                                    <button type="button" class="btn btn-primary" style="float: right;" data-toggle="modal" data-target="#primary">
                                        <i class="bx bx-plus"></i>Période
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
                    <div class="card card-animated-border-top ">
                        <div class="card-body">
                            <div class="d-flex justify-content-between flex-column mb-3 mb-md-0">
                                <ul class="nav nav-align-left nav-pills flex-column">
                                    <li class="nav-item mb-1">
                                        <a class="nav-link  " href="<?= ROOT ?>/Modules/listeModule">

                                            <span class="align-middle">Modules</span>
                                        </a>
                                    </li>
                                    <li class="nav-item mb-1">
                                        <a class="nav-link " href="<?= ROOT ?>/Semestres/Liste">
                                            <i class="fa-solid fa-user me-2"></i>
                                            <span class="align-middle">Semestre</span>
                                        </a>
                                    </li>
                                    <li class="nav-item mb-1">
                                        <a class="nav-link  active" href="<?= ROOT ?>/Periodes/Liste">
                                            <i class="fa-solid fa-calendar me-2"></i>
                                            <span class="align-middle">Periode</span>
                                        </a>
                                    </li>
                                    <li class="nav-item mb-1">
                                        <a class="nav-link " href="<?= ROOT ?>/Utilisateurs/liste_utilisateur">
                                            <i class="fa-solid fa-calendar me-2"></i>
                                            <span class="align-middle">Utilisateur</span>
                                        </a>
                                    </li>
                                    <li class="nav-item mb-1">
                                        <a class="nav-link " href="<?= ROOT ?>/Salles/Liste">
                                            <i class="fa-solid fa-calendar me-2"></i>
                                            <span class="align-middle">Salles</span>
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
                                                       
                                                        <th>Date de début</th>
                                                        <th>Date de fin</th>
                                                        <th>Status</th>
                                                        <th class="text-center dt-no-sorting">Action</th>
                                                    </tr>
                                                </thead>
                                                <?php foreach ($datas  as $data): ?>
                                                    <tbody>
                                                        <tr>
                                                          
                                                          
                                                            <td><?= date_format(date_create($data->date_debut), 'd-m-Y'); ?></td>
                                                            <td><?= date_format(date_create($data->date_fin), 'd-m-Y'); ?></td>
                                                            <td class="text-primary"><span class=" badge badge-light-primary"><?= $data->status ?></span></td>
                                                            <td class="text-center ">
                                                                <div class=" dropdown">
                                                                    <span class="bx bx-dots-horizontal-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu">
                                                                    </span>
                                                                    <div class="dropdown-menu dropdown-menu-right">
                                                                        <a class="dropdown-item edit-btn" data-toggle="modal" data-target="#default"
                                                                            href="#"
                                                                            data-id="<?= $data->id_anne ?>"
                                                                            data-anne_scolaire="<?= $data->anne_scolaire ?>"
                                                                            data-date_debut="<?= $data->date_debut ?>"
                                                                            data-date_fin="<?= $data->date_fin ?>"><i class="bx bx-edit-alt mr-1"></i> edit</a>
                                                                        <a class="dropdown-item" href="<?= ROOT ?>/Annees_universites/supprimer/<?= $data->id_anne ?>"><i class="bx bx-trash mr-1"></i> delete</a>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>

                                                    </tbody>
                                                <?php endforeach ?>
                                            </table>
                                            <!-- fin de la  partie liste de l'école -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <form action="" method="post">
                                <!-- partie insertion des données -->
                                <div class="modal-primary mr-1 mb-1 d-inline-block">
                                    <div class="modal fade text-left" id="primary" tabindex="-1" role="dialog" aria-labelledby="myModalLabel160" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header bg-primary">
                                                    <h5 class="modal-title white" id="myModalLabel160"> Enregistrements de année universitaire</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <i class="bx bx-x"></i>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <!-- <div class="row">
                                                        <div class="col mb-3">
                                                            <label for="nameBasic" class="form-label">Années Scolaires <span class="text-danger fs-6">*</span></label>
                                                            <input type="text" id="nameBasic" value="" name="anne_scolaire" class="form-control" placeholder="Années Scolaires" />
                                                        </div>
                                                    </div> -->
                                                    <div class="row g-2">
                                                        <div class="col mb-0">
                                                            <label for="dateDebut" class="form-label">Date de début</label>
                                                            <input type="date" id="dateDebut" name="date_debut" class="form-control" placeholder="Date de début" />
                                                        </div>
                                                        <div class="col mb-0">
                                                            <label for="dateFin" class="form-label">Date de fin</label>
                                                            <input type="date" id="dateFin" name="date_fin" class="form-control" placeholder="Date de fin" readonly />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                                                        <i class="bx bx-x d-block d-sm-none"></i>
                                                        <span class="d-none d-sm-block">Annuler</span>
                                                    </button>
                                                    <button type="submit" name="submit" class="btn btn-primary ml-1">
                                                        <i class="bx bx-check d-block d-sm-none"></i>
                                                        <span class="d-none d-sm-block">Accept</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <!-- fin insertion des données -->

                            <!-- partie modification des données -->
                            <div class="modal-primary mr-1 mb-1 d-inline-block">
                                <div class="modal fade text-left" id="default" tabindex="-1" aria-labelledby="defaultLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary">
                                                <h5 class="modal-title white" id="defaultLabel">Modification de l'année</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <i class="bx bx-x"></i>
                                                </button>
                                            </div>
                                            <form method="post" action="<?= ROOT ?>/Annees_universites/update">
                                                <div class="modal-body">
                                                    <input type="hidden" id="inputId_anne" name="id_anne">
                                                    <div class="form-group">
                                                        <label for="inputAnne_scolaire">Anne scolaire</label>
                                                        <input type="text" class="form-control" id="inputAnne_scolaire" name="anne_scolaire" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="inputDate_debut">Date debut</label>
                                                        <input type="text" class="form-control" id="inputDate_debut" name="date_debut" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="inputDate_fin">Date Fin</label>
                                                        <input type="text" class="form-control" id="inputDate_fin" name="date_fin" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                                                        <i class="bx bx-x d-block d-sm-none"></i>
                                                        <span class="d-none d-sm-block">Annuler</span>
                                                    </button>
                                                    <button type="submit" class="btn btn-primary" name="modifier">Modifier</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
    <script src="<?= ROOT ?>/assets/mon_js/modification_anne.js"></script>

    <script>
        document.getElementById('dateDebut').addEventListener('change', function() {
            const dateDebut = new Date(this.value);
            if (!isNaN(dateDebut)) { // Vérifie si la date est valide
                const dateFin = new Date(dateDebut);
                dateFin.setMonth(dateFin.getMonth() + 6); // Ajoute 6 mois
                const isoDate = dateFin.toISOString().split('T')[0]; // Format ISO (YYYY-MM-DD)
                document.getElementById('dateFin').value = isoDate;
            }
        });
    </script>
    <!-- inclusion du partie footer fin-->
</body>
<!-- END: Body-->

</html>