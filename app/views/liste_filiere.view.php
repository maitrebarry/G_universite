<?php $this->view("Partials/header") ?>

<style>
    #filiereWrap .gu-kpis { display: flex; gap: 12px; flex-wrap: wrap; margin-bottom: 16px; }
    #filiereWrap .gu-kpi { flex: 1; min-width: 150px; background: #fff; border: 1px solid #e7ecf5; border-radius: 12px; padding: 12px 16px; display: flex; align-items: center; gap: 12px; }
    #filiereWrap .gu-kpi i { width: 42px; height: 42px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 22px; background: #eaf1fb; color: #1f4f9c; }
    #filiereWrap .gu-kpi .v { font-size: 20px; font-weight: 800; color: #14346b; line-height: 1; }
    #filiereWrap .gu-kpi .l { font-size: 12px; color: #5a6b86; }

    #filiereGrid { display: grid; grid-template-columns: repeat(auto-fill, minmax(310px, 1fr)); gap: 16px; }
    .gu-fcard { background: #fff; border: 1px solid #e7ecf5; border-radius: 14px; padding: 16px; display: flex; flex-direction: column; gap: 12px; transition: box-shadow .15s ease, transform .15s ease; border-top: 3px solid #1f4f9c; }
    .gu-fcard:hover { box-shadow: 0 10px 30px rgba(31, 79, 156, .12); transform: translateY(-2px); }
    .gu-fcard .fc-top { display: flex; align-items: center; justify-content: space-between; gap: 8px; }
    .gu-fcard .fc-sigle { font-weight: 800; font-size: 15px; color: #fff; background: linear-gradient(135deg, #1f4f9c, #4f86d6); padding: 4px 12px; border-radius: 8px; letter-spacing: .5px; }
    .gu-fcard .fc-dep { font-size: 11px; color: #5a6b86; background: #f1f4fa; padding: 3px 9px; border-radius: 20px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 160px; }
    .gu-fcard .fc-name { font-weight: 700; color: #14346b; font-size: 15px; line-height: 1.3; min-height: 39px; }
    .gu-fcard .fc-stats { display: grid; grid-template-columns: repeat(5, 1fr); gap: 4px; background: #f6f8fc; border-radius: 10px; padding: 10px 6px; }
    .gu-fcard .fc-stats > div { text-align: center; }
    .gu-fcard .fc-stats b { display: block; font-size: 16px; color: #1f4f9c; font-weight: 800; }
    .gu-fcard .fc-stats span { font-size: 9.5px; color: #5a6b86; text-transform: uppercase; letter-spacing: .2px; }
    .gu-fcard .fc-actions { display: flex; gap: 6px; justify-content: space-between; border-top: 1px solid #eef2f9; padding-top: 12px; }
    .gu-fcard .gu-icon-act { flex: 1; border: 1px solid #e3e8f2; background: #fff; color: #1f4f9c; border-radius: 9px; height: 36px; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 18px; transition: all .12s ease; text-decoration: none; }
    .gu-fcard .gu-icon-act:hover { background: #eaf1fb; border-color: #1f4f9c; }
    .gu-fcard .gu-icon-act.danger { color: #b91c1c; }
    .gu-fcard .gu-icon-act.danger:hover { background: #fdecec; border-color: #f0b4b4; }

    /* Modale Éditer (custom) */
    #menuEditer { position: fixed; inset: 0; z-index: 1080; display: none; align-items: center; justify-content: center; }
    #menuEditer.open { display: flex; }
    #menuEditer .gm-back { position: absolute; inset: 0; background: rgba(15, 23, 42, .55); }
    #menuEditer .gm-card { position: relative; background: #fff; border-radius: 12px; width: min(440px, 94vw); max-height: 90vh; box-shadow: 0 20px 60px rgba(0, 0, 0, .3); overflow: hidden; display: flex; flex-direction: column; }
    #menuEditer .gm-head { padding: 14px 18px; border-bottom: 1px solid #eef2f9; display: flex; align-items: center; justify-content: space-between; flex: 0 0 auto; }
    #menuEditer .gm-head h5 { margin: 0; color: #14346b; font-weight: 700; }
    #menuEditer .gm-body { padding: 18px; display: flex; flex-direction: column; gap: 10px; overflow-y: auto; flex: 1 1 auto; min-height: 0; }
    #menuEditer .gm-opt { display: flex; align-items: center; gap: 12px; padding: 12px 14px; border: 1px solid #e7ecf5; border-radius: 10px; color: #14346b; text-decoration: none; font-weight: 600; transition: all .12s ease; }
    #menuEditer .gm-opt:hover { transform: translateX(3px); }
    #menuEditer .gm-opt i { font-size: 22px; }
    #menuEditer .gm-opt.e { color: #b76e00; } #menuEditer .gm-opt.e:hover { background: #fff4e5; border-color: #f0c27a; }
    #menuEditer .gm-opt.a { color: #1f4f9c; } #menuEditer .gm-opt.a:hover { background: #eaf1fb; border-color: #9fc0ec; }
    #menuEditer .gm-opt.d { color: #b91c1c; } #menuEditer .gm-opt.d:hover { background: #fdecec; border-color: #f0b4b4; }
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
                <?php $this->view("set_flash"); ?>
                <div class="content-header-left col-12 mb-2 mt-1">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h5 class="content-header-title float-left pr-1 mb-0">
                                <?= isset($_SESSION['nom_departement']) ? strtoupper(htmlspecialchars($_SESSION['nom_departement'] . ' (' . $_SESSION['sigle_departement'] . ')')) : "IUFP" ?>
                            </h5>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb p-0 mb-0">
                                    <li class="breadcrumb-item"><a href="<?= ROOT ?>/Homes"><i class="bx bx-home-alt"></i></a></li>
                                    <li class="breadcrumb-item">Gestion Filière</li>
                                    <li class="breadcrumb-item active">Liste</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-body" id="filiereWrap">
                <?php
                $tF = count($filieres); $tUE = 0; $tMod = 0; $tClasses = 0; $tCred = 0;
                foreach ($filieres as $f) { $tUE += (int) $f->nb_ue; $tMod += (int) $f->nb_modules; $tClasses += (int) $f->nb_promotions; $tCred += (int) $f->total_credits; }
                ?>
                <!-- KPIs -->
                <div class="gu-kpis">
                    <div class="gu-kpi"><i class="bx bx-bookmark-alt"></i><div><div class="v"><?= $tF ?></div><div class="l">Filières</div></div></div>
                    <div class="gu-kpi"><i class="bx bx-layer"></i><div><div class="v"><?= $tUE ?></div><div class="l">Unités d'enseignement</div></div></div>
                    <div class="gu-kpi"><i class="bx bx-book-content"></i><div><div class="v"><?= $tMod ?></div><div class="l">Modules</div></div></div>
                    <div class="gu-kpi"><i class="bx bx-group"></i><div><div class="v"><?= $tClasses ?></div><div class="l">Classes</div></div></div>
                </div>

                <!-- Barre d'outils -->
                <div class="d-flex align-items-center justify-content-between flex-wrap mb-3" style="gap:10px;">
                    <div class="gu-field gu-search" style="position:relative;min-width:260px;">
                        <i class="bx bx-search" style="position:absolute;left:.7rem;top:50%;transform:translateY(-50%);color:var(--text-tertiary);"></i>
                        <input type="text" class="form-control" id="filiereSearch" style="padding-left:2rem;" placeholder="Rechercher une filière…">
                    </div>
                    <a href="<?= ROOT ?>/Filieres/ajouter_Filiere" class="btn btn-primary"><i class="bx bx-plus"></i> Nouvelle filière</a>
                </div>

                <?php if (empty($filieres)): ?>
                    <div class="gu-empty"><i class="bx bx-bookmark-alt"></i>Aucune filière. Cliquez sur « Nouvelle filière » pour commencer.</div>
                <?php else: ?>
                <div id="filiereGrid">
                    <?php foreach ($filieres as $filiere):
                        $nom = strtoupper($filiere->nom_filiere ?? '');
                    ?>
                        <div class="gu-fcard" data-nom="<?= htmlspecialchars(mb_strtolower($nom . ' ' . $filiere->sigle_filiere, 'UTF-8')) ?>">
                            <div class="fc-top">
                                <span class="fc-sigle"><?= strtoupper(htmlspecialchars($filiere->sigle_filiere)) ?></span>
                                <?php if (!empty($filiere->nom_departement)): ?>
                                    <span class="fc-dep" title="<?= htmlspecialchars($filiere->nom_departement) ?>"><i class="bx bx-building"></i> <?= htmlspecialchars($filiere->sigle_departement ?: $filiere->nom_departement) ?></span>
                                <?php endif ?>
                            </div>
                            <div class="fc-name"><?= htmlspecialchars($nom) ?></div>
                            <div class="fc-stats">
                                <div><b><?= (int) $filiere->nb_semestres ?></b><span>Sem.</span></div>
                                <div><b><?= (int) $filiere->nb_ue ?></b><span>UE</span></div>
                                <div><b><?= (int) $filiere->nb_modules ?></b><span>Mod.</span></div>
                                <div><b><?= (int) $filiere->total_credits ?></b><span>Créd.</span></div>
                                <div><b><?= (int) $filiere->nb_promotions ?></b><span>Cl.</span></div>
                            </div>
                            <div class="fc-actions">
                                <a class="gu-icon-act" href="<?= ROOT ?>/Filieres/apercu_Filiere/<?= $filiere->id_filiere ?>" title="Aperçu de la maquette"><i class="bx bx-show"></i></a>
                                <button type="button" class="gu-icon-act imprimerFiliere" data-id="<?= $filiere->id_filiere ?>" data-nom="Maquette-<?= strtoupper(htmlspecialchars($filiere->sigle_filiere)) ?>" title="Imprimer la maquette (PDF)"><i class="bx bx-printer"></i></button>
                                <a class="gu-icon-act" href="<?= ROOT ?>/Filieres/liste_promotion/<?= $filiere->id_filiere ?>" title="Classes / promotions"><i class="bx bx-group"></i></a>
                                <button type="button" class="gu-icon-act btn-edit-filiere" data-id="<?= $filiere->id_filiere ?>" title="Éditer la filière"><i class="bx bx-edit-alt"></i></button>
                                <button type="button" class="gu-icon-act danger btn-del-filiere" data-id="<?= $filiere->id_filiere ?>" data-nom="<?= htmlspecialchars($nom) ?>" title="Supprimer la filière"><i class="bx bx-trash"></i></button>
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>
                <?php endif ?>
            </div>
        </div>
    </div>

    <!-- Modale Éditer (custom) -->
    <div id="menuEditer" aria-hidden="true">
        <div class="gm-back" data-close-edit></div>
        <div class="gm-card" role="dialog" aria-modal="true">
            <div class="gm-head"><h5><i class="bx bx-edit-alt"></i> Éditer la filière</h5>
                <button type="button" class="gu-icon-act" data-close-edit aria-label="Fermer" style="border:0;background:transparent;font-size:22px;cursor:pointer;"><i class="bx bx-x"></i></button>
            </div>
            <div class="gm-body">
                <a href="#" class="gm-opt e" id="editButton"><i class="bx bx-edit-alt"></i> Modifier les informations</a>
                <a href="#" class="gm-opt a" id="addButton"><i class="bx bx-plus-circle"></i> Ajouter un élément</a>
                <a href="#" class="gm-opt d" id="delButton"><i class="bx bx-minus-circle"></i> Supprimer un élément</a>
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
        // Recherche
        $("#filiereSearch").on("input", function () {
            var q = this.value.toLowerCase().trim();
            $("#filiereGrid .gu-fcard").each(function () {
                $(this).toggle(!q || ("" + $(this).data("nom")).indexOf(q) > -1);
            });
        });

        // Impression maquette (filiere.js)
        $(document).on("click", ".imprimerFiliere", function (e) {
            e.preventDefault();
            var el = $(this);
            window.scrollTo({ top: 0, behavior: "smooth" });
            setTimeout(function () { imprimerFiliere(el.data("id"), el.data("nom")); }, 400);
        });

        // Modale Éditer
        var idFiliereEdit = null;
        function openEdit() { document.getElementById("menuEditer").classList.add("open"); }
        function closeEdit() { document.getElementById("menuEditer").classList.remove("open"); }
        $(document).on("click", ".btn-edit-filiere", function () {
            idFiliereEdit = $(this).data("id");
            $("#editButton").attr("href", ROOT + "/Filieres/editer_Filiere/" + idFiliereEdit);
            $("#addButton").attr("href", ROOT + "/Filieres/ajouter_element_filiere/" + idFiliereEdit);
            $("#delButton").attr("href", ROOT + "/Filieres/supprimer_element_filiere/" + idFiliereEdit);
            openEdit();
        });
        $(document).on("click", "[data-close-edit]", closeEdit);
        $(document).on("keydown", function (e) { if (e.key === "Escape") closeEdit(); });

        // Suppression filière
        $(document).on("click", ".btn-del-filiere", function () {
            var id = $(this).data("id"), nom = $(this).data("nom"), card = $(this).closest(".gu-fcard");
            Swal.fire({
                title: "Supprimer la filière ?",
                html: "<b>" + nom + "</b><br><small>Cette action supprime ses semestres, UE et modules. Impossible si des classes existent.</small>",
                icon: "warning", showCancelButton: true, confirmButtonText: "Supprimer", cancelButtonText: "Annuler",
                confirmButtonColor: "#b91c1c"
            }).then(function (r) {
                if (!(r && (r.isConfirmed || (r.value && !r.dismiss)))) return;
                $("#loader").removeClass("d-none").addClass("d-flex");
                $.post(ROOT + "/Filieres/supprimer_filiere", { idFiliere: id }, function (res) {
                    $("#loader").removeClass("d-flex").addClass("d-none");
                    if (res && res.ok) {
                        card.fadeOut(250, function () { $(this).remove(); });
                        Swal.fire("Supprimée", res.msg || "Filière supprimée.", "success");
                    } else {
                        Swal.fire("Action impossible", (res && res.msg) || "Échec de la suppression.", "error");
                    }
                }, "json").fail(function () {
                    $("#loader").removeClass("d-flex").addClass("d-none");
                    Swal.fire("Erreur", "Problème de communication.", "error");
                });
            });
        });
    </script>
</body>

</html>
