<?php $this->view("Partials/header") ?>
<?php $filiere = $infoFiliere['filiere']; $semestres = $infoFiliere['semestres']; ?>

<style>
    #apercuWrap .gu-toolbar { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; background: #fff; border: 1px solid #e7ecf5; border-radius: 12px; padding: 12px 16px; margin-bottom: 16px; }
    #apercuWrap .gu-toggles { display: flex; gap: 8px; flex-wrap: wrap; }
    #apercuWrap .gu-pill { display: inline-flex; align-items: center; gap: 6px; padding: 5px 12px; border: 1px solid #c7d2e6; border-radius: 20px; font-size: 12.5px; color: #14346b; cursor: pointer; user-select: none; background: #f4f7fc; margin: 0; }
    #apercuWrap .gu-pill input { margin: 0; }
    #apercuWrap .maquette-card { background: #fff; border: 1px solid #e7ecf5; border-radius: 12px; padding: 22px; }
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
                                    <li class="breadcrumb-item"><a href="<?= ROOT ?>/Filieres">Gestion Filière</a></li>
                                    <li class="breadcrumb-item active">Maquette</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-body" id="apercuWrap">
                <div class="gu-toolbar">
                    <div class="gu-toggles">
                        <span style="font-size:12px;color:#5a6b86;align-self:center;margin-right:4px;"><i class="bx bx-filter-alt"></i> Semestres :</span>
                        <?php foreach ($semestres as $semestre): ?>
                            <label class="gu-pill"><input type="checkbox" class="showOption" data-id="s_<?= $semestre->id_parcours ?>" checked> <?= htmlspecialchars($semestre->sigle_semestre) ?></label>
                        <?php endforeach ?>
                    </div>
                    <div style="display:flex;gap:8px;">
                        <a href="<?= ROOT ?>/Filieres" class="btn btn-ghost"><i class="bx bx-arrow-back"></i> Retour</a>
                        <a href="<?= ROOT ?>/Filieres/editer_Filiere/<?= $filiere->id_filiere ?>" class="btn btn-soft-primary"><i class="bx bx-edit-alt"></i> Éditer</a>
                        <button type="button" class="btn btn-primary" id="print" data-nom="Maquette-<?= strtoupper(htmlspecialchars($filiere->sigle_filiere)) ?>"><i class="bx bx-printer"></i> Imprimer (PDF)</button>
                    </div>
                </div>

                <div class="maquette-card">
                    <?php $this->view('post_apercu_filiere', ['infoFiliere' => $infoFiliere]); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <?php $this->view("Partials/foot") ?>
    <?php $this->view("Partials/footer") ?>
    <script>const ROOT = "<?= ROOT ?>";</script>
    <script src="<?= ROOT ?>/assets/mon_js/filiere.js"></script>
    <script>
        // Filtre des semestres
        $(".showOption").on("change", function () {
            $("#" + $(this).data("id")).toggle($(this).is(":checked"));
        });
        // Impression (capture #semestresTable via imprimer2 de filiere.js)
        $("#print").on("click", function (e) {
            e.preventDefault();
            var nom = $(this).data("nom");
            window.scrollTo({ top: 0, behavior: "smooth" });
            setTimeout(function () { imprimer2(nom); }, 400);
        });
    </script>
</body>

</html>
