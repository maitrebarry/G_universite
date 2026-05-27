<!-- inclusion du partie header -->
<?php
 $this->view("Partials/header") ?>
<body class="vertical-layout vertical-menu-modern boxicon-layout no-card-shadow 2-columns  navbar-sticky footer-static  " data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">
    <!-- inclusion du partie header -->
    <?php $this->view("Partials/navbar") ?>
    <!-- inclusion du partie header fin-->
    <!-- inclusion du partie seibar-->
    <?php $this->view("Partials/seibar") ?>
    <!-- inclusion du partie seibar fin-->
    <!--  Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-12 mb-2 mt-1">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h5 class="content-header-title float-left pr-1 mb-0">Details des Etudiants</h5>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb p-0 mb-0">
                                    <li class="breadcrumb-item"><a href="index.html"><i class="bx bx-home-alt"></i></a>
                                    </li>
                                    <li class="breadcrumb-item"><a href="#">Gestion Etudiants</a>
                                    </li>
                                    <li class="breadcrumb-item active">Liste
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <!-- formulaire -->
                <section id="basic-datatable">
                <?php //if (!empty($etudiant)): ?>
                    <form action="" method="post">
                        <div class="row">
                            <div class="col-12">
                                <?php $this->view('set_flash'); ?>
                                <div class="card card-animated-border-top ">
                                    <div class="card-content">
                                        <div class="card-body">
                                            <!-- <p class="mb-1">Filtré par</p> -->
                                            <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="nom_prenom_etudiant">Nom et Prénom de l'étudiant</label>
                                                    <!-- Affichage des données dans le champ input -->
                                                    <?php if ($etudiant): ?>
                                                        <input type="text" class="form-control" id="nom_prenom_etudiant" name="nom_prenom_etudiant" value="<?= htmlspecialchars($etudiant['nom_prenom_etudiant']); ?> <?= htmlspecialchars($etudiant['prenom']); ?>" disabled>
                                                    <?php else: ?>
                                                        <p>Aucun étudiant trouvé.</p>
                                                    <?php endif; ?>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="date_naissance_etudiant">Date de naissance</label>
                                                    <!-- Affichage des données dans le champ input -->
                                                    <?php if ($etudiant): ?>
                                                        <input type="text" class="form-control" id="date_naissance_etudiant" name="date_naissance_etudiant" value="<?= htmlspecialchars($etudiant['date_naissance_etudiant']); ?>" disabled>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="lieu_naissance_etudiant">Lieu de naissance</label>
                                                    <!-- Affichage des données dans le champ input -->
                                                    <?php if ($etudiant): ?>
                                                        <input type="text" class="form-control" id="lieu_naissance_etudiant" name="lieu_naissance_etudiant" value="<?= htmlspecialchars($etudiant['lieu_naissance_etudiant']); ?>" disabled>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label" for="contact_etudiant">Contact</label>
                                                        <?php if ($etudiant): ?>
                                                            <input type="text" class="form-control" id="contact_etudiant" name="contact_etudiant" value="<?= htmlspecialchars($etudiant['contact_etudiant']); ?>" disabled>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label" for="single-select ">Genre d'etudiant</label>
                                                         <input type="text" class="form-control" id="" name="" value="<?= htmlspecialchars($etudiant['genre_etudiant']); ?>" disabled>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label" for="single-select ">Matricule</label>
                                                        <?php if ($etudiant): ?>
                                                        <input type="text" class="form-control" value="<?= htmlspecialchars($etudiant['matricule_etudiant']); ?>" disabled>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-8">
                                <div class="card card-animated-border-top ">
                                    <div class="card-content">
                                        <div class="card-body">
                                            <!-- <p class="mb-1">Filtré par</p> -->
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label" for="single-select ">Numero d'etudiant</label>
                                                        <?php if ($etudiant): ?>
                                                        <input type="text" class="form-control" name=""
                                                        value="<?= htmlspecialchars($etudiant['numetudiant']); ?>" disabled>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label" for="single-select ">Cercle de naissance</label>
                                                        <?php if ($etudiant): ?>
                                                        <input type="text" class="form-control" value="<?= htmlspecialchars($etudiant['cercleNais']); ?>" disabled>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label" for="single-select ">Commune de naissance</label>
                                                        <?php if ($etudiant): ?>
                                                        <input type="text" class="form-control" value="<?= htmlspecialchars($etudiant['commNais']); ?>" disabled>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label" for="single-select ">Nationalite</label>
                                                        <?php if ($etudiant): ?>
                                                        <input type="text" name="" class="form-control" value="<?= htmlspecialchars($etudiant['nationnalite']); ?>" disabled>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label" for="single-select ">Annee du diplome</label>
                                                        
                                                        <input type="text" class="form-control" value="<?= htmlspecialchars($etudiant['anneediplome']); ?>" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label" for="single-select ">Serie</label>
                                                        <?php if ($etudiant): ?>
                                                        <input type="text" class="form-control" value="<?= htmlspecialchars($etudiant['serie']); ?>" disabled>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label" for="single-select ">Pays</label>
                                                        <?php if ($etudiant): ?>
                                                       <input type="text" class="form-control" value="<?= htmlspecialchars($etudiant['pays']); ?>" disabled>
                                                       <?php endif; ?>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label" for="single-select ">Academie</label>
                                                        <?php if ($etudiant): ?>
                                                        <input type="text" class="form-control" value="<?= htmlspecialchars($etudiant['academie']); ?>" disabled>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label" for="single-select ">Adresse</label>
                                                        <?php if ($etudiant): ?>
                                                        <input type="text" class="form-control" value="<?= htmlspecialchars($etudiant['adresseactuel']); ?>" disabled>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label" for="single-select ">Numero de place</label>
                                                        <?php if ($etudiant): ?>
                                                        <input type="text" class="form-control" value="<?= htmlspecialchars($etudiant['numplace']); ?>" disabled>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="card card-animated-border-top ">
                                    <div class="card-content">
                                        <div class="card-body">
                                            <!-- <p class="mb-1">Filtré par</p> -->
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="form-label" for="single-select ">Image</label>
                                                        <!-- <img src="<?= ROOT ?>/téle.png" alt=""> -->
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="form-label" for="single-select ">Diplome</label>
                                                        <?php if ($etudiant): ?>
                                                        <input type="text" class="form-control" value="<?= htmlspecialchars($etudiant['diplome']); ?>" disabled>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label" for="single-select ">Statut</label>
                                                        <?php if ($etudiant): ?>
                                                        <input type="text" class="form-control" value="<?= htmlspecialchars($etudiant['id_statut']); ?>" disabled>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                    <?php if ($etudiant): ?>
                                                        <label class="form-label" for="single-select">Filière</label>
                                                        <input type="text" class="form-control" value="<?= htmlspecialchars($etudiant['sigle_filiere']); ?>" disabled>
            

                                                        </select>
                                                    <?php endif; ?>

                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="form-label" for="single-select ">Promotion</label>
                                                            <?php foreach ($filieres as $Promotion): ?>  
                                                        <input type="text" class="form-control"  value="<?= htmlspecialchars($Promotion->sigle_filiere."-".$Promotion->nom_semestre ."(".$Promotion->annee_universitaire.")"); ?>" disabled>
                                                                              
                                                          <?php endforeach; ?>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="card card-animated-border-top ">
                                    <div class="card-content">
                                        <div class="card-body card-dashboard">
                                            <div class="table-responsive">
                                                <table class="table zero-configuration">
                                                    <thead>
                                                        <tr>
                                                            <th>Nom && Prénom du pere</th>
                                                            <!-- <th>statut</th> -->
                                                            <th>Nom && Prénom du mere</th>
                                                            <th>Lieu de residence</th>
                                                            <!-- <th>MATRICULE</th>
                                                        <th class="text-center dt-no-sorting">Action</th> -->
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php if($etudiant): ?>
                                                            <tr>
                                                                <td>
                                                                    <?php echo strtoupper($etudiant['prenompere']) ?>
                                                                </td>
                                                                <td>
                                                                    <?php echo strtoupper($etudiant['prenomnommere']) ?>
                                                                </td>
                                                                <td class="text-center dt-no-sorting">
                                                                    <?php echo strtoupper($etudiant['lieuresidenceparents']) ?>
                                                                </td>
                                                            </tr>
                                                        <?php endif ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <?php //else: ?>
                        <p class="alert alert-danger">Aucun étudiant trouvé.</p>
                    <?php //endif; ?>
                </section>
                <!-- formulaire -->
            </div>
        </div>
    </div>
    <!-- fin: Content-->
    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>
    <!-- inclusion du partie foot-->
    <?php $this->view("Partials/foot") ?>
    <!-- inclusion du partie foot fin-->
    <!-- inclusion du partie footer-->
    <?php $this->view("Partials/footer") ?>
    <!-- inclusion du partie footer fin-->

</body>
<!-- END: Body-->
</html>