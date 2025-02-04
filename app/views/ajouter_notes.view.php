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
    max-width: 80px;
    width: 80px;
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
                                        <div class="button-container">
                                            <button type="button" id="session_rattrapage_btn"
                                                class="session-button">Session Normale</button>
                                            <button type="button" id="session_normale_btn"
                                                class="session-button">Session De Rattrappage</button>
                                        </div>

                                        <!-- Section des champs de sélection -->
                                        <div class="form-section hidden" id="session_info">
                                            <div class="row">
                                                <div class="col-sm-4">
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
                                                <div class="col-sm-4">
                                                    <label class="form-label">Promotion</label>
                                                    <select class="select2 form-control disabled" id="promotions"
                                                        name="promotions" onchange="loadEtudiants()">
                                                        <option value="" disabled selected>Selectionner une Promotion
                                                        </option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-4">
                                                    <label class="form-label">Modules</label>
                                                    <select class="select2 form-control disabled" id="modules">
                                                        <option value="" disabled selected>Selectionner un Module
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Cadre du tableau -->
                                        <div class="table-container hidden" id="table_section">
                                            <!-- Affichge dynamique avec ajax -->
                                            <table class="table table-striped table-bordered zero-configuration"
                                                id="notesTable">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center">Numero Matricule</th>
                                                        <th class="text-center">Nom && Prénom</th>
                                                        <th class="text-center">Genre</th>
                                                        <th class="text-center note_devoir">Note devoir</th>
                                                        <th class="text-center note_evaluation">Note Évaluation</th>
                                                        <th class="text-center note_session hidden">Note Session</th>
                                                        <th class="text-center ">Moyenne Module</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    <!-- Afichage dynamique avec ajax -->

                                                </tbody>
                                                <caption id="alerte">
                                                    <h6 class=" text-center text-bold-600 text-warning ">Aucune note
                                                        disponible pour cette promotion dans ce module !</h6>
                                                </caption>
                                            </table>
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
<script src="<?= ROOT ?>/assets/mon_js/edt.js"></script>
<script src="<?= ROOT ?>/assets/mon_js/note.js"></script>

<script>
var infoFiliere = [];
$("#filiere").change(async function() {

    infoFiliere = await infosFiliere($(this).val(), "all");
    promotionsFiliere(infoFiliere);
    idSemestre = $("#promotions option:selected").data("id");
    modulesSemestre(idSemestre, infoFiliere);
    //infoModule($("#infoModule").val(), infoFiliere);


})

$("#promotions").change(function() {
    idSemestre = $("#promotions option:selected").data("id");
    modulesSemestre(idSemestre, infoFiliere);
    //infoModule($("#infoModule").val(), infoFiliere);
})


$("#modules").change(function() {
    //infoModule($(this).val(), infoFiliere);
    //Appel de la fonction ajax quand le module est sélectionné
    if ($(this).val() != "" && $(this).val() != null) {
        loadEtudiants(); //Foncton pour chargér les étudiant et leur note lorsque le module est selectionnée
    }
})


// }) //KONE

const sessionInfo = document.getElementById("session_info");
const tableSection = document.getElementById("table_section");
const submitBtn = document.getElementById("submit_btn");
const noteSessionFields = document.querySelectorAll(".note_session");

document.addEventListener("DOMContentLoaded", function() {
    const sessionNormaleBtn = document.getElementById(
        "session_normale_btn"
    );
    const sessionRattrapageBtn = document.getElementById(
        "session_rattrapage_btn"
    );


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
</script>