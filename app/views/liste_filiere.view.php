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
                            <h5 class="content-header-title float-left pr-1 mb-0">Enregistrements du filière</h5>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb p-0 mb-0">
                                    <li class="breadcrumb-item"><a href="index.html"><i class="bx bx-home-alt"></i></a>
                                    </li>
                                    <li class="breadcrumb-item"><a href="#">Gestion Filière</a>
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
                <section id="table-chechbox">

                    <div class="row">
                        <div>
                            <?php  $this->view("set_flash"); ?>
                        </div>
                        <div class="col-12">
                            <div class="card">

                                <div class="card-content">
                                    <a href="<?= ROOT ?>/Filieres/ajouter_Filiere"><button class="btn btn-primary"
                                            style="float:right;"><i class="bx bx-plus"></i>&nbsp; FILIERE </button></a>
                                    <div class="card-body card-dashboard">


                                        <div class="table-responsive">
                                            <table id="table-extended-chechbox"
                                                class="table zero-configuration table-bordered">
                                                <thead>
                                                    <tr>

                                                        <th>Nom filière</th>
                                                        <th>Code Filière</th>
                                                        <th class="text-center dt-no-sorting">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach($filieres as $filiere): ?>
                                                    <tr>
                                                        <td>
                                                            <?php echo strtoupper($filiere->nom_filiere) ?>
                                                        </td>

                                                        <td>
                                                            <?php echo strtoupper($filiere->sigle_filiere) ?>
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
                                                                        href="<?= ROOT ?>/Filieres/apercu_Filiere/<?php echo $filiere->id_filiere ?>">
                                                                        <i class="bx bx-edit-alt mr-1"></i>Aperçu
                                                                    </a>
                                                                    <a class="dropdown-item" href="#"><i
                                                                            class="bx bx-edit-alt mr-1"></i> edit</a>
                                                                    <a class="dropdown-item" href="#"><i
                                                                            class="bx bx-trash mr-1"></i> delete</a>
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