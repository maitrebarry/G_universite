<!-- inclusion du partie header -->
<?php $this->view("Partials/header") ?>
<style>
    body {
        background-color: #f8f9fa;
    }

    .container {
        margin-top: 30px;
    }

    .section {
        background-color: #ffffff;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        margin-top: 20px;
    }


    .module-item input:last-child {
        margin-right: 0;
    }

    .btn-custom {
        background-color: #007bff;
        color: white;
        border-radius: 6px;
    }



    .module-list {
        list-style-type: none;
        padding-left: 0;

    }

    .module-list li {
        padding: 10px;
        border-radius: 4px;
        margin: 5px 0;
    }

    .module-list li label {
        display: block;
        text-align: center;
    }

    .delete-btn {
        color: red;
        cursor: pointer;
        font-size: 14px;
    }

    .delete-btn:hover {
        color: darkred;
    }
</style>
<body
    class="vertical-layout vertical-menu-modern boxicon-layout no-card-shadow 2-columns  navbar-sticky footer-static  "
    data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">

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
                            <h5 class="content-header-title float-left pr-1 mb-0">Créer une Filière</h5>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb p-0 mb-0">
                                    <li class="breadcrumb-item"><a href="<?php echo ROOT ?>"><i
                                                class="bx bx-home-alt"></i></a>
                                    </li>
                                    <li class="breadcrumb-item"><a
                                            href="<?php echo ROOT ?>/Filieres/liste_filiere">Liste des Filières</a>
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
                    <div class="row">
                        <div id="message"></div>
                        <div class="col-12">
                            <div class="card card-animated-border-top ">
                                <div class="card-content">
                                    <div class="card-body">
                                        <div class="row d-flex justify-content-around">
                                            <div class="col-md-4">
                                                <label for="nomFiliere" class="form-label">Nom de la Filière</label>
                                                <input type="text" id="nomFiliere" class="form-control"
                                                    placeholder="ex : Génie Informatique" required>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="codeFiliere" class="form-label">Code de la Filière</label>
                                                <input type="text" id="sigleFiliere" class="form-control"
                                                    placeholder="ex : GI" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="card card-animated-border-top">
                                <div class="card-content">
                                    <div class="card-body card-dashboard">
                                        <div class="container">
                                            <!-- Formulaire de création de la filière -->
                                            <div class="section">
                                                <!-- le selection d'un semestre -->
                                                <div class="semestre d-flex justify-content-center align-items-center">
                                                    <!-- Sélectionner un semestre déjà créé -->
                                                    <label for="idSemestre" class="form-label col-md-4">
                                                        Selectionner un Niveau
                                                    </label>
                                                    <select id="idSemestre" class="select2 form-control col-md-4"
                                                        onchange="addSemestre()">
                                                        <option value="">Choisir un Semestre</option>
                                                        <?php foreach ($semestres as $semestre): ?>
                                                            <option value="<?php echo $semestre->id_semestre ?>">
                                                                <?php echo strtoupper($semestre->nom_semestre) ?>(
                                                                <?php echo strtoupper($semestre->sigle_semestre) ?> )
                                                            </option>
                                                        <?php endforeach ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <!-- Tableau des semestres ajoutés -->
                                            <div id="semestresTable"></div>
                                            <!-- Sauvegarde de la filière -->
                                            <div class="mt-4" style="float: right;">
                                                <button type="button" class="btn btn-primary"
                                                    onclick="ajouterFiliere()">Enregistrer la Filière</button>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
    <script src="<?= ROOT ?>/assets/mon_js/filiere.js"></script>
</body>
<!-- END: Body-->

</html>