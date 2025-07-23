<style>
    .badge.bg-pink {
        background-color: #e83e8c;
    }

    .card-animated-border-top1 {
        border-top: 3px solid;
        border-image-slice: 1;
        border-image-source: linear-gradient(to right, #ff416c, #ff4b2b);
        animation: border-shift 3s linear infinite;
        min-height: 150px;
    }
    @keyframes border-shift {
        0% { border-image-source: linear-gradient(to right, #ff416c, #ff4b2b); }
        50% { border-image-source: linear-gradient(to right, #4facfe, #00f2fe); }
        100% { border-image-source: linear-gradient(to right, #ff416c, #ff4b2b); }
    }
    .widget-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 60px;
        height: 60px;
        border-radius: 50%;
    }
    .chart-container {
        height: 300px;
        margin-bottom: 30px;
    }
    .exam-calendar {
        background-color: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
    }
    .exam-item {
        border-left: 4px solid #7367f0;
        padding-left: 15px;
        margin-bottom: 15px;
    }
    .alert-department {
        border-left: 4px solid #ff9f43;
    }
    
</style>

<?php $this->view("Partials/header") ?>

<body class="vertical-layout vertical-menu-modern boxicon-layout no-card-shadow navbar-sticky footer-static 2-columns">

<?php $this->view("Partials/navbar") ?>
<?php $this->view("Partials/seibar") ?>

<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="col-12">
                <h5 class="content-header-title float-left pr-1 mb-0">Accueil</h5>
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb p-0 mb-0">
                        <li class="breadcrumb-item">
                          
                        </li>
                       
                    </ol>
                </div>
            </div>
        </div>
        <?php if (isset($_SESSION['role'])): ?>
        <div class="content-body">
            <!-- ==================================== -->
            <!-- SECTION ENSEIGNANT (BASE POUR TOUS) -->
            <!-- ==================================== -->
             <?php if ($_SESSION['role'] === 'Enseignant'): ?>
            <section id="dashboard-enseignant">
                <div class="row">
                    <?php $this->view("set_flash") ?>
                    <?php
                    $activite = $activiteSemaine ?? (object)[
                        'total_cours' => 0,
                        'cours_confirmes' => 0,
                        'cours_en_attente' => 0,
                        'heures_confirmées' => 0,
                        'heures_en_attente' => 0
                    ];
                    ?>

                    <!-- Cartes Enseignant -->
                    <div class="row col-12 mt-2">

                        <!-- Activité hebdomadaire -->
                        <div class="col-lg-4 col-md-6 col-sm-12 mb-2">
                            <div class="card card-animated-border-top1">
                                <div class="card-body">
                                    <p class="text-muted mb-1">Cours planifiés</p>
                                    <h4 class="text-primary mb-0"><?= $activite->total_cours ?> cours</h4>
                                    <small class="text-muted">
                                        <?= $activite->cours_confirmes ?> confirmés, <?= $activite->cours_en_attente ?> en attente
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Heures enseignées -->
                        <div class="col-lg-4 col-md-6 col-sm-12 mb-2">
                            <div class="card card-animated-border-top1">
                                <div class="card-body">
                                    <p class="text-muted mb-1">Heures enseignées</p>
                                    <h4 class="text-success mb-0"><?= $activite->heures_confirmées ?>h</h4>
                                    <small class="text-muted"><?= $activite->heures_en_attente ?>h en attente</small>
                                </div>
                            </div>
                        </div>

                        <!-- Semaine en cours -->
                        <div class="col-lg-4 col-md-6 col-sm-12 mb-2">
                            <div class="card card-animated-border-top1">
                                <div class="card-body">
                                    <p class="text-muted mb-1">Semaine en cours</p>
                                    <small class="text-info mb-0">
                                        <?= date('d/m/Y', strtotime('monday this week')) ?> - <?= date('d/m/Y', strtotime('sunday this week')) ?>
                                    </small>
                                    <?php if (!empty($periodeActive)): ?>
                                        <div class="mt-2">
                                            <small class="text-muted">
                                                📘 Période pédagogique :
                                                <?= date('d/m/Y', strtotime($periodeActive->date_debut)) ?>
                                                - <?= date('d/m/Y', strtotime($periodeActive->date_fin)) ?>
                                                (<?= htmlspecialchars($periodeActive->status) ?>)
                                            </small>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Ligne suivante : Performance Globale -->
                    <div class="row col-12 mt-2">
                        <?php if (!empty($statsMoyenne)): ?>
                            <?php
                                $pourcentageGlobal = $statsMoyenne['pourcentage'];
                                $avecMoyenne = $statsMoyenne['avec_moyenne'];
                                $totalEvalues = $statsMoyenne['total_evalues'];
                            ?>
                            <div class="col-lg-6 col-md-6 col-sm-12 mb-2">
                                <div class="card card-animated-border-top1">
                                    <div class="card-body">
                                        <p class="text-muted mb-1">Performance globale</p>
                                        <h4 class="text-success mb-0"><?= $pourcentageGlobal ?>%</h4>
                                        <small class="text-muted"><?= $avecMoyenne ?> sur <?= $totalEvalues ?> étudiants évalués</small>
                                        <div class="progress mt-2" style="height: 6px;">
                                            <div class="progress-bar bg-success" role="progressbar"
                                                style="width: <?= $pourcentageGlobal ?>%;"
                                                aria-valuenow="<?= $pourcentageGlobal ?>" aria-valuemin="0" aria-valuemax="100">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Détails par parcours -->
                        <?php if (!empty($statsParcours)): ?>
                            <?php foreach ($statsParcours as $stat): ?>
                                <?php
                                    $total = (int) $stat->total_etudiants;
                                    $avecMoyenne = (int) $stat->avec_moyenne;
                                    $pourcentage = $total > 0 ? round(($avecMoyenne / $total) * 100, 1) : 0;
                                ?>
                                <div class="col-lg-6 col-md-6 col-sm-12 mb-2">
                                    <div class="card card-animated-border-top1">
                                        <div class="card-body">
                                            <p class="text-muted mb-1"><?= htmlspecialchars($stat->nom_parcours) ?></p>
                                            <h4 class="text-info mb-0"><?= $pourcentage ?>%</h4>
                                            <small class="text-muted"><?= $avecMoyenne ?> sur <?= $total ?> ont la moyenne</small>
                                            <div class="progress mt-2" style="height: 6px;">
                                                <div class="progress-bar bg-info" role="progressbar"
                                                    style="width: <?= $pourcentage ?>%;"
                                                    aria-valuenow="<?= $pourcentage ?>" aria-valuemin="0" aria-valuemax="100">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="col-12">
                                <div class="alert alert-warning">Aucune donnée disponible pour les parcours.</div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <!-- Emploi du temps -->
                    <div class="row col-12 mt-2">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Mon emploi du temps</h4>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Filière</th>
                                                    <th>Promotion</th>
                                                    <th>Module</th>
                                                    <th>Date</th>
                                                    <th>Salle</th>
                                                    <th>Statut</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (!empty($emploiDuTemps)) : ?>
                                                    <?php foreach ($emploiDuTemps as $cours) : ?>
                                                        <tr>
                                                            <td><?= htmlspecialchars($cours->filiere) ?></td>
                                                            <td><?= htmlspecialchars($cours->promotion) ?></td>
                                                            <td><?= htmlspecialchars($cours->module) ?></td>
                                                            <td><?= htmlspecialchars($cours->date_cours) ?></td>
                                                            <td><?= htmlspecialchars($cours->nom_salle) ?></td>
                                                            <td>
                                                                <?php if ($cours->statut == 1) : ?>
                                                                    <span class="badge bg-success">Confirmé</span>
                                                                <?php else : ?>
                                                                    <span class="badge bg-warning">En attente</span>
                                                                <?php endif; ?>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php else : ?>
                                                    <tr>
                                                        <td colspan="6" class="text-center text-muted">Aucun emploi du temps trouvé</td>
                                                    </tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </section>
            <!-- ==================================== -->
            <!-- SECTION Scolarite -->
            <!-- ==================================== -->
            <?php elseif ($_SESSION['role'] === 'Scolarite'): ?>
            <section id="dashboard-scolarite" class="role-specific">
               <!-- ✅ Indicateurs clés -->
                <div class="row mt-3">
                    <!-- Total Étudiants -->
                    <div class="col-lg-3 col-md-6 col-sm-12 mb-2">
                        <div class="card card-animated-border-top1">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted mb-1">Étudiants</p>
                                    <h4 class="text-primary mb-0"><?= $indicateurs->total_etudiants ?></h4>
                                    <small><?= $indicateurs->total_inscrits ?> inscrits</small><br>
                                    <span class="badge badge-info">
                                        <?= $indicateurs->total_inscrits_3_ans ?> 
                                    </span>
                                </div>
                                <div class="widget-icon bg-primary text-white"><i class="fa-solid fa-users"></i></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Filières -->
                    <div class="col-lg-3 col-md-6 col-sm-12 mb-2">
                        <div class="card card-animated-border-top1">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted mb-1">Filières</p>
                                    <h4 class="text-info mb-0"><?= $indicateurs->total_filieres ?></h4>
                                </div>
                                <div class="widget-icon bg-info text-white"><i class="fa-solid fa-graduation-cap"></i></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Admis -->
                    <div class="col-lg-3 col-md-6 col-sm-12 mb-2">
                        <div class="card card-animated-border-top1">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted mb-1">Admis</p>
                                    <h4 class="text-success mb-0"><?= $indicateurs->total_admis ?></h4>
                                    <small><?= $indicateurs->total_etudiants > 0 ? round(($indicateurs->total_admis/$indicateurs->total_etudiants)*100, 1).'%' : '0%' ?> de réussite</small>
                                </div>
                                <div class="widget-icon bg-success text-white"><i class="fa-solid fa-check-circle"></i></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Ajournés -->
                    <div class="col-lg-3 col-md-6 col-sm-12 mb-2">
                        <div class="card card-animated-border-top1">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted mb-1">Ajournés</p>
                                    <h4 class="text-warning mb-0"><?= $indicateurs->total_ajournes ?></h4>
                                    <small><?= $indicateurs->total_etudiants > 0 ? round(($indicateurs->total_ajournes/$indicateurs->total_etudiants)*100, 1).'%' : '0%' ?> d'échec</small>
                                </div>
                                <div class="widget-icon bg-warning text-white"><i class="fa-solid fa-exclamation-circle"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ✅ Inscriptions par année -->
                <div class="row mt-4">
                    <div class="col-lg-12">
                        <h5 class="text-primary mb-3"><i class="fa-solid fa-chart-line"></i> Inscriptions par année</h5>
                        <div class="d-flex justify-content-around flex-wrap">
                            <?php foreach ($inscrits_par_annee as $annee): ?>
                                <div class="card text-center shadow-sm m-2" style="width:140px; border-top:3px solid #007bff;">
                                    <div class="card-body p-3">
                                        <h6 class="text-muted"><?= $annee->annee ?></h6>
                                        <h4 class="text-info mb-0"><?= $annee->total ?></h4>
                                        <small>Inscrits</small>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <!-- ✅ Graphique Chart.js -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="mb-3"><i class="fa-solid fa-chart-bar"></i> Statistiques visuelles</h5>
                                <canvas id="chartInscriptions" height="100"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                  <!-- ✅ Tableau des étudiants -->
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="card shadow-sm">
                            <div class="card-header bg-primary text-white">
                                <i class="fa-solid fa-users me-2"></i> Répartition des étudiants
                            </div>
                            <div class="card-body">
                                <div class="table-responsive ">
                                    <table class="table table-sm table-hover table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Département</th>
                                                <th>Filière</th>
                                                <th>Niveau</th>
                                                <th>Année</th>
                                                <th>Inscrits</th>
                                                <th>Non-inscrits</th>
                                                <th>Hommes</th>
                                                <th>Femmes</th>                
                                                <th>Admis</th>
                                                <th>Ajournés</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($etudiants as $e): ?>
                                            <tr>
                                                <td><?= $e->nom_departement ?></td>
                                                <td><?= $e->sigle_filiere ?></td>
                                                <td><?= $e->niveau ?></td>
                                                <td><?= $e->annee_universitaire ?></td>
                                                <td><?= $e->inscrits ?></td> 
                                                 <td><?= $e->non_inscrits ?></td>
                                                <td><?= $e->hommes ?></td>
                                                <td><?= $e->femmes ?></td>
                                                <td><?= $e->admis ?></td>
                                                <td><?= $e->ajournes ?></td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- ==================================== -->
            <!-- SECTION SECRETAIRE GENERAL (SGP) -->
            <!-- ==================================== -->
            <?php elseif ($_SESSION['role'] === 'Sécretaire principale'): ?>
            <section id="dashboard-sgp" class="role-specific">
                <div class="row mt-3">
                    <!-- Cartes SGP -->
                    <div class="row col-12 mt-2">
                        <!-- Départements -->
                        <div class="col-lg-3 col-md-6 col-sm-12 mb-2">
                            <div class="card card-animated-border-top1">
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="text-muted mb-1">Départements</p>
                                        <h4 class="text-primary mb-0"><?= $stats->total_departements ?></h4>
                                    </div>
                                    <div class="widget-icon bg-primary text-white">
                                        <i class="fa-solid fa-building"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Filières -->
                        <div class="col-lg-3 col-md-6 col-sm-12 mb-2">
                            <div class="card card-animated-border-top1">
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="text-muted mb-1">Filières</p>
                                        <h4 class="text-success mb-0"><?= $stats->total_filieres ?></h4>
                                    </div>
                                    <div class="widget-icon bg-success text-white">
                                        <i class="fa-solid fa-network-wired"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Étudiants -->
                        <div class="col-lg-3 col-md-6 col-sm-12 mb-2">
                            <div class="card card-animated-border-top1">
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="text-muted mb-1">Étudiants</p>
                                        <h4 class="text-info mb-0"><?= $stats->total_etudiants ?></h4>
                                    </div>
                                    <div class="widget-icon bg-info text-white">
                                        <i class="fa-solid fa-user-graduate"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Enseignants -->
                        <div class="col-lg-3 col-md-6 col-sm-12 mb-2">
                            <div class="card card-animated-border-top1">
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="text-muted mb-1">Enseignants</p>
                                        <h4 class="text-danger mb-0"><?= $stats->total_enseignants ?></h4>
                                    </div>
                                    <div class="widget-icon bg-danger text-white">
                                        <i class="fa-solid fa-chalkboard-teacher"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Dernières inscriptions -->
                    <div class="col-md-6 mt-3">
                        <div class="card shadow-sm">
                            <div class="card-header bg-primary text-white">
                                <i class="fa-solid fa-user-plus me-2"></i> Dernières inscriptions
                            </div>
                            <div class="card-body">
                                <ul class="list-group">
                                    <?php foreach ($dernieres_inscriptions as $inscription): ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong><?= $inscription->nom_prenom_etudiant ?></strong>
                                            <small class="d-block text-muted"><?= $inscription->nom_filiere ?> (<?= $inscription->sigle_filiere ?>)</small>
                                        </div>
                                        <span class="badge bg-light text-dark"><?= $inscription->date_inscription ?></span>
                                    </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Prochains événements -->
                    <div class="col-md-6 mt-3">
                        <div class="card shadow-sm">
                            <div class="card-header bg-success text-white">
                                <i class="fa-solid fa-calendar-day me-2"></i> Prochains événements
                            </div>
                            <div class="card-body">
                                <ul class="list-group">
                                    <?php foreach ($prochains_evenements as $event): ?>
                                    <li class="list-group-item">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <strong><?= $event->evenement ?></strong>
                                                <small class="d-block text-muted"><?= $event->type ?> - <?= $event->niveau ?></small>
                                            </div>
                                            <span class="text-<?= $event->type === 'Cours' ? 'info' : 'warning' ?>"><?= $event->date ?></span>
                                        </div>
                                    </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        
            <!-- ==================================== -->
            <!-- SECTION CHEF DER (GEA OU ST) -->
            <!-- ==================================== -->
            <?php elseif ($_SESSION['role'] === 'Chef DR'): ?>
            <section id="dashboard-chef-der" class="role-specific">
                <!-- ✅ Indicateurs clés -->
                <div class="row mt-3">
                    <!-- Total Étudiants -->
                    <div class="col-lg-3 col-md-6 col-sm-12 mb-2">
                        <div class="card card-animated-border-top1">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted mb-1">Étudiants</p>
                                    <h4 class="text-primary mb-0"><?= $indicateurs->total_etudiants ?></h4>
                                    <small><?= $indicateurs->total_inscrits ?> inscrits</small>
                                </div>
                                <div class="widget-icon bg-primary text-white"><i class="fa-solid fa-users"></i></div>
                            </div>
                        </div>
                    </div>

                    <!-- Enseignants -->
                    <div class="col-lg-3 col-md-6 col-sm-12 mb-2">
                        <div class="card card-animated-border-top1">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted mb-1">Enseignants</p>
                                    <h4 class="text-success mb-0"><?= $enseignants->total ?></h4>
                                    <small><?= $enseignants->permanents ?> permanents</small>
                                </div>
                                <div class="widget-icon bg-success text-white"><i class="fa-solid fa-chalkboard-user"></i></div>
                            </div>
                        </div>
                    </div>

                    <!-- Filières -->
                    <div class="col-lg-3 col-md-6 col-sm-12 mb-2">
                        <div class="card card-animated-border-top1">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted mb-1">Filières</p>
                                    <h4 class="text-info mb-0"><?= $indicateurs->total_filieres ?></h4>
                                </div>
                                <div class="widget-icon bg-info text-white"><i class="fa-solid fa-graduation-cap"></i></div>
                            </div>
                        </div>
                    </div>

                    <!-- Examens -->
                    <div class="col-lg-3 col-md-6 col-sm-12 mb-2">
                        <div class="card card-animated-border-top1">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted mb-1">Examens</p>
                                    <h4 class="text-danger mb-0"><?= count($examens) ?></h4>
                                    <small>30 prochains jours</small>
                                </div>
                                <div class="widget-icon bg-danger text-white"><i class="fa-solid fa-clipboard-list"></i></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ✅ Tableau des étudiants -->
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="card shadow-sm">
                            <div class="card-header bg-primary text-white">
                                <i class="fa-solid fa-users me-2"></i> Répartition des étudiants
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                   <table class="table table-sm table-hover table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Filière</th>
                                                <th>Niveau</th>
                                                <th>Année</th>
                                                <th>Inscrits</th>
                                                <th>Hommes</th>
                                                <th>Femmes</th>
                                                <th>Non-inscrits</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($etudiants as $e): ?>
                                            <tr>
                                                <td><?= $e->nom_filiere ?> (<?= $e->sigle_filiere ?>)</td>
                                                <td><?= $e->niveau ?></td>
                                                <td><?= $e->annee_universitaire ?></td>
                                                <td><?= $e->inscrits ?></td>
                                                <td><?= $e->hommes ?></td>
                                                <td><?= $e->femmes ?></td>
                                                <td><?= $e->non_inscrits ?></td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ✅ Tableau des enseignants -->
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="card shadow-sm">
                            <div class="card-header bg-secondary text-white">
                                <i class="fa-solid fa-chalkboard-user me-2"></i> Répartition des enseignants
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                  <table class="table table-sm table-hover table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Type</th>
                                                <th>Nombre</th>
                                                <th>Pourcentage</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Permanents</td>
                                                <td><?= $enseignants->permanents ?></td>
                                                <td><?= $enseignants->total > 0 ? round(($enseignants->permanents/$enseignants->total)*100, 1) : 0 ?>%</td>
                                            </tr>
                                            <tr>
                                                <td>Non permanents</td>
                                                <td><?= $enseignants->non_permanents ?></td>
                                                <td><?= $enseignants->total > 0 ? round(($enseignants->non_permanents/$enseignants->total)*100, 1) : 0 ?>%</td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr class="table-secondary">
                                                <th>Total</th>
                                                <th><?= $enseignants->total ?></th>
                                                <th>100%</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ✅ Prochains événements -->
                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-info text-white">
                                <i class="fa-solid fa-calendar-check me-2"></i> Prochains cours
                            </div>
                            <div class="card-body">
                                <?php if (!empty($cours)): ?>
                                    <ul class="list-group">
                                        <?php foreach ($cours as $c): ?>
                                        <li class="list-group-item">
                                            <strong><?= $c->date_cours ?></strong> - <?= $c->module ?>
                                            <br><small><?= $c->professeurs ?> - <?= $c->salle ?></small>
                                        </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php else: ?>
                                    <p class="text-muted">Aucun cours programmé</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-warning text-dark">
                                <i class="fa-solid fa-clipboard-list me-2"></i> Prochains examens
                            </div>
                            <div class="card-body">
                                <?php if (!empty($examens)): ?>
                                    <ul class="list-group">
                                        <?php foreach ($examens as $ex): ?>
                                        <li class="list-group-item">
                                            <strong><?= $ex->date_examen ?></strong> - <?= $ex->module ?>
                                            <br><small><?= $ex->niveau ?> - <?= $ex->salle ?></small>
                                        </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php else: ?>
                                    <p class="text-muted">Aucun examen prévu</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- ==================================== -->
            <!-- SECTION DIRECTEUR GENERAL ADJOINT (DGA) -->
            <!-- ==================================== -->  
           <?php elseif ($_SESSION['role'] === 'DGA'): ?>
            <section id="dashboard-dga" class="role-specific">
                <div class="row mt-3">

                    <!-- ✅ Indicateurs clés -->
                    <div class="row col-12 mt-2">

                        <!-- Taux de réussite global -->
                        <div class="col-lg-4 col-md-6 col-sm-12 mb-2">
                            <div class="card card-animated-border-top1">
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="text-muted mb-1">Taux de réussite global</p>
                                        <h4 class="text-primary mb-0"><?= $stats['taux_reussite'] ?>%</h4>
                                        <small class="text-muted"><?= $stats['evolution'] ?> vs année passée</small>
                                    </div>
                                    <div class="widget-icon bg-primary text-white">
                                        <i class="fa-solid fa-trophy"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Meilleur département -->
                        <div class="col-lg-4 col-md-6 col-sm-12 mb-2">
                            <div class="card card-animated-border-top1">
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="text-muted mb-1">Meilleur département</p>
                                        <h4 class="text-success mb-0"><?= htmlspecialchars($stats['best_dep']['nom']) ?></h4>
                                        <small class="text-muted"><?= $stats['best_dep']['taux'] ?>% de réussite</small>
                                    </div>
                                    <div class="widget-icon bg-success text-white">
                                        <i class="fa-solid fa-medal"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Département à suivre -->
                        <div class="col-lg-4 col-md-6 col-sm-12 mb-2">
                            <div class="card card-animated-border-top1">
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="text-muted mb-1">Département à suivre</p>
                                        <h4 class="text-warning mb-0"><?= htmlspecialchars($stats['worst_dep']['nom']) ?></h4>
                                        <small class="text-muted"><?= $stats['worst_dep']['taux'] ?>% de réussite</small>
                                    </div>
                                    <div class="widget-icon bg-warning text-white">
                                        <i class="fa-solid fa-binoculars"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ✅ Nouveaux indicateurs -->
                    <div class="row col-12 mt-3">
                        <div class="col-lg-3 col-md-6 col-sm-12 mb-2">
                            <div class="card card-animated-border-top1">
                                <div class="card-body">
                                    <p class="text-muted mb-1">Total étudiants</p>
                                    <h4 class="text-info mb-0"><?= $stats['total_etudiants'] ?></h4>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 col-sm-12 mb-2">
                            <div class="card card-animated-border-top1">
                                <div class="card-body">
                                    <p class="text-muted mb-1">Total inscrits</p>
                                    <h4 class="text-info mb-0"><?= $stats['total_inscrits'] ?></h4>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 col-sm-12 mb-2">
                            <div class="card card-animated-border-top1">
                                <div class="card-body">
                                    <p class="text-muted mb-1">Taux d'inscription</p>
                                    <h4 class="text-info mb-0"><?= $stats['taux_inscription'] ?>%</h4>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 col-sm-12 mb-2">
                            <div class="card card-animated-border-top1">
                                <div class="card-body">
                                    <p class="text-muted mb-1">Taux d’échec</p>
                                    <h4 class="text-danger mb-0"><?= $stats['taux_echec'] ?>%</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-12">
                    <div class="card mt-4">
                <div class="card-header bg-primary text-white text-center">
                    Statistiques détaillées par département
                </div>
                <div class="card-body">
                    <!-- Ici ton tableau ou contenu -->
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Département</th>
                                <th>Taux réussite (%)</th>
                                <th>Effectif</th>
                                <th>Critère de sélection</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($departements as $dep): ?>
                                <tr>
                                    <td><?= htmlspecialchars($dep->departement) ?></td>
                                    <td><?= number_format($dep->taux_reussite, 2) ?></td>
                                    <td><?= (int)$dep->total_etudiants ?></td>
                                    <td><?= htmlspecialchars($dep->critere) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                </div>

                    </div>
                    </div>
            </section>
            <!-- ==================================== -->
            <!-- SECTION DIRECTEUR GENERAL (DG) -->
            <!-- ==================================== -->
            <?php elseif ($_SESSION['role'] === 'DG'): ?>
         <section id="dashboard-dg" class="role-specific">
    <div class="row mt-3">

        <!-- KPI DG -->
        <div class="row col-12 mt-2">
            <!-- Taux réussite -->
            <div class="col-lg-3 col-md-6 col-sm-12 mb-2">
                <div class="card card-animated-border-top1">
                    <div class="card-body d-flex align-items-center">
                        <div class="me-3 p-2 bg-primary text-white rounded-circle">
                            <i class="fa-solid fa-chart-line fa-2x"></i>
                        </div>
                        <div>
                            <p class="text-muted mb-1">Taux de réussite global</p>
                            <h4 class="mb-0"><?= $stats['taux_reussite'] ?>%</h4>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Taux d'échec -->
            <div class="col-lg-3 col-md-6 col-sm-12 mb-2">
                <div class="card card-animated-border-top1">
                    <div class="card-body d-flex align-items-center">
                        <div class="me-3 p-2 bg-danger text-white rounded-circle">
                            <i class="fa-solid fa-times-circle fa-2x"></i>
                        </div>
                        <div>
                            <p class="text-muted mb-1">Taux d'échec</p>
                            <h4 class="mb-0"><?= $stats['taux_echec'] ?>%</h4>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Taux inscription -->
            <div class="col-lg-3 col-md-6 col-sm-12 mb-2">
                <div class="card card-animated-border-top1">
                    <div class="card-body d-flex align-items-center">
                        <div class="me-3 p-2 bg-info text-white rounded-circle">
                            <i class="fa-solid fa-user-check fa-2x"></i>
                        </div>
                        <div>
                            <p class="text-muted mb-1">Taux d'inscription</p>
                            <h4 class="mb-0"><?= $stats['taux_inscription'] ?>%</h4>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total étudiants -->
            <div class="col-lg-3 col-md-6 col-sm-12 mb-2">
                <div class="card card-animated-border-top1">
                    <div class="card-body d-flex align-items-center">
                        <div class="me-3 p-2 bg-secondary text-white rounded-circle">
                            <i class="fa-solid fa-users fa-2x"></i>
                        </div>
                        <div>
                            <p class="text-muted mb-1">Total étudiants</p>
                            <h4 class="mb-0"><?= $stats['total_etudiants'] ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

       <div class="col-12 mt-4">
            <div class="card">
                <div class="card-header bg-primary text-white text-center">
                    Statistiques détaillées par département
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-striped table-bordered table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Département</th>
                                <th>Effectif</th>
                                <th>Admis</th>
                                <th>Taux réussite</th>
                                <th>Critère</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($departements)): ?>
                                <?php foreach ($departements as $dep): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($dep->nom_departement) ?></td>
                                        <td><?= $dep->total_etudiants ?></td>
                                        <td><?= $dep->admis ?></td>
                                        <td><?= $dep->taux_reussite ?>%</td>
                                        <td><?= $dep->critere ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Aucune donnée disponible</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


        <!-- Top 3 filières -->
        <div class="col-12 mt-4">
            <div class="card">
                <div class="card-header bg-primary text-white text-center">
                    Top 3 filières avec meilleur taux de réussite
                </div>
                <div class="card-body table-responsive p-0">
                   <table class="table table-striped table-bordered table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Filière</th>
                                <th>Effectif</th>
                                <th>Admis</th>
                                <th>Taux réussite</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($topFilieres as $filiere): ?>
                                <tr>
                                    <td><?= htmlspecialchars($filiere->filiere) ?></td>
                                    <td><?= $filiere->total_etudiants ?></td>
                                    <td><?= $filiere->admis ?></td>
                                    <td><?= $filiere->taux_reussite ?>%</td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</section>


                </div>
                <?php else: ?>
                <p>Rôle non reconnu.</p>
                    <?php endif; ?>
                <?php else: ?>
                    <p>Vous n’êtes pas connecté.</p>
                <?php endif; ?>
            </div>
        </div>

<!-- Scripts pour les graphiques et gestion des rôles -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const levelChart = new Chart(document.getElementById('levelChart'), {
        type: 'doughnut',
        data: {
            labels: ['L1', 'L2', 'L3'],
            datasets: [{
                data: [<?= $statsNiveaux->l1 ?>, <?= $statsNiveaux->l2 ?>, <?= $statsNiveaux->l3 ?>],
                backgroundColor: ['#007bff', '#28a745', '#17a2b8']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' },
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            let total = <?= $statsNiveaux->l1 + $statsNiveaux->l2 + $statsNiveaux->l3 ?>;
                            let value = context.parsed;
                            let percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                            return `${context.label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });

    const genderChart = new Chart(document.getElementById('genderChart'), {
        type: 'pie',
        data: {
            labels: ['Hommes', 'Femmes'],
            datasets: [{
                data: [<?= $statsGenre->male ?>, <?= $statsGenre->female ?>],
                backgroundColor: ['#6c757d', '#e83e8c']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' },
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            let total = <?= $statsGenre->male + $statsGenre->female ?>;
                            let value = context.parsed;
                            let percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                            return `${context.label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });
</script>

<script>
    const ctx = document.getElementById('chartInscriptions').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [<?php foreach($inscrits_par_annee as $a) echo "'".$a->annee."',"; ?>],
            datasets: [{
                label: 'Étudiants inscrits',
                data: [<?php foreach($inscrits_par_annee as $a) echo $a->total.","; ?>],
                backgroundColor: ['#007bff','#17a2b8','#28a745'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                title: { display: true, text: 'Inscriptions sur les 3 dernières années' }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>
<?php $this->view("Partials/foot") ?>
<?php $this->view("Partials/footer") ?>
</body>
</html>