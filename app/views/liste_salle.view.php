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
                                    <li class="breadcrumb-item active">Salles</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-body">
                <div id="confLayout">
                    <?php $this->view("Partials/config_nav", ['active' => 'salles']) ?>
                    <div class="conf-main">
                        <div class="conf-card">
                            <div class="d-flex align-items-center justify-content-between flex-wrap mb-2" style="gap:10px;">
                                <div class="gu-section-title"><span class="gu-ico-chip"><i class="bx bx-door-open"></i></span> Salles <span class="badge badge-soft-primary"><?= count($datas) ?></span></div>
                                <button class="btn btn-primary" onclick="guConfOpen('addSalleModal')"><i class="bx bx-plus"></i> Ajouter une salle</button>
                            </div>
                            <?php if (empty($datas)): ?>
                                <div class="gu-empty"><i class="bx bx-door-open"></i>Aucune salle.</div>
                            <?php else: ?>
                            <div class="gu-table-wrap" style="max-height:calc(100vh - 320px);">
                                <table class="gu-table" style="width:100%;">
                                    <thead><tr><th style="width:46px;text-align:center;">N°</th><th>Nom de la salle</th><th class="text-center">Capacité</th><th class="text-center" style="width:110px;">Actions</th></tr></thead>
                                    <tbody>
                                        <?php $i = 1; foreach ($datas as $l): ?>
                                            <tr>
                                                <td class="text-center"><?= $i++ ?></td>
                                                <td style="font-weight:var(--fw-medium);"><?= htmlspecialchars($l->nom_salle) ?></td>
                                                <td class="text-center"><span class="badge badge-soft-primary"><?= htmlspecialchars($l->capacite_salle) ?> places</span></td>
                                                <td class="text-center" style="white-space:nowrap;">
                                                    <button type="button" class="conf-act btn-edit-salle" data-id="<?= $l->id_salle ?>" data-nom="<?= htmlspecialchars($l->nom_salle) ?>" data-cap="<?= htmlspecialchars($l->capacite_salle) ?>" title="Modifier"><i class="bx bx-edit-alt"></i></button>
                                                    <a class="conf-act danger btn-conf-del" href="<?= ROOT ?>/Salles/supprimer/<?= $l->id_salle ?>" data-nom="<?= htmlspecialchars($l->nom_salle) ?>" title="Supprimer"><i class="bx bx-trash"></i></a>
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

    <div class="gu-conf-modal" id="addSalleModal">
        <div class="gm-back" data-conf-close="addSalleModal"></div>
        <div class="gm-card">
            <form action="" method="post">
                <div class="gm-head"><h5><i class="bx bx-plus-circle"></i> Nouvelle salle</h5><button type="button" class="conf-act" data-conf-close="addSalleModal" style="border:0;background:transparent;color:#fff;"><i class="bx bx-x"></i></button></div>
                <div class="gm-body"><div class="row" style="row-gap:12px;">
                    <div class="col-md-7"><label class="form-label">Nom de la salle <span class="text-danger">*</span></label><input type="text" class="form-control" name="nom_salle" placeholder="ex : Salle A1" required></div>
                    <div class="col-md-5"><label class="form-label">Capacité <span class="text-danger">*</span></label><input type="number" min="1" class="form-control" name="capacite_salle" placeholder="ex : 40" required></div>
                </div></div>
                <div class="gm-foot"><button type="button" class="btn btn-ghost" data-conf-close="addSalleModal">Annuler</button><button type="submit" class="btn btn-primary" name="envoie"><i class="bx bx-check"></i> Enregistrer</button></div>
            </form>
        </div>
    </div>

    <div class="gu-conf-modal" id="editSalleModal">
        <div class="gm-back" data-conf-close="editSalleModal"></div>
        <div class="gm-card">
            <form action="<?= ROOT ?>/Salles/update" method="post">
                <div class="gm-head"><h5><i class="bx bx-edit-alt"></i> Modifier la salle</h5><button type="button" class="conf-act" data-conf-close="editSalleModal" style="border:0;background:transparent;color:#fff;"><i class="bx bx-x"></i></button></div>
                <div class="gm-body"><input type="hidden" id="esa_id" name="id_salle"><div class="row" style="row-gap:12px;">
                    <div class="col-md-7"><label class="form-label">Nom de la salle</label><input type="text" class="form-control" id="esa_nom" name="nom_salle" required></div>
                    <div class="col-md-5"><label class="form-label">Capacité</label><input type="number" min="1" class="form-control" id="esa_cap" name="capacite_salle" required></div>
                </div></div>
                <div class="gm-foot"><button type="button" class="btn btn-ghost" data-conf-close="editSalleModal">Annuler</button><button type="submit" class="btn btn-primary" name="modifier">Modifier</button></div>
            </form>
        </div>
    </div>

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <?php $this->view("Partials/foot") ?>
    <?php $this->view("Partials/footer") ?>
    <script>
        document.querySelectorAll('.btn-edit-salle').forEach(function (b) {
            b.addEventListener('click', function () {
                document.getElementById('esa_id').value = this.dataset.id;
                document.getElementById('esa_nom').value = this.dataset.nom;
                document.getElementById('esa_cap').value = this.dataset.cap;
                guConfOpen('editSalleModal');
            });
        });
    </script>
</body>

</html>
