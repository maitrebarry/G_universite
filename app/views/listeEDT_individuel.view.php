<?php
$titre = "EMPLOI DU TEMPS INDIVIDUEL : du " . date('d-m-Y', strtotime($date_debut)) . " au " . date('d-m-Y', strtotime($date_fin));
$statut = $enseignant->enseignant_statut ?? '';
$statutLabel = ($statut === 'PERMANANT') ? 'Permanent' : 'Vacataire';
?>
<?php $this->view("Partials/header") ?>

<style>
    #edtWrap .gu-toolbar { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; background: #fff; border: 1px solid #e7ecf5; border-radius: 12px; padding: 12px 16px; margin-bottom: 16px; }
    #edtWrap .gu-toolbar .form-select { min-width: 220px; }
    /* Document imprimable (#edtIndi) — couleurs concrètes */
    #edtIndi { background: #fff; border: 1px solid #e7ecf5; border-radius: 12px; padding: 24px; font-family: Arial, Helvetica, sans-serif; color: #111; }
    #edtIndi .doc-head { text-align: center; margin-bottom: 16px; }
    #edtIndi .doc-head h1 { font-size: 16px; font-weight: bold; color: #14346b; margin: 0; }
    #edtIndi .doc-head h2 { font-size: 13px; color: #1f4f9c; margin: 4px 0 0; font-weight: bold; }
    #edtIndi .doc-head .rng { font-size: 11px; color: #555; }
    #edtIndi .infos { display: flex; flex-wrap: wrap; gap: 6px 24px; font-size: 12px; margin-bottom: 14px; }
    #edtIndi .infos b { color: #14346b; }
    #edtIndi .hrs { display: flex; gap: 10px; margin-bottom: 16px; }
    #edtIndi .hrs .box { flex: 1; border: 1px solid #c7d2e6; border-radius: 8px; padding: 8px; text-align: center; background: #f4f7fc; }
    #edtIndi .hrs .box .lab { font-size: 10px; color: #5a6b86; text-transform: uppercase; letter-spacing: .3px; }
    #edtIndi .hrs .box .val { font-size: 19px; font-weight: 800; color: #14346b; }
    #edtIndi .hrs .box.eff { background: #eaf1fb; } #edtIndi .hrs .box.eff .val { color: #1f4f9c; }
    #edtIndi .hrs .box.sup { background: #e9f7ef; border-color: #9ed9b5; } #edtIndi .hrs .box.sup .val { color: #15803d; }
    #edtIndi table { width: 100%; border-collapse: collapse; font-size: 12px; }
    #edtIndi th, #edtIndi td { border: 1px solid #9aa3b2; padding: 8px; text-align: left; vertical-align: top; }
    #edtIndi thead th { background: #14346b; color: #fff; text-align: center; }
    #edtIndi tbody tr:nth-child(even) { background: #f6f8fc; }
    #edtIndi .wk { white-space: nowrap; font-weight: 600; color: #0f2a52; }
    #edtIndi .mod-chip { display: inline-block; background: #eef3fb; border: 1px solid #dbe3f0; border-radius: 6px; padding: 2px 8px; margin: 2px; font-size: 11px; }
    #edtIndi .mod-chip b { color: #1f4f9c; }
    #edtIndi .sign { margin-top: 22px; text-align: right; font-size: 12px; }
    @media print { .no-print { display: none !important; } }
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
                            <h5 class="content-header-title float-left pr-1 mb-0">EDT individuel</h5>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb p-0 mb-0">
                                    <li class="breadcrumb-item"><a href="<?= ROOT ?>/Homes"><i class="bx bx-home-alt"></i></a></li>
                                    <li class="breadcrumb-item"><a href="<?= ROOT ?>/Enseignants">Enseignants</a></li>
                                    <li class="breadcrumb-item active">Emploi du temps</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-body" id="edtWrap">
                <!-- Barre d'outils (non imprimée) -->
                <div class="gu-toolbar no-print">
                    <form method="POST" action="<?= ROOT ?>/Enseignants/listeEDT_individuel/<?= (int) ($id ?? 0) ?>" class="d-flex align-items-center" style="gap:8px;">
                        <span style="font-size:12.5px;color:#5a6b86;"><i class="bx bx-calendar"></i> Période :</span>
                        <select name="periode_id" class="form-select" onchange="this.form.submit()">
                            <?php foreach (($periodes ?? []) as $p): ?>
                                <option value="<?= $p->id_periode ?>" <?= ($p->id_periode == ($periode_id ?? null)) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars(date('d/m/Y', strtotime($p->date_debut)) . ' → ' . date('d/m/Y', strtotime($p->date_fin))) ?>
                                </option>
                            <?php endforeach ?>
                        </select>
                    </form>
                    <div style="display:flex;gap:8px;">
                        <a href="<?= ROOT ?>/Enseignants" class="btn btn-ghost"><i class="bx bx-arrow-back"></i> Retour</a>
                        <button type="button" class="btn btn-primary" onclick="imprimerEdtIndi('<?= htmlspecialchars($titre, ENT_QUOTES) ?>')"><i class="bx bx-printer"></i> Imprimer (PDF)</button>
                    </div>
                </div>

                <?php if (!empty($errors)): ?>
                    <div class="mb-2 p-2 no-print" style="background:#fff4e5;border:1px solid #f0c27a;border-radius:8px;color:#b76e00;font-size:13px;">
                        <?php foreach ($errors as $error): ?><div><i class="bx bx-info-circle"></i> <?= htmlspecialchars($error) ?></div><?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <!-- Document imprimable -->
                <div id="edtIndi">
                    <div class="doc-head">
                        <h1>INSTITUT UNIVERSITAIRE DE LA FORMATION PROFESSIONNELLE (IUFP)</h1>
                        <h2>Emploi du temps individuel — <?= htmlspecialchars(($enseignant->enseignant_prenom ?? '') . ' ' . ($enseignant->enseignant_nom ?? '')) ?></h2>
                        <div class="rng">du <?= date('d-m-Y', strtotime($date_debut)) ?> au <?= date('d-m-Y', strtotime($date_fin)) ?></div>
                    </div>

                    <div class="infos">
                        <div><b>Grade :</b> <?= htmlspecialchars($enseignant->nom_grade ?? '—') ?></div>
                        <div><b>Statut :</b> <?= htmlspecialchars($statutLabel) ?></div>
                        <div><b>Matricule :</b> <?= htmlspecialchars($enseignant->enseignant_matricule ?? '—') ?></div>
                        <div><b>Semestres/Promotions :</b> <?= !empty($semestres_promotions) ? implode(', ', array_map('htmlspecialchars', $semestres_promotions)) : '—' ?></div>
                    </div>

                    <div class="hrs">
                        <div class="box eff"><div class="lab">Heures effectuées</div><div class="val"><?= (int) $heures_totales ?> h</div></div>
                        <div class="box"><div class="lab">Heures dues</div><div class="val"><?= (int) $heures_dues ?> h</div></div>
                        <div class="box sup"><div class="lab">Heures supplémentaires</div><div class="val"><?= (int) $heures_supp ?> h</div></div>
                    </div>

                    <table>
                        <thead><tr><th style="width:200px;">Semaine</th><th>Cours (module · volume horaire · classe)</th><th style="width:80px;">Total</th></tr></thead>
                        <tbody>
                            <?php if (!empty($emplois_du_temps)):
                                $weeks = [];
                                foreach ($emplois_du_temps as $edt) {
                                    $k = $edt->date_debut . '|' . $edt->date_fin;
                                    if (!isset($weeks[$k])) $weeks[$k] = ['debut' => $edt->date_debut, 'fin' => $edt->date_fin, 'mods' => [], 'h' => 0];
                                    $weeks[$k]['mods'][] = $edt;
                                    $weeks[$k]['h'] += (int) $edt->heure_total;
                                }
                                foreach ($weeks as $w): ?>
                                    <tr>
                                        <td class="wk">Du <?= date('d-m-Y', strtotime($w['debut'])) ?><br>au <?= date('d-m-Y', strtotime($w['fin'])) ?></td>
                                        <td>
                                            <?php foreach ($w['mods'] as $m): ?>
                                                <span class="mod-chip"><?= htmlspecialchars($m->nom_module) ?> <b>(<?= (int) $m->heure_total ?>h)</b> · <?= htmlspecialchars(($m->sigle_filiere ?? '') . '-' . ($m->sigle_semestre ?? '')) ?></span>
                                            <?php endforeach ?>
                                        </td>
                                        <td class="text-center" style="text-align:center;font-weight:700;"><?= $w['h'] ?> h</td>
                                    </tr>
                                <?php endforeach;
                            else: ?>
                                <tr><td colspan="3" style="text-align:center;color:#888;">Aucun cours sur cette période.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>

                    <div class="sign">
                        <div>Ségou, le <?= date('d-m-Y') ?></div>
                        <div style="margin-top:6px;font-weight:bold;">Le Chef de Département <?= isset($_SESSION['sigle_departement']) ? strtoupper(htmlspecialchars($_SESSION['sigle_departement'])) : '' ?></div>
                        <?php if (!empty($_SESSION['signature'])): ?>
                            <div><img src="<?= ROOT . htmlspecialchars($_SESSION['signature']) ?>" alt="signature" style="width:150px;max-height:60px;"></div>
                        <?php endif ?>
                        <div>Dr <?= isset($_SESSION['nom_prenom']) ? strtoupper(htmlspecialchars($_SESSION['nom_prenom'])) : '' ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <?php $this->view("Partials/foot") ?>
    <?php $this->view("Partials/footer") ?>
    <script src="<?= ROOT ?>/assets/mon_js/pdfedIndividuel.js"></script>
</body>

</html>
