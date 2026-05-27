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
        max-width: 900px;
        /* Largeur maximale uniforme */
        margin: 0 auto;
        /* Centrer les éléments */
        padding: 10px;
        border: 2px solid #007bff;
        border-radius: 10px;
        margin-bottom: 20px;
    }

    /* Tableau */
    #notesTable {
        width: 100%;
        border-collapse: collapse;
    }

    #notesTable th,
    #notesTable td {
        padding: 10px;
        text-align: center;
        /* Centrer les contenus des cellules */
    }

    #notesTable th {
        background-color: rgb(96, 103, 105);
        /* Couleur d'entête */
        color: white;
    }

    /* Cadre des boutons */
    .button-container {
        display: flex;
        justify-content: center;
        gap: 20px;
        margin-bottom: 20px;
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
        width: 100%;
        padding: 8px;
        font-size: 16px;
        text-align: center;
    }

    /* Style pour les champs de texte dans le tableau */
    .hidden {
        display: none;
    }
</style>

<!-- Inclure DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
</head>

<body class="vertical-layout vertical-menu-modern boxicon-layout no-card-shadow 2-columns navbar-sticky footer-static"
    data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">



    <?php $this->view("Partials/header") ?>

    <div class="app-content content">
        <div class="content-wrapper">

            <div class="content-body">
                <section class="simple-validation">
                    <div class="content-header row">
                        <!-- Texte défilant -->
                        <div class="scrolling-text">
                            <span>Bienvenue dans l'IUFP de Ségou</span>
                        </div>
                        <div class="content-header-left col-12 mb-2 mt-1">
                            <h5 class="content-header-title float-left pr-1 mb-0">Gestion des notes</h5>
                        </div>
                    </div>
                    <div class="row">
                        <div id="message" class="col-12"></div>
                        <div class="col-md-12">
                            <div class="card card-animated-border-top">
                                <div class="card-header">
                                    <h4 class="card-title text-center">Saisie des notes</h4>
                                </div>
                                <div class="card-content">
                                    <div class="card-body">
                                        <form method="POST" class="form-horizontal" novalidate id="edtForm">

                                            <!-- Cadre des boutons -->
                                            <div class="button-container">
                                                <button type="button" id="session_normale_btn" class="session-button">Session Normale</button>
                                                <button type="button" id="session_rattrapage_btn" class="session-button">Session de Rattrapage</button>
                                            </div>

                                            <!-- Section des champs de sélection -->
                                            <div class="form-section hidden" id="session_info">
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <label class="form-label">Filière</label>
                                                        <select class="select2 form-control disabled" id="filiere">
                                                            <option value="0" disabled selected>Selectionner une Filière</option>
                                                            <?php foreach ($filieres as $filiere): ?>
                                                                <option value="<?php echo $filiere->id_filiere ?>">
                                                                    <?php echo strtoupper($filiere->sigle_filiere) ?>
                                                                </option>
                                                            <?php endforeach ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <label class="form-label">Promotion</label>
                                                        <select class="select2 form-control disabled" id="promotions">
                                                            <option value="" disabled selected>Selectionner une Promotion</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <label class="form-label">Modules</label>
                                                        <select class="select2 form-control disabled" id="modules">
                                                            <option value="" disabled selected>Selectionner un Module</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Cadre du tableau -->
                                            <div class="table-container hidden" id="table_section">
                                                <table class="table table-striped table-bordered" id="notesTable">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center">Numero Matricule</th>
                                                            <th class="text-center">Nom && Prénom</th>
                                                            <th class="text-center">Prénom</th>
                                                            <th class="text-center note_devoir">Note devoir</th>
                                                            <th class="text-center note_evaluation">Note Évaluation</th>
                                                            <th class="text-center note_session hidden">Note Session</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <!-- AFFICHAGE DES DONNEES DYNAMIQUEMENT PAR AJAX DEPUIS post_liste_note.view -->
                                                    </tbody>
                                                </table>
                                            </div>

                                            <button type="submit" style="float: right;" class="btn btn-primary hidden" id="submit_btn">Enregistrer</button><br>
                                        </form>
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
    <script src="<?= ROOT ?>/assets/mon_js/edt.js"></script>

    <script>
        var infoFiliere = [];
        $("#filiere").change(async function() {

            infoFiliere = await infosFiliere($(this).val());
            promotionsFiliere(infoFiliere);
            idSemestre = $("#promotions option:selected").data("id");
            modulesSemestre(idSemestre, infoFiliere);
            infoModule($("#infoModule").val(), infoFiliere);


        })

        $("#promotions").change(function() {
            idSemestre = $("#promotions option:selected").data("id");
            modulesSemestre(idSemestre, infoFiliere);
            infoModule($("#infoModule").val(), infoFiliere);
        })


        $("#modules").change(function() {
            infoModule($(this).val(), infoFiliere);
        })
        //KONE
        $(document).ready(async function() {
            infoFiliere = await infosFiliere($("#filiere").val());
            promotionsFiliere(infoFiliere);
            idSemestre = $("#promotions option:selected").data("id");
            modulesSemestre(idSemestre, infoFiliere);

        }) //KONE




        document.addEventListener("DOMContentLoaded", function() {
            const sessionNormaleBtn = document.getElementById("session_normale_btn");
            const sessionRattrapageBtn = document.getElementById("session_rattrapage_btn");
            const sessionInfo = document.getElementById("session_info");
            const tableSection = document.getElementById("table_section");
            const submitBtn = document.getElementById("submit_btn");
            const noteSessionFields = document.querySelectorAll(".note_session");
            const filiere = document.getElementById("filiere");
            const promotions = document.getElementById("promotions");
            const modules = document.getElementById("modules");
            const formWrapper = document.querySelector(".form-wrapper");


            $(document).ready(function() {
                $('#notesTable').DataTable({
                    "paging": true, // Pagination activée
                    "searching": true, // Recherche activée
                    "info": true, // Information de pagination
                    "language": {
                        "url": "https://cdn.datatables.net/plug-ins/1.13.6/i18n/French.json" // Traduction en français
                    }
                });
            });


            function toggleSessionFields(show, hideNotes) {
                sessionInfo.classList.toggle("hidden", !show);
                tableSection.classList.toggle("hidden", !show);
                submitBtn.classList.toggle("hidden", !show);
                filiere.classList.toggle("disabled", !show);
                promotions.classList.toggle("disabled", !show);
                modules.classList.toggle("disabled", !show);
                noteSessionFields.forEach(field => {
                    field.classList.toggle("hidden", hideNotes);
                });
            }

            sessionNormaleBtn.addEventListener("click", function() {
                sessionNormaleBtn.classList.add("active");
                sessionRattrapageBtn.classList.remove("active");
                toggleSessionFields(true, false);
            });

            sessionRattrapageBtn.addEventListener("click", function() {
                sessionRattrapageBtn.classList.add("active");
                sessionNormaleBtn.classList.remove("active");
                toggleSessionFields(true, true);
            });

            // Rendre le formulaire initial visible et masqué correctement
            toggleSessionFields(false, false);
        });


        /////////////////////////////////////////////////////////////////////////////////////////////////
        $(document).ready(function() {
            $("#promotions").change(function() {
                const ROOT = window.APP_ROUTE("Notes");
                var promotionId = $(this).val();

                if (promotionId) {
                    $.ajax({
                        url: ROOT + "/post_ajouter_notes",
                        type: "POST",
                        data: {
                            promotionId: promotionId,
                            filiereId: $("#filiere").val(),
                            moduleId: $("#modules").val()
                        },
                        success: function(response) {
                            var tableBody = $("#notesTable tbody");
                            tableBody.empty(); // Vider le tableau avant d'ajouter les nouvelles données

                            if (response.length > 0) {
                                $.each(response, function(index, student) {
                                    var row = `<tr>
                                <td>${student.matricule}</td>
                                <td>${student.nom}</td>
                                <td>${student.prenom}</td>
                                <td class="note_devoir"><input type="text" class="note_input"></td>
                                <td class="note_evaluation"><input type="text" class="note_input"></td>
                                <td class="note_session hidden"><input type="text" class="note_input"></td>
                            </tr>`;
                                    tableBody.append(row);
                                });
                            } else {
                                tableBody.append("<tr><td colspan='6' class='text-center'>Aucun étudiant trouvé</td></tr>");
                            }
                        },
                        error: function(xhr, status, error) {
                            console.log("Erreur lors de l'appel AJAX : " + error);
                        }
                    });
                }
            });
        });



        $(document).ready(function() {
            $("#promotions").change(function() {
                const ROOT = window.APP_ROUTE("Notes");
                var promotionId = $(this).val(); // Récupérer l'ID de la promotion sélectionnée

                if (promotionId) {
                    $.ajax({
                        url: ROOT + "/post_ajouter_notes", // Script PHP qui renvoie la liste des étudiants
                        type: "POST",
                        data: {
                            promotion_id: promotionId
                        }, // Envoyer l'ID de la promotion
                        dataType: "json",
                        success: function(response) {
                            var tableBody = $("#notesTable tbody");
                            tableBody.empty(); // Vider le tableau avant d'ajouter les nouvelles données

                            if (response.length > 0) {
                                $.each(response, function(index, student) {
                                    var row = `<tr>
                                <td>${student.matricule}</td>
                                <td>${student.nom}</td>
                                <td>${student.prenom}</td>
                                <td class="note_devoir"><input type="text" class="note_input"></td>
                                <td class="note_evaluation"><input type="text" class="note_input"></td>
                                <td class="note_session hidden"><input type="text" class="note_input"></td>
                            </tr>`;
                                    tableBody.append(row);
                                });
                            } else {
                                tableBody.append("<tr><td colspan='6' class='text-center'>Aucun étudiant trouvé</td></tr>");
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error("Erreur AJAX :", error);
                        }
                    });
                }
            });
        });
    </script>
</body>
