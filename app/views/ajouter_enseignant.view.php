<?php $this->view("Partials/header") ?>

<body class="vertical-layout vertical-menu-modern boxicon-layout no-card-shadow 2-columns navbar-sticky footer-static" data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">
    <?php $this->view("Partials/navbar") ?>
    <?php $this->view("Partials/seibar") ?>
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-12 mb-2 mt-1">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h5 class="content-header-title float-left pr-1 mb-0">Enregistrement de l'Enseignant</h5>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb p-0 mb-0">
                                    <li class="breadcrumb-item"><a href="index.html"><i class="bx bx-home-alt"></i></a></li>
                                    <li class="breadcrumb-item"><a href="#">Gestion Enseignant</a></li>
                                    <li class="breadcrumb-item active">Enregistrement</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>              
            <div class="content-body">
                <section class="simple-validation">
                <?php $this->view("set_flash"); ?> 
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card ">
                                <div class="card card-animated-border-top">                                                  
                                    <div class="card-content">
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
                                        <div class="card-body">
                                          <form method="POST" class="form" enctype="multipart/form-data">
                                                <div class="box-body">
                                                    <div class="row">
                                                        <!-- Statut -->
                                                        <div class="col-md-6">
                                                            <label class="form-label">Statut<span class="text-danger">*</span></label>
                                                            <select name="statut" id="statut" class="form-select form-control" onchange="toggleFields()">
                                                                <option value="" disabled selected>Choisissez le statut</option>
                                                                <option value="NON_PERMANANT">NON PERMANANT</option>
                                                                <option value="PERMANANT">PERMANANT</option>
                                                            </select>
                                                        </div>
                                                        <!-- CV -->
                                                        <div class="col-md-6" id="cv-container">
                                                            <label class="form-label">CV<span class="text-danger">*</span></label>
                                                            <input type="file" name="cv" class="form-control" 
                                                            value="<?= htmlspecialchars($input_values['cv'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                                        </div>
                                                    </div>              
                                                    <div class="row">
                                                        <!-- Contrat -->
                                                        <div class="col-md-6" id="contarat-container">
                                                            <label class="form-label">Contrat<span class="text-danger">*</span></label>
                                                            <input type="file" name="contrat" class="form-control" 
                                                            value="<?= htmlspecialchars($input_values['contrat'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                                        </div>
                                                        <!-- Code Bancaire -->
                                                        <div class="col-md-6" id="code-container">
                                                            <label class="form-label">Code Bancaire<span class="text-danger">*</span></label>
                                                            <input name="code" id="code" type="text" class="form-control" placeholder="Code Bancaire"
                                                            value="<?= htmlspecialchars($input_values['code'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="row">
                                                        <!-- Matricule -->
                                                        <div class="col-md-6">
                                                            <label class="form-label">Matricule<span class="text-danger">*</span></label>
                                                            <input type="text" name="matricule" id="matricule" class="form-control" placeholder="Matricule"
                                                            value="<?= htmlspecialchars($input_values['matricule'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                                        </div>     
                                                        <!-- Administration (visible seulement si PERMANANT) -->
                                                        <div class="col-md-3" id="administration-container" style="display: none;">
                                                            <label class="form-label">Administration</label>
                                                            <div class="btn-group w-100" role="group">
                                                                <button type="button" class="btn btn-outline-primary active" data-value="0">Non</button>
                                                                <button type="button" class="btn btn-outline-primary" data-value="1">Oui</button>
                                                            </div>
                                                            <input type="hidden" name="administration" id="administrationInput" value="0">
                                                        </div>            
                                                        <!-- Grade -->
                                                        <div class="col-md-3" id="grade-container" style="display: none;">
                                                            <label class="form-label">Grade<span class="text-danger">*</span></label>
                                                            <select id="grade" name="grade" class="form-select form-control select2">
                                                                <option selected disabled>Choisissez le grade</option>
                                                                <?php foreach ($filiere as $filieres): ?>
                                                                    <option value="<?= $filieres->id_grade ?>"><?= $filieres->nom_grade ?></option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>                        
                                                    </div>                               
                                                    <!-- Autres champs -->
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label class="form-label">Nom<span class="text-danger">*</span></label>
                                                            <input name="nom" id="nom" type="text" class="form-control" placeholder="Nom"
                                                            value="<?= htmlspecialchars($input_values['nom'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label">Prénom<span class="text-danger">*</span></label>
                                                            <input name="prenom" id="prenom" type="text" class="form-control" placeholder="Prénom"
                                                            value="<?= htmlspecialchars($input_values['prenom'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label class="form-label">Date de naissance<span class="text-danger">*</span></label>
                                                            <input name="date_naissance" id="date_naissance" type="date" class="form-control"
                                                            value="<?= htmlspecialchars($input_values['date_naissance'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label">Email<span class="text-danger">*</span></label>
                                                            <input name="email" id="email" type="email" class="form-control" placeholder="email"
                                                            value="<?= htmlspecialchars($input_values['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label class="form-label">Téléphone<span class="text-danger">*</span></label>
                                                            <input name="telephone" id="telephone" type="text" class="form-control" placeholder="Téléphone" 
                                                            value="<?= htmlspecialchars($input_values['telephone'] ?? '', ENT_QUOTES, 'UTF-8') ?>" oninput="formatPhoneNumber(this)">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label">Diplôme<span class="text-danger">*</span></label>
                                                            <select name="diplome" id="diplome" class="form-select form-control">
                                                                <option value="" disabled selected>Choisissez le diplôme</option>
                                                                <option value="Master">Master</option>
                                                                <option value="Doctorat">Doctorat</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Bouton envoyer -->
                                                    <div class="col-12 d-flex justify-content-end mt-4">
                                                        <button name="envoyer" type="submit" class="btn btn-primary">Envoyer</button>
                                                    </div>
                                                </div>
                                            </form>
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
    <?php $this->view("Partials/foot") ?>
    <?php $this->view("Partials/footer") ?>
  <script src="<?= ROOT ?>/assets/mon_js/validation_enseignant.js"></script>
  <script src="<?= ROOT ?>/assets/mon_js/formate_numberphone.js"></script>
  <script>
    function toggleFields() {
        const statut = document.getElementById("statut").value;
        const gradeContainer = document.getElementById("grade-container");
        const cvContainer = document.getElementById("cv-container");
        const contratContainer = document.getElementById("contarat-container");
        const codeContainer = document.getElementById("code-container");
        const adminContainer = document.getElementById("administration-container");

        if (statut === "PERMANANT") {
            gradeContainer.style.display = "block";
            adminContainer.style.display = "block";
            cvContainer.style.display = "none";
            contratContainer.style.display = "none";
            codeContainer.style.display = "none";
            
            // Réinitialiser l'état administration quand on change le statut
            document.getElementById('administrationInput').value = 0;
            const adminButtons = document.querySelectorAll('#administration-container .btn');
            adminButtons.forEach(btn => {
                btn.classList.remove('active');
                if(btn.dataset.value === '0') btn.classList.add('active');
            });
            
            // Mettre à jour les options de grade
            updateGradeOptions(0);
        } else {
            gradeContainer.style.display = "none";
            adminContainer.style.display = "none";
            cvContainer.style.display = "block";
            contratContainer.style.display = "block";
            codeContainer.style.display = "block";
        }
    }

    // Gestion des boutons Administration
    document.querySelectorAll('#administration-container .btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const value = this.dataset.value;
            document.getElementById('administrationInput').value = value;
            
            // Mettre à jour l'état visuel des boutons
            this.parentNode.querySelectorAll('.btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            // Mettre à jour les options de grade
            updateGradeOptions(value);
        });
    });

    // Fonction pour mettre à jour les options de grade
    function updateGradeOptions(isAdmin) {
        const gradeSelect = document.getElementById('grade');
        const currentValue = gradeSelect.value;
        
        // Vider les options actuelles
        gradeSelect.innerHTML = '<option selected disabled>Choisissez le grade</option>';
        
        // Filtrer les grades selon le statut administration
        <?php 
        // Préparer les grades en deux groupes (JS)
        $gradesNormal = [];
        $gradesAdmin = [];
        foreach ($filiere as $filieres) {
            if(strpos($filieres->nom_grade, '_admin') !== false) {
                $gradesAdmin[] = $filieres;
            } else {
                $gradesNormal[] = $filieres;
            }
        }
        ?>
        
        // Ajouter les options appropriées
        const grades = isAdmin == 1 ? <?= json_encode($gradesAdmin) ?> : <?= json_encode($gradesNormal) ?>;
        
        grades.forEach(grade => {
            const option = document.createElement('option');
            option.value = grade.id_grade;
            option.textContent = grade.nom_grade;
            if(grade.id_grade == currentValue) option.selected = true;
            gradeSelect.appendChild(option);
        });
    }
    
    // Initialiser au chargement si PERMANANT est sélectionné
    document.addEventListener('DOMContentLoaded', function() {
        if(document.getElementById('statut').value === 'PERMANANT') {
            toggleFields();
        }
    });
    </script>
</body>
</html>






