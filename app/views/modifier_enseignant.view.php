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
                            <h5 class="content-header-title float-left pr-1 mb-0">Enregistrements de l'Enseignant</h5>
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
                                                <div class="col-sm-4">
                                                    <label class="form-label" for="statut">Statut</label>
                                                    <div class="form-group">
                                                        <select class="select2 form-control" name="enseignant_statut" id="statut" onchange="toggleFields()">
                                                            <option value="VACT" <?= $enseignant->enseignant_statut === 'VACT' ? 'selected' : '' ?>>VACT</option>
                                                            <option value="CDI" <?= $enseignant->enseignant_statut === 'CDI' ? 'selected' : '' ?>>CDI</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <label class="form-label">Nom</label>
                                                    <div class="form-group">
                                                        <input type="text" name="enseignant_nom" class="form-control" value="<?= htmlspecialchars($enseignant->enseignant_nom) ?>" />
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <label class="form-label">Prénom</label>
                                                    <div class="form-group">
                                                        <input type="text" name="enseignant_prenom" class="form-control" value="<?= htmlspecialchars($enseignant->enseignant_prenom) ?>" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <label class="form-label">Date de Naissance</label>
                                                    <div class="form-group">
                                                        <input type="date" name="enseignant_date_naissance" class="form-control" value="<?= htmlspecialchars($enseignant->enseignant_date_naissance) ?>" />
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <label class="form-label">Email</label>
                                                    <div class="form-group">
                                                        <input type="email" name="enseignant_email" class="form-control" value="<?= htmlspecialchars($enseignant->enseignant_email) ?>" />
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <label class="form-label">Téléphone</label>
                                                    <div class="form-group">
                                                        <input type="text" name="enseignant_telephone" class="form-control" value="<?= htmlspecialchars($enseignant->enseignant_telephone) ?>" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <label class="form-label">Diplôme</label>
                                                    <div class="form-group">
                                                        <input type="text" name="enseignant_diplome" class="form-control" value="<?= htmlspecialchars($enseignant->enseignant_diplome) ?>" />
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <label class="form-label">CV</label>
                                                    <div class="form-group">
                                                        <input type="file" name="enseignant_cv" class="form-control" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div id="matricule-container" style="display: <?= $enseignant->enseignant_statut === 'CDI' ? 'block' : 'none' ?>;">
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <label class="form-label">Matricule</label>
                                                        <div class="form-group">
                                                            <input type="text" name="enseignant_matricule" class="form-control" value="<?= htmlspecialchars($enseignant->enseignant_matricule) ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <label class="form-label">Grade</label>
                                                        <div class="form-group">
                                                            <select class="select2 form-control" name="enseignant_grade">
                                                                <option value="Assistant" <?= $enseignant->enseignant_grade === 'Assistant' ? 'selected' : '' ?>>Assistant</option>
                                                                <option value="Maitre Assistant" <?= $enseignant->enseignant_grade === 'Maitre Assistant' ? 'selected' : '' ?>>Maitre Assistant</option>
                                                                <option value="Maitre de Conférence" <?= $enseignant->enseignant_grade === 'Maitre de Conférence' ? 'selected' : '' ?>>Maitre de Conférence</option>
                                                                <option value="Professeur" <?= $enseignant->enseignant_grade === 'Professeur' ? 'selected' : '' ?>>Professeur</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                                 <div class="col-12 d-flex justify-content-end mt-4">
                                                        <button name="submit" type="submit" class="btn btn-primary">Modifier</button>
                                                </div>
                                            <!-- <button type="submit" name="submit" class="btn btn-primary" style="float: right;"></button> -->
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
            const statut = document.getElementById('statut').value;
            const matriculeContainer = document.getElementById('matricule-container');
            const gradeContainer = document.getElementById('grade-container');

            if (statut === 'CDI') {
                matriculeContainer.style.display = 'block';
                gradeContainer.style.display = 'block';
            } else {
                matriculeContainer.style.display = 'none';
                gradeContainer.style.display = 'none';
            }
        }
        document.addEventListener('DOMContentLoaded', toggleFields);
    </script>
</body>
</html>
