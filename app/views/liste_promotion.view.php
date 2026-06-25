<?php $this->view("Partials/header") ?>

<style>
    #promoWrap .gu-kpis { display: flex; gap: 12px; flex-wrap: wrap; margin-bottom: 16px; }
    #promoWrap .gu-kpi { flex: 1; min-width: 140px; background: #fff; border: 1px solid #e7ecf5; border-radius: 12px; padding: 12px 16px; display: flex; align-items: center; gap: 12px; }
    #promoWrap .gu-kpi i { width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 21px; }
    #promoWrap .gu-kpi .v { font-size: 20px; font-weight: 800; color: #14346b; line-height: 1; }
    #promoWrap .gu-kpi .l { font-size: 12px; color: #5a6b86; }
    #promoWrap .gu-pact { display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 8px; border: 1px solid #e3e8f2; color: #1f4f9c; background: #fff; cursor: pointer; font-size: 17px; text-decoration: none; margin: 0 1px; transition: all .12s ease; }
    #promoWrap .gu-pact:hover { background: #eaf1fb; border-color: #1f4f9c; }
    #promoWrap .gu-pact.ok { color: #15803d; } #promoWrap .gu-pact.ok:hover { background: #e9f7ef; border-color: #9ed9b5; }
    #promoWrap .gu-pact.warn { color: #b76e00; } #promoWrap .gu-pact.warn:hover { background: #fff4e5; border-color: #f0c27a; }
    #promoWrap .gu-pact.danger { color: #b91c1c; } #promoWrap .gu-pact.danger:hover { background: #fdecec; border-color: #f0b4b4; }

    #menuPromotion { position: fixed; inset: 0; z-index: 1080; display: none; align-items: center; justify-content: center; }
    #menuPromotion.open { display: flex; }
    #menuPromotion .gm-back { position: absolute; inset: 0; background: rgba(15, 23, 42, .55); }
    #menuPromotion .gm-card { position: relative; background: #fff; border-radius: 12px; width: min(560px, 94vw); box-shadow: 0 20px 60px rgba(0, 0, 0, .3); overflow: hidden; }
    #menuPromotion .gm-head { padding: 14px 18px; background: #14346b; color: #fff; display: flex; align-items: center; justify-content: space-between; }
    #menuPromotion .gm-head h5 { margin: 0; font-weight: 700; font-size: 16px; }
    #menuPromotion .gm-body { padding: 18px; }
    #menuPromotion .gm-foot { padding: 12px 18px; border-top: 1px solid #eef2f9; display: flex; justify-content: flex-end; gap: 8px; }
    #menuPromotion .form-label { font-size: .85rem; color: #475569; font-weight: 500; }
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
                            <h5 class="content-header-title float-left pr-1 mb-0">
                                <?= isset($_SESSION['nom_departement']) ? strtoupper(htmlspecialchars($_SESSION['nom_departement'] . ' (' . $_SESSION['sigle_departement'] . ')')) : "IUFP" ?>
                            </h5>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb p-0 mb-0">
                                    <li class="breadcrumb-item"><a href="<?= ROOT ?>/Homes"><i class="bx bx-home-alt"></i></a></li>
                                    <li class="breadcrumb-item"><a href="<?= ROOT ?>/Filieres">Gestion Filière</a></li>
                                    <li class="breadcrumb-item active">Classes</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-body" id="promoWrap">
                <?php
                $nomF = !empty($promotions) ? strtoupper($promotions[0]->nom_filiere) : '';
                $sigleF = !empty($promotions) ? $promotions[0]->sigle_filiere : '';
                $nbEnCours = 0; $nbAttente = 0; $nbAchevee = 0;
                foreach ($promotions as $p) { if ($p->statut == 1) $nbEnCours++; elseif ($p->statut == 0) $nbAttente++; else $nbAchevee++; }
                ?>
                <div class="gu-kpis">
                    <div class="gu-kpi"><i style="background:#eaf1fb;color:#1f4f9c;" class="bx bx-group"></i><div><div class="v"><?= count($promotions) ?></div><div class="l">Classes</div></div></div>
                    <div class="gu-kpi"><i style="background:#e9f7ef;color:#15803d;" class="bx bx-play-circle"></i><div><div class="v"><?= $nbEnCours ?></div><div class="l">En cours</div></div></div>
                    <div class="gu-kpi"><i style="background:#fff4e5;color:#b76e00;" class="bx bx-stopwatch"></i><div><div class="v"><?= $nbAttente ?></div><div class="l">En attente</div></div></div>
                    <div class="gu-kpi"><i style="background:#eef2f9;color:#5a6b86;" class="bx bx-check-circle"></i><div><div class="v"><?= $nbAchevee ?></div><div class="l">Achevées</div></div></div>
                </div>

                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between flex-wrap mb-2" style="gap:10px;">
                                <div class="gu-section-title"><span class="gu-ico-chip"><i class="bx bx-group"></i></span> Classes — <?= htmlspecialchars($nomF) ?></div>
                                <button class="btn btn-primary ajouterPromotion" data-id="<?= htmlspecialchars($idFiliere) ?>" data-nom="<?= htmlspecialchars($sigleF) ?>"><i class="bx bx-plus"></i> Nouvelle classe</button>
                            </div>

                            <?php if (empty($promotions)): ?>
                                <div class="gu-empty"><i class="bx bx-group"></i>Aucune classe pour cette filière. Cliquez sur « Nouvelle classe ».</div>
                            <?php else: ?>
                            <div class="gu-table-wrap">
                                <table class="gu-table" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th style="width:46px;text-align:center;">N°</th>
                                            <th>Classe</th>
                                            <th>Niveau</th>
                                            <th>Année</th>
                                            <th class="text-center">Statut</th>
                                            <th class="text-center" style="width:170px;">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 1; foreach ($promotions as $promotion):
                                            $s = strtolower($promotion->sigle_semestre);
                                            $niveau = (in_array($s, ['s1', 's2'])) ? 'L1' : ((in_array($s, ['s3', 's4'])) ? 'L2' : 'L3');
                                            $label = strtoupper($promotion->sigle_filiere . '-' . $promotion->sigle_semestre . ' (' . $promotion->annee_universitaire . ')');
                                            $base = ROOT . '/Filieres/set_status_promotion/' . $promotion->id_filiere . '/' . $promotion->id_promotion;
                                        ?>
                                            <tr>
                                                <td class="text-center"><?= $i++ ?></td>
                                                <td style="font-weight:var(--fw-medium);"><?= strtoupper(htmlspecialchars($promotion->sigle_filiere . '-' . $niveau)) ?></td>
                                                <td><span class="badge badge-soft-primary"><?= $niveau ?></span></td>
                                                <td><?= htmlspecialchars($promotion->annee_universitaire) ?></td>
                                                <td class="text-center">
                                                    <?php if ($promotion->statut == 0): ?><span class="badge badge-soft-warning">En attente</span>
                                                    <?php elseif ($promotion->statut == 1): ?><span class="badge badge-soft-success">En cours</span>
                                                    <?php else: ?><span class="badge badge-soft-secondary">Achevée</span><?php endif ?>
                                                </td>
                                                <td class="text-center" style="white-space:nowrap;">
                                                    <a class="gu-pact" href="<?= ROOT ?>/Filieres/apercu_Filiere/<?= $promotion->id_filiere ?>" title="Aperçu de la maquette"><i class="bx bx-show"></i></a>
                                                    <?php if ($promotion->statut == 0 || $promotion->statut == 2): ?>
                                                        <a class="gu-pact ok statutButton" href="<?= $base ?>/1" data-promotion="<?= htmlspecialchars($label) ?>" data-etat="en marche" title="Mettre en marche"><i class="bx bx-play-circle"></i></a>
                                                    <?php endif ?>
                                                    <?php if ($promotion->statut == 1 || $promotion->statut == 2): ?>
                                                        <a class="gu-pact warn statutButton" href="<?= $base ?>/0" data-promotion="<?= htmlspecialchars($label) ?>" data-etat="en attente" title="Mettre en attente"><i class="bx bx-stopwatch"></i></a>
                                                    <?php endif ?>
                                                    <?php if ($promotion->statut == 0 || $promotion->statut == 1): ?>
                                                        <a class="gu-pact danger statutButton" href="<?= $base ?>/2" data-promotion="<?= htmlspecialchars($label) ?>" data-etat="en arrêt" title="Mettre en arrêt"><i class="bx bx-stop-circle"></i></a>
                                                    <?php endif ?>
                                                    <?php if ($promotion->statut == 1): ?>
                                                        <a class="gu-pact" href="<?= ROOT ?>/Emploi_du_temps/ajouter_EDT/<?= $promotion->id_filiere . '/' . $promotion->id_promotion ?>" title="Emploi du temps"><i class="bx bx-calendar-plus"></i></a>
                                                    <?php endif ?>
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

    <!-- Modale Nouvelle classe (custom) -->
    <div id="menuPromotion" aria-hidden="true">
        <div class="gm-back" data-close-promo></div>
        <div class="gm-card" role="dialog" aria-modal="true">
            <form action="<?= ROOT ?>/Filieres/ajouter_promotion" method="post">
                <div class="gm-head">
                    <h5><i class="bx bx-plus-circle"></i> Nouvelle classe — <span class="nomFiliere"></span></h5>
                    <button type="button" class="gu-icon-act" data-close-promo aria-label="Fermer" style="border:0;background:transparent;color:#fff;font-size:22px;cursor:pointer;"><i class="bx bx-x"></i></button>
                </div>
                <div class="gm-body">
                    <input type="hidden" name="idFiliere" id="filiere">
                    <div class="row" style="row-gap:12px;">
                        <div class="col-12 col-md-5">
                            <label class="form-label">Année universitaire <span class="text-danger">*</span></label>
                            <input type="text" id="anneeUniversitaire" name="anneeUniversitaire" placeholder="YYYY-YYYY" maxlength="9" class="form-control text-center" required>
                        </div>
                        <div class="col-12 col-md-7">
                            <label class="form-label">Semestre de début <span class="text-danger">*</span></label>
                            <select name="idParcours" id="semestres" class="form-select" required></select>
                        </div>
                    </div>
                </div>
                <div class="gm-foot">
                    <button type="button" class="btn btn-ghost" data-close-promo>Annuler</button>
                    <button type="submit" class="btn btn-primary" name="savePromotion"><i class="bx bx-check"></i> Enregistrer</button>
                </div>
            </form>
        </div>
    </div>

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <?php $this->view("Partials/foot") ?>
    <?php $this->view("Partials/footer") ?>
    <script src="<?= ROOT ?>/assets/mon_js/edt.js"></script>
    <script>
        var infoFiliere = [];
        function openPromoModal() { document.getElementById("menuPromotion").classList.add("open"); }
        function closePromoModal() { document.getElementById("menuPromotion").classList.remove("open"); }
        $(document).on("click", "[data-close-promo]", closePromoModal);
        $(document).on("keydown", function (e) { if (e.key === "Escape") closePromoModal(); });

        $(".ajouterPromotion").click(async function () {
            var idFiliere = $(this).data("id");
            $("#filiere").val(idFiliere);
            $(".nomFiliere").text($(this).data("nom"));
            openPromoModal();
            infoFiliere = await infosFiliere(idFiliere);
            semestresFiliere(infoFiliere);
        });

        $(".statutButton").click(function (event) {
            event.preventDefault();
            var url = $(this).attr("href");
            Swal.fire({
                title: "Êtes-vous sûr ?",
                text: "La classe " + $(this).data("promotion") + " sera mise " + $(this).data("etat") + ".",
                type: "warning", icon: "warning",
                showCancelButton: true, confirmButtonColor: "#1f4f9c", cancelButtonColor: "#d33",
                confirmButtonText: "Confirmer", cancelButtonText: "Annuler"
            }).then(function (result) {
                if (result && (result.isConfirmed || (result.value && !result.dismiss))) { window.location.href = url; }
            });
        });

        $(document).ready(function () {
            setDefaultAcademicYear();
            $("#anneeUniversitaire").keyup(function (event) { formatAcademicYear(event); });
        });
    </script>
</body>

</html>
