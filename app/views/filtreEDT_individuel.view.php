<?php $this->view("Partials/header") ?>

<style>
    #filtreEdt .gu-card { background: #fff; border: 1px solid #e7ecf5; border-radius: 12px; padding: 20px; max-width: 640px; margin: 0 auto; }
    #filtreEdt .form-label { font-size: .85rem; font-weight: 500; color: #475569; margin-bottom: 4px; }
</style>

<body class="vertical-layout vertical-menu-modern boxicon-layout no-card-shadow 2-columns navbar-sticky footer-static"
    data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">

    <?php $this->view("Partials/navbar") ?>
    <?php $this->view("Partials/seibar") ?>

    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-12 mb-2 mt-1">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h5 class="content-header-title float-left pr-1 mb-0">EDT individuel</h5>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb p-0 mb-0">
                                    <li class="breadcrumb-item"><a href="<?= ROOT ?>/Homes"><i class="bx bx-home-alt"></i></a></li>
                                    <li class="breadcrumb-item"><a href="<?= ROOT ?>/Enseignants">Enseignants</a></li>
                                    <li class="breadcrumb-item active">Choix de la période</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-body" id="filtreEdt">
                <div class="gu-card">
                    <div class="gu-section-title"><span class="gu-ico-chip"><i class="bx bx-calendar"></i></span> Emploi du temps individuel</div>

                    <?php if (!empty($errors)): ?>
                        <div class="mb-2 p-2" style="background:#fdecec;border:1px solid #f0b4b4;border-radius:8px;color:#b91c1c;font-size:13px;">
                            <?php foreach ($errors as $error): ?><div><i class="bx bx-error-circle"></i> <?= htmlspecialchars($error) ?></div><?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <?php if (empty($periodes)): ?>
                        <div class="gu-empty"><i class="bx bx-calendar-x"></i>Aucune période enregistrée. Créez d'abord une période dans la configuration.</div>
                    <?php else: ?>
                        <p class="text-muted" style="font-size:13px;">Sélectionnez la période pour afficher l'emploi du temps de l'enseignant.</p>
                        <form action="<?= ROOT ?>/Enseignants/listeEDT_individuel/<?= (int) ($id ?? 0) ?>" method="POST">
                            <div class="row align-items-end" style="row-gap:14px;">
                                <div class="col-12 col-md-6">
                                    <label class="form-label">Période</label>
                                    <select name="periode_id" id="periode_id" class="form-select" required>
                                        <option value="" disabled selected>Choisir une période…</option>
                                        <?php foreach ($periodes as $p): ?>
                                            <option value="<?= $p->id_periode ?>" data-debut="<?= htmlspecialchars($p->date_debut) ?>" data-fin="<?= htmlspecialchars($p->date_fin) ?>">
                                                <?= htmlspecialchars(date('d/m/Y', strtotime($p->date_debut)) . ' → ' . date('d/m/Y', strtotime($p->date_fin))) ?>
                                            </option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                                <div class="col-6 col-md-3">
                                    <label class="form-label">Du (optionnel)</label>
                                    <input type="date" name="date_debut" id="f_debut" class="form-control">
                                </div>
                                <div class="col-6 col-md-3">
                                    <label class="form-label">Au (optionnel)</label>
                                    <input type="date" name="date_fin" id="f_fin" class="form-control">
                                </div>
                            </div>
                            <div class="d-flex justify-content-end mt-3" style="gap:8px;">
                                <a href="<?= ROOT ?>/Enseignants" class="btn btn-ghost">Retour</a>
                                <button type="submit" class="btn btn-primary"><i class="bx bx-show"></i> Afficher l'emploi du temps</button>
                            </div>
                        </form>
                    <?php endif ?>
                </div>
            </div>
        </div>
    </div>

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <?php $this->view("Partials/foot") ?>
    <?php $this->view("Partials/footer") ?>
    <script>
        // Pré-remplit les dates selon la période choisie
        $("#periode_id").on("change", function () {
            var o = this.options[this.selectedIndex];
            $("#f_debut").val($(o).data("debut") || "");
            $("#f_fin").val($(o).data("fin") || "");
        });
    </script>
</body>

</html>
