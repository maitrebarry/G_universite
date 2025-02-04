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

.alert {
    margin-top: 10px;
}

.char-count {
    font-size: 12px;
    color: gray;
    text-align: right;
}

.error {
    font-size: 12px;
    color: red;
}
</style>

<body class="vertical-layout vertical-menu-modern boxicon-layout no-card-shadow 2-columns navbar-sticky footer-static"
    data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">

    <!-- inclusion du partie navbar -->
    <?php $this->view("Partials/navbar") ?>

    <!-- inclusion du partie seibar -->
    <?php $this->view("Partials/seibar") ?>

    <!-- Content -->
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
                                                class="bx bx-home-alt"></i></a></li>
                                    <li class="breadcrumb-item"><a
                                            href="<?php echo ROOT ?>/Filieres/liste_filiere">Liste des Filières</a></li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <section id="basic-datatable">
                    <div class="row">
                        <div id="message" class="col-12"></div>
                        <div class="col-12">
                            <div class="card card-animated-border-top">
                                <div class="card-content">
                                    <div class="card-body">
                                        <div class="row d-flex justify-content-around">
                                            <div class="col-md-4">
                                                <label for="nomFiliere" class="form-label">Nom de la Filière</label>
                                                <input type="text" id="nomFiliere" class="form-control"
                                                    placeholder="ex : Génie Informatique" maxlength="101">
                                                <div class="char-count" id="nomFiliereCount">0/100</div>
                                                <div class="error" id="nomFiliereError"></div>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="sigleFiliere" class="form-label">Code de la Filière</label>
                                                <input type="text" id="sigleFiliere" class="form-control"
                                                    placeholder="ex : GI" maxlength="50">
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
                                            <div class="section">
                                                <div class="semestre d-flex justify-content-center align-items-center ">
                                                    <label for="idSemestre" class="form-label col-md-4">Selectionner un
                                                        Semestre</label>
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
                                                <div class="error w-100 d-flex justify-content-end"
                                                    id="idSemestreError"></div>
                                            </div>
                                            <div id="semestresTable"></div>
                                            <div class="mt-4" style="float: right;">
                                                <button type="button" class="btn btn-primary" id="btnEnregistrer"
                                                    disabled onclick="ajouterFiliere()">Enregistrer la Filière</button>
                                            </div>
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

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <!-- inclusion du partie foot -->
    <?php $this->view("Partials/foot") ?>

    <!-- inclusion du partie footer -->
    <?php $this->view("Partials/footer") ?>
    <script src="<?= ROOT ?>/assets/mon_js/filiere.js"></script>
    <script>
    $(document).ready(function() {


        // Mise à jour des compteurs de caractères
        $(".form-control").on("input", function() {
            const maxLength = $(this).attr("maxlength");
            const currentLength = $(this).val().length;
            $(this).siblings(".char-count").text(`${currentLength}/${maxLength}`);
            validateForm();
        });

        // Validation lors du changement de sélection
        $("#idSemestre").on("change", function() {
            validateForm();
        });


    });
    </script>

</body>