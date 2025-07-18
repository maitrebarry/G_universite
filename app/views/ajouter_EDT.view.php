<style>
.toast {
    min-width: 300px;
    border-radius: 12px;
    font-family: 'Segoe UI', sans-serif;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
    animation: slideInRight 0.5s ease, fadeOut 0.5s ease 4.5s;
}

@keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }

    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes fadeOut {
    to {
        opacity: 0;
        transform: translateX(100%);
    }
}

.toast .toast-body i {
    margin-right: 10px;
}

body {
    font-size: 14px !important;
}

input {

    padding: 8px;
    font-size: 16px;
    text-align: center;
}

td {
    padding: 8px 5px !important;
}

.custom-checkbox {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 0.8rem;
    cursor: pointer;
    font-weight: bold;
}

.custom-checkbox input[type="checkbox"] {
    display: none;
}

.checkmark {
    width: 20px;
    height: 20px;
    border-radius: 6px;
    background-color: #e0e0e0;
    position: relative;
    transition: background-color 0.3s;
    border: 2px solid #aaa;
}

.custom-checkbox input:checked+.checkmark {
    background-color: rgb(0, 36, 241);
    border-color: rgb(47, 0, 255);
}

.checkmark::after {
    content: "";
    position: absolute;
    left: 4px;
    top: 2px;
    width: 8px;
    height: 12px;
    border: solid white;
    border-width: 0 4px 4px 0;
    transform: rotate(45deg);
    opacity: 0;
    transition: opacity 0.2s ease-in-out;
}

.custom-checkbox input:checked+.checkmark::after {
    opacity: 1;
}
</style>
<!-- inclusion du partie header -->
<?php $this->view("Partials/header") ?>

