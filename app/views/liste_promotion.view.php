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

                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb p-0 mb-0">
                                    <li class="breadcrumb-item"><a href="index.html"><i class="bx bx-home-alt"></i></a>
                                    </li>
                                    <li class="breadcrumb-item"><a href="<?= ROOT ?>/Filieres/">Gestion Filière</a>
                                    </li>
                                    <li class="breadcrumb-item active">Liste Promotions
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <!-- formulaire -->
                <section id="table-chechbox">

                    <div class="row">
                        <div>
                            <?php $this->view("set_flash"); ?>
                        </div>
                        <div class="col-12">
                            <div class="card">
                                <div class="card-content">

                                    <div class="d-flex justify-content-between align-items-center p-1 m-0">
                                        <div>
                                            <h6>
                                                <?php echo strtoupper($promotions[0]->nom_filiere) ?>
                                            </h6>
                                        </div>
                                        <a href="<?= ROOT ?>/Filieres/ajouter_Filiere">
                                            <button class="btn btn-primary" style="float:right;"><i
                                                    class="bx bx-plus"></i>&nbsp; Promotion
                                            </button>
                                        </a>

                                    </div>

                                    <div class="card-body card-dashboard mt-0">


                                        <div class="table-responsive">
                                            <table class="table zero-configuration table-bordered" style="width:100%">
                                                <thead>
                                                    <tr>

                                                        <th>Promotion</th>
                                                        <th>Semestre</th>
                                                        <th>Statut</th>
                                                        <th class="text-center dt-no-sorting">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($promotions as $promotion): ?>
                                                    <tr>
                                                        <td>
                                                            <?php echo strtoupper($promotion->sigle_filiere . '-' . $promotion->sigle_semestre . '( ' . $promotion->annee_universitaire . ' )') ?>
                                                        </td>
                                                        <td>
                                                            <?php echo strtoupper($promotion->nom_semestre) ?>
                                                        </td>
                                                        <td>
                                                            <?php if ($promotion->statut == 0): ?>
                                                            <span class="badge badge-warning">En Attente</span>
                                                            <?php endif ?>
                                                            <?php if ($promotion->statut == 1): ?>
                                                            <span class=" badge badge-primary">En Cours</span>
                                                            <?php endif ?>
                                                            <?php if ($promotion->statut == 2): ?>
                                                            <span class=" badge badge-success">Achévée</span>
                                                            <?php endif ?>
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
                                                                        href="<?= ROOT ?>/Filieres/apercu_Filiere/">
                                                                        <i class="bx bx-show mr-1"></i>Aperçu
                                                                    </a>
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

</html>