
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
            <div class="modal-header bg-primary">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body border-top border-4 border-primary">
                <form id="formEmargement" method="POST" action="" class="form" novalidate>
                    <div class="box-body">
                        <div class="row">
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
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Enseignant <span class="text-danger fs-6">*</span></label>
                                    <select id="enseignant" name="id_enseignant" class="form-select form-control" required>
                                        <option selected disabled>Sélectionnez un enseignant</option>
                                        <option value="1">M. Alpha</option>
                                        <option value="2">Mme Beta</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Filière <span class="text-danger fs-6">*</span></label>
                                    <select class="select2 form-control" name="filliere" required>
                                        <option selected disabled>Sélectionnez une filière</option>
                                        <option value="1">Informatique</option>
                                        <option value="2">Mathématiques</option>
                                    </select>
                                </div>
                            </div>                         
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Niveau <span class="text-danger fs-6">*</span></label>
                                    <select id="niveau" name="niveau" class="form-select form-control" required>
                                        <option selected disabled>Sélectionnez un niveau</option>
                                        <option value="1">Semestre 1</option>
                                        <option value="2">Semestre 2</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Date début EDT <span class="text-danger fs-6">*</span></label>
                                    <input type="date" class="form-control" id="date_debut" name="date_debut" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Date fin EDT <span class="text-danger fs-6">*</span></label>
                                    <input type="date" class="form-control" id="date_fin" name="date_fin" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">N/H Programmé <span class="text-danger fs-6">*</span></label>
                                    <input type="number" class="form-control" id="nh_programme" name="nh_programme" placeholder="Nombre d'heures" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Heures Supp</label>
                                    <input type="number" class="form-control" id="heures_supp" name="heures_supp" placeholder="Heures supplémentaires" value="0" readonly>
                                </div>
                            </div>
                        </div>
                        <div id="grade-container" class="row" style="display: none;">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Grade</label>
                                    <select id="grade" name="grade" class="form-select form-control">
                                        <option selected disabled>Sélectionnez un grade</option>
                                        <option value="assistant">Assistant</option>
                                        <option value="maitre_assistant">Maitre-Assistant</option>
                                        <option value="maitre_conference">Maitre de Conférence</option>
                                        <option value="professeur">Professeur</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Heures Dues</label>
                                    <input type="number" class="form-control" id="heures_dues" name="heures_dues" placeholder="Heures dues" readonly>
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
<script>
    document.addEventListener("DOMContentLoaded", function() {
    $('#modalEmargement .select2').select2();
        const statutSelect = document.getElementById('statut');
        const gradeContainer = document.getElementById('grade-container');
        const heuresSuppInput = document.getElementById('heures_supp');
        const heuresDuesInput = document.getElementById('heures_dues');
        const nhProgrammeInput = document.getElementById('nh_programme');
        const gradeSelect = document.getElementById('grade');
        
        // Gère l'affichage du container de grade en fonction du statut
        statutSelect.addEventListener('change', function() {
            if (statutSelect.value == '1') { // CDI
                gradeContainer.style.display = 'block';
                // Met à jour les heures dues lorsque le statut CDI est sélectionné
                updateHeuresDues(); 
            } else {
                gradeContainer.style.display = 'none';
            }
        });

        // Fonction pour mettre à jour les heures dues en fonction du grade
        function updateHeuresDues() {
            const grade = gradeSelect.value;
            let heuresDues = 0;
            
            switch(grade) {
                case 'assistant':
                    heuresDues = 168;
                    break;
                case 'maitre_assistant':
                    heuresDues = 140;
                    break;
                case 'maitre_conference':
                    heuresDues = 112;
                    break;
                case 'professeur':
                    heuresDues = 82;
                    break;
                default:
                    heuresDues = 0;
                    break;
            }

            // Mettre à jour le champ heures dues
            heuresDuesInput.value = heuresDues;
            calculateHeuresSupp(); 
        }
        // Gère le calcul des heures supplémentaires
        function calculateHeuresSupp() {
            if (statutSelect.value == '1' && heuresDuesInput.value) { 
                const nhProgramme = parseInt(nhProgrammeInput.value);
                const heuresDues = parseInt(heuresDuesInput.value);

                if (nhProgramme > heuresDues) {
                    heuresSuppInput.value = nhProgramme - heuresDues; 
                } else {
                    heuresSuppInput.value = 0; 
                }
            }
        }
        // Recalcule automatiquement les heures dues lorsque le grade est modifié
        gradeSelect.addEventListener('change', function() {
            if (statutSelect.value == '1') { // CDI
                updateHeuresDues(); 
            }
        });

        // Calcule les heures supplémentaires quand l'utilisateur entre un nombre d'heures programmées
        nhProgrammeInput.addEventListener('input', calculateHeuresSupp);
        // Envoi du formulaire avec AJAX pour l'enregistrement des données
        document.getElementById('formEmargement').addEventListener('submit', function(event) {
            event.preventDefault(); 
            const formData = new FormData(this);
            // Envoi de la requête AJAX
            fetch('votre_url_de_traitement.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("Émargement ajouté avec succès !");
                    $('#modalEmargement').modal('hide');
                } else {
                    alert("Erreur lors de l'ajout de l'émargement !");
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert("Une erreur s'est produite, veuillez réessayer.");
            });
        });
    });   
</script>



<!-- Script JS pour Select2 -->



    <!-- inclusion du footer -->
    <?php $this->view("Partials/foot") ?>
    <?php $this->view("Partials/footer") ?>

 
</body>
