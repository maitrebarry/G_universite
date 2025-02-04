<!-- Inclusion de l'en-tête -->
<?php $this->view("Partials/header") ?>

<body class="vertical-layout vertical-menu-modern boxicon-layout no-card-shadow 2-columns navbar-sticky footer-static" data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">

    <!-- Inclusion de la barre de navigation -->
    <?php $this->view("Partials/navbar") ?>

    <!-- Inclusion de la barre latérale -->
    <?php $this->view("Partials/seibar") ?>

    <!-- Contenu principal -->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-12 mb-2 mt-1">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h5 class="content-header-title float-left pr-1 mb-0">Notes des Étudiants</h5>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb p-0 mb-0">
                                    <li class="breadcrumb-item"><a href="index.html"><i class="bx bx-home-alt"></i></a></li>
                                    <li class="breadcrumb-item"><a href="#">Gestion des notes</a></li>
                                    <li class="breadcrumb-item active">Enregistrement des notes</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-body">
                <!-- Formulaire -->
                <section id="table-checkbox">
                    <div class="row">
                        <div class="col-12">
                            <div class="card card-animated-border-top">
                                <div class="card-content">
                                    <div class="card-body">
                                        <!-- Formulaire pour ajouter des notes -->
                                        <form id="idForm">
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <label class="form-label" for="filiere">Filière</label>
                                                    <div class="form-group">
                                                        <select class="select2 form-control" id="filiere">
                                                            <option value="" disabled selected>Selectionner une Filière</option>
                                                            <?php foreach ($filieres as $filiere): ?>
                                                                <option value="<?php echo $filiere['id_filiere'] ?>">
                                                                    <?php echo strtoupper($filiere['sigle_filiere']) ?>
                                                                </option>
                                                            <?php endforeach ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <!-- Assure-toi que les filières sont correctement affichées dans la vue -->
                                                <div class="col-sm-3">
                                                    <label class="form-label" for="filiere">Filiere</label>
                                                    <div class="form-group">
                                                        <select class="select2 form-control" id="filiere">
                                                            <option value="0" disabled selected>Selectionner une Filiere</option>
                                                            <?php
                                                            echo '<pre>';
                                                            var_dump($filieres); // Vérifie si les données des filières existent
                                                            echo '</pre>';
                                                            foreach ($filieres as $filiere): ?>
                                                                <option value="<?= $filiere['id_filiere'] ?>"><?= strtoupper($filiere['sigle_filiere']) ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>


                                                <div class="col-sm-3">
                                                    <label class="form-label" for="modules">Modules</label>
                                                    <div class="form-group">
                                                        <select class="select2 form-control" id="modules" name="modules">
                                                            <option value="" disabled selected>Selectionner un Module</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Tableau des étudiants et notes -->
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Nom & Prénom</th>
                                                        <th>Devoir</th>
                                                        <th>Évaluation</th>
                                                        <th>Note session</th>
                                                        <th>Moyenne</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (!empty($etudiants)) : ?>
                                                        <?php foreach ($etudiants as $student) : ?>
                                                            <tr>
                                                                <td><?= htmlspecialchars($student['nom_prenom']) ?></td>
                                                                <td><input type="number" name="notes[<?= $student['id_etudiant'] ?>][note_devoir]" class="devoir" value=""></td>
                                                                <td><input type="number" name="notes[<?= $student['id_etudiant'] ?>][note_evaluation]" class="evaluation" value=""></td>
                                                                <td><input type="number" name="notes[<?= $student['id_etudiant'] ?>][note_session]" class="note_session" value=""></td>
                                                                <td><input type="text" name="notes[<?= $student['id_etudiant'] ?>][moyenne]" class="moyenne" disabled></td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    <?php else : ?>
                                                        <tr>
                                                            <td colspan="5">Aucun étudiant trouvé.</td>
                                                        </tr>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>
                                            <button type="submit" class="btn btn-primary">Envoyer</button>
                                        </form>

                                        <!-- Message de résultat -->
                                        <div id="message"></div>

                                    </div>
                                </div>
                            </div>
                </section>
                <!-- Fin du formulaire -->
            </div>
        </div>
    </div>

    <!-- Inclusion du pied de page -->
    <?php $this->view("Partials/foot") ?>
    <?php $this->view("Partials/footer") ?>

</body>
<!-- Script de calcul automatique de la moyenne --> 
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script> 
<script src="<?= ROOT ?>/assets/mon_js/edt.js"></script>
<!-- Script de calcul automatique de la moyenne -->
<script>
    // Variables pour stocker les informations de la filière
    var infoFiliere = [];

    // Lorsque la filière est changée
    $("#filiere").change(async function() {
        infoFiliere = await infosFiliere($(this).val());
        // Met à jour les promotions et modules liés à la filière sélectionnée
        promotionsFiliere(infoFiliere);
        var idSemestre = $("#promotions option:selected").data("id");
        modulesSemestre(idSemestre, infoFiliere);
        infoModule($("#modules").val(), infoFiliere);
        console.log(infoFiliere); // Pour le débogage
    });

    // Lorsque la promotion est changée
    $("#promotions").change(function() {
        var idSemestre = $("#promotions option:selected").data("id");
        modulesSemestre(idSemestre, infoFiliere);
        infoModule($("#modules").val(), infoFiliere);
    });

    // Initialisation des données lors du chargement du document
    $(document).ready(async function() {
        infoFiliere = await infosFiliere($("#filiere").val());
        promotionsFiliere(infoFiliere);
        var idSemestre = $("#promotions option:selected").data("id");
        modulesSemestre(idSemestre, infoFiliere);
        console.log(infoFiliere); // Pour le débogage
    });
</script>
