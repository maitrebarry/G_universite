<?php $this->view("Partials/header") ?>

<style>
    #ensWrap .gu-kpis { display: flex; gap: 12px; flex-wrap: wrap; margin-bottom: 16px; }
    #ensWrap .gu-kpi { flex: 1; min-width: 150px; background: #fff; border: 1px solid #e7ecf5; border-radius: 12px; padding: 12px 16px; display: flex; align-items: center; gap: 12px; }
    #ensWrap .gu-kpi i { width: 42px; height: 42px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 21px; }
    #ensWrap .gu-kpi .v { font-size: 20px; font-weight: 800; color: #14346b; line-height: 1; }
    #ensWrap .gu-kpi .l { font-size: 12px; color: #5a6b86; }
    #ensWrap .gu-tabs { display: inline-flex; background: #eef2f9; border-radius: 10px; padding: 4px; gap: 4px; }
    #ensWrap .gu-tab { padding: 7px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; color: #5a6b86; cursor: pointer; border: 0; background: transparent; }
    #ensWrap .gu-tab.active { background: #fff; color: #14346b; box-shadow: 0 1px 4px rgba(0, 0, 0, .08); }
    #ensWrap .gu-pact { display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 8px; border: 1px solid #e3e8f2; color: #1f4f9c; background: #fff; cursor: pointer; font-size: 17px; text-decoration: none; margin: 0 1px; transition: all .12s ease; }
    #ensWrap .gu-pact:hover { background: #eaf1fb; border-color: #1f4f9c; }
    #ensWrap .gu-pact.danger { color: #b91c1c; } #ensWrap .gu-pact.danger:hover { background: #fdecec; border-color: #f0b4b4; }
    #ensWrap .doc-link { color: #1f4f9c; font-size: 18px; } #ensWrap .doc-none { color: #c2c9d6; font-size: 18px; }
</style>

<body class="vertical-layout vertical-menu-modern boxicon-layout no-card-shadow 2-columns navbar-sticky footer-static"
    data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">

    <?php $this->view("Partials/navbar") ?>
    <?php $this->view("Partials/seibar") ?>

    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-header row">
                <?php $this->view("set_flash"); ?>
                <div class="content-header-left col-12 mb-2 mt-1">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h5 class="content-header-title float-left pr-1 mb-0">Enseignants</h5>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb p-0 mb-0">
                                    <li class="breadcrumb-item"><a href="<?= ROOT ?>/Homes"><i class="bx bx-home-alt"></i></a></li>
                                    <li class="breadcrumb-item">Gestion Enseignant</li>
                                    <li class="breadcrumb-item active">Liste</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-body" id="ensWrap">
                <?php $nbCDI = count($enseignat_CDI); $nbVCT = count($enseignat_VCT); ?>
                <div class="gu-kpis">
                    <div class="gu-kpi"><i style="background:#eaf1fb;color:#1f4f9c;" class="bx bx-user-voice"></i><div><div class="v"><?= $nbCDI + $nbVCT ?></div><div class="l">Enseignants</div></div></div>
                    <div class="gu-kpi"><i style="background:#e9f7ef;color:#15803d;" class="bx bx-badge-check"></i><div><div class="v"><?= $nbCDI ?></div><div class="l">Permanents</div></div></div>
                    <div class="gu-kpi"><i style="background:#fff4e5;color:#b76e00;" class="bx bx-time-five"></i><div><div class="v"><?= $nbVCT ?></div><div class="l">Vacataires</div></div></div>
                </div>

                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between flex-wrap mb-2" style="gap:10px;">
                                <div class="gu-tabs">
                                    <button class="gu-tab active" data-tab="perm">Permanents</button>
                                    <button class="gu-tab" data-tab="vac">Vacataires</button>
                                </div>
                                <div class="d-flex align-items-center" style="gap:10px;">
                                    <div class="gu-field gu-search" style="position:relative;min-width:240px;">
                                        <i class="bx bx-search" style="position:absolute;left:.7rem;top:50%;transform:translateY(-50%);color:var(--text-tertiary);"></i>
                                        <input type="text" class="form-control" id="ensSearch" style="padding-left:2rem;" placeholder="Rechercher un enseignant…">
                                    </div>
                                    <a href="<?= ROOT ?>/Enseignants/ajouter_enseignant" class="btn btn-primary"><i class="bx bx-plus"></i> Enseignant</a>
                                </div>
                            </div>

                            <?php
                            $renderActions = function ($e) {
                                ob_start(); ?>
                                <a class="gu-pact" href="<?= ROOT ?>/Enseignants/listeEDT_individuel/<?= $e->enseignant_id ?>" title="EDT individuel"><i class="bx bx-calendar"></i></a>
                                <a class="gu-pact" href="<?= ROOT ?>/Enseignants/update/<?= $e->enseignant_id ?>" title="Modifier"><i class="bx bx-edit-alt"></i></a>
                                <a class="gu-pact" href="<?= ROOT ?>/Utilisateurs/liste_utilisateur/<?= $e->enseignant_id ?>" title="Attribuer un compte"><i class="bx bx-user-plus"></i></a>
                                <a class="gu-pact danger btn-del-ens" href="<?= ROOT ?>/Enseignants/delete/<?= $e->enseignant_id ?>" data-nom="<?= htmlspecialchars($e->enseignant_prenom . ' ' . $e->enseignant_nom) ?>" title="Supprimer"><i class="bx bx-trash"></i></a>
                                <?php return ob_get_clean();
                            };
                            $docCell = function ($path, $label) {
                                if (!empty($path)) return '<a class="doc-link" href="' . ROOT . htmlspecialchars($path) . '" target="_blank" title="Voir ' . $label . '"><i class="bx bx-file"></i></a>';
                                return '<i class="bx bx-minus doc-none" title="Aucun ' . $label . '"></i>';
                            };
                            ?>

                            <!-- Permanents -->
                            <div class="ens-pane" data-pane="perm">
                                <?php if (empty($enseignat_CDI)): ?>
                                    <div class="gu-empty"><i class="bx bx-user-voice"></i>Aucun enseignant permanent.</div>
                                <?php else: ?>
                                <div class="gu-table-wrap" style="max-height:calc(100vh - 380px);">
                                    <table class="gu-table" style="width:100%;">
                                        <thead><tr><th style="width:42px;text-align:center;">N°</th><th>Prénom &amp; Nom</th><th>Matricule</th><th>Grade</th><th>Téléphone</th><th>Diplôme</th><th class="text-center">CV</th><th class="text-center">Contrat</th><th class="text-center" style="width:150px;">Actions</th></tr></thead>
                                        <tbody>
                                            <?php $i = 1; foreach ($enseignat_CDI as $e): ?>
                                                <tr data-nom="<?= htmlspecialchars(mb_strtolower($e->enseignant_prenom . ' ' . $e->enseignant_nom . ' ' . $e->enseignant_matricule, 'UTF-8')) ?>">
                                                    <td class="text-center"><?= $i++ ?></td>
                                                    <td style="font-weight:var(--fw-medium);"><?= htmlspecialchars($e->enseignant_prenom . ' ' . $e->enseignant_nom) ?></td>
                                                    <td><?= htmlspecialchars($e->enseignant_matricule ?? '—') ?></td>
                                                    <td><span class="badge badge-soft-primary"><?= htmlspecialchars($e->nom_grade ?? '—') ?></span></td>
                                                    <td><?= htmlspecialchars($e->enseignant_telephone ?? '—') ?></td>
                                                    <td><?= htmlspecialchars($e->enseignant_diplome ?? '—') ?></td>
                                                    <td class="text-center"><?= $docCell($e->enseignant_cv ?? '', 'le CV') ?></td>
                                                    <td class="text-center"><?= $docCell($e->contrat ?? '', 'le contrat') ?></td>
                                                    <td class="text-center" style="white-space:nowrap;"><?= $renderActions($e) ?></td>
                                                </tr>
                                            <?php endforeach ?>
                                        </tbody>
                                    </table>
                                </div>
                                <?php endif ?>
                            </div>

                            <!-- Vacataires -->
                            <div class="ens-pane" data-pane="vac" style="display:none;">
                                <?php if (empty($enseignat_VCT)): ?>
                                    <div class="gu-empty"><i class="bx bx-time-five"></i>Aucun enseignant vacataire.</div>
                                <?php else: ?>
                                <div class="gu-table-wrap" style="max-height:calc(100vh - 380px);">
                                    <table class="gu-table" style="width:100%;">
                                        <thead><tr><th style="width:42px;text-align:center;">N°</th><th>Prénom &amp; Nom</th><th>Matricule</th><th>Téléphone</th><th>Diplôme</th><th class="text-center">CV</th><th class="text-center">Contrat</th><th class="text-center" style="width:150px;">Actions</th></tr></thead>
                                        <tbody>
                                            <?php $i = 1; foreach ($enseignat_VCT as $e): ?>
                                                <tr data-nom="<?= htmlspecialchars(mb_strtolower($e->enseignant_prenom . ' ' . $e->enseignant_nom . ' ' . $e->enseignant_matricule, 'UTF-8')) ?>">
                                                    <td class="text-center"><?= $i++ ?></td>
                                                    <td style="font-weight:var(--fw-medium);"><?= htmlspecialchars($e->enseignant_prenom . ' ' . $e->enseignant_nom) ?></td>
                                                    <td><?= htmlspecialchars($e->enseignant_matricule ?? '—') ?></td>
                                                    <td><?= htmlspecialchars($e->enseignant_telephone ?? '—') ?></td>
                                                    <td><?= htmlspecialchars($e->enseignant_diplome ?? '—') ?></td>
                                                    <td class="text-center"><?= $docCell($e->enseignant_cv ?? '', 'le CV') ?></td>
                                                    <td class="text-center"><?= $docCell($e->contrat ?? '', 'le contrat') ?></td>
                                                    <td class="text-center" style="white-space:nowrap;"><?= $renderActions($e) ?></td>
                                                </tr>
                                            <?php endforeach ?>
                                        </tbody>
                                    </table>
                                </div>
                                <?php endif ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <?php $this->view("Partials/foot") ?>
    <?php $this->view("Partials/footer") ?>
    <script>const ROOT = "<?= ROOT ?>";</script>
    <script>
        // Onglets
        $(".gu-tab").on("click", function () {
            $(".gu-tab").removeClass("active"); $(this).addClass("active");
            var t = $(this).data("tab");
            $(".ens-pane").each(function () { $(this).toggle($(this).data("pane") === t); });
            $("#ensSearch").trigger("input");
        });
        // Recherche (panneau actif)
        $("#ensSearch").on("input", function () {
            var q = this.value.toLowerCase().trim();
            $(".ens-pane:visible tbody tr").each(function () {
                $(this).toggle(!q || ("" + $(this).data("nom")).indexOf(q) > -1);
            });
        });
        // Suppression
        $(document).on("click", ".btn-del-ens", function (e) {
            e.preventDefault();
            var url = $(this).attr("href"), nom = $(this).data("nom");
            Swal.fire({
                title: "Supprimer l'enseignant ?", html: "<b>" + nom + "</b> sera supprimé définitivement.",
                icon: "warning", showCancelButton: true, confirmButtonText: "Supprimer", cancelButtonText: "Annuler", confirmButtonColor: "#b91c1c"
            }).then(function (r) { if (r && (r.isConfirmed || (r.value && !r.dismiss))) window.location.href = url; });
        });
    </script>
</body>

</html>
