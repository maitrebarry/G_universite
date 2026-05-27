<?php
$this->view("Partials/header") ?>
<style>
    /* @media print {
        @page {
            size: A4 landscape;
        }
    } */

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

    /* Style général des entêtes */
    #notesTable th {
        background-color: #dad7cd;
        /* dégradé élégant */
        color: #000;
        /* texte blanc */
        font-weight: 600;
        /* texte plus gras */
        text-align: center;
        /* centré */
        border: 1px solid #000;
        /* fine bordure */
        padding: 1px;
        font-size: 12px;
    }

    /* Entêtes verticaux */
    #notesTable th.vertical-header {
        height: 150px;
        /* hauteur adaptée */
        width: 30px;
        /* largeur contrôlée */
        padding: 0;
    }

    @media print {
        .vertical-header>div {
            overflow: visible !important;
            max-width: none !important;
        }
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
                                <div class="card-header d-flex justify-content-around align-items-center">
                                    <h4 class="card-title text-center">Liste des notes</h4>
                                    <div class="d-none aligns-items-center justify-content-arround" id="printSection">
                                        <!-- Bouton Impression -->
                                        <button id="print" class="btn d-flex align-items-center mr-1" style="
                                            background: linear-gradient(135deg, #0d6efd, #0a58ca);
                                            color: white;
                                            font-weight: 600;
                                            border: none;
                                            border-radius: 50px;
                                            padding: 12px 24px;
                                            box-shadow: 0px 4px 12px rgba(0,0,0,0.25);
                                            transition: all 0.3s ease;
                                        " onmouseover="this.style.background='linear-gradient(135deg, #0a58ca, #0d6efd)'"
                                            onmouseout="this.style.background='linear-gradient(135deg, #0d6efd, #0a58ca)'">
                                            <i class="bi bi-printer-fill"
                                                style="font-size: 1.4rem; margin-right: 10px;"></i>
                                            Imprimer
                                        </button>

                                        <!-- Bouton Impression -->
                                        <button id="printReleve" class="btn d-flex align-items-center" style="
                                            background: linear-gradient(135deg, #0d6efd, #0a58ca);
                                            color: white;
                                            font-weight: 600;
                                            border: none;
                                            border-radius: 50px;
                                            padding: 12px 24px;
                                            box-shadow: 0px 4px 12px rgba(0,0,0,0.25);
                                            transition: all 0.3s ease;
                                        " onmouseover="this.style.background='linear-gradient(135deg, #0a58ca, #0d6efd)'"
                                            onmouseout="this.style.background='linear-gradient(135deg, #0d6efd, #0a58ca)'">
                                            <i class="bi bi-printer-fill"
                                                style="font-size: 1.4rem; margin-right: 10px;"></i>
                                            Relevés de Note
                                        </button>

                                        <!-- Bootstrap Icons -->
                                        <link rel="stylesheet"
                                            href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">


                                    </div>
                                </div>

                                <div class="card-content">
                                    <div class="card-body">
                                        <!-- Cadre des boutons -->
                                        <!-- Section des champs de sélection -->
                                        <div class="form-section " id="session_info">
                                            <div class="row d-flex justify-content-around">
                                                <div class="col-sm-2">
                                                    <label class="form-label">Année Universitaire</label>
                                                    <select class="select2 form-control disabled"
                                                        id="anneeUniversitaire" name="anneeUniversitaire">
                                                        <?php
                                                        $annee_debut = 2012;
                                                        $annee_actuelle = date('Y');
                                                        $mois_actuel = date('n');

                                                        // Si on est avant septembre, l'année universitaire en cours commence l'année précédente
                                                        if ($mois_actuel <= 8) {
                                                            $annee_actuelle--;
                                                        }

                                                        $annee_universitaire_courante = $annee_actuelle . '-' . ($annee_actuelle + 1);

                                                        for ($annee = $annee_debut; $annee <= $annee_actuelle; $annee++) {
                                                            $annee_suivante = $annee + 1;
                                                            $valeur = $annee . '-' . $annee_suivante;

                                                            // Si cette valeur correspond à l'année universitaire en cours, on ajoute "selected"
                                                            $selected = ($valeur == $annee_universitaire_courante) ? 'selected' : '';
                                                            echo "<option value=\"$valeur\" $selected>$valeur</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-sm-3">
                                                    <label class="form-label">Classe</label>
                                                    <select class="select2 form-control disabled" id="promotions"
                                                        name="promotions">
                                                        <option value="" disabled selected>Selectionner une
                                                            Classe
                                                        </option>
                                                    </select>
                                                </div>

                                                <div class="col-sm-3" id="ueContainer">
                                                    <label class="form-label">UEs</label>
                                                    <select class="select2 form-control disabled" id="ues">
                                                        <option value="" selected>Tous les Ues
                                                        </option>
                                                    </select>
                                                </div>

                                                <div class="col-sm-4 " id="moduleContainer">
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
    var infoFiliere = [];

    $(document).ready(function() {
        classesAnneeUniversitaire($("#anneeUniversitaire option:selected").val());

        $("#table_section").html(
            "<h6 class='text-center text-bold-600 text-warning'>" +
            "Selectionner l'ue et les notes vont apparaître &#x1F603</h6>"
        );

    })

    $("#anneeUniversitaire").change(async function() {

        classesAnneeUniversitaire($("#anneeUniversitaire option:selected").val());

        idSemestre = $("#promotions option:selected").data("semestre");
        ueSemestre(idSemestre, infoFiliere);
        $('#ues').val("");
        moduleUe($("#ues option:selected").data("id"), infoFiliere)

        $("#printSection").removeClass("d-flex");
        $("#printSection").addClass("d-none");
        $("#table_section").html(
            "<h6 class='text-center text-bold-600 text-warning'>" +
            "Selectionner l'ue et les notes vont apparaître &#x1F603</h6>"
        );

    })

    $("#promotions").change(async function() {
        infoFiliere = await infosFiliere($("#promotions option:selected").data("filiere"), "all");
        idSemestre = $("#promotions option:selected").data("semestre");
        ueSemestre(idSemestre, infoFiliere);
        moduleUe($("#ues option:selected").data("id"), infoFiliere)
        $("#printSection").removeClass("d-none");
        $("#printSection").addClass("d-flex");
        // if (isNaN($("#promotions option:selected").val())) {
        //     alert('hihihihihihi');


        // } else {
        //     alert('hahahahah');
        // }

        if (!$("#promotions option:selected").text().includes("S")) {
            loadEtudiants(window.APP_ROUTE("Notes/get_moyenne_licence_etudiant"));
            $("#ues").empty();
            $("#ues").append(
                `<option value="" >Selectionner ue</option>`
            );


        } else
            loadEtudiants();

    })

    $("#ues").change(function() {
        moduleUe($("#ues option:selected").data("id"), infoFiliere)
        loadEtudiants();
        $("#printSection").removeClass("d-none");
        $("#printSection").addClass("d-block");


        //sessionStorage.setItem("ue", $("#ues option:selected").data("id"));

    })

    $("#modules").change(function() {
        loadEtudiants();
        $("#printSection").removeClass("d-none");
        $("#printSection").addClass("d-block");
    })

    // l'action pour imprimer
    $("#print").click(function() {
        nom = "RES " + $("#promotions option:selected").text() + " PROV";
        html = document.getElementById("table_section").innerHTML;
        imprimer(nom, html); // Appel de la fonction
    });
    $("#printReleve").click(function() {

        imprimerReleve(); // Appel de la fonction
    });
</script>
