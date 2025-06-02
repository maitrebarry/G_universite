<?php $this->view("Partials/header") ?>

<style>
    td {
        padding: 0;
        margin: 0 !important;
    }
    .card-custom {
        padding: 20px;
        margin: 20px;
    }
    .form-group {
        margin-bottom: 15px; 
    }
</style>

<body class="vertical-layout vertical-menu-modern boxicon-layout no-card-shadow 2-columns navbar-sticky footer-static"
      data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">

    <?php $this->view("Partials/navbar") ?>
    <?php $this->view("Partials/seibar") ?>

    <div class="app-content content">
        <div id="loader" class="w-100 position-absolute d-none justify-content-center align-items-center"
             style="height:100vh;z-index:100">
            <div class="spinner-border" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>

        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-12 mb-2 mt-1">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h5 class="content-header-title float-left pr-1 mb-0">
                                <?= isset($_SESSION['nom_departement'])
                                    ? strtoupper($_SESSION['nom_departement'] . ' (' . $_SESSION['sigle_departement'] . ')')
                                    : "IUFP" ?>
                            </h5>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb p-0 mb-0">
                                    <li class="breadcrumb-item"><a href="index.html"><i class="bx bx-home-alt"></i></a></li>
                                    <li class="breadcrumb-item"><a href="#">Gestion EDT</a></li>
                                    <li class="breadcrumb-item active">Liste EDT INDIVIDUEL</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <section id="basic-datatable">
                    <?php $this->view('set_flash'); ?>
                    <div class="row">
                        <div class="col-12">
                            <div class="card card-animated-border-top card-custom">
                                <div class="card-body">
                                                                 
                                    <div class="row">
                                        <div class="col-md-4 form-group">
                                            <label for="periode_debut">Date début de la période</label>
                                            <select id="periode_debut" class="form-control"></select>
                                        </div>
                                        <div class="col-md-4 form-group">
                                            <label for="periode_fin">Date fin de la période</label>
                                            <input id="periode_fin" class="form-control" readonly>
                                        </div>
                                        <div class="col-md-4 form-group">
                                            <label for="prof">Professeurs</label>
                                            <select id="prof" class="form-control">
                                                <option value="">Tous les enseignants</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="table-responsive col-12 mt-4" id="table-edt-individuels">
                                        <h6 class="text-center text-bold-500 text-success">
                                            Sélectionnez une période et la liste des EDT individuels apparaitra 😀
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <?php $this->view("Partials/foot") ?>
    <?php $this->view("Partials/footer") ?>

    <script>var ROOT = "<?= ROOT ?>";</script>
    <script src="<?= ROOT ?>/assets/mon_js/sweet_alert_suppression.js"></script>
    <script src="<?= ROOT ?>/assets/mon_js/edt_individuels.js"></script>

</body>