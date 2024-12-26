<?php $this->view("Partials/header") ?>

<body class="vertical-layout vertical-menu-modern boxicon-layout no-card-shadow 2-columns navbar-sticky footer-static" data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">
    <!-- inclusion de la navbar -->
    <?php $this->view("Partials/navbar") ?>

    <!-- inclusion de la sidebar -->
    <?php $this->view("Partials/seibar") ?>

    <!-- Content -->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-12 mb-2 mt-1">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h5 class="content-header-title float-left pr-1 mb-0">Gestion des Émargements</h5>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb p-0 mb-0">
                                    <li class="breadcrumb-item"><a href="<?= ROOT ?>"><i class="bx bx-home-alt"></i></a></li>
                                    <li class="breadcrumb-item active">Liste d'Émargements</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-12">
                    <div class="card card-animated-border-top">
                        <div class="card-body">
                            <button type="button" class="btn btn-primary float-end mb-3" data-bs-toggle="modal" data-bs-target="#modalEmargement"style="float:right">
                                + Émargement
                            </button>

                            <table class="table zero-configuration table-bordered">
                                <thead>
                                    <tr>
                                        <th>Enseignant</th>
                                        <th>Filière</th>
                                        <th>Niveau</th>
                                        <th>Date debut EDT</th>
                                        <th>Date fin EDT</th>
                                        <th>N/H Programmé</th>
                                        <th>Heures Supp</th>
                                        <th>Statut</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="emargement-row" data-id="1" data-status="CDI">
                                        <td>Mr. Dupont</td>
                                        <td>Informatique</td>
                                        <td>L1-S1</td>
                                        <td>2024-12-15</td>
                                        <td>10</td>
                                        <td>2</td>
                                        <td>CDI</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

   <!-- Modal pour Ajouter un Émargement -->
<div class="modal fade" id="modalEmargement" tabindex="-1" aria-labelledby="modalEmargementTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <!-- Entête du modal -->
            <div class="modal-header">
                <h5 class="modal-title" id="modalEmargementTitle">Ajouter un Émargement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- Corps du modal -->
            <div class="modal-body border-top border-4 border-primary">
                <form method="POST" action="" class="form" novalidate>
                    <div class="box-body">
                        <div class="row">
                            <!-- Statut -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Statut <span class="text-danger fs-6">*</span></label>
                                    <select id="statut" name="statut" class="form-select form-control" required>
                                        <option selected disabled>Sélectionnez un statut</option>
                                        <option value="1">CDI</option>
                                        <option value="2">VCT</option>
                                    </select>
                                </div>
                            </div>
                            <!-- Filière -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Filière <span class="text-danger fs-6">*</span></label>
                                    <select class="select2 form-control" name="filliere" required>
                                        <option selected disabled>Sélectionnez une filière</option>
                                        <!-- Option dynamique des filières -->
                                        <option value="1">Informatique</option>
                                        <option value="2">Mathématiques</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <!-- Niveau -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Niveau <span class="text-danger fs-6">*</span></label>
                                    <select id="niveau" name="niveau" class="form-select form-control" required>
                                        <option selected disabled>Sélectionnez un niveau</option>
                                        <!-- Option dynamique des niveaux -->
                                        <option value="1">Licence 1</option>
                                        <option value="2">Licence 2</option>
                                    </select>
                                </div>
                            </div>
                            <!-- Date début EDT -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Date début EDT <span class="text-danger fs-6">*</span></label>
                                    <input type="date" class="form-control" id="date_debut" name="date_debut" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <!-- Date fin EDT -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Date fin EDT <span class="text-danger fs-6">*</span></label>
                                    <input type="date" class="form-control" id="date_fin" name="date_fin" required>
                                </div>
                            </div>
                            <!-- N/H Programmé -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">N/H Programmé <span class="text-danger fs-6">*</span></label>
                                    <input type="number" class="form-control" id="nh_programme" name="nh_programme" placeholder="Nombre d'heures" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <!-- Heures Supp -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Heures Supp</label>
                                    <input type="number" class="form-control" id="heures_supp" name="heures_supp" placeholder="Heures supplémentaires" value="0">
                                </div>
                            </div>
                            <!-- Enseignant -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Enseignant <span class="text-danger fs-6">*</span></label>
                                    <select id="enseignant" name="id_enseignant" class="form-select form-control" required>
                                        <option selected disabled>Sélectionnez un enseignant</option>
                                        <!-- Option dynamique des enseignants -->
                                        <option value="1">M. Alpha</option>
                                        <option value="2">Mme Beta</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Footer du modal -->
                    <div class="modal-footer">          
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" name="submit" class="btn btn-primary">Envoyer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Script JS pour Select2 -->



    <!-- inclusion du footer -->
    <?php $this->view("Partials/foot") ?>
    <?php $this->view("Partials/footer") ?>

 
</body>
