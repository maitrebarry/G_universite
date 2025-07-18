<style>
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
                                        <h4 class="text-primary mb-0">6</h4>
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
                                        <h4 class="text-success mb-0">12</h4>
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
                                        <h4 class="text-info mb-0">2,500</h4>
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
                                        <h4 class="text-danger mb-0">150</h4>
                                    </div>
                                    <div class="widget-icon bg-danger text-white">
                                        <i class="fa-solid fa-chalkboard-teacher"></i>
                                    </div>
                                </div>
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
                <div class="row mt-3">
                    <!-- Cartes Chef DER -->
                    <div class="row col-12 mt-2">
                       <!-- Étudiants L1 -->
                        <div class="col-lg-3 col-md-6 col-sm-12 mb-2">
                            <div class="card card-animated-border-top1">
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="text-muted mb-1">Étudiants L1</p>
                                        <h4 class="text-primary mb-0"><?= $statsNiveaux->l1 ?></h4>
                                        <small class="text-muted">
                                            <?php 
                                            $totalEtudiants = $statsNiveaux->l1 + $statsNiveaux->l2 + $statsNiveaux->l3;
                                            echo $totalEtudiants > 0 ? round(($statsNiveaux->l1 / $totalEtudiants) * 100, 1).'%' : '0%';
                                            ?> du total
                                        </small>
                                    </div>
                                    <div class="widget-icon bg-primary text-white">
                                        <i class="fa-solid fa-users"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Étudiants L2 -->
                        <div class="col-lg-3 col-md-6 col-sm-12 mb-2">
                            <div class="card card-animated-border-top1">
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="text-muted mb-1">Étudiants L2</p>
                                        <h4 class="text-success mb-0"><?= $statsNiveaux->l2 ?></h4>
                                        <small class="text-muted">
                                            <?php 
                                            $totalEtudiants = $statsNiveaux->l1 + $statsNiveaux->l2 + $statsNiveaux->l3;
                                            echo $totalEtudiants > 0 ? round(($statsNiveaux->l2 / $totalEtudiants) * 100, 1).'%' : '0%';
                                            ?> du total
                                        </small>
                                    </div>
                                    <div class="widget-icon bg-success text-white">
                                        <i class="fa-solid fa-users"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Étudiants L3 -->
                        <div class="col-lg-3 col-md-6 col-sm-12 mb-2">
                            <div class="card card-animated-border-top1">
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="text-muted mb-1">Étudiants L3</p>
                                        <h4 class="text-info mb-0"><?= $statsNiveaux->l3 ?></h4>
                                        <small class="text-muted">
                                            <?php 
                                            $totalEtudiants = $statsNiveaux->l1 + $statsNiveaux->l2 + $statsNiveaux->l3;
                                            echo $totalEtudiants > 0 ? round(($statsNiveaux->l3 / $totalEtudiants) * 100, 1).'%' : '0%';
                                            ?> du total
                                        </small>
                                    </div>
                                    <div class="widget-icon bg-info text-white">
                                        <i class="fa-solid fa-users"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Non-inscrits -->
                        <div class="col-lg-3 col-md-6 col-sm-12 mb-2">
                            <div class="card card-animated-border-top1">
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="text-muted mb-1">Non-inscrits</p>
                                        <h4 class="text-danger mb-0"><?= $statsNiveaux->unregistered ?></h4>
                                        <small class="text-muted">
                                            <?php 
                                            $totalGeneral = $statsNiveaux->l1 + $statsNiveaux->l2 + $statsNiveaux->l3 + $statsNiveaux->unregistered;
                                            echo $totalGeneral > 0 ? round(($statsNiveaux->unregistered / $totalGeneral) * 100, 1).'%' : '0%';
                                            ?> du total
                                        </small>
                                    </div>
                                    <div class="widget-icon bg-danger text-white">
                                        <i class="fa-solid fa-user-slash"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Deuxième ligne Chef DER -->
                    <div class="row col-12 mt-2">
                        <!-- Professeurs -->
                        <div class="col-lg-3 col-md-6 col-sm-12 mb-2">
                            <div class="card card-animated-border-top1">
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="text-muted mb-1">Professeurs</p>
                                        <h4 class="text-warning mb-0"><?= $statsEnseignants->total ?? 0 ?></h4>
                                        <small class="text-muted"><?= $statsEnseignants->actifs ?? 0 ?> actifs</small>
                                    </div>
                                    <div class="widget-icon bg-warning text-white">
                                        <i class="fa-solid fa-chalkboard-user"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Cours programmés -->
                        <div class="col-lg-3 col-md-6 col-sm-12 mb-2">
                            <div class="card card-animated-border-top1">
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="text-muted mb-1">Cours programmés</p>
                                        <h4 class="text-purple mb-0"><?= $coursProgrammes->total ?? 0 ?></h4>
                                        <small class="text-muted"><?= $coursProgrammes->confirmes ?? 0 ?> confirmés</small>
                                    </div>
                                    <div class="widget-icon bg-purple text-white">
                                        <i class="fa-solid fa-calendar-check"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                      <!-- Taux de réussite -->
                        <div class="col-lg-3 col-md-6 col-sm-12 mb-2">
                            <div class="card card-animated-border-top1">
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="text-muted mb-1">Taux de réussite</p>
                                        <h4 class="text-teal mb-0">
                                            <?= ($tauxReussite->total ?? 0) > 0 ? round($tauxReussite->taux ?? 0, 1) : 0 ?>%
                                        </h4>
                                        <small class="text-muted">
                                            <?= $tauxReussite->reussis ?? 0 ?>/<?= $tauxReussite->total ?? 0 ?> étudiants
                                        </small>
                                    </div>
                                    <div class="widget-icon bg-teal text-white">
                                        <i class="fa-solid fa-chart-line"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Examens à venir -->
                           <div class="col-lg-3 col-md-6 col-sm-12 mb-2">
                                <div class="card card-animated-border-top1">
                                    <div class="card-body d-flex justify-content-between align-items-center">
                                        <div>
                                            <p class="text-muted mb-1">Examens à venir</p>
                                            <h4 class="text-pink mb-0"><?= $examensAVenir ?></h4>
                                            <small class="text-muted">30 prochains jours</small>
                                        </div>
                                        <div class="widget-icon bg-pink text-white">
                                            <i class="fa-solid fa-clipboard-list"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                        <!-- Tableau des examens à venir -->
                <div class="row col-12 mt-2">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Détails des examens à venir</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Heure</th>
                                                <th>Matière</th>
                                                <th>Niveau</th>
                                                <th>Salle</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($examensDetails)): ?>
                                                <?php foreach ($examensDetails as $examen): ?>
                                                <tr>
                                                    <td><?= $examen->date_examen ?></td>
                                                    <td><?= $examen->heure ?></td>
                                                    <td><?= $examen->module ?></td>
                                                    <td><?= $examen->niveau ?></td>
                                                    <td><?= $examen->salle ?></td>
                                                </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="5" class="text-center">Aucun examen prévu dans les 30 prochains jours</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                    <!-- Graphiques Chef DER -->
                    <div class="row col-12 mt-3">
                        <!-- Répartition par niveau -->
                        <div class="col-lg-6 col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Répartition par niveau</h4>
                                </div>
                                <div class="card-body">
                                    <div class="chart-container">
                                        <canvas id="levelChart" height="300"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Répartition par genre -->
                        <div class="col-lg-6 col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Répartition par genre</h4>
                                </div>
                                <div class="card-body">
                                    <div class="chart-container">
                                        <canvas id="genderChart" height="300"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tableau des cours programmés -->
                    <div class="row col-12 mt-2">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Cours programmés cette semaine</h4>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Heure</th>
                                                <th>Matière</th>
                                                <th>Niveau</th>
                                                <th>Professeur</th>
                                                <th>Salle</th>
                                                <th>Statut</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($coursProgrammesListe)): ?>
                                                <?php foreach ($coursProgrammesListe as $cours): ?>
                                                <tr>
                                                    <td><?= $cours->date_cours ?></td>
                                                    <td><?= $cours->heure ?></td>
                                                    <td><?= $cours->sigle ?> - <?= $cours->module ?></td>
                                                    <td><?= $cours->niveau ?></td>
                                                    <td><?= $cours->professeurs ?></td>
                                                    <td><?= $cours->salle ?></td>
                                                    <td>
                                                        <span class="badge <?= $cours->statut === 'Confirmé' ? 'bg-success' : 'bg-warning' ?>">
                                                            <?= $cours->statut ?>
                                                        </span>
                                                    </td>
                                                </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="7" class="text-center">Aucun cours programmé cette semaine</td>
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
            <!-- SECTION DIRECTEUR GENERAL ADJOINT (DGA) -->
            <!-- ==================================== -->  
            <?php elseif ($_SESSION['role'] === 'DGA'): ?>
            <section id="dashboard-dga" class="role-specific">
                <div class="row mt-3">
            
                    <!-- Cartes DGA -->
                    <div class="row col-12 mt-2">
                        <!-- Taux de réussite global -->
                        <div class="col-lg-4 col-md-6 col-sm-12 mb-2">
                            <div class="card card-animated-border-top1">
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="text-muted mb-1">Taux de réussite global</p>
                                        <h4 class="text-primary mb-0">72%</h4>
                                        <small class="text-muted">+3% vs dernière année</small>
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
                                        <h4 class="text-success mb-0">GEA</h4>
                                        <small class="text-muted">82% de réussite</small>
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
                                        <h4 class="text-warning mb-0">ST</h4>
                                        <small class="text-muted">65% de réussite</small>
                                    </div>
                                    <div class="widget-icon bg-warning text-white">
                                        <i class="fa-solid fa-binoculars"></i>
                                    </div>
                                </div>
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
                        <!-- Satisfaction étudiants -->
                        <div class="col-lg-3 col-md-6 col-sm-12 mb-2">
                            <div class="card card-animated-border-top1">
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="text-muted mb-1">Satisfaction étudiants</p>
                                        <h4 class="text-primary mb-0">4.2/5</h4>
                                        <small class="text-muted">Enquête 2023</small>
                                    </div>
                                    <div class="widget-icon bg-primary text-white">
                                        <i class="fa-solid fa-smile"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Taux d'insertion -->
                        <div class="col-lg-3 col-md-6 col-sm-12 mb-2">
                            <div class="card card-animated-border-top1">
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="text-muted mb-1">Taux d'insertion</p>
                                        <h4 class="text-success mb-0">78%</h4>
                                        <small class="text-muted">6 mois après diplôme</small>
                                    </div>
                                    <div class="widget-icon bg-success text-white">
                                        <i class="fa-solid fa-briefcase"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Budget -->
                        <div class="col-lg-3 col-md-6 col-sm-12 mb-2">
                            <div class="card card-animated-border-top1">
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="text-muted mb-1">Budget annuel</p>
                                        <h4 class="text-info mb-0">2.8M €</h4>
                                        <small class="text-muted">+5% vs 2022</small>
                                    </div>
                                    <div class="widget-icon bg-info text-white">
                                        <i class="fa-solid fa-coins"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Partenariats -->
                        <div class="col-lg-3 col-md-6 col-sm-12 mb-2">
                            <div class="card card-animated-border-top1">
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="text-muted mb-1">Partenariats</p>
                                        <h4 class="text-warning mb-0">42</h4>
                                        <small class="text-muted">Entreprises</small>
                                    </div>
                                    <div class="widget-icon bg-warning text-white">
                                        <i class="fa-solid fa-handshake"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Graphiques comparatifs -->
                    <div class="row col-12 mt-3">
                        <!-- Performance académique -->
                        <div class="col-lg-6 col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Performance académique par département</h4>
                                </div>
                                <div class="card-body">
                                    <div class="chart-container">
                                        <canvas id="academicPerfChart" height="300"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Evolution effectifs -->
                        <div class="col-lg-6 col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Evolution des effectifs</h4>
                                </div>
                                <div class="card-body">
                                    <div class="chart-container">
                                        <canvas id="studentsEvolutionChart" height="300"></canvas>
                                    </div>
                                </div>
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


<?php $this->view("Partials/foot") ?>
<?php $this->view("Partials/footer") ?>
</body>
</html>