<?php $this->view("Partials/header") ?>

<style>
    #dash .kpi { display: flex; align-items: center; gap: 14px; padding: 16px 18px; border-radius: 14px; background: #fff; border: 1px solid #e7ecf5; height: 100%; }
    #dash .kpi-ico { width: 54px; height: 54px; border-radius: 13px; display: flex; align-items: center; justify-content: center; font-size: 27px; flex: 0 0 auto; }
    #dash .kpi-val { font-size: 24px; font-weight: 800; color: #14346b; line-height: 1.1; }
    #dash .kpi-lab { font-size: 12.5px; color: #5a6b86; }
    #dash .kpi-sub { font-size: 11px; color: #8a97ad; }
    #dash .panel { background: #fff; border: 1px solid #e7ecf5; border-radius: 14px; padding: 16px 18px; height: 100%; }
    #dash .panel h6 { color: #14346b; font-weight: 700; margin: 0 0 14px; display: flex; align-items: center; gap: 7px; }

    /* Bar chart vertical (inscrits/année) */
    #dash .bars { display: flex; align-items: flex-end; gap: 18px; height: 180px; padding-top: 10px; }
    #dash .bars .col { flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: flex-end; height: 100%; }
    #dash .bars .bar { width: 60%; max-width: 64px; background: linear-gradient(180deg, #4f86d6, #1f4f9c); border-radius: 7px 7px 0 0; min-height: 4px; position: relative; transition: height .5s ease; }
    #dash .bars .bar .v { position: absolute; top: -20px; left: 0; right: 0; text-align: center; font-size: 12px; font-weight: 700; color: #14346b; }
    #dash .bars .lab { margin-top: 8px; font-size: 12px; color: #5a6b86; }

    /* Top filières (barres horizontales) */
    #dash .tf-row { margin-bottom: 12px; }
    #dash .tf-head { display: flex; justify-content: space-between; font-size: 12.5px; margin-bottom: 4px; }
    #dash .tf-track { height: 9px; background: #eef2f9; border-radius: 6px; overflow: hidden; }
    #dash .tf-fill { height: 100%; border-radius: 6px; background: linear-gradient(90deg, #1f4f9c, #4f86d6); }

    #dash table.dep { width: 100%; border-collapse: collapse; font-size: 12.5px; }
    #dash table.dep th, #dash table.dep td { border-bottom: 1px solid #eef2f9; padding: 8px 10px; text-align: left; }
    #dash table.dep th { color: #5a6b86; font-weight: 600; text-transform: uppercase; font-size: 10.5px; letter-spacing: .3px; }
    #dash table.dep td.num { text-align: right; font-weight: 600; color: #0f2a52; }

    #dash .shortcut { display: flex; align-items: center; gap: 12px; padding: 14px 16px; border-radius: 12px; border: 1px solid #e7ecf5; background: #fff; color: #14346b; text-decoration: none; transition: all .15s ease; height: 100%; }
    #dash .shortcut:hover { border-color: #1f4f9c; box-shadow: 0 6px 18px rgba(31, 79, 156, .12); transform: translateY(-2px); }
    #dash .shortcut i { font-size: 24px; color: #1f4f9c; }
    #dash .shortcut .t { font-weight: 600; }
</style>

<body class="vertical-layout vertical-menu-modern boxicon-layout no-card-shadow 2-columns navbar-sticky footer-static"
    data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">

    <?php $this->view("Partials/navbar") ?>
    <?php $this->view("Partials/seibar") ?>

    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-12 mb-1 mt-1">
                    <h2 style="font-weight:800;color:#14346b;margin:0;">Bonjour, <?= isset($_SESSION['nom_prenom']) ? htmlspecialchars($_SESSION['nom_prenom']) : 'Administrateur' ?> 👋</h2>
                    <p style="color:#5a6b86;margin:2px 0 0;"><?php date_default_timezone_set('Africa/Bamako'); $jours=['Sunday'=>'Dimanche','Monday'=>'Lundi','Tuesday'=>'Mardi','Wednesday'=>'Mercredi','Thursday'=>'Jeudi','Friday'=>'Vendredi','Saturday'=>'Samedi']; $mois=['January'=>'janvier','February'=>'février','March'=>'mars','April'=>'avril','May'=>'mai','June'=>'juin','July'=>'juillet','August'=>'août','September'=>'septembre','October'=>'octobre','November'=>'novembre','December'=>'décembre']; echo ($jours[date('l')]??'').' '.date('d').' '.($mois[date('F')]??'').' '.date('Y'); ?> · Institut Universitaire de Formation Professionnelle (IUFP)</p>
                </div>
            </div>

            <div class="content-body" id="dash">
                <?php $fmt = function ($v) { return number_format((int) $v, 0, ',', ' '); }; ?>

                <!-- KPI principaux -->
                <div class="row mb-1" style="row-gap:18px;">
                    <div class="col-12 col-md-6 col-xl-3">
                        <div class="kpi"><div class="kpi-ico" style="background:#eaf1fb;color:#1f4f9c;"><i class="bx bx-group"></i></div>
                            <div><div class="kpi-val"><?= $fmt($data['total_etudiants']) ?></div><div class="kpi-lab">Étudiants</div></div></div>
                    </div>
                    <div class="col-12 col-md-6 col-xl-3">
                        <div class="kpi"><div class="kpi-ico" style="background:#e9f7ef;color:#15803d;"><i class="bx bx-trophy"></i></div>
                            <div><div class="kpi-val"><?= $data['taux_reussite'] ?>%</div><div class="kpi-lab">Taux de réussite</div><div class="kpi-sub"><?= $fmt($data['nb_admis']) ?>/<?= $fmt($data['nb_evalues']) ?> évalués</div></div></div>
                    </div>
                    <div class="col-12 col-md-6 col-xl-3">
                        <div class="kpi"><div class="kpi-ico" style="background:#fff4e5;color:#b76e00;"><i class="bx bx-wallet"></i></div>
                            <div><div class="kpi-val"><?= $fmt($data['recettes']) ?></div><div class="kpi-lab">Recettes (FCFA)</div></div></div>
                    </div>
                    <div class="col-12 col-md-6 col-xl-3">
                        <div class="kpi"><div class="kpi-ico" style="background:#f3eafe;color:#6b3fbf;"><i class="bx bx-bookmark-alt"></i></div>
                            <div><div class="kpi-val"><?= $fmt($data['total_promotions']) ?></div><div class="kpi-lab">Promotions</div></div></div>
                    </div>
                </div>

                <!-- KPI secondaires -->
                <div class="row mb-1" style="row-gap:18px;">
                    <div class="col-6 col-xl-3"><div class="kpi"><div class="kpi-ico" style="background:#eaf1fb;color:#1f4f9c;"><i class="bx bx-book-content"></i></div><div><div class="kpi-val"><?= $fmt($data['total_filieres']) ?></div><div class="kpi-lab">Filières</div></div></div></div>
                    <div class="col-6 col-xl-3"><div class="kpi"><div class="kpi-ico" style="background:#e9f7ef;color:#15803d;"><i class="bx bx-chalkboard"></i></div><div><div class="kpi-val"><?= $fmt($data['total_enseignants']) ?></div><div class="kpi-lab">Enseignants</div></div></div></div>
                    <div class="col-6 col-xl-3"><div class="kpi"><div class="kpi-ico" style="background:#fff4e5;color:#b76e00;"><i class="bx bx-building-house"></i></div><div><div class="kpi-val"><?= $fmt($data['total_departements']) ?></div><div class="kpi-lab">Départements</div></div></div></div>
                    <div class="col-6 col-xl-3"><div class="kpi"><div class="kpi-ico" style="background:#fdeaea;color:#b91c1c;"><i class="bx bx-line-chart"></i></div><div><div class="kpi-val"><?= $fmt($data['nb_evalues']) ?></div><div class="kpi-lab">Étudiants évalués</div></div></div></div>
                </div>

                <!-- Graphiques -->
                <div class="row mb-1" style="row-gap:18px;">
                    <div class="col-12 col-lg-5">
                        <div class="panel">
                            <h6><i class="bx bx-bar-chart-alt-2"></i> Inscriptions par année</h6>
                            <?php $insc = array_reverse($data['inscrits_par_annee'] ?? []); $maxI = 1; foreach ($insc as $x) { $maxI = max($maxI, (int) $x->total); } ?>
                            <?php if (empty($insc)): ?>
                                <div class="gu-empty"><i class="bx bx-bar-chart-alt-2"></i>Aucune inscription enregistrée.</div>
                            <?php else: ?>
                                <div class="bars">
                                    <?php foreach ($insc as $x): $h = round(((int) $x->total) * 100 / $maxI); ?>
                                        <div class="col"><div class="bar" style="height:<?= max(4, $h) ?>%;"><span class="v"><?= $fmt($x->total) ?></span></div><div class="lab"><?= htmlspecialchars($x->annee) ?></div></div>
                                    <?php endforeach ?>
                                </div>
                            <?php endif ?>
                        </div>
                    </div>
                    <div class="col-12 col-lg-7">
                        <div class="panel">
                            <h6><i class="bx bx-medal"></i> Top filières — taux de réussite</h6>
                            <?php $tf = $data['top_filieres'] ?? []; ?>
                            <?php if (empty($tf)): ?>
                                <div class="gu-empty"><i class="bx bx-medal"></i>Aucune donnée de réussite.</div>
                            <?php else: foreach ($tf as $f): $t = (float) ($f->taux_reussite ?? 0); ?>
                                <div class="tf-row">
                                    <div class="tf-head"><span style="font-weight:600;color:#14346b;"><?= htmlspecialchars($f->filiere) ?></span><span style="color:#5a6b86;"><?= $t ?>% · <?= $fmt($f->total_etudiants) ?> étu.</span></div>
                                    <div class="tf-track"><div class="tf-fill" style="width:<?= min(100, $t) ?>%;"></div></div>
                                </div>
                            <?php endforeach; endif ?>
                        </div>
                    </div>
                </div>

                <!-- Départements + Accès rapides -->
                <div class="row mb-1" style="row-gap:18px;">
                    <div class="col-12 col-lg-7">
                        <div class="panel">
                            <h6><i class="bx bx-building"></i> Performance par département</h6>
                            <?php $deps = $data['departements'] ?? []; ?>
                            <?php if (empty($deps)): ?>
                                <div class="gu-empty"><i class="bx bx-building"></i>Aucun département.</div>
                            <?php else: ?>
                                <div style="overflow:auto;">
                                <table class="dep">
                                    <thead><tr><th>Département</th><th class="num">Étudiants</th><th class="num">Inscrits</th><th class="num">Réussite</th><th class="num">Recettes</th></tr></thead>
                                    <tbody>
                                        <?php foreach ($deps as $d): $t = (float) ($d->taux_reussite ?? 0); ?>
                                            <tr>
                                                <td style="font-weight:600;color:#14346b;"><?= htmlspecialchars($d->nom_departement ?? '—') ?></td>
                                                <td class="num"><?= $fmt($d->total_etudiants ?? 0) ?></td>
                                                <td class="num"><?= $fmt($d->inscrits ?? 0) ?></td>
                                                <td class="num"><span class="badge badge-soft-<?= $t >= 50 ? 'success' : ($t > 0 ? 'warning' : 'secondary') ?>"><?= $t ?>%</span></td>
                                                <td class="num"><?= $fmt($d->recettes ?? 0) ?></td>
                                            </tr>
                                        <?php endforeach ?>
                                    </tbody>
                                </table>
                                </div>
                            <?php endif ?>
                        </div>
                    </div>
                    <div class="col-12 col-lg-5">
                        <div class="panel">
                            <h6><i class="bx bx-rocket"></i> Accès rapides</h6>
                            <div class="row" style="row-gap:12px;">
                                <div class="col-6"><a class="shortcut" href="<?= ROOT ?>/Notes"><i class="bx bx-edit"></i><span class="t">Saisie des notes</span></a></div>
                                <div class="col-6"><a class="shortcut" href="<?= ROOT ?>/Notes/releves"><i class="bx bx-file"></i><span class="t">Relevés</span></a></div>
                                <div class="col-6"><a class="shortcut" href="<?= ROOT ?>/Etudiants"><i class="bx bx-group"></i><span class="t">Étudiants</span></a></div>
                                <div class="col-6"><a class="shortcut" href="<?= ROOT ?>/Reinsciptions"><i class="bx bx-user-plus"></i><span class="t">Réinscription</span></a></div>
                                <div class="col-6"><a class="shortcut" href="<?= ROOT ?>/Emploi_du_temps"><i class="bx bx-calendar"></i><span class="t">Emploi du temps</span></a></div>
                                <div class="col-6"><a class="shortcut" href="<?= ROOT ?>/Filieres"><i class="bx bx-bookmark-alt"></i><span class="t">Filières</span></a></div>
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
