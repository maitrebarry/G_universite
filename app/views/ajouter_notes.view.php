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

    .session-button {
        padding: 10px 20px;
        font-size: 16px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .session-button:active {
        background-color: #0056b3;
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

    <body
        class="vertical-layout vertical-menu-modern boxicon-layout no-card-shadow 2-columns navbar-sticky footer-static"
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
                                        <li class="breadcrumb-item"><a href="index.html"><i
                                                    class="bx bx-home-alt"></i></a>
                                        </li>
                                        <li class="breadcrumb-item"><a
                                                href="<?php echo ROOT . '/Emploi_du_temps/' ?>">Gestion Notes</a>

                                        </li>
                                        <li class="breadcrumb-item active">Engistrements

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
                                        <h4 class="card-title text-center">Saisie des notes</h4>
                                    </div>

                                    <div class="card-content">
                                        <div class="card-body">
                                            <!-- Cadre des boutons -->
                                            <!-- Section des champs de sélection -->
                                            <div class="form-section " id="session_info">
                                                <div class="row">
                                                    <div class="col-sm-3">
                                                        <label class="form-label">Année universitaire</label>
                                                        <select class="form-control disabled select2"
                                                            id="anneeUniversitaire" name="anneeUniversitaire">

                                                        </select>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <label class="form-label">Classe</label>
                                                        <select class="select2 form-control disabled" id="promotions"
                                                            name="promotions">
                                                            <option value="" disabled selected>Selectionner une Classe
                                                            </option>
                                                        </select>
                                                    </div>

                                                    <div class="col-sm-6">
                                                        <label class="form-label">Modules</label>
                                                        <select class="select2 form-control disabled" id="modules">
                                                            <option value="" disabled selected>Selectionner un Module
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Cadre du tableau -->
                                            <div class="table-container " id="table_section">
                                                <h6 class=" text-center text-bold-600 text-warning ">
                                                    Selectionner le module et les notes vont apparaître &#x1F603
                                                </h6>

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
    </body>
    <!-- END: Body-->

    </html>
    <script src="<?= ROOT ?>/assets/mon_js/note.js"></script>

    <script>
        var infoFiliere = [];
        $(document).ready(async function() {
            let anneeSaved = sessionStorage.getItem('annee');

            const debut = 2012;
            const today = new Date();
            let annee_actuelle = today.getFullYear();
            let mois = today.getMonth() + 1; // janvier = 0, donc on ajoute 1

            // Si on est avant septembre (mois 9), l'année universitaire commence l'année précédente
            if (mois <= 8) {
                annee_actuelle -= 1;
            }

            const annee_en_cours = annee_actuelle + '-' + (annee_actuelle + 1);

            for (let annee = debut; annee <= annee_actuelle; annee++) {
                let annee_suivante = annee + 1;
                let valeur = annee + '-' + annee_suivante;
                let selected = (valeur === anneeSaved) ? 'selected' : '';
                $('#anneeUniversitaire').append(`<option value="${valeur}" ${selected}>${valeur}</option>`);
            }

            await classesAnneeUniversitaire(anneeSaved);
            infoFiliere = JSON.parse(sessionStorage.getItem('infoFiliere'));
            idSemestre = sessionStorage.getItem('semestre');
            modulesSemestre(idSemestre, infoFiliere);
            if ($("#modules option:selected").val() != "" && $("#modules option:selected").val() != null) {
                loadEtudiants(true);

            }

        })

        $("#anneeUniversitaire").change(async function() {

            classesAnneeUniversitaire($("#anneeUniversitaire option:selected").val());

            infoFiliere = await infosFiliere($("#promotions option:selected").data("filiere"), "all");
            idSemestre = $("#promotions option:selected").data("semestre");
            modulesSemestre(idSemestre, infoFiliere);
            $("#table_section").html(
                "<h6 class='text-center text-bold-600 text-warning'>" +
                "Veuillez selectionner un module &#x1F603</h6>"
            );
            sessionStorage.setItem("annee", $("#anneeUniversitaire option:selected").val());
            sessionStorage.setItem("module", $("#modules option:selected").val());



        })

        $("#promotions").change(async function() {

            sessionStorage.setItem("classe", $("#promotions option:selected").val());
            sessionStorage.setItem("semestre", $("#promotions option:selected").data('semestre'));
            sessionStorage.setItem("filiere", $("#promotions option:selected").data('filiere'));


            infoFiliere = await infosFiliere($("#promotions option:selected").data("filiere"), "all");
            idSemestre = $("#promotions option:selected").data("semestre");
            sessionStorage.setItem("infoFiliere", JSON.stringify(infoFiliere));
            modulesSemestre(idSemestre, infoFiliere);
            $("#table_section").html(
                "<h6 class='text-center text-bold-600 text-warning'>" +
                "Veuillez selectionner un module &#x1F603</h6>"
            );

            sessionStorage.setItem("module", $("#modules option:selected").val());

        })

        $("#modules").change(function() {

            if ($("#modules option:selected").val() != "" && $("#modules option:selected").val() != null) {
                loadEtudiants(); //Foncton pour chargér les étudiant et leur note lorsque le module est selectionnée
            } else {
                $("#table_section").html(
                    "<h6 class='text-center text-bold-600 text-warning'>" +
                    "Veuillez selectionner un module &#x1F603</h6>"
                );
            }

            sessionStorage.setItem("module", $("#modules option:selected").val());
        })


        // }) //KONE
    </script>