<?php $this->view("Partials/header") ?>

<style>
    #edtPerWrap .gu-card { background: #fff; border: 1px solid #e7ecf5; border-radius: 12px; padding: 18px; margin-bottom: 16px; }
    #edtPerWrap .form-label { font-size: .85rem; font-weight: 500; color: #475569; margin-bottom: 4px; }
</style>

<body class="vertical-layout vertical-menu-modern boxicon-layout no-card-shadow 2-columns navbar-sticky footer-static"
    data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">

    <?php $this->view("Partials/navbar") ?>
    <?php $this->view("Partials/seibar") ?>

    <div class="app-content content">
        <div id="loader" class="w-100 position-absolute d-none justify-content-center align-items-center" style="height:100vh;z-index:100">
            <div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div>
        </div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-12 mb-2 mt-1">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h5 class="content-header-title float-left pr-1 mb-0">
                                <?= isset($_SESSION['nom_departement']) ? strtoupper(htmlspecialchars($_SESSION['nom_departement'] . ' (' . $_SESSION['sigle_departement'] . ')')) : "IUFP" ?>
                            </h5>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb p-0 mb-0">
                                    <li class="breadcrumb-item"><a href="<?= ROOT ?>/Homes"><i class="bx bx-home-alt"></i></a></li>
                                    <li class="breadcrumb-item">Gestion EDT</li>
                                    <li class="breadcrumb-item active">EDT individuels par période</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-body" id="edtPerWrap">
                <?php $this->view('set_flash'); ?>
                <div class="gu-card">
                    <div class="gu-section-title"><span class="gu-ico-chip"><i class="bx bx-calendar"></i></span> EDT individuels — filtrage par période</div>
                    <div class="row mt-1" style="row-gap:14px;">
                        <div class="col-md-4">
                            <label class="form-label" for="periode_debut">Période</label>
                            <select id="periode_debut" class="form-select"></select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="periode_fin">Date de fin</label>
                            <input id="periode_fin" class="form-control" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="prof">Enseignant</label>
                            <select id="prof" class="form-select">
                                <option value="">Tous les enseignants</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div id="table-edt-individuels">
                    <div class="gu-empty"><i class="bx bx-calendar"></i>Sélectionnez une période pour afficher les emplois du temps individuels.</div>
                </div>
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

</html>