<body class="vertical-layout vertical-menu-modern boxicon-layout no-card-shadow 2-columns navbar-sticky footer-static"
    data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">

    <!-- inclusion du partie header -->
    <?php $this->view("Partials/navbar") ?>
    <!-- inclusion du partie header fin-->

    <!-- inclusion du partie seibar-->
    <?php $this->view("Partials/seibar") ?>
    <!-- inclusion du partie seibar fin-->

    <!-- Zone d’alerte toast -->
    <div id="notificationZone" class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;"></div>

    <!-- Content-->
    <div class="app-content content">
        <div id="loader" class="w-100 position-absolute d-none justify-content-center align-items-center"
            style="height:100vh;z-index:100">

            <div class="spinner-border  " role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>

        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-12 mb-2 mt-1">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h5 class="content-header-title float-left pr-1 mb-0">
                                <?php
                                echo (isset($_SESSION['nom_departement']))
                                    ? strtoupper($_SESSION['nom_departement'] . ' (' . $_SESSION['sigle_departement'] . ')')
                                    : "IUFP"
                                ?>
                            </h5>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb p-0 mb-0">
                                    <li class="breadcrumb-item"><a href="index.html"><i class="bx bx-home-alt"></i></a>
                                    </li>
                                    <li class="breadcrumb-item"><a
                                            href="<?php echo ROOT . '/Emploi_du_temps/' ?>">Gestion EDT</a>
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
                        <div id="message" class="col-12 d-flex justify-content-start"></div>
                        <div class="col-md-12">
                            <div class="card card-animated-border-top">
                                <div class="card-header">
                                    <h4 class="card-title text-center">Création d'emploi du temps</h4>
                                </div>
                                <div class="card-content">
                                    <div class="card-body">
                                        <form method="POST" class="form-horizontal" novalidate id="edtForm">
                                            <div class="row d-flex justify-content-around align-items-center">
                                                <div class="col-6 col-lg-3">
                                                    <label class="form-label" for="single-select">Année
                                                        universitaire</label>
                                                    <div class="form-group">
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
                                                </div>
                                                <div class="col-6 col-lg-3">
                                                    <label class="form-label" for="single-select">Classe</label>
                                                    <div class="form-group">
                                                        <select class="select2 form-control" id="promotions"
                                                            data-id="<?php echo $idPromotion ?>">
                                                            <option value="" disabled>Selectioner une Classe
                                                            </option>

                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-6 col-lg-3">
                                                    <label class="form-label" for="single-select">Modules</label>
                                                    <div class="form-group">
                                                        <select class="select2 form-control champ" id="modules"
                                                            name="modules">
                                                            <option value="" disabled selected>Selectioner un Module
                                                            </option>

                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-6 col-lg-3">
                                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                                        data-target="#menuConfig"><i class="bx bxs-cog"></i>
                                                        Modèle Edt</button>
                                                </div>
                                            </div>

                                            <div
                                                class="row d-flex justify-content-between align-items-center mb-1 w-100 m-auto">
                                                <div
                                                    class="col-12 col-md-4 row d-flex justify-content-between align-items-center">
                                                    <div class=" col-12 m-0 d-flex align-items-end mt-2">
                                                        <!-- Bouton pour ajouter une nouvelle ligne -->
                                                        <i class="bx bx-plus btn btn-secondary mr-1" id="add-row"></i>
                                                        <!-- Bouton pour supprimer la dernière ligne -->
                                                        <i class="bx bx-minus btn btn-danger" id="remove-row"></i>
                                                    </div>

                                                </div>
                                                <div class=" col-12 col-md-8 row d-none " id="infoModule">
                                                    <input type="hidden" id="vht" class="vht">
                                                    <!-- Boutton de Commande -->
                                                    <!-- Boutons de commande avec icônes uniquement -->
                                                    <div class="col-4 d-flex justify-content-around align-items-end">
                                                        <!-- Bouton Reset -->
                                                        <button type="button" class="btn btn-outline-warning"
                                                            title="Réinitialiser" id="renitialiser">
                                                            <i class="bx bx-reset fs-4"></i>
                                                        </button>

                                                        <!-- Bouton Recalculer -->
                                                        <button class="btn btn-outline-primary" title="Recalculer"
                                                            id="recalculer">
                                                            <i class="fas fa-redo-alt"></i> </button>
                                                    </div>

                                                    <!-- CM -->
                                                    <div class='col-2' style="max-width:100px !important;">
                                                        <label class="d-block text-center">CM</label>
                                                        <input type='number' class='heure form-control text-center cm'>
                                                    </div>
                                                    <!-- TD -->
                                                    <div class='col-2' style="max-width:100px !important;">
                                                        <label class="d-block text-center">TD</label>
                                                        <input type='number' class='heure form-control text-center td'>
                                                    </div>
                                                    <!-- TP -->
                                                    <div class='col-2' style="max-width:100px !important;">
                                                        <label class="d-block text-center">TP</label>
                                                        <input type='number' class='heure form-control text-center tp'>
                                                    </div>
                                                    <!-- TPE -->
                                                    <div class='col-2' style="max-width: 100px !important;">
                                                        <label class="d-block text-center">TPE</label>
                                                        <input type='number' class='heure form-control text-center tpe'>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="table-responsive mt-1">
                                                <table id="table-extended-chechbox"
                                                    class="table table-striped table-bordered" style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center">Horaire</th>
                                                            <?php foreach ($jours as $jour): ?>
                                                            <th class="jour" data-id="<?php echo $jour->id_jour ?>">
                                                                <?php echo strtoupper($jour->nom_jour) ?></th>
                                                            <?php endforeach ?>

                                                        </tr>
                                                    </thead>
                                                    <tbody class="corpsEdt" id="corpsEdt">

                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="row d-flex justify-content-between">
                                                <div class="col-12 col-sm-4">
                                                    <label class="form-label" for="enseignants">ENSEIGNANT :</label>
                                                    <div class="form-group">
                                                        <select class=" form-control champ" id="enseignants"
                                                            name="enseignants">
                                                            <option value="" disabled>
                                                                Sélectionner un
                                                                enseignant</option>
                                                            <?php foreach ($enseignants as $enseignant): ?>
                                                            <option value="<?php echo $enseignant->enseignant_id ?>"
                                                                class=" text-capitalize"
                                                                data-enseignant="<?php echo $enseignant->enseignant_nom . " " . $enseignant->enseignant_prenom ?>"
                                                                data-id="<?php echo $enseignant->enseignant_id ?>">
                                                                <?php echo
                                                                    $enseignant->enseignant_nom . " "
                                                                        . $enseignant->enseignant_prenom

                                                                    ?>
                                                            </option>
                                                            <?php endforeach ?>
                                                            <!-- Ajoutez ici les options des enseignants -->
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-4">
                                                    <label class="form-label" for="single-select">SALE DE COURS</label>
                                                    <div class="form-group">
                                                        <select class="form-control champ" name="salles" id="salles">
                                                            <option value="" disabled selected>Selectionner une Salle
                                                            </option>
                                                            <?php foreach ($salles as $salle): ?>
                                                            <option value="<?php echo $salle->id_salle ?>">
                                                                <?php echo strtoupper($salle->nom_salle) . "(" . $salle->capacite_salle . ")" ?>
                                                            </option>
                                                            <?php endforeach ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-4 ">
                                                    <label class="form-label" for="dateDebut champ">Date de Debut
                                                        :</label>
                                                    <div class="form-group w-100 d-flex justify-content-end ">
                                                        <input type="date" class="form-control" name="dateDebut"
                                                            id="dateDebut" value="<?php echo date("d/m/Y") ?>">
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="col d-flex justify-content-center align-items-center mt-2">
                                                <label class="custom-checkbox">
                                                    <input type="checkbox" id="groupeSelect">
                                                    <span class="checkmark"></span>
                                                    Partager en Groupe
                                                </label>
                                            </div>

                                            <div class="table-responsive m-auto" id="listEnseignant"
                                                style="width: 900px;">
                                                <div class=" col-12 m-0  d-flex justify-content-end mb-1">
                                                    <!-- Bouton pour supprimer la dernière ligne -->
                                                    <i class="bx bx-minus btn btn-danger d-flex justify-content-center align-items-center"
                                                        id="removeEnseignant"
                                                        style="width: 20px !important; height:20px;"></i>
                                                </div>
                                                <table id="" class="table table-striped table-bordered m-auto ">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center">Num</th>
                                                            <th class="text-center">Enseignant</th>
                                                            <th class="text-center">Groupe</th>
                                                            <th>Type de Cours</th>
                                                            <th>Nombre d'heure</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="corpsEnseignant">

                                                    </tbody>
                                                </table>
                                            </div>

                                            <button type="submit" style="float: right;" class="btn btn-primary mt-1"
                                                id="valider">Enregistrer</button><br>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- formulaire -->
            </div>
        </div>

        <div class="modal-primary mr-1 mb-1 d-inline-block ">
            <div class=" modal fade text-left" id="menuConfig" tabindex="-1" role="dialog"
                aria-labelledby="myModalLabel160" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class=" row">
                                <div class=" col-12 row mb-1">
                                    <h6 class=" col-12 text-center">Models Edt</h6>
                                    <div class="col-12 border p-2 d-flex justify-content-center ">

                                        <div style="width: 300px" class="cursor-pointer ">
                                            <span class=" text-center">Horizontal</span>
                                            <img class="img img-thumbnail d-block border border-primary"
                                                src="<?= ROOT ?>/assets/images/model-row.png" alt="model-row"
                                                id="model-row" style="border-width: 2px !important;"
                                                data-model="edt-row">
                                        </div>

                                        <div style=" width:300px; " class="ml-2 cursor-pointer">
                                            <span class=" text-center">Vertical</span>
                                            <img class="img img-thumbnail d-block border"
                                                src="<?= ROOT ?>/assets/images/model-column.png" alt="model-column"
                                                id="model-column" style="border-width: 2px !important;"
                                                data-model="edt-column">
                                        </div>
                                    </div>
                                </div>
                                <div class=" col-12 row">
                                    <h6 class=" col-12 text-center">Type de Cours</h6>
                                    <div class="col-12 border d-flex justify-content-center p-2">

                                        <div class=" radio radio-primary mr-4"> <input type="radio" name="type" id="cm"
                                                class="type" value="0">
                                            <label for="cm">CM</label>
                                        </div>

                                        <div class="radio radio-primary mr-4"> <input type="radio" name="type" id="td"
                                                class="type" value="1">
                                            <label for="td">TD</label>
                                        </div>

                                        <div class="radio radio-primary form-group mr-4"> <input type="radio"
                                                name="type" id="tp" class="type" value="2">
                                            <label for="tp">TP</label>
                                        </div>

                                        <div class="radio radio-primary form-group mr-4"> <input type="radio"
                                                name="type" id="all" class="type" value="3" checked>
                                            <label for="all">Mixe</label>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn  btn-link" data-dismiss="modal">
                                <i class="bx bx-x d-block d-sm-none"></i>
                                <span class="d-none d-sm-block">Fermer</span>
                            </button>
                        </div>

                    </div>
                </div>
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
<script src="<?= ROOT ?>/assets/mon_js/contrainte_date_edt.js"></script>

