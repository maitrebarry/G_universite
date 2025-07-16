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
                <h2 class="content-header-title float-start mb-0">Tableau de bord</h2>
                <div class="breadcrumb-wrapper">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active" id="role-indicator">Enseignant</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="content-body">

            <!-- ==================================== -->
            <!-- SECTION ENSEIGNANT (BASE POUR TOUS) -->
            <!-- ==================================== -->
            <section id="dashboard-enseignant">
                <div class="row">
                    <?php $this->view("set_flash") ?>

                    <!-- Cartes Enseignant -->
                    <div class="row col-12 mt-2">
                        <!-- Mes Cours -->
                        <div class="col-lg-3 col-md-6 col-sm-12 mb-2">
                            <div class="card card-animated-border-top1">
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="text-muted mb-1">Mes cours</p>
                                        <h4 class="text-primary mb-0">8</h4>
                                        <small class="text-muted">Cette semaine</small>
                                    </div>
                                    <div class="widget-icon bg-primary text-white">
                                        <i class="fa-solid fa-book-open"></i>
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
                                        <h4 class="text-success mb-0">142</h4>
                                        <small class="text-muted">Total</small>
                                    </div>
                                    <div class="widget-icon bg-success text-white">
                                        <i class="fa-solid fa-users"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Présences -->
                        <div class="col-lg-3 col-md-6 col-sm-12 mb-2">
                            <div class="card card-animated-border-top1">
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="text-muted mb-1">Présences</p>
                                        <h4 class="text-info mb-0">85%</h4>
                                        <small class="text-muted">Moyenne</small>
                                    </div>
                                    <div class="widget-icon bg-info text-white">
                                        <i class="fa-solid fa-user-check"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Notifications -->
                        <div class="col-lg-3 col-md-6 col-sm-12 mb-2">
                            <div class="card card-animated-border-top1">
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="text-muted mb-1">Notifications</p>
                                        <h4 class="text-warning mb-0">3</h4>
                                        <small class="text-muted">Non lues</small>
                                    </div>
                                    <div class="widget-icon bg-warning text-white">
                                        <i class="fa-solid fa-bell"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
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
                                                    <th>Jour</th>
                                                    <th>Heure</th>
                                                    <th>Matière</th>
                                                    <th>Niveau</th>
                                                    <th>Salle</th>
                                                    <th>Statut</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Lundi</td>
                                                    <td>08:00-10:00</td>
                                                    <td>Comptabilité</td>
                                                    <td>L2 GEA</td>
                                                    <td>B12</td>
                                                    <td><span class="badge bg-success">Confirmé</span></td>
                                                </tr>
                                                <tr>
                                                    <td>Mardi</td>
                                                    <td>10:30-12:30</td>
                                                    <td>Statistiques</td>
                                                    <td>L1 ST</td>
                                                    <td>A07</td>
                                                    <td><span class="badge bg-warning">En attente</span></td>
                                                </tr>
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
            <section id="dashboard-sgp" class="role-specific">
                <div class="row mt-3">
                    <div class="col-12">
                        <h4 class="mb-2">Vue Secrétaire Général</h4>
                    </div>

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
            <section id="dashboard-chef-der" class="role-specific">
                <div class="row mt-3">
                    <div class="col-12">
                        <h4 class="mb-2" id="chef-der-title">Vue Chef Département</h4>
                    </div>

                    <!-- Cartes Chef DER -->
                    <div class="row col-12 mt-2">
                        <!-- Étudiants L1 -->
                        <div class="col-lg-3 col-md-6 col-sm-12 mb-2">
                            <div class="card card-animated-border-top1">
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="text-muted mb-1">Étudiants L1</p>
                                        <h4 class="text-primary mb-0" id="l1-count">0</h4>
                                        <small class="text-muted" id="l1-trend">--</small>
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
                                        <h4 class="text-success mb-0" id="l2-count">0</h4>
                                        <small class="text-muted" id="l2-trend">--</small>
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
                                        <h4 class="text-info mb-0" id="l3-count">0</h4>
                                        <small class="text-muted" id="l3-trend">--</small>
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
                                        <h4 class="text-danger mb-0" id="unregistered-count">0</h4>
                                        <small class="text-muted" id="unregistered-detail">--</small>
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
                                        <h4 class="text-warning mb-0" id="teachers-count">0</h4>
                                        <small class="text-muted" id="teachers-detail">--</small>
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
                                        <h4 class="text-purple mb-0" id="courses-count">0</h4>
                                        <small class="text-muted" id="courses-period">--</small>
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
                                        <h4 class="text-teal mb-0" id="success-rate">0%</h4>
                                        <small class="text-muted" id="success-period">--</small>
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
                                        <h4 class="text-pink mb-0" id="exams-count">0</h4>
                                        <small class="text-muted" id="exams-period">--</small>
                                    </div>
                                    <div class="widget-icon bg-pink text-white">
                                        <i class="fa-solid fa-clipboard-list"></i>
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

                   <!-- Tableau des cours ST -->
                    <div class="row col-12 mt-2">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Cours programmés</h4>
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
                                                <tr>
                                                    <td>15/03/2023</td>
                                                    <td>10:30-12:30</td>
                                                    <td>Statistiques</td>
                                                    <td>L1 ST</td>
                                                    <td>Pr. Martin</td>
                                                    <td>A07</td>
                                                    <td><span class="badge bg-warning">En attente</span></td>
                                                </tr>
                                                <tr>
                                                    <td>17/03/2023</td>
                                                    <td>09:00-11:00</td>
                                                    <td>Informatique appliquée</td>
                                                    <td>L2 ST</td>
                                                    <td>Pr. Dubois</td>
                                                    <td>D15</td>
                                                    <td><span class="badge bg-success">Confirmé</span></td>
                                                </tr>
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
            <section id="dashboard-dga" class="role-specific">
                <div class="row mt-3">
                    <div class="col-12">
                        <h4 class="mb-2">Vue Directeur Général Adjoint</h4>
                    </div>

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
            <section id="dashboard-dg" class="role-specific">
                <div class="row mt-3">
                    <div class="col-12">
                        <h4 class="mb-2">Vue Directeur Général</h4>
                    </div>

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
    </div>
</div>

<!-- Scripts pour les graphiques et gestion des rôles -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


<?php $this->view("Partials/foot") ?>
<?php $this->view("Partials/footer") ?>
</body>
</html>