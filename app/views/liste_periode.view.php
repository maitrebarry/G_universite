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
                                    <li class="breadcrumb-item active">Périodes</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-body">
                <div id="confLayout">
                    <?php $this->view("Partials/config_nav", ['active' => 'periodes']) ?>
                    <div class="conf-main">
                        <div class="conf-card">
                            <div class="d-flex align-items-center justify-content-between flex-wrap mb-2" style="gap:10px;">
                                <div class="gu-section-title"><span class="gu-ico-chip"><i class="bx bx-calendar"></i></span> Périodes <span class="badge badge-soft-primary"><?= count($datas) ?></span></div>
                                <button class="btn btn-primary" onclick="guConfOpen('addPerModal')"><i class="bx bx-plus"></i> Ajouter une période</button>
                            </div>
                            <?php if (empty($datas)): ?>
                                <div class="gu-empty"><i class="bx bx-calendar"></i>Aucune période.</div>
                            <?php else: ?>
                            <div class="gu-table-wrap" style="max-height:calc(100vh - 320px);">
                                <table class="gu-table" style="width:100%;">
                                    <thead><tr><th style="width:46px;text-align:center;">N°</th><th>Date de début</th><th>Date de fin</th><th class="text-center">Statut</th><th class="text-center" style="width:110px;">Actions</th></tr></thead>
                                    <tbody>
                                        <?php $i = 1; foreach ($datas as $l):
                                            $st = strtolower($l->status ?? '');
                                            $cls = (strpos($st, 'cours') !== false || strpos($st, 'inach') !== false) ? 'success' : 'secondary';
                                        ?>
                                            <tr>
                                                <td class="text-center"><?= $i++ ?></td>
                                                <td style="font-weight:var(--fw-medium);"><?= htmlspecialchars(date('d/m/Y', strtotime($l->date_debut))) ?></td>
                                                <td><?= htmlspecialchars(date('d/m/Y', strtotime($l->date_fin))) ?></td>
                                                <td class="text-center"><span class="badge badge-soft-<?= $cls ?>"><?= htmlspecialchars($l->status ?? '—') ?></span></td>
                                                <td class="text-center" style="white-space:nowrap;">
                                                    <button type="button" class="conf-act btn-edit-per" data-id="<?= $l->id_periode ?>" data-debut="<?= htmlspecialchars($l->date_debut) ?>" data-fin="<?= htmlspecialchars($l->date_fin) ?>" title="Modifier"><i class="bx bx-edit-alt"></i></button>
                                                    <a class="conf-act danger btn-conf-del" href="<?= ROOT ?>/Periodes/supprimer/<?= $l->id_periode ?>" data-nom="la période du <?= htmlspecialchars(date('d/m/Y', strtotime($l->date_debut))) ?>" title="Supprimer"><i class="bx bx-trash"></i></a>
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

    <div class="gu-conf-modal" id="addPerModal">
        <div class="gm-back" data-conf-close="addPerModal"></div>
        <div class="gm-card">
            <form action="" method="post">
                <div class="gm-head"><h5><i class="bx bx-plus-circle"></i> Nouvelle période</h5><button type="button" class="conf-act" data-conf-close="addPerModal" style="border:0;background:transparent;color:#fff;"><i class="bx bx-x"></i></button></div>
                <div class="gm-body"><div class="row" style="row-gap:12px;">
                    <div class="col-md-6"><label class="form-label">Date de début <span class="text-danger">*</span></label><input type="date" class="form-control" name="date_debut" required></div>
                    <div class="col-md-6"><label class="form-label">Date de fin <span class="text-danger">*</span></label><input type="date" class="form-control" name="date_fin" required></div>
                </div></div>
                <div class="gm-foot"><button type="button" class="btn btn-ghost" data-conf-close="addPerModal">Annuler</button><button type="submit" class="btn btn-primary" name="submit"><i class="bx bx-check"></i> Enregistrer</button></div>
            </form>
        </div>
    </div>

    <div class="gu-conf-modal" id="editPerModal">
        <div class="gm-back" data-conf-close="editPerModal"></div>
        <div class="gm-card">
            <form action="<?= ROOT ?>/Periodes/update" method="post">
                <div class="gm-head"><h5><i class="bx bx-edit-alt"></i> Modifier la période</h5><button type="button" class="conf-act" data-conf-close="editPerModal" style="border:0;background:transparent;color:#fff;"><i class="bx bx-x"></i></button></div>
                <div class="gm-body"><input type="hidden" id="ep_id" name="id_periode"><div class="row" style="row-gap:12px;">
                    <div class="col-md-6"><label class="form-label">Date de début</label><input type="date" class="form-control" id="ep_debut" name="date_debut" required></div>
                    <div class="col-md-6"><label class="form-label">Date de fin</label><input type="date" class="form-control" id="ep_fin" name="date_fin" required></div>
                </div></div>
                <div class="gm-foot"><button type="button" class="btn btn-ghost" data-conf-close="editPerModal">Annuler</button><button type="submit" class="btn btn-primary" name="modifier">Modifier</button></div>
            </form>
        </div>
    </div>

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <?php $this->view("Partials/foot") ?>
    <?php $this->view("Partials/footer") ?>
    <script>
        document.querySelectorAll('.btn-edit-per').forEach(function (b) {
            b.addEventListener('click', function () {
                document.getElementById('ep_id').value = this.dataset.id;
                document.getElementById('ep_debut').value = this.dataset.debut;
                document.getElementById('ep_fin').value = this.dataset.fin;
                guConfOpen('editPerModal');
            });
        });
    </script>
</body>

</html>