<script>
// la recuperation des liste de promotion d'une filière lors d'une selection de fiilière
var infoFiliere = [];
var num = 0;
var coursRestants = {
    cm: ($(".cm").val() != "") ? parseInt($(".cm").val(), 10) : 0,
    td: ($(".td").val() != "") ? parseInt($(".td").val(), 10) : 0,
    tp: ($(".tp").val() != "") ? parseInt($(".tp").val(), 10) : 0
};

var groupeIndex = 0;

var selectedType = "cm";
$(".heure").change(function() {
    if ($(this).val() == "") {
        $(this).val(0);
    }
    coursRestants = {
        cm: ($(".cm").val() != "") ? parseInt($(".cm").val(), 10) : 0,
        td: ($(".td").val() != "") ? parseInt($(".td").val(), 10) : 0,
        tp: ($(".tp").val() != "") ? parseInt($(".tp").val(), 10) : 0
    };
})
$("#recalculer").click(function(event) {
    event.preventDefault();
    $('#corpsEdt').html("");
    const heureCm = ($(".cm").val() != "") ? parseInt($(".cm").val(), 10) : 0;
    const heureTd = ($(".td").val() != "") ? parseInt($(".td").val(), 10) : 0;
    const heureTp = ($(".tp").val() != "") ? parseInt($(".tp").val(), 10) : 0;
    const heureTpe = parseInt($(".tpe").val(), 10);

    $("#vht").val(heureCm + heureTp + heureTd);



    const heuresModule = calculerHeuresModuleEdt();
    const model = ($('#model-row').hasClass("border-primary")) ? $('#model-row').data('model') : $(
        '#model-column').data('model');
    const type = parseInt($('input[name="type"]:checked').val(), 10);
    genererEdt(heuresModule, model, type);

})





$("#anneeUniversitaire").change(async function() {

    classesAnneeUniversitaire($("#anneeUniversitaire option:selected").val());

    infoFiliere = await infosFiliere($("#promotions option:selected").data("filiere"), "all");
    idSemestre = $("#promotions option:selected").data("semestre");
    modulesSemestre(idSemestre, infoFiliere);
    infoModule($("#infoModule").val(), infoFiliere);

})

$("#promotions").change(async function() {
    infoFiliere = await infosFiliere($("#promotions option:selected").data("filiere"), "all");
    idSemestre = $("#promotions option:selected").data("semestre");
    modulesSemestre(idSemestre, infoFiliere);
    infoModule($("#infoModule").val(), infoFiliere);



})

// la recuperation des heures d'un module lors d'une selection de module
$("#modules").change(function() {
    infoModule($(this).val(), infoFiliere);
    getDefaultEnseignantAndSalleModule($("#promotions option:selected").data("filiere"), $(this).val());
    coursRestants = {
        cm: ($(".cm").val() != "") ? parseInt($(".cm").val(), 10) : 0,
        td: ($(".td").val() != "") ? parseInt($(".td").val(), 10) : 0,
        tp: ($(".tp").val() != "") ? parseInt($(".tp").val(), 10) : 0
    };


})

// les actions lors du rechargement de la page
$(document).ready(async function() {

    $('#edtForm').submit(function(event) {
        event.preventDefault();
        ajouterEdt();

    })

    // la recupeation des promotions de la filière selectionner après le rechargement
    classesAnneeUniversitaire($("#anneeUniversitaire option:selected").val());
})


// Mettre un edt en model horizontal
$('#model-row').click(function() {
    $('#model-column').removeClass('border-primary');
    $(this).addClass("border-primary");
    $(this).css('transition', 'all 0.5s');
    const heuresModule = calculerHeuresModuleEdt();
    const model = $(this).data('model');
    const type = parseInt($('input[name="type"]:checked').val(), 10);
    genererEdt(heuresModule, model, type);


})

// Mettre un edt en model vertical
$('#model-column').click(function() {
    $('#model-row').removeClass('border-primary');
    $(this).addClass("border-primary");
    $(this).css('transition', 'all 0.5s');
    const heuresModule = calculerHeuresModuleEdt();
    const model = $(this).data('model');
    const type = parseInt($('input[name="type"]:checked').val(), 10);
    genererEdt(heuresModule, model, type);
})

// le changement du type de cours d'un edt
$('.type').click(function() {
    const heuresModule = calculerHeuresModuleEdt();
    const model = ($('#model-row').hasClass("border-primary")) ? $('#model-row').data('model') : $(
        '#model-column').data('model')
    const type = parseInt($('input[name="type"]:checked').val(), 10);
    genererEdt(heuresModule, model, type);
})

// Ajouter une ligne à un edt
document.getElementById('add-row').addEventListener('click', function() {
    $('#table-extended-chechbox tbody tr').each(function(index) {
        if (index == $('#table-extended-chechbox tbody tr').length - 1) {
            horaireDebut = $(this).find('.horaireFin').val()
        }
    })
    heure = horaireDebut.split(':');
    horaireFin = (parseInt(heure[0], 10) + 2) + ':' + heure[1];
    const type = parseInt($('input[name="type"]:checked').val(), 10);
    genererCoursEdt(typeEdt[type]);
    addHeure(horaireDebut, horaireFin, coursJour);

});

// Supprimer une ligne d'un edt
document.getElementById('remove-row').addEventListener('click', function() {
    removeHeure();
});



function getPremierTypeDisponible() {
    let totalCM = 0,
        totalTD = 0,
        totalTP = 0;

    document.querySelectorAll('#corpsEnseignant tr').forEach(row => {
        let t = row.querySelector('.typeCours')?.value;
        let h = parseFloat(row.querySelector('#nombreHeure')?.value) || 0;

        if (t === "cm") totalCM += h;
        if (t === "td") totalTD += h;
        if (t === "tp") totalTP += h;
        if (t === "cm-td") {
            totalCM += h / 2;
            totalTD += h / 2;
        }
        if (t === "cm-tp") {
            totalCM += h / 2;
            totalTP += h / 2;
        }
        if (t === "td-tp") {
            totalTD += h / 2;
            totalTP += h / 2;
        }
    });

    if (coursRestants.cm - totalCM > 0) return "cm";
    if (coursRestants.td - totalTD > 0) return "td";
    if (coursRestants.tp - totalTP > 0) return "tp";
    if (coursRestants.cm - totalCM > 0 && coursRestants.td - totalTD > 0) return "cm-td";
    if (coursRestants.cm - totalCM > 0 && coursRestants.tp - totalTP > 0) return "cm-tp";
    if (coursRestants.td - totalTD > 0 && coursRestants.tp - totalTP > 0) return "td-tp";

    return null;
}



function intToRoman(index) {
    const romans = ["I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX", "X"];
    return romans[index] || (index + 1);
}

function showNotificationToast(message, type = "info") {
    const id = Date.now();
    const classes = {
        success: "bg-success text-white",
        danger: "bg-danger text-white",
        warning: "bg-warning text-dark",
        info: "bg-info text-dark"
    };
    const icons = {
        success: "bi-check-circle-fill",
        danger: "bi-x-circle-fill",
        warning: "bi-exclamation-triangle-fill",
        info: "bi-info-circle-fill"
    };

    const toastHTML = `
        <div id="toast-${id}" class="toast align-items-center ${classes[type]} border-0 mb-2" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="bi ${icons[type]} me-2"></i>
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Fermer"></button>
            </div>
        </div>
    `;

    $("#notificationZone").append(toastHTML);
    const toastElement = new bootstrap.Toast(document.getElementById(`toast-${id}`), {
        delay: 5000
    });
    toastElement.show();
}

function getCoursDispo() {
    const disponibles = [];
    if (coursRestants.cm > 0) disponibles.push("cm");
    if (coursRestants.td > 0) disponibles.push("td");
    if (coursRestants.tp > 0) disponibles.push("tp");
    return disponibles;
}

// function getPremierTypeDisponible() {
//     const dispo = [];

//     if (coursRestants.cm > 0) dispo.push("cm");
//     if (coursRestants.td > 0) dispo.push("td");
//     if (coursRestants.tp > 0) dispo.push("tp");
//     if (coursRestants.cm > 0 && coursRestants.td > 0) dispo.push("cm-td");
//     if (coursRestants.cm > 0 && coursRestants.tp > 0) dispo.push("cm-tp");
//     if (coursRestants.td > 0 && coursRestants.tp > 0) dispo.push("td-tp");

//     return dispo.length > 0 ? dispo[0] : null;
// }

function afficherAlerteBootstrap(message, type = "warning") {
    // Fonction d’alerte personnalisée (toast Bootstrap)
    showNotificationToast(message, type);
}

function ajouterLigneEnseignant(id, enseignant) {
    const typeParDefaut = getPremierTypeDisponible();

    if (!typeParDefaut) {
        afficherAlerteBootstrap(
            "⛔ Tous les types de cours sont déjà attribués. Vous ne pouvez plus ajouter d'enseignant.");
        return;
    }

    const newRow = document.createElement("tr");
    num++;
    newRow.innerHTML = `
        <td class="id" id="${id}"><span>${num}</span></td>
        <td><span>${enseignant}</span></td>
        <td><input type="text" class="form-control" id="groupe"></td>
        <td>
            <select class='select2 form-control typeCours'>
                <option value="cm" ${typeParDefaut === "cm" ? "selected" : ""}>CM</option>
                <option value="td" ${typeParDefaut === "td" ? "selected" : ""}>TD</option>
                <option value="tp" ${typeParDefaut === "tp" ? "selected" : ""}>TP</option>
                <option value="cm-td" ${typeParDefaut === "cm-td" ? "selected" : ""}>CM + TD</option>
                <option value="cm-tp" ${typeParDefaut === "cm-tp" ? "selected" : ""}>CM + TP</option>
                <option value="td-tp" ${typeParDefaut === "td-tp" ? "selected" : ""}>TD + TP</option>
            </select>
        </td>
        <td><input type="text" class="form-control" id="nombreHeure" value="" disabled></td>
    `;
    document.querySelector("#corpsEnseignant").appendChild(newRow);

    const selectCours = newRow.querySelector('.typeCours');
    const inputHeure = newRow.querySelector('#nombreHeure');
    let previousType = typeParDefaut;
    let previousHeure = 0;

    function calculerHeuresDisponibles() {
        let totalCM = 0,
            totalTD = 0,
            totalTP = 0;

        document.querySelectorAll('#corpsEnseignant tr').forEach(row => {
            if (row === newRow) return;
            let t = row.querySelector('.typeCours')?.value;
            let h = parseFloat(row.querySelector('#nombreHeure')?.value) || 0;

            if (t === "cm") totalCM += h;
            if (t === "td") totalTD += h;
            if (t === "tp") totalTP += h;
            if (t === "cm-td") {
                totalCM += h / 2;
                totalTD += h / 2;
            }
            if (t === "cm-tp") {
                totalCM += h / 2;
                totalTP += h / 2;
            }
            if (t === "td-tp") {
                totalTD += h / 2;
                totalTP += h / 2;
            }
        });

        return {
            totalCM,
            totalTD,
            totalTP
        };
    }

    function updateNombreHeure(type) {
        const {
            totalCM,
            totalTD,
            totalTP
        } = calculerHeuresDisponibles();
        let heureMax = 0;

        if (type === "cm") heureMax = coursRestants.cm - totalCM;
        else if (type === "td") heureMax = coursRestants.td - totalTD;
        else if (type === "tp") heureMax = coursRestants.tp - totalTP;
        else if (type === "cm-td") heureMax = Math.min(coursRestants.cm - totalCM, coursRestants.td - totalTD) * 2;
        else if (type === "cm-tp") heureMax = Math.min(coursRestants.cm - totalCM, coursRestants.tp - totalTP) * 2;
        else if (type === "td-tp") heureMax = Math.min(coursRestants.td - totalTD, coursRestants.tp - totalTP) * 2;

        return heureMax > 0 ? heureMax : 0;
    }

    selectCours.addEventListener("focus", function() {
        previousType = selectCours.value;
        previousHeure = parseFloat(inputHeure.value) || 0;
    });

    selectCours.addEventListener("change", function() {
        const type = selectCours.value;
        const heureMax = updateNombreHeure(type);

        if (heureMax <= 0) {
            afficherAlerteBootstrap("⚠️ Ce type de cours n'est plus disponible.");

            // Revenir à un type disponible
            const nouveau = getPremierTypeDisponible();
            if (nouveau) {
                selectCours.value = nouveau;
                inputHeure.disabled = false;
                inputHeure.value = updateNombreHeure(nouveau);
                previousType = nouveau;
                previousHeure = parseFloat(inputHeure.value);
            } else {
                selectCours.value = ""; // Aucun dispo
                inputHeure.disabled = true;
                inputHeure.value = "";
            }
        } else {
            inputHeure.disabled = false;
            inputHeure.value = heureMax;
            previousType = type;
            previousHeure = heureMax;
        }
    });

    // Initialiser la valeur par défaut
    const heureInitiale = updateNombreHeure(typeParDefaut);
    inputHeure.disabled = false;
    inputHeure.value = heureInitiale;
    previousHeure = heureInitiale;
}

function ajouterEnseignantAutoGroupe(id, enseignant) {
    num++;
    const nomGroupe = `Groupe ${intToRoman(num - 1)}`;

    let newRow = `
        <tr>
            <td class="id" id="${id}"><span>${num}</span></td>
            <td><span>${enseignant}</span></td>
            <td><input type="text" class="form-control" value="${nomGroupe}" readonly></td>
            <td>
                <select class="form-control typeCours" disabled>
                    <option value="cm-td-tp" selected>CM-TD-TP</option>
                </select>
            </td>
            <td><input type="text" class="form-control" value="${coursRestants.cm+coursRestants.td+coursRestants.tp}" readonly></td>
        </tr>
    `;
    $("#corpsEnseignant").append(newRow);
}



$("#enseignants").change(function() {
    const id = $('#enseignants option:selected').data("id");
    const enseignant = $('#enseignants option:selected').data("enseignant");
    const isGroupe = $("#groupeSelect").is(":checked");

    if (!id || !enseignant) return;

    let isExist = false;
    $("#corpsEnseignant tr").each(function() {
        if ($(this).find('.id').attr("id") == id) {
            isExist = true;
        }
    });

    if (isExist) {
        showNotificationToast("⚠️ Cet enseignant est déjà dans la liste", "warning");
        return;
    }

    if (isGroupe) {
        // Ajout automatique groupe (CM-TD-TP)
        ajouterEnseignantAutoGroupe(id, enseignant);
    } else {
        // Ajout avec vérifications heures
        ajouterLigneEnseignant(id, enseignant);
    }
});


$("#groupeSelect").change(function() {
    // Toujours afficher la table
    $('#listEnseignant').removeClass('d-none').addClass('d-block');

    // Réinitialiser l'affichage
    $("#corpsEnseignant").html("");
    num = 0;
    groupeIndex = 0;

    // Optionnel : Réinitialise les heures si décoché
    if (!this.checked) {
        coursRestants = {
            cm: ($(".cm").val() != "") ? parseInt($(".cm").val(), 10) : 0,
            td: ($(".td").val() != "") ? parseInt($(".td").val(), 10) : 0,
            tp: ($(".tp").val() != "") ? parseInt($(".tp").val(), 10) : 0
        };
    }

    showNotificationToast(this.checked ? "✅ Mode groupe activé" : "ℹ️ Mode normal activé", "info");
});




// Suppression d’un enseignant
$("#removeEnseignant").click(function() {
    var tableBody = document.querySelector("#corpsEnseignant");
    var rows = tableBody.querySelectorAll("tr");
    if (rows.length > 0) {
        tableBody.removeChild(rows[rows.length - 1]);
        num--;
    }
});
</script>