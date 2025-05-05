<?php $this->view("Partials/header") ?>
<style>
    /* Style pour le texte défilant */
    .scrolling-text {
        background-color: #007bff;
        /* Couleur d'arrière-plan */
        color: white;
        /* Couleur du texte */
        font-size: 20px;
        /* Taille du texte */
        padding: 10px 0;
        /* Espacement autour du texte */
        text-align: center;
        /* Centrer le texte */
        width: 100%;
        /* Largeur complète */
        overflow: hidden;
        /* Empêcher le texte de déborder */
    }

    .scrolling-text span {
        display: inline-block;
        white-space: nowrap;
        animation: scroll-left 10s linear infinite;
    }

    @keyframes scroll-left {
        0% {
            transform: translateX(100%);
            /* Début du texte à droite */
        }

        100% {
            transform: translateX(-100%);
            /* Fin du texte à gauche */
        }
    }

    /* Style pour les cadres */
    .form-section,
    .table-container {
        width: 100%;

        /* Largeur maximale uniforme */
        margin: 0 auto;
        /* Centrer les éléments */
        padding: 20px;
        border: 2px solid #007bff;
        border-radius: 10px;
        margin-bottom: 20px;
    }

    /* Tableau */
    #notesTable {
        width: 100%;
        max-width: 100%;
        border-collapse: collapse;
        margin: auto;
    }

    #notesTable th,
    #notesTable td {
        padding: 5px;
        text-align: center;
        width: auto;
        /* Centrer les contenus des cellules */
    }

    .noteContainer {
        max-width: 80px !important;
        min-width: 60px !important;
        padding: auto 0 !important;

    }

    #notesTable th {
        background-color: rgb(96, 103, 105);
        /* Couleur d'entête */
        color: white;
        width: auto;
    }

    /* Cadre des boutons */
    .button-container {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin-bottom: 10px;
    }



    /* Style pour les champs de sélection */
    .form-section select {
        width: 100%;
        padding: 8px;
        font-size: 16px;
        text-align: center;
    }

    /* Style pour les champs de texte */
    .note_input {
        width: 80% !important;
        padding: 8px;
        font-size: 16px;
        text-align: center;
    }

    .etudiant {
        width: auto !important;
    }

    .genre {

        max-width: 40px !important;
    }
</style>
<!-- inclusion du partie header -->


<body class="vertical-layout vertical-menu-modern boxicon-layout no-card-shadow 2-columns navbar-sticky footer-static"
    data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">

    <!-- inclusion du partie header -->
    <?php $this->view("Partials/navbar") ?>
    <!-- inclusion du partie header fin-->

    <!-- inclusion du partie seibar-->
    <?php $this->view("Partials/seibar") ?>
    <!-- inclusion du partie seibar fin-->

    <!-- Content-->

    <!-- Content-->
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-12 mb-2 mt-1">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h5 class="content-header-title float-left pr-1 mb-0">Science et Technique</h5>

                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb p-0 mb-0">
                                    <li class="breadcrumb-item"><a href="index.html"><i class="bx bx-home-alt"></i></a>
                                    </li>
                                    <li class="breadcrumb-item"><a
                                            href="<?php echo ROOT . '/Emploi_du_temps/' ?>">Gestion Notes</a>

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
                <section class="simple-validation">

                    <div class="row">
                        <div id="message" class="col-12"></div>
                        <div class="col-md-12">
                            <div class="card card-animated-border-top">
                                <div class="card-header">
                                    <h4 class="card-title text-center">Liste des notes</h4>
                                    <!-- <div class=" d-flex justify-content-center mt-1">
                                        <div class=" radio radio-primary mr-4">
                                            <input type="radio" name="choix" id="semestreChoise"
                                                class="type text-bold-600" value="0" checked>
                                            <label for="semestreChoise">Par Semestre</label>
                                        </div>
                                        <div class=" radio radio-primary mr-4">
                                            <input type="radio" name="choix" id="licenceChoise"
                                                class="type text-bold-600" value="0">
                                            <label for="licenceChoise">Par Licence</label>
                                        </div>
                                    </div> -->
                                </div>

                                <div class="card-content">
                                    <div class="card-body">
                                        <!-- Cadre des boutons -->
                                        <!-- Section des champs de sélection -->
                                        <div class="form-section " id="session_info">
                                            <div class="row d-flex justify-content-around">
                                                <div class="col-sm-3">
                                                    <label class="form-label">Filière</label>
                                                    <select class="select2 form-control disabled" id="filiere"
                                                        name="filiere">
                                                        <option value="0" disabled selected>Selectionner une Filière
                                                        </option>
                                                        <?php foreach ($filieres as $filiere): ?>
                                                            <option value="<?php echo $filiere->id_filiere ?>">
                                                                <?php echo strtoupper($filiere->sigle_filiere) ?>
                                                            </option>
                                                        <?php endforeach ?>
                                                    </select>
                                                </div>
                                                <div class="col-sm-3">
                                                    <label class="form-label">Classe</label>
                                                    <select class="select2 form-control disabled" id="promotions"
                                                        name="promotions" onchange="loadEtudiants()">
                                                        <option value="" disabled selected>Selectionner une
                                                            Classe
                                                        </option>
                                                    </select>
                                                </div>

                                                <div class="col-sm-3" id="semestreContainer">
                                                    <label for="semestres" class="form-label ">
                                                        Semestre</label>
                                                    <select id="semestres" class="select2 form-control">
                                                        <option value="">Tous les Semestre</option>

                                                    </select>
                                                </div>

                                                <div class="col-sm-3 d-none" id="licenceContainer">
                                                    <label for="licences" class="form-label ">
                                                        Licences</label>
                                                    <select id="licences" class="select2 form-control">
                                                        <option value="">Toutes les Licences</option>
                                                        <option value="L1">L1</option>
                                                        <option value="L2">L2</option>
                                                        <option value="L3">L3</option>
                                                    </select>
                                                </div>

                                                <div class="col-sm-3" id="ueContainer">
                                                    <label class="form-label">UEs</label>
                                                    <select class="select2 form-control disabled" id="ues">
                                                        <option value="" selected>Tous les Ues
                                                        </option>
                                                    </select>
                                                </div>

                                                <div class="col-sm-3 mt-2" id="moduleContainer">
                                                    <label class="form-label">Modules</label>
                                                    <select class="select2 form-control disabled" id="modules">
                                                        <option value="" selected>Tous les Modules
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Cadre du tableau -->
                                        <div class="table-container " id="table_section">
                                            <h6 class=" text-center text-bold-600 text-warning ">
                                                Selectionner l'ue et les notes vont apparaître &#x1F603
                                            </h6>
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
<script src="<?= ROOT ?>/assets/mon_js/liste_note.js"></script>

<script>
    $('#semestreChoise').change(function() {
        $('#semestreContainer').removeClass("d-none");
        $('#ueContainer').removeClass("d-none");
        $('#moduleContainer').removeClass("d-none");
        $('#licenceContainer').addClass("d-none");
        $("#table_section").html(
            "<h6 class='text-center text-bold-600 text-warning'>" +
            "Selectionner l'ue  et les notes vont apparaître &#x1F603</h6>"
        );

    })
    $('#licenceChoise').change(function() {
        $('#semestreContainer').addClass("d-none");
        $('#ueContainer').addClass("d-none");
        $('#moduleContainer').addClass("d-none");
        $('#licenceContainer').removeClass("d-none");
        $("#table_section").html(
            "<h6 class='text-center text-bold-600 text-warning'>" +
            "Selectionner une licence et les notes vont apparaître &#x1F603</h6>"
        );

    })


    var infoFiliere = [];
    $("#filiere").change(async function() {
        infoFiliere = await infosFiliere($(this).val(), "all");
        promotionsFiliere(infoFiliere);
        semestresPromotion(infoFiliere, $("#promotions option:selected").data("semestre"));
        licencesPromotion($("#promotions option:selected").data("semestre"));
        idSemestre = $("#semestres option:selected").data("id");
        ueSemestre(idSemestre, infoFiliere);
        $('#ues').val("");
        moduleUe($("#ues option:selected").data("id"), infoFiliere)
        loadEtudiants(ROOT + "/get_moyenne_licence_etudiant")

        $("#table_section").html(
            "<h6 class='text-center text-bold-600 text-warning'>" +
            "Selectionner l'ue et les notes vont apparaître &#x1F603</h6>"
        );

    })

    $("#promotions").change(function() {
        semestresPromotion(infoFiliere, $("#promotions option:selected").data("semestre"));
        idSemestre = $("#semestres option:selected").data("id");
        ueSemestre(idSemestre, infoFiliere);
        moduleUe($("#ues option:selected").data("id"), infoFiliere)
        loadEtudiants(ROOT + "/get_moyenne_licence_etudiant")
        $("#table_section").html(
            "<h6 class='text-center text-bold-600 text-warning'>" +
            "Selectionner l'ue  et les notes vont apparaître &#x1F603</h6>"
        );

    })

    $("#semestres").change(function() {
        idSemestre = $("#semestres option:selected").data("id");
        ueSemestre(idSemestre, infoFiliere);
        moduleUe($("#ues option:selected").data("id"), infoFiliere)
        loadEtudiants();
        if ($("#semestres option:selected").val() == "") {
            loadEtudiants(ROOT + "/get_moyenne_licence_etudiant")
        }

    })

    $("#ues").change(function() {
        moduleUe($("#ues option:selected").data("id"), infoFiliere)
        loadEtudiants();

    })

    $("#modules").change(function() {
        loadEtudiants();

    })
</script>