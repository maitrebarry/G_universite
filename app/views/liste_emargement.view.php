
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
                        <div class="card-header ">
                            <button type="button" class="btn btn-primary float-end " data-bs-toggle="modal" data-bs-target="#modalEmargement" style="float:right">
                                + Émargement
                            </button>
                        </div>
                        <div class="card-body">
                            <?php $this->view("set_flash"); ?>
                            <?php if (!empty($errors)): ?>
                                <div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <div class="d-flex align-items-center">
                                        <i class="bx bx-error"></i>
                                        <span>
                                            <?php foreach ($errors as $error): ?>
                                                <?= htmlspecialchars($error) ?><br>
                                            <?php endforeach; ?>
                                        </span>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="card border-top border-4 border-primary">
                                <form method="POST" id="filterForm" class="mb-3">
                                    <div class="card-body row gy-3">
                                        <!-- Filtrer par enseignant -->
                                        <div class="col-md-3">

                                            <label for="filter_enseignant" class="form-label">Enseignant <span class="text-danger fs-6">*</span></label>
                                            <select name="enseignant" id="filter_enseignant" class="form-select form-control">
                                                <option value="">-- Tous les enseignants --</option>
                                                <?php foreach ($enseignants as $enseignant): ?>
                                                    <option value="<?php echo htmlspecialchars($enseignant->enseignant_id, ENT_QUOTES, 'UTF-8'); ?>"
                                                        <?php echo isset($_POST['enseignant']) && $_POST['enseignant'] == $enseignant->enseignant_id ? 'selected' : ''; ?>>
                                                        <?php echo htmlspecialchars($enseignant->enseignant_prenom . ' ' . $enseignant->enseignant_nom, ENT_QUOTES, 'UTF-8'); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>

                                        </div>

                                        <!-- Filtrer par filière -->
                                        <div class="col-md-3">

                                            <label for="filter_filiere" class="form-label">Filière <span class="text-danger fs-6">*</span></label>
                                            <select name="filiere" id="filter_filiere" class="form-select form-control">
                                                <option value="">-- Toutes les filières --</option>
                                                <?php foreach ($filiere as $fil): ?>
                                                    <option value="<?php echo htmlspecialchars($fil->id_filiere, ENT_QUOTES, 'UTF-8'); ?>"
                                                        <?php echo isset($_POST['filiere']) && $_POST['filiere'] == $fil->id_filiere ? 'selected' : ''; ?>>
                                                        <?php echo htmlspecialchars($fil->nom_filiere, ENT_QUOTES, 'UTF-8'); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>

                                        </div>

                                        <!-- Filtrer par semestre -->
                                        <div class="col-md-3">
                                            <label for="filter_semestre" class="form-label">Semestre <span class="text-danger fs-6">*</span></label>
                                            <select name="semestre" id="filter_semestre" class="form-select form-control">
                                                <option value="">-- Tous les semestres --</option>
                                                <?php foreach ($semestre as $sem): ?>
                                                    <option value="<?php echo htmlspecialchars($sem->id_semestre, ENT_QUOTES, 'UTF-8'); ?>"
                                                        <?php echo isset($_POST['semestre']) && $_POST['semestre'] == $sem->id_semestre ? 'selected' : ''; ?>>
                                                        <?php echo htmlspecialchars($sem->nom_semestre, ENT_QUOTES, 'UTF-8'); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <!-- Bouton de filtre aligné à droite -->
                                        <div class="col-md-3 d-flex align-items-end">
                                            <button type="submit" name="submit_filtre" class="btn btn-primary w-100">Chercher</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <table class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Enseignant</th>
                                        <th style="width:5%">Statut</th>
                                        <th>Filière</th>
                                        <th>Semestre</th>
                                        <th style="width:10%">Heure Supp</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($resultats)): ?>
                                        <?php foreach ($resultats as $resultat): ?>
                                            <tr class="emargement-row" data-id="<?php echo htmlspecialchars($resultat->id_emargement); ?>" data-status="<?php echo htmlspecialchars($resultat->statut); ?>">
                                                <td><?php echo htmlspecialchars($resultat->enseignant_prenom . ' ' . $resultat->enseignant_nom); ?></td>
                                                <td>
                                                    <span class="badge bg-info text-wight">
                                                        <?php echo htmlspecialchars($resultat->enseignant_statut); ?>
                                                    </span>
                                                </td>
                                                <td><?php echo htmlspecialchars($resultat->nom_filiere); ?></td>
                                                <td><?php echo htmlspecialchars($resultat->nom_semestre); ?></td>
                                                <td>
                                                    <span class="badge bg-info text-wight">
                                                        <?php echo htmlspecialchars($resultat->total_heures_supplementaires); ?>
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <div class="">
                                                        <a href="#" role="button" id="dropdownMenuLink1" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal">
                                                                <circle cx="12" cy="12" r="1"></circle>
                                                                <circle cx="19" cy="12" r="1"></circle>
                                                                <circle cx="5" cy="12" r="1"></circle>
                                                            </svg>
                                                        </a>
                                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink1">
                                                            <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modalEmargementUpdate"
                                                                data-id="<?= $resultat->id_emargement ?>"
                                                                data-enseignant="<?= $resultat->id_enseignant ?>"
                                                                data-filiere="<?= $resultat->id_filiere ?>"
                                                                data-semestre="<?= $resultat->id_semestre ?>"
                                                                data-nh_programme="<?= $resultat->nh_programme ?>"
                                                                data-heuresSupp="<?= $resultat->heures_supp ?>"
                                                                data-heuresDues="<?= $resultat->heures_dues ?>"
                                                                data-statut="<?= $resultat->statut ?>"
                                                                data-grade="<?= $resultat->grade ?>"
                                                                data-dateDebut="<?= $resultat->date_debut ?>"
                                                                data-dateFin="<?= $resultat->date_fin ?>">
                                                                <i class="fa-solid fa-eye" style="color: #7367F0;"></i> Modifier
                                                            </a>
                                                            <a class="dropdown-item" href="<?= ROOT ?>/Enseignants/delete_emargement/<?= $resultat->id_emargement ?>">
                                                                <i class="fa-solid fa-trash me-1" style="color: #EA5455;"></i>Supprimer
                                                            </a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6">Aucune donnée trouvée.</td>
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
    <!-- Modal pour Ajouter un Émargement -->
    <div class="modal fade" id="modalEmargement" tabindex="-1" aria-labelledby="modalEmargementTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                <h5 class="modal-title white" id="myModalLabel160">Enregistrement de l'emargements</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body border-top border-4 border-primary">
                    <form method="POST" action="" class='form'>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Statut <span class="text-danger fs-6">*</span></label>
                                        <select id="statut" name="statut" class="form-select form-control">
                                            <option selected disabled>Sélectionnez un statut</option>
                                            <option value="1">CDI</option>
                                            <option value="2">VCT</option>
                                        </select>
                                        <div id="statut_error" class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Enseignant <span class="text-danger fs-6">*</span></label>
                                        <select id="enseignant_select" name="enseignant" class="select2 form-select form-control" data-placeholder="Sélectionnez un enseignant">
                                            <!-- Les options seront ajoutées par JavaScript -->
                                        </select>
                                        <div id="statut_error" class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Filière <span class="text-danger fs-6">*</span></label>
                                        <select class="select2 form-control" name="filiere" id="filiere" required>
                                            <option selected disabled>Sélectionnez une filière</option>
                                            <?php foreach ($filiere as $filieres): ?>
                                                <option value="<?= $filieres->id_filiere ?>"><?= $filieres->nom_filiere ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div id="statut_error" class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Semestre <span class="text-danger fs-6">*</span></label>
                                        <select id="semestre" name="semestre" class="form-select form-control" required>
                                            <option selected disabled>Sélectionnez un semestre</option>
                                            <?php foreach ($semestre as $semestres): ?>
                                                <option value="<?= $semestres->id_semestre ?>"><?= $semestres->sigle_semestre ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div id="statut_error" class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Date début EDT <span class="text-danger fs-6">*</span></label>
                                        <input type="date" class="form-control" id="date_debut" name="date_debut">
                                        <div id="statut_error" class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Date fin EDT <span class="text-danger fs-6">*</span></label>
                                        <input type="date" class="form-control" id="date_fin" name="date_fin">
                                        <div id="statut_error" class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">N/H Programmé <span class="text-danger fs-6">*</span></label>
                                        <input type="number" class="form-control" id="nh_programme" name="nh_programme" placeholder="Nombre d'heures">
                                        <div id="statut_error" class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Heures Supp</label>
                                        <input type="number" class="form-control" id="heures_supp" name="heures_supp" placeholder="Heures supplémentaires" value="0" readonly>
                                        <div id="statut_error" class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                            <div id="grade-container" class="row" style="display: none;">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Grade</label>
                                        <input type="text" class="form-control" id="grade" name="grade" placeholder="Grade" readonly>
                                        <div id="statut_error" class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Heures Dues</label>
                                        <input type="number" class="form-control" id="heures_dues" name="heures_dues" placeholder="Heures dues" readonly>
                                        <div id="statut_error" class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" name="submit" class="btn btn-primary">Envoyer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- partie Modification -->
    <div class="modal fade" id="modalEmargementUpdate" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title white" id="myModalLabel160"> Modification d'emargement</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="bx bx-x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="modalEmargementUpdate" action="<?= ROOT ?>/Enseignants/update_emargement/<?= $resultat->id_emargement  ?>">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Statut <span class="text-danger fs-6">*</span></label>
                                        <select id="statutUpdate" name="statut" class="form-select form-control">
                                            <option selected disabled>Sélectionnez un statut</option>
                                            <option value="1">CDI</option>
                                            <option value="2">VACT</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Enseignant <span class="text-danger fs-6">*</span></label>
                                        <select id="enseignant_selectUpdate" name="id_enseignant" class="form-select form-control select2">
                                            <option selected disabled>Sélectionnez un enseignant</option>
                                            <?php foreach ($enseignants as $enseignant): ?>
                                                <option value="<?= $enseignant->enseignant_id ?>">
                                                    <?= $enseignant->enseignant_prenom . ' ' . $enseignant->enseignant_nom ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Filière <span class="text-danger fs-6">*</span></label>
                                        <select id="filiereUpdate" name="id_filiere" class="form-select form-control select2">
                                            <option selected disabled>Sélectionnez une filière</option>
                                            <?php foreach ($filiere as $filieres): ?>
                                                <option value="<?= $filieres->id_filiere ?>"><?= $filieres->nom_filiere ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Semestre <span class="text-danger fs-6">*</span></label>
                                        <select id="semestreUpdate" name="id_semestre" class="form-select form-control select2">
                                            <option selected disabled>Sélectionnez un semestre</option>
                                            <?php foreach ($semestre as $semestres): ?>
                                                <option value="<?= $semestres->id_semestre ?>"><?= $semestres->nom_semestre ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Nombre d'heures programmées <span class="text-danger fs-6">*</span></label>
                                        <input type="text" class="form-control" id="nh_programmeUpdate" name="nh_programme" placeholder="Nombre d'heures programmées">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Heures supplémentaires <span class="text-danger fs-6">*</span></label>
                                        <input type="text" class="form-control" id="heures_suppUpdate" name="heures_supp" placeholder="Heures supplémentaires">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Date de début <span class="text-danger fs-6">*</span></label>
                                        <input type="date" class="form-control" id="date_debutUpdate" name="date_debut">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Date de fin <span class="text-danger fs-6">*</span></label>
                                        <input type="date" class="form-control" id="date_finUpdate" name="date_fin">
                                    </div>
                                </div>
                            </div>
                            <!-- Champs supplémentaires pour le statut "CDI" -->
                            <div id="cdiFieldsUpdate" class="row" style="display: none;">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Heures dues</label>
                                        <input type="text" class="form-control" id="heures_duesUpdate" name="heures_dues" placeholder="Heures dues">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Grade</label>
                                        <input type="text" class="form-control" id="gradeUpdate" name="grade" placeholder="Grade">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" name="modifier" class="btn btn-primary">Modifier</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- fin de la partie modification -->
    <!-- inclusion du footer -->
    <?php $this->view("Partials/foot") ?>
    <?php $this->view("Partials/footer") ?>
    <script src="<?= ROOT ?>/assets/mon_js/emargement.js"></script>
    <script src="<?= ROOT ?>/assets/mon_js/recuperation_modal_emargement.js"></script>
    <script src="<?= ROOT ?>/assets/mon_js/sweet_alert_suppression.js"></script>
    <script src="<?= ROOT ?>/assets/mon_js/contrainte_date.js"></script>
    <script>
        // Transmettez les enseignants à JavaScript
        var enseignants = <?= json_encode($enseignants); ?>;
    </script>






</body>