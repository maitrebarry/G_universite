<?php $this->view("Partials/header") ?>

<body class="vertical-layout vertical-menu-modern boxicon-layout no-card-shadow 2-columns navbar-sticky footer-static"
    data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">

    <?php $this->view("Partials/navbar") ?>
    <?php $this->view("Partials/seibar") ?>

    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-header row">
                <?php $this->view("set_flash") ?>
                <div class="content-header-left col-12 mb-2 mt-1">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h5 class="content-header-title float-left pr-1 mb-0">Configuration</h5>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb p-0 mb-0">
                                    <li class="breadcrumb-item"><a href="<?= ROOT ?>/Homes"><i class="bx bx-home-alt"></i></a></li>
                                    <li class="breadcrumb-item">Configuration</li>
                                    <li class="breadcrumb-item active">Modules</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-body">
                <div id="confLayout">
                    <?php $this->view("Partials/config_nav", ['active' => 'modules']) ?>
                    <div class="conf-main">
                        <div class="conf-card">
                            <div class="d-flex align-items-center justify-content-between flex-wrap mb-2" style="gap:10px;">
                                <div class="gu-section-title"><span class="gu-ico-chip"><i class="bx bx-book-content"></i></span> Modules <span class="badge badge-soft-primary"><?= count($liste) ?></span></div>
                                <button class="btn btn-primary" onclick="guConfOpen('addModuleModal')"><i class="bx bx-plus"></i> Ajouter un module</button>
                            </div>
                            <?php if (empty($liste)): ?>
                                <div class="gu-empty"><i class="bx bx-book-content"></i>Aucun module. Cliquez sur « Ajouter un module ».</div>
                            <?php else: ?>
                            <div class="gu-table-wrap" style="max-height:calc(100vh - 320px);">
                                <table class="gu-table" style="width:100%;">
                                    <thead><tr><th style="width:46px;text-align:center;">N°</th><th>Nom du module</th><th>Code</th><th class="text-center" style="width:110px;">Actions</th></tr></thead>
                                    <tbody>
                                        <?php $i = 1; foreach ($liste as $l): ?>
                                            <tr>
                                                <td class="text-center"><?= $i++ ?></td>
                                                <td style="font-weight:var(--fw-medium);"><?= htmlspecialchars($l->nom_module) ?></td>
                                                <td><span class="badge badge-soft-secondary"><?= htmlspecialchars($l->sigle_module) ?></span></td>
                                                <td class="text-center" style="white-space:nowrap;">
                                                    <button type="button" class="conf-act btn-edit-mod" data-id="<?= $l->id_module ?>" data-nom="<?= htmlspecialchars($l->nom_module) ?>" data-code="<?= htmlspecialchars($l->sigle_module) ?>" title="Modifier"><i class="bx bx-edit-alt"></i></button>
                                                    <a class="conf-act danger btn-conf-del" href="<?= ROOT ?>/Modules/delete/<?= $l->id_module ?>" data-nom="<?= htmlspecialchars($l->nom_module) ?>" title="Supprimer"><i class="bx bx-trash"></i></a>
                                                </td>
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

    <!-- Ajouter -->
    <div class="gu-conf-modal" id="addModuleModal">
        <div class="gm-back" data-conf-close="addModuleModal"></div>
        <div class="gm-card">
            <form action="" method="post">
                <div class="gm-head"><h5><i class="bx bx-plus-circle"></i> Nouveau module</h5><button type="button" class="conf-act" data-conf-close="addModuleModal" style="border:0;background:transparent;color:#fff;"><i class="bx bx-x"></i></button></div>
                <div class="gm-body"><div class="row" style="row-gap:12px;">
                    <div class="col-md-7"><label class="form-label">Nom du module <span class="text-danger">*</span></label><input type="text" class="form-control" name="nom_module" placeholder="Nom du module" required></div>
                    <div class="col-md-5"><label class="form-label">Code <span class="text-danger">*</span></label><input type="text" class="form-control" name="sigle_module" placeholder="Code" required></div>
                </div></div>
                <div class="gm-foot"><button type="button" class="btn btn-ghost" data-conf-close="addModuleModal">Annuler</button><button type="submit" class="btn btn-primary" name="saveFiliere"><i class="bx bx-check"></i> Enregistrer</button></div>
            </form>
        </div>
    </div>

    <!-- Modifier -->
    <div class="gu-conf-modal" id="editModuleModal">
        <div class="gm-back" data-conf-close="editModuleModal"></div>
        <div class="gm-card">
            <form action="<?= ROOT ?>/Modules/editFiliere" method="post">
                <div class="gm-head"><h5><i class="bx bx-edit-alt"></i> Modifier le module</h5><button type="button" class="conf-act" data-conf-close="editModuleModal" style="border:0;background:transparent;color:#fff;"><i class="bx bx-x"></i></button></div>
                <div class="gm-body"><input type="hidden" id="em_id" name="id_module"><div class="row" style="row-gap:12px;">
                    <div class="col-md-7"><label class="form-label">Nom du module</label><input type="text" class="form-control" id="em_nom" name="nom_module" required></div>
                    <div class="col-md-5"><label class="form-label">Code</label><input type="text" class="form-control" id="em_code" name="sigle_module" required></div>
                </div></div>
                <div class="gm-foot"><button type="button" class="btn btn-ghost" data-conf-close="editModuleModal">Annuler</button><button type="submit" class="btn btn-primary" name="editmodule">Modifier</button></div>
            </form>
        </div>
    </div>

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <?php $this->view("Partials/foot") ?>
    <?php $this->view("Partials/footer") ?>
    <script>
        document.querySelectorAll('.btn-edit-mod').forEach(function (b) {
            b.addEventListener('click', function () {
                document.getElementById('em_id').value = this.dataset.id;
                document.getElementById('em_nom').value = this.dataset.nom;
                document.getElementById('em_code').value = this.dataset.code;
                guConfOpen('editModuleModal');
            });
        });
    </script>
</body>

</html>
