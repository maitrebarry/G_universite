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
                                    <li class="breadcrumb-item active">Départements</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-body">
                <div id="confLayout">
                    <?php $this->view("Partials/config_nav", ['active' => 'departements']) ?>
                    <div class="conf-main">
                        <div class="conf-card">
                            <div class="d-flex align-items-center justify-content-between flex-wrap mb-2" style="gap:10px;">
                                <div class="gu-section-title"><span class="gu-ico-chip"><i class="bx bx-building"></i></span> Départements <span class="badge badge-soft-primary"><?= count($liste) ?></span></div>
                                <button class="btn btn-primary" onclick="guConfOpen('addDepModal')"><i class="bx bx-plus"></i> Ajouter un département</button>
                            </div>
                            <?php if (empty($liste)): ?>
                                <div class="gu-empty"><i class="bx bx-building"></i>Aucun département.</div>
                            <?php else: ?>
                            <div class="gu-table-wrap" style="max-height:calc(100vh - 320px);">
                                <table class="gu-table" style="width:100%;">
                                    <thead><tr><th style="width:46px;text-align:center;">N°</th><th>Nom du département</th><th>Sigle</th><th class="text-center" style="width:110px;">Actions</th></tr></thead>
                                    <tbody>
                                        <?php $i = 1; foreach ($liste as $l): ?>
                                            <tr>
                                                <td class="text-center"><?= $i++ ?></td>
                                                <td style="font-weight:var(--fw-medium);"><?= htmlspecialchars($l->nom_departement) ?></td>
                                                <td><span class="badge badge-soft-secondary"><?= htmlspecialchars($l->sigle_departement) ?></span></td>
                                                <td class="text-center" style="white-space:nowrap;">
                                                    <button type="button" class="conf-act btn-edit-dep" data-id="<?= $l->id_departement ?>" data-nom="<?= htmlspecialchars($l->nom_departement) ?>" data-code="<?= htmlspecialchars($l->sigle_departement) ?>" title="Modifier"><i class="bx bx-edit-alt"></i></button>
                                                    <a class="conf-act danger btn-conf-del" href="<?= ROOT ?>/Departements/delete/<?= $l->id_departement ?>" data-nom="<?= htmlspecialchars($l->nom_departement) ?>" title="Supprimer"><i class="bx bx-trash"></i></a>
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

    <div class="gu-conf-modal" id="addDepModal">
        <div class="gm-back" data-conf-close="addDepModal"></div>
        <div class="gm-card">
            <form action="" method="post">
                <div class="gm-head"><h5><i class="bx bx-plus-circle"></i> Nouveau département</h5><button type="button" class="conf-act" data-conf-close="addDepModal" style="border:0;background:transparent;color:#fff;"><i class="bx bx-x"></i></button></div>
                <div class="gm-body"><div class="row" style="row-gap:12px;">
                    <div class="col-md-7"><label class="form-label">Nom du département <span class="text-danger">*</span></label><input type="text" class="form-control" name="nom_departement" required></div>
                    <div class="col-md-5"><label class="form-label">Sigle <span class="text-danger">*</span></label><input type="text" class="form-control" name="sigle_departement" required></div>
                </div></div>
                <div class="gm-foot"><button type="button" class="btn btn-ghost" data-conf-close="addDepModal">Annuler</button><button type="submit" class="btn btn-primary" name="envoyer"><i class="bx bx-check"></i> Enregistrer</button></div>
            </form>
        </div>
    </div>

    <div class="gu-conf-modal" id="editDepModal">
        <div class="gm-back" data-conf-close="editDepModal"></div>
        <div class="gm-card">
            <form action="<?= ROOT ?>/Departements/editDepartements" method="post">
                <div class="gm-head"><h5><i class="bx bx-edit-alt"></i> Modifier le département</h5><button type="button" class="conf-act" data-conf-close="editDepModal" style="border:0;background:transparent;color:#fff;"><i class="bx bx-x"></i></button></div>
                <div class="gm-body"><input type="hidden" id="ed_id" name="id_departement"><div class="row" style="row-gap:12px;">
                    <div class="col-md-7"><label class="form-label">Nom du département</label><input type="text" class="form-control" id="ed_nom" name="nom_departement" required></div>
                    <div class="col-md-5"><label class="form-label">Sigle</label><input type="text" class="form-control" id="ed_code" name="sigle_departement" required></div>
                </div></div>
                <div class="gm-foot"><button type="button" class="btn btn-ghost" data-conf-close="editDepModal">Annuler</button><button type="submit" class="btn btn-primary" name="editdepartement">Modifier</button></div>
            </form>
        </div>
    </div>

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <?php $this->view("Partials/foot") ?>
    <?php $this->view("Partials/footer") ?>
    <script>
        document.querySelectorAll('.btn-edit-dep').forEach(function (b) {
            b.addEventListener('click', function () {
                document.getElementById('ed_id').value = this.dataset.id;
                document.getElementById('ed_nom').value = this.dataset.nom;
                document.getElementById('ed_code').value = this.dataset.code;
                guConfOpen('editDepModal');
            });
        });
    </script>
</body>

</html>
