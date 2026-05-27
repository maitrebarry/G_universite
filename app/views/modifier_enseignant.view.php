 <style>
    .form-horizontal .row {
    margin-bottom: 1.5rem;
}

.form-horizontal .form-label {
    font-weight: bold;
}

.form-horizontal .form-control {
    width: 100%;
    padding: 0.5rem;
}

#matricule-container {
    margin-top: 1rem;
}

 </style>

<!-- inclusion du partie header -->
<?php $this->view("Partials/header") ?>
<body class="vertical-layout vertical-menu-modern boxicon-layout no-card-shadow 2-columns  navbar-sticky footer-static" data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">
    <!-- inclusion du partie header -->
    <?php $this->view("Partials/navbar") ?>
    <!-- inclusion du partie header fin-->
    <!-- inclusion du partie sidebar-->
    <?php $this->view("Partials/seibar") ?>
    <!-- inclusion du partie sidebar fin-->
    <!-- Content -->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-12 mb-2 mt-1">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h5 class="content-header-title float-left pr-1 mb-0">Modification de l'Enseignant</h5>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb p-0 mb-0">
                                    <li class="breadcrumb-item"><a href="index.html"><i class="bx bx-home-alt"></i></a></li>
                                    <li class="breadcrumb-item"><a href="#">Gestion Enseignant</a></li>
                                    <li class="breadcrumb-item active">modification</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <!-- Formulaire -->
                <section id="table-chechbox">
                    <div class="row">
                        <div class="col-12">
                            <div class="card card-animated-border-top">
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
                                <div class="card-content">
                            
                                    <div class="card-body card-dashboard">
                                       
                                    <form method="POST" enctype="multipart/form-data" id="updateForm" class="form-horizontal">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <label class="form-label" for="statut">Statut</label>
                                                <select class="select2 form-control" name="enseignant_statut" id="statut" onchange="toggleFields()">
                                                    <option value="NON_PERMANANT" <?= $enseignant->enseignant_statut === 'NON_PERMANANT' ? 'selected' : '' ?>>NON PERMANANT</option>
                                                    <option value="PERMANANT" <?= $enseignant->enseignant_statut === 'PERMANANT' ? 'selected' : '' ?>>PERMANANT</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-6">
                                                <label class="form-label">Nom</label>
                                                <input type="text" name="enseignant_nom" class="form-control" value="<?= htmlspecialchars($enseignant->enseignant_nom) ?>" />
                                            </div>
                                            <div class="col-sm-6">
                                                <label class="form-label">Matricule</label>
                                                <input type="text" name="enseignant_matricule" class="form-control" value="<?= htmlspecialchars($enseignant->enseignant_matricule) ?>" />
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-6">
                                                <label class="form-label">Prénom</label>
                                                <input type="text" name="enseignant_prenom" class="form-control" value="<?= htmlspecialchars($enseignant->enseignant_prenom) ?>" />
                                            </div>
                                            <div class="col-sm-6">
                                                <label class="form-label">Date de Naissance</label>
                                                <input type="date" name="enseignant_date_naissance" class="form-control" value="<?= htmlspecialchars($enseignant->enseignant_date_naissance) ?>" />
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-6">
                                                <label class="form-label">Email</label>
                                                <input type="email" name="enseignant_email" class="form-control" value="<?= htmlspecialchars($enseignant->enseignant_email) ?>" />
                                            </div>
                                            <div class="col-sm-6">
                                                <label class="form-label">Téléphone</label>
                                                <input type="text" name="enseignant_telephone" class="form-control" value="<?= htmlspecialchars($enseignant->enseignant_telephone) ?>" oninput="formatPhoneNumber(this)" />
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-6">
                                                <label class="form-label">Diplôme</label>
                                                <select class="select2 form-control" name="enseignant_diplome">
                                                    <option value="Master" <?= $enseignant->enseignant_diplome === 'Master' ? 'selected' : '' ?>>Master</option>
                                                    <option value="Doctorat" <?= $enseignant->enseignant_diplome === 'Doctorat' ? 'selected' : '' ?>>Doctorat</option>
                                                </select>
                                            </div>
                                            <div class="col-sm-6" id="cv-container">
                                                <label class="form-label">CV</label>
                                                <input type="file" name="enseignant_cv" class="form-control" />
                                                <?php if (!empty($enseignant->enseignant_cv)): ?>
                                                    <small>Fichier existant : <a href="<?= ROOT ?>/public/assets/cv/<?= htmlspecialchars($enseignant->enseignant_cv) ?>" target="_blank">Voir</a></small>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                        <!-- PERMANANT: Grade seulement -->
                                        <div id="grade-container" class="row" style="display: <?= $enseignant->enseignant_statut === 'PERMANANT' ? 'flex' : 'none' ?>;">
                                            <div class="col-sm-6">
                                                <label class="form-label">Grade</label>
                                                <select class="select2 form-control" name="id_grade">
                                                    <?php foreach ($grades as $grade): ?>
                                                        <option value="<?= $grade->id_grade ?>" <?= $enseignant->id_grade == $grade->id_grade ? 'selected' : '' ?>>
                                                            <?= htmlspecialchars($grade->nom_grade, ENT_QUOTES, 'UTF-8') ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- NON PERMANANT: Contrat et code bancaire -->
                                        <div id="non-permanent-fields" class="row" style="display: <?= $enseignant->enseignant_statut === 'NON_PERMANANT' ? 'flex' : 'none' ?>;">
                                            <div class="col-sm-6">
                                                <label class="form-label">Contrat</label>
                                                <input type="file" name="contrat" class="form-control" />
                                                <?php if (!empty($enseignant->contrat)): ?>
                                                    <small>Fichier existant : <a href="<?= ROOT ?>/public/assets/contrat/<?= htmlspecialchars($enseignant->contrat) ?>" target="_blank">Voir</a></small>
                                                <?php endif; ?>
                                            </div>
                                            <div class="col-sm-6">
                                                <label class="form-label">Code Bancaire</label>
                                                <input type="text" name="code_bancaire" class="form-control" value="<?= htmlspecialchars($enseignant->code_bancaire ?? '') ?>" />
                                            </div>
                                        </div>

                                        <div class="col-12 d-flex justify-content-end mt-4">
                                            <button name="submit" type="submit" class="btn btn-primary">Modifier</button>
                                        </div>
                                    </form>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- Formulaire -->
            </div>
        </div>
    </div>
    <!-- Fin Content -->

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <!-- inclusion du partie footer -->
    <?php $this->view("Partials/foot") ?>
    <?php $this->view("Partials/footer") ?>
    <script>
        function toggleFields() {
            const statut = document.getElementById("statut").value;
            const gradeContainer = document.getElementById("grade-container");
            const nonPermanentFields = document.getElementById("non-permanent-fields");
            const cvContainer = document.getElementById("cv-container");

            if (statut === "PERMANANT") {
                gradeContainer.style.display = "flex";
                nonPermanentFields.style.display = "none";
                cvContainer.style.display = "none";
            } else {
                gradeContainer.style.display = "none";
                nonPermanentFields.style.display = "flex";
                cvContainer.style.display = "flex";
                }
            }

            document.addEventListener('DOMContentLoaded', toggleFields);
    </script>

    <script src="<?= ROOT ?>/assets/mon_js/formate_numberphone.js"></script>
</body>
</html>
