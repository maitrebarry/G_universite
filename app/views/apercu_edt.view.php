<?php
$premierEns = !empty($enseignants) ? $enseignants[0] : null;
$titre =
    'edt-' . ($infosEdt->module->sigle_module ?? '') . '-' . ($infosEdt->promotion->sigle_filiere ?? '') . '-' .
    ($infosEdt->promotion->sigle_semestre ?? '') . '-' . ($infosEdt->promotion->annee_universitaire ?? '')
    . ($premierEns ? '_' . strtoupper($premierEns->enseignant_nom) : '');
$valide = ((int) ($infosEdt->edt->statut ?? 0) === 1);
?>
<?php $this->view("Partials/header") ?>

<style>
    /* ===== Feuille EDT (élément #edt capturé par html2pdf : couleurs concrètes) ===== */
    #edt { background: #fff; color: #1f2937; font-family: 'Inter', system-ui, sans-serif; }
    #edt .edt-head { display: flex; align-items: center; justify-content: space-between; gap: 16px;
        border-bottom: 3px solid #1f4f9c; padding-bottom: 12px; }
    #edt .edt-head .brand { display: flex; align-items: center; gap: 12px; }
    #edt .edt-head img { width: 76px; height: auto; border-radius: 6px; }
    #edt .edt-inst { font-weight: 700; color: #1f4f9c; font-size: 15px; line-height: 1.25; }
    #edt .edt-inst small { display: block; color: #64748b; font-weight: 500; font-size: 12px; }
    #edt .edt-formation { font-weight: 600; color: #334155; white-space: nowrap; }
    #edt .edt-title { text-align: center; text-transform: uppercase; font-weight: 700; letter-spacing: .05em;
        color: #1f2937; margin: 14px 0 4px; font-size: 16px; }
    #edt .edt-period { text-align: center; color: #475569; font-size: 13px; margin-bottom: 14px; }
    #edt .edt-meta { display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; margin: 0 0 16px; }
    #edt .edt-meta .cell { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 10px; text-align: center; }
    #edt .edt-meta .k { font-size: 10px; text-transform: uppercase; letter-spacing: .05em; color: #64748b; margin-bottom: 3px; }
    #edt .edt-meta .v { font-weight: 700; color: #1f2937; font-size: 14px; }
    #edt table.edt-grid { width: 100%; border-collapse: collapse; }
    #edt table.edt-grid th, #edt table.edt-grid td { border: 1px solid #cbd5e1; text-align: center; padding: 9px 6px; vertical-align: middle; }
    #edt table.edt-grid thead th { background: #1f4f9c; color: #fff; text-transform: uppercase; font-size: 12px; letter-spacing: .03em; }
    #edt table.edt-grid tbody tr:nth-child(even) td { background: #f1f5f9; }
    #edt .edt-h { font-weight: 600; color: #1f4f9c; white-space: nowrap; }
    #edt .edt-mod { font-weight: 700; font-size: 13px; color: #1f2937; }
    #edt .edt-type { font-size: 11px; color: #64748b; font-style: italic; }
    #edt .edt-chip { display: inline-block; background: #eef2fb; color: #1f4f9c; border: 1px solid #d6def2;
        border-radius: 999px; padding: 3px 11px; font-size: 12px; margin: 2px 4px 2px 0; }
    #edt .edt-legend { margin-top: 12px; font-size: 13px; color: #334155; }
    #edt .edt-people { margin-top: 14px; }
    #edt .edt-people .lbl { font-size: 11px; text-transform: uppercase; letter-spacing: .04em; color: #64748b; margin-bottom: 4px; }
    #edt .edt-sign { margin-top: 22px; text-align: right; color: #334155; }
    #edt .edt-sign .role { font-weight: 700; font-size: 13px; }
    #edt .edt-sign img { display: inline-block; max-height: 60px; max-width: 170px; margin: 4px 0; }
    #edt .edt-sign .dr { font-size: 13px; }
    #edt .edt-sign .dr i { font-size: 11px; color: #64748b; }
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
                            <h5 class="content-header-title float-left pr-1 mb-0">Emploi du temps</h5>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb p-0 mb-0">
                                    <li class="breadcrumb-item"><a href="<?= ROOT ?>/Homes"><i class="bx bx-home-alt"></i></a></li>
                                    <li class="breadcrumb-item"><a href="<?= ROOT ?>/Emploi_du_temps">Gestion EDT</a></li>
                                    <li class="breadcrumb-item active">Aperçu</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-body">
                <div id="message"></div>

                <!-- Barre d'action (écran uniquement) -->
                <div class="d-flex align-items-center flex-wrap mb-2" style="gap:10px;">
                    <a href="<?= ROOT ?>/Emploi_du_temps" class="btn btn-ghost"><i class="bx bx-arrow-back"></i> Retour</a>
                    <?php if ($valide): ?>
                        <span class="badge badge-soft-success"><i class="bx bx-check-circle"></i> Validé</span>
                    <?php else: ?>
                        <span class="badge badge-soft-warning"><i class="bx bx-time"></i> En cours</span>
                    <?php endif; ?>
                    <div class="ms-auto d-flex" style="gap:10px;">
                        <a href="<?= ROOT ?>/Emploi_du_temps/editer_edt/<?= $infosEdt->edt->id_edt ?>" class="btn btn-soft-primary"><i class="bx bx-edit-alt"></i> Éditer</a>
                        <button type="button" class="btn btn-primary" id="print" data-nom="<?= htmlspecialchars($titre) ?>"><i class="bx bx-printer"></i> Imprimer (PDF)</button>
                    </div>
                </div>

                <!-- Feuille imprimable -->
                <div class="card">
                    <div class="card-body" id="edt">
                        <div class="edt-head">
                            <div class="brand">
                                <img src="<?= ROOT ?>/assets/images/logo.jpg" alt="IUFP">
                                <div class="edt-inst">Institut Universitaire de Formation Professionnelle
                                    <small>Université de Ségou</small>
                                </div>
                            </div>
                            <div class="edt-formation">Formation Initiale</div>
                        </div>

                        <div class="edt-title">Emploi du temps</div>
                        <div class="edt-period">du <?= htmlspecialchars($infosEdt->edt->date_debut) ?> au <?= htmlspecialchars($infosEdt->edt->date_fin) ?></div>

                        <div class="edt-meta">
                            <div class="cell"><div class="k">Filière</div><div class="v"><?= strtoupper(htmlspecialchars($infosEdt->promotion->sigle_filiere ?? '—')) ?></div></div>
                            <div class="cell"><div class="k">Année universitaire</div><div class="v"><?= htmlspecialchars($infosEdt->promotion->annee_universitaire ?? '—') ?></div></div>
                            <div class="cell"><div class="k">Niveau</div><div class="v"><?= strtoupper(htmlspecialchars($infosEdt->promotion->sigle_semestre ?? '—')) ?></div></div>
                            <div class="cell"><div class="k">Salle(s)</div><div class="v" style="font-size:12px;">
                                <?php if ($premierEns): ?>
                                    <?php if (in_array(trim(strtoupper($premierEns->groupe)), ['GP', 'GROUPE PRINCIPAL'], true)): ?>
                                        <?= strtoupper(htmlspecialchars($premierEns->nom_salle)) ?>
                                    <?php else: foreach ($enseignants as $e): ?>
                                        <span class="d-inline-block mr-1"><?= htmlspecialchars($e->groupe) . ' (' . strtoupper(htmlspecialchars($e->nom_salle)) . ')' ?></span>
                                    <?php endforeach; endif; ?>
                                <?php else: ?>—<?php endif; ?>
                            </div></div>
                        </div>

                        <table class="edt-grid">
                            <thead>
                                <tr>
                                    <th>Horaire</th>
                                    <?php foreach ($jours as $jour): ?>
                                        <?php if (trim($jour->nom_jour) !== ''): ?>
                                            <th><?= strtoupper(htmlspecialchars($jour->nom_jour)) ?></th>
                                        <?php endif ?>
                                    <?php endforeach ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($horairesEdt as $horaire): ?>
                                    <tr>
                                        <td class="edt-h"><?= substr($horaire->heure_debut, 0, 5) ?> – <?= substr($horaire->heure_fin, 0, 5) ?></td>
                                        <?php foreach ($horaire->taches as $tache): ?>
                                            <td>
                                                <?php if (strtoupper($tache->type_tache) !== 'X'): ?>
                                                    <div class="edt-mod"><?= (strlen($infosEdt->module->nom_module) < 20) ? strtoupper(htmlspecialchars($infosEdt->module->nom_module)) : strtoupper(htmlspecialchars($infosEdt->module->sigle_module)) ?></div>
                                                    <div class="edt-type"><?= strtoupper(htmlspecialchars($tache->type_tache)) ?></div>
                                                <?php endif ?>
                                            </td>
                                        <?php endforeach ?>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>

                        <?php if (strlen($infosEdt->module->nom_module) >= 20): ?>
                            <div class="edt-legend"><b><?= strtoupper(htmlspecialchars($infosEdt->module->sigle_module)) ?></b> = <?= strtoupper(htmlspecialchars($infosEdt->module->nom_module)) ?></div>
                        <?php endif ?>

                        <div class="edt-people">
                            <div class="lbl">Enseignant(s)</div>
                            <?php if ($premierEns): foreach ($enseignants as $e): ?>
                                <span class="edt-chip"><?= ucwords(strtolower(htmlspecialchars($e->enseignant_prenom . ' ' . $e->enseignant_nom))) ?><?= !empty(trim($e->groupe)) ? ' · ' . htmlspecialchars($e->groupe) : '' ?></span>
                            <?php endforeach; else: ?>
                                <span class="text-muted">Aucun enseignant affecté.</span>
                            <?php endif; ?>
                        </div>

                        <div class="edt-sign">
                            <div>Ségou, le <?= date('d-m-Y') ?></div>
                            <div class="role">Le Chef de DER <?= isset($_SESSION['sigle_departement']) ? strtoupper(htmlspecialchars($_SESSION['sigle_departement'])) : '' ?></div>
                            <?php if (!empty($_SESSION['signature'])): ?>
                                <div><img src="<?= ROOT . htmlspecialchars($_SESSION['signature']) ?>" alt="signature"></div>
                            <?php endif; ?>
                            <div class="dr">Dr <?= isset($_SESSION['nom_prenom']) ? strtoupper(htmlspecialchars($_SESSION['nom_prenom'])) : '' ?>
                                <?php if (!empty($_SESSION['nom_grade'])): ?><br><i><?= strtoupper(htmlspecialchars($_SESSION['nom_grade'])) ?></i><?php endif; ?>
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
</body>

</html>
<script src="<?= ROOT ?>/assets/mon_js/edt.js"></script>
<script>
    $('#print').click(function () {
        window.scrollTo({ top: 0, behavior: "smooth" });
        const nomEdt = $(this).data('nom');
        setTimeout(function () { imprimer(nomEdt); }, 500);
    });
</script>
