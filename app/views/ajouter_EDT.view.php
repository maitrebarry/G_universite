<style>
    /* Création EDT — aligné au design system (tokens), styles scopés au formulaire */
    #edtForm .gu-section-title { margin-bottom: 14px; }
    #edtForm label.form-label { font-size: var(--fs-sm); font-weight: var(--fw-medium); color: var(--text-secondary); margin-bottom: 4px; }
    #table-extended-chechbox th,
    #table-extended-chechbox td { text-align: center; vertical-align: middle; }
    #table-extended-chechbox input,
    #corpsEnseignant input { text-align: center; }

    /* Interrupteur "Partager en groupe" */
    .gu-switch { display: inline-flex; align-items: center; gap: 12px; cursor: pointer; font-weight: var(--fw-medium); user-select: none; }
    .gu-switch input { display: none; }
    .gu-switch .track { width: 46px; height: 24px; border-radius: var(--radius-pill); background: var(--border-strong); position: relative; transition: var(--transition); flex-shrink: 0; }
    .gu-switch .track::after { content: ""; position: absolute; top: 2px; left: 2px; width: 20px; height: 20px; border-radius: 50%; background: #fff; transition: var(--transition); box-shadow: var(--shadow-sm); }
    .gu-switch input:checked + .track { background: var(--brand-600); }
    .gu-switch input:checked + .track::after { transform: translateX(22px); }

    /* Vignettes "Modèle EDT" */
    #model-row, #model-column { cursor: pointer; border: 2px solid var(--border) !important; border-radius: var(--radius-md); transition: var(--transition); }
    #model-row.border-primary, #model-column.border-primary { border-color: var(--brand-600) !important; box-shadow: var(--shadow-sm); }

    /* Toasts */
    #notificationZone .toast { min-width: 300px; border-radius: var(--radius-md); box-shadow: var(--shadow-lg);
        animation: slideInRight .4s ease, fadeOut .4s ease 4.5s; }
    #notificationZone .toast .toast-body i { margin-right: 10px; }
    @keyframes slideInRight { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
    @keyframes fadeOut { to { opacity: 0; transform: translateX(100%); } }
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
                            <div class="card">
                                <div class="card-content">
                                    <div class="card-body">
                                        <div class="gu-section-title lg"><span class="gu-ico-chip"><i class="bx bx-calendar-plus"></i></span> Création d'emploi du temps</div>
                                        <form method="POST" novalidate id="edtForm">
                                            <div class="row" style="row-gap:14px;">
                                                <div class="col-6 col-lg-3">
                                                    <label class="form-label" for="anneeUniversitaire">Année universitaire</label>
                                                    <select class="form-select" id="anneeUniversitaire" name="anneeUniversitaire">
                                                        <?php
                                                        $annee_debut = 2012;
                                                        $annee_actuelle = date('Y');
                                                        $mois_actuel = date('n');
                                                        if ($mois_actuel <= 8) {
                                                            $annee_actuelle--;
                                                        }
                                                        $annee_universitaire_courante = $annee_actuelle . '-' . ($annee_actuelle + 1);
                                                        for ($annee = $annee_debut; $annee <= $annee_actuelle; $annee++) {
                                                            $annee_suivante = $annee + 1;
                                                            $valeur = $annee . '-' . $annee_suivante;
                                                            $selected = ($valeur == $annee_universitaire_courante) ? 'selected' : '';
                                                            echo "<option value=\"$valeur\" $selected>$valeur</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-6 col-lg-3">
                                                    <label class="form-label" for="promotions">Classe</label>
                                                    <select class="form-select" id="promotions" data-id="<?php echo $idPromotion ?>">
                                                        <option value="" disabled>Sélectionner une classe</option>
                                                    </select>
                                                </div>
                                                <div class="col-6 col-lg-3">
                                                    <label class="form-label" for="modules">Module</label>
                                                    <select class="form-select champ" id="modules" name="modules">
                                                        <option value="" disabled selected>Sélectionner un module</option>
                                                    </select>
                                                </div>
                                                <div class="col-6 col-lg-3 d-flex align-items-end">
                                                    <button type="button" class="btn btn-soft-primary w-100" data-toggle="modal" data-target="#menuConfig"><i class="bx bxs-cog"></i> Modèle EDT</button>
                                                </div>
                                            </div>

                                            <hr style="border:0;border-top:1px solid var(--border);margin:18px 0 14px;">
                                            <div class="d-flex align-items-center flex-wrap justify-content-between mb-2" style="gap:10px;">
                                                <div class="gu-section-title" style="margin:0;"><span class="gu-ico-chip success"><i class="bx bx-time-five"></i></span> Volume horaire &amp; grille</div>
                                                <div class="d-flex align-items-center" style="gap:8px;">
                                                    <button type="button" class="btn btn-soft-primary btn-sm" id="add-row" title="Ajouter une ligne"><i class="bx bx-plus"></i> Ligne</button>
                                                    <button type="button" class="btn btn-soft-danger btn-sm" id="remove-row" title="Supprimer la dernière ligne"><i class="bx bx-minus"></i> Ligne</button>
                                                </div>
                                            </div>
                                            <div class="d-none align-items-end flex-wrap mb-2" id="infoModule" style="gap:12px;">
                                                <input type="hidden" id="vht" class="vht">
                                                <div style="width:88px;">
                                                    <label class="form-label text-center d-block">CM</label>
                                                    <input type="number" class="heure form-control text-center cm">
                                                </div>
                                                <div style="width:88px;">
                                                    <label class="form-label text-center d-block">TD</label>
                                                    <input type="number" class="heure form-control text-center td">
                                                </div>
                                                <div style="width:88px;">
                                                    <label class="form-label text-center d-block">TP</label>
                                                    <input type="number" class="heure form-control text-center tp">
                                                </div>
                                                <div style="width:88px;">
                                                    <label class="form-label text-center d-block">TPE</label>
                                                    <input type="number" class="heure form-control text-center tpe">
                                                </div>
                                                <button type="button" class="btn btn-outline-primary" title="Recalculer la grille" id="recalculer"><i class="bx bx-revision"></i> Recalculer</button>
                                            </div>

                                            <div class="gu-table-wrap">
                                                <table id="table-extended-chechbox" class="gu-table" style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center">Horaire</th>
                                                            <?php foreach ($jours as $jour): ?>
                                                                <th class="jour text-center" data-id="<?php echo $jour->id_jour ?>"><?php echo strtoupper($jour->nom_jour) ?></th>
                                                            <?php endforeach ?>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="corpsEdt" id="corpsEdt"></tbody>
                                                </table>
                                            </div>
                                            <hr style="border:0;border-top:1px solid var(--border);margin:18px 0 14px;">
                                            <div class="gu-section-title"><span class="gu-ico-chip warning"><i class="bx bx-user-voice"></i></span> Enseignants &amp; affectation</div>
                                            <div class="row" style="row-gap:14px;">
                                                <div class="col-12 col-sm-4">
                                                    <label class="form-label" for="enseignants">Enseignant</label>
                                                    <select class="form-select champ" id="enseignants" name="enseignants" data-smart>
                                                        <option value="" disabled>Sélectionner un enseignant</option>
                                                        <?php foreach ($enseignants as $enseignant): ?>
                                                            <option value="<?php echo $enseignant->enseignant_id ?>" class="text-capitalize"
                                                                data-enseignant="<?php echo $enseignant->enseignant_nom . " " . $enseignant->enseignant_prenom ?>"
                                                                data-id="<?php echo $enseignant->enseignant_id ?>">
                                                                <?php echo $enseignant->enseignant_nom . " " . $enseignant->enseignant_prenom ?>
                                                            </option>
                                                        <?php endforeach ?>
                                                    </select>
                                                </div>
                                                <div class="col-12 col-sm-4">
                                                    <label class="form-label" for="salles">Salle de cours</label>
                                                    <select class="form-select champ" name="salles" id="salles" data-smart>
                                                        <option value="" disabled selected>Sélectionner une salle</option>
                                                        <?php foreach ($salles as $salle): ?>
                                                            <option value="<?php echo $salle->id_salle ?>"><?php echo strtoupper($salle->nom_salle) . " (" . $salle->capacite_salle . ")" ?></option>
                                                        <?php endforeach ?>
                                                    </select>
                                                </div>
                                                <div class="col-12 col-sm-4">
                                                    <label class="form-label" for="dateDebut">Date de début</label>
                                                    <input type="date" class="form-control" name="dateDebut" id="dateDebut" value="<?= date('Y-m-d') ?>">
                                                </div>
                                            </div>

                                            <div class="d-flex justify-content-center my-3">
                                                <label class="gu-switch">
                                                    <input type="checkbox" id="groupeSelect">
                                                    <span class="track"></span>
                                                    Partager en groupe
                                                </label>
                                            </div>

                                            <div id="listEnseignant">
                                                <div class="d-flex justify-content-end mb-1">
                                                    <button type="button" class="btn btn-soft-danger btn-sm" id="removeEnseignant" title="Retirer le dernier enseignant"><i class="bx bx-minus"></i> Retirer</button>
                                                </div>
                                                <div class="gu-table-wrap">
                                                    <table class="gu-table" style="width:100%;">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-center">N°</th>
                                                                <th>Enseignant</th>
                                                                <th class="text-center">Groupe</th>
                                                                <th>Cours</th>
                                                                <th style="width:90px;">Heures</th>
                                                                <th id="thSalle" class="d-none">Salle</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="corpsEnseignant"></tbody>
                                                    </table>
                                                </div>
                                            </div>

                                            <div class="gu-edt-actions">
                                                <div id="conflitsBox" class="gu-edt-actions__msg"></div>
                                                <button type="submit" class="btn btn-gradient" id="valider"><i class="bx bx-save"></i> Enregistrer l'emploi du temps</button>
                                            </div>
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

        <div class="modal fade text-left" id="menuConfig" tabindex="-1" role="dialog"
            aria-labelledby="myModalLabel160" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" style="margin:0;"><i class="bx bxs-cog"></i> Modèle d'emploi du temps</h5>
                        <button type="button" class="btn btn-ghost btn-sm" data-dismiss="modal" aria-label="Fermer"><i class="bx bx-x"></i></button>
                    </div>
                    <div class="modal-body">
                        <div class="gu-section-title">Disposition de la grille</div>
                        <div class="row mb-3" style="row-gap:12px;">
                            <div class="col-6 text-center">
                                <div class="mb-1" style="font-size:var(--fs-sm);color:var(--text-secondary);">Horizontal</div>
                                <img class="img-thumbnail d-block border-primary" src="<?= ROOT ?>/assets/images/model-row.png" alt="model-row" id="model-row" data-model="edt-row">
                            </div>
                            <div class="col-6 text-center">
                                <div class="mb-1" style="font-size:var(--fs-sm);color:var(--text-secondary);">Vertical</div>
                                <img class="img-thumbnail d-block" src="<?= ROOT ?>/assets/images/model-column.png" alt="model-column" id="model-column" data-model="edt-column">
                            </div>
                        </div>
                        <div class="gu-section-title">Type de cours</div>
                        <div class="d-flex flex-wrap justify-content-center" style="gap:20px;">
                            <div class="radio radio-primary"><input type="radio" name="type" id="cm" class="type" value="0"><label for="cm">CM</label></div>
                            <div class="radio radio-primary"><input type="radio" name="type" id="td" class="type" value="1"><label for="td">TD</label></div>
                            <div class="radio radio-primary"><input type="radio" name="type" id="tp" class="type" value="2"><label for="tp">TP</label></div>
                            <div class="radio radio-primary"><input type="radio" name="type" id="all" class="type" value="3" checked><label for="all">Mixte</label></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-ghost" data-dismiss="modal">Fermer</button>
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
 <script>
    const ROOT = "<?= ROOT ?>";
    const ROOT_EDT = ROOT + "/Emploi_du_temps";
</script>
<script src="<?= ROOT ?>/assets/mon_js/edt.js"></script>
<script src="<?= ROOT ?>/assets/mon_js/contrainte_date_edt.js"></script>
<script src="<?= ROOT ?>/assets/mon_js/gu-smart-select.js"></script>

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
})


$("#anneeUniversitaire").change(async function() {

    classesAnneeUniversitaire($("#anneeUniversitaire option:selected").val());

    infoFiliere = await infosFiliere($("#promotions option:selected").data("filiere"), "all");
    idSemestre = $("#promotions option:selected").data("semestre");
    modulesSemestre(idSemestre, infoFiliere);
    infoModule($("#modules").val(), infoFiliere);

})

$("#promotions").change(async function() {
    infoFiliere = await infosFiliere($("#promotions option:selected").data("filiere"), "all");
    idSemestre = $("#promotions option:selected").data("semestre");
    modulesSemestre(idSemestre, infoFiliere);
    infoModule($("#modules").val(), infoFiliere);



})

// la recuperation des heures d'un module lors d'une selection de module
$("#modules").change(function() {
    infoModule($(this).val(), infoFiliere);

    coursRestants = {
        cm: $(".cm").val() != "" ? parseInt($(".cm").val(), 10) : 0,
        td: $(".td").val() != "" ? parseInt($(".td").val(), 10) : 0,
        tp: $(".tp").val() != "" ? parseInt($(".tp").val(), 10) : 0,
    };
    console.log(coursRestants);

    getDefaultEnseignantAndSalleModule($("#promotions option:selected").data("filiere"), $(this).val());

    // Réinitialiser l'affichage des enseignants
    $("#corpsEnseignant").html("");

})

// les actions lors du rechargement de la page
$(document).ready(async function() {

    $('#edtForm').submit(function(event) {
        event.preventDefault();
        var data = collecterConflits();
        // Rien à vérifier côté disponibilités -> le serveur validera le reste.
        if (!data.cells.length || (!data.salles.length && !data.enseignants.length)) { doSubmit(false); return; }
        $.ajax({
            method: "POST", url: ROOT_EDT + "/conflits", dataType: "json",
            data: { cells: data.cells, salles: data.salles, enseignants: data.enseignants, excludeEdtId: $("#valider").data("id") || "" },
            success: function(res) {
                res = res || {};
                var salle = res.salle || [], ens = res.enseignant || [];
                if (ens.length) { // enseignant = bloquant
                    conflitModal({ confirm: false, title: "Conflit d'enseignant", items: ens, message: "Un enseignant ne peut pas être à deux endroits au même moment. Modifiez l'horaire ou l'enseignant concerné." });
                    return;
                }
                if (salle.length) { // salle = confirmation
                    conflitModal({ confirm: true, title: "Salle déjà occupée", items: salle, message: "Cette salle est déjà utilisée sur ce créneau. Voulez-vous créer l'emploi du temps malgré ce conflit ?" })
                        .then(function(ok) { if (ok) doSubmit(true); });
                    return;
                }
                doSubmit(false);
            },
            error: function() { doSubmit(false); } // pré-vérif indisponible -> on laisse le serveur trancher
        });
    })

    // la recupeation des promotions de la filière selectionner après le rechargement
    classesAnneeUniversitaire($("#anneeUniversitaire option:selected").val());

    // Selects intelligents (recherche) sur Enseignant et Salle
    if (window.guSmartSelectAll) guSmartSelectAll();
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
    nombreEnseignant = 0;;


    document.querySelectorAll('#corpsEnseignant tr').forEach(row => {
        nombreEnseignant++;
        let t = row.querySelector('.typeCours')?.value;
        let h = parseFloat(row.querySelector('#nombreHeure')?.value) || 0;

        if (t === "cm") totalCM += h;
        if (t === "td") totalTD += h;
        if (t === "tp") totalTP += h;
        if (t === "cm-td") {
            totalCM += h - (parseInt($(".td").val(), 10));
            totalTD += h - (parseInt($(".cm").val(), 10));
        }
        if (t === "cm-tp") {
            totalCM += h - (parseInt($(".tp").val(), 10));
            totalTP += h - parseInt($(".cm").val(), 10);
        }
        if (t === "td-tp") {
            totalTD += h - parseInt($(".tp").val(), 10);
            totalTP += h - parseInt($(".td").val(), 10);
        }
        if (t === "cm-td-tp") {
            totalCM += h - parseInt($(".td").val(), 10) - parseInt($(".tp").val(), 10);
            totalTD += h - parseInt($(".cm").val(), 10) - parseInt($(".tp").val(), 10);
            totalTP += h - parseInt($(".cm").val(), 10) - parseInt($(".td").val(), 10);
        }
    });

    if (nombreEnseignant == 0) {
        if (coursRestants.cm != 0 && coursRestants.td != 0 && coursRestants.tp != 0) {
            return "cm-td-tp";
        } else if (coursRestants.cm != 0 && coursRestants.td != 0 && coursRestants.tp == 0) {
            return "cm-td";
        } else if (coursRestants.cm != 0 && coursRestants.td == 0 && coursRestants.tp != 0) {
            return "cm-tp";
        } else if (coursRestants.cm == 0 && coursRestants.td != 0 && coursRestants.tp != 0) {
            return "td-tp";
        } else {

            return null;
        }

    }
    if (coursRestants.cm - totalCM > 0) return "cm";
    if (coursRestants.td - totalTD > 0) return "td";
    if (coursRestants.tp - totalTP > 0) return "tp";
    if (coursRestants.cm - totalCM > 0 && coursRestants.td - totalTD > 0) return "cm-td";
    if (coursRestants.cm - totalCM > 0 && coursRestants.tp - totalTP > 0) return "cm-tp";
    if (coursRestants.td - totalTD > 0 && coursRestants.tp - totalTP > 0) return "td-tp";
    if (coursRestants.cm - totalCM > 0 && coursRestants.td - totalTD > 0 && coursRestants.tp - totalTP > 0)
        return "cm-td-tp";
    return null;
}

function recalculerTousLesHeures() {
    let totalCM = 0,
        totalTD = 0,
        totalTP = 0;

    document.querySelectorAll('#corpsEnseignant tr').forEach(row => {
        const t = row.querySelector('.typeCours')?.value;
        const h = parseFloat(row.querySelector('#nombreHeure')?.value) || 0;

        if (t === "cm") totalCM += h;
        if (t === "td") totalTD += h;
        if (t === "tp") totalTP += h;
        if (t === "cm-td") {
            totalCM += h - (parseInt($(".td").val(), 10));
            totalTD += h - (parseInt($(".cm").val(), 10));
        }
        if (t === "cm-tp") {
            totalCM += h - (parseInt($(".tp").val(), 10));
            totalTP += h - parseInt($(".cm").val(), 10);
        }
        if (t === "td-tp") {
            totalTD += h - parseInt($(".tp").val(), 10);
            totalTP += h - parseInt($(".td").val(), 10);
        }
        if (t === "cm-td-tp") {
            totalCM += h - parseInt($(".td").val(), 10) - parseInt($(".tp").val(), 10);
            totalTD += h - parseInt($(".cm").val(), 10) - parseInt($(".tp").val(), 10);
            totalTP += h - parseInt($(".cm").val(), 10) - parseInt($(".td").val(), 10);
        }
    });

    console.log(`Total : CM=${totalCM} TD=${totalTD} TP=${totalTP}`);
    // Vous pouvez aussi mettre à jour des affichages visuels ici si besoin
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
        <td><input type="text" class="form-control groupe" id="groupe" value="GP" readonly disabled></td>
        <td>
            <select class='form-select typeCours'>
                <option value="cm" ${typeParDefaut === "cm" ? "selected" : ""}>CM</option>
                <option value="td" ${typeParDefaut === "td" ? "selected" : ""}>TD</option>
                <option value="tp" ${typeParDefaut === "tp" ? "selected" : ""}>TP</option>
                <option value="cm-td" ${typeParDefaut === "cm-td" ? "selected" : ""}>CM + TD</option>
                <option value="cm-tp" ${typeParDefaut === "cm-tp" ? "selected" : ""}>CM + TP</option>
                <option value="td-tp" ${typeParDefaut === "td-tp" ? "selected" : ""}>TD + TP</option>
                <option value="cm-td-tp" ${typeParDefaut === "cm-td-tp" ? "selected" : ""}>CM + TD + TP</option>
            </select>
        </td>

        <td style="width:100px !important"><input type="text" class="form-control nombreHeure" id="nombreHeure" value="" readonly disabled></td>
        `;
    document.querySelector("#corpsEnseignant").appendChild(newRow);

    const selectCours = newRow.querySelector('.typeCours');
    const nombreHeures = newRow.querySelector('.nombreHeure');
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
                totalCM += h - (parseInt($(".td").val(), 10));
                totalTD += h - (parseInt($(".cm").val(), 10));
            }
            if (t === "cm-tp") {
                totalCM += h - (parseInt($(".tp").val(), 10));
                totalTP += h - parseInt($(".cm").val(), 10);
            }
            if (t === "td-tp") {
                totalTD += h - parseInt($(".tp").val(), 10);
                totalTP += h - parseInt($(".td").val(), 10);
            }
            if (t === "cm-td-tp") {
                totalCM += h - parseInt($(".td").val(), 10) - parseInt($(".tp").val(), 10);
                totalTD += h - parseInt($(".cm").val(), 10) - parseInt($(".tp").val(), 10);
                totalTP += h - parseInt($(".cm").val(), 10) - parseInt($(".td").val(), 10);
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
        else if (type === "cm-td") heureMax = coursRestants.cm - totalCM + coursRestants.td - totalTD;
        else if (type === "cm-tp") heureMax = coursRestants.cm - totalCM + coursRestants.tp - totalTP;
        else if (type === "td-tp") heureMax = coursRestants.td - totalTD + coursRestants.tp - totalTP;
        else if (type === "cm-td-tp") heureMax = coursRestants.cm - totalCM + coursRestants.td - totalTD +
            coursRestants.tp - totalTP;

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

    // l'évenenment pour le changement de l'heure
    inputHeure.addEventListener("input", function() {
        const type = selectCours.value;
        let heureSaisie = parseFloat(inputHeure.value) || 0;
        const heureMax = updateNombreHeure(type);

        if (heureSaisie > heureMax) {
            afficherAlerteBootstrap(
                `⚠️ Vous avez dépassé la limite pour ${type.toUpperCase()}. Max autorisé : ${heureMax}h`,
                "danger");
            heureSaisie = heureMax;
            inputHeure.value = heureSaisie;
        }

        // Mettre à jour les totaux en recalculant pour toutes les lignes
        recalculerTousLesHeures(); // Fonction que je vous fournis juste après
    });


    nombreHeures.addEventListener("change", function() {

    });

    // Initialiser la valeur par défaut
    const heureInitiale = updateNombreHeure(typeParDefaut);
    inputHeure.disabled = false;
    inputHeure.value = heureInitiale;
    previousHeure = heureInitiale;
}

function ajouterEnseignantAutoGroupe(id, enseignant) {
    num++;
    const nomGroupe = `Gr ${intToRoman(num - 1)}`;

    let newRow = `
        <tr>
            <td class="id" id="${id}"><span>${num}</span></td>
            <td><span>${enseignant}</span></td>
            <td><input type="text" class="form-control" value="${nomGroupe}" readonly id="groupe"></td>
            <td>
                <select class="form-select typeCours" disabled>
                    <option value="cm-td-tp" selected>CM-TD-TP</option>
                </select>
            </td>
            <td><input type="text" class="form-control nombreHeure" value="${coursRestants.cm+coursRestants.td+coursRestants.tp}" readonly></td>
            <td style="width:200px !important">
                <select class="form-select salle" id="salle_${num}">
                    <option value="" disabled selected>Salle
                    </option>
                    <?php foreach ($salles as $salle): ?>
                        <option value="<?php echo $salle->id_salle ?>">
                            <?php echo strtoupper($salle->nom_salle) . "(" . $salle->capacite_salle . ")" ?>
                        </option>
                    <?php endforeach ?>
                </select>
            </td>
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
        $('#thSalle').addClass('d-none');

    } else {
        $('#thSalle').removeClass('d-none');

    }

    showNotificationToast(this.checked ? "✅ Mode groupe activé" : "ℹ️ Mode normal activé", "info");

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




// Suppression d’un enseignant
$("#removeEnseignant").click(function() {
    var tableBody = document.querySelector("#corpsEnseignant");
    var rows = tableBody.querySelectorAll("tr");
    if (rows.length > 0) {
        tableBody.removeChild(rows[rows.length - 1]);
        num--;
    }
});

// ============ Vérification des conflits EN DIRECT (salle / enseignant) ============
var _conflitTimer = null;
function collecterConflits() {
    var cells = [];
    $("#corpsEdt tr").each(function() {
        var d = $(this).find(".horaireDebut").val();
        var f = $(this).find(".horaireFin").val();
        if (!d || !f) return;
        $(this).find("td").each(function(index) {
            if (index === 0) return;
            var t = ($(this).find(".tache").val() || "").toLowerCase();
            if (!t || t === "x") return;
            var idJour = $("#table-extended-chechbox thead th").eq(index).data("id");
            if (idJour) cells.push({ jour: idJour, debut: d, fin: f });
        });
    });
    var enseignants = [], salles = [], isGroupe = $("#groupeSelect").is(":checked");
    $("#corpsEnseignant tr").each(function() {
        var id = $(this).find(".id").attr("id");
        if (id) enseignants.push(id);
        if (isGroupe) { var s = $(this).find(".salle").val(); if (s) salles.push(s); }
    });
    if (!isGroupe) { var s = $("#salles").val(); if (s) salles.push(s); }
    return { cells: cells, salles: salles, enseignants: enseignants };
}
function conflitListe(items) {
    return '<ul style="margin:.35rem 0 0;padding-left:1.1rem;">' +
        items.map(function(x) { return '<li>' + String(x).replace(/</g, "&lt;") + '</li>'; }).join('') + '</ul>';
}
function renderConflits(box, res) {
    res = res || {};
    var salle = res.salle || [], ens = res.enseignant || [], html = '';
    if (ens.length) {
        html += '<div class="alert alert-soft-danger"><b><i class="bx bx-error-circle"></i> ' + ens.length +
            ' conflit(s) d\'enseignant — bloquant</b>' + conflitListe(ens) + '</div>';
    }
    if (salle.length) {
        html += '<div class="alert alert-soft-warning"><b><i class="bx bx-error"></i> ' + salle.length +
            ' conflit(s) de salle — confirmation à l\'enregistrement</b>' + conflitListe(salle) + '</div>';
    }
    if (!html) {
        html = '<div class="alert alert-soft-success d-flex align-items-center gap-2"><i class="bx bx-check-circle"></i> Aucun conflit sur ces créneaux.</div>';
    }
    box.innerHTML = html;
}
function verifierConflits() {
    var box = document.getElementById("conflitsBox");
    if (!box) return;
    var data = collecterConflits();
    if (!data.cells.length || (!data.salles.length && !data.enseignants.length)) { box.innerHTML = ""; return; }
    box.innerHTML = '<div class="alert alert-soft-info d-flex align-items-center gap-2"><i class="bx bx-loader-alt bx-spin"></i> Vérification des disponibilités…</div>';
    $.ajax({
        method: "POST", url: ROOT_EDT + "/conflits", dataType: "json",
        data: { cells: data.cells, salles: data.salles, enseignants: data.enseignants, excludeEdtId: $("#valider").data("id") || "" },
        success: function(res) { renderConflits(box, res); },
        error: function() { box.innerHTML = ""; }
    });
}
function planifierVerifConflits() { clearTimeout(_conflitTimer); _conflitTimer = setTimeout(verifierConflits, 450); }

// Modale de conflit (SweetAlert2 si dispo) — renvoie une Promise<boolean> (true = confirmé)
function conflitModal(opts) {
    var liste = '<ul style="text-align:left;margin:.4rem 0;padding-left:1.2rem;">' +
        opts.items.map(function(x) { return '<li>' + String(x).replace(/</g, "&lt;") + '</li>'; }).join('') + '</ul>';
    var html = liste + '<p style="margin-top:.6rem;">' + opts.message + '</p>';
    if (window.Swal) {
        return Swal.fire({
            icon: opts.confirm ? 'warning' : 'error',
            title: opts.title,
            html: html,
            showCancelButton: !!opts.confirm,
            confirmButtonText: opts.confirm ? 'Créer quand même' : 'Compris',
            cancelButtonText: 'Annuler',
            confirmButtonColor: opts.confirm ? '#d97706' : '#1f4f9c',
            cancelButtonColor: '#64748b',
            reverseButtons: true
        }).then(function(r) { return !!(r && (r.isConfirmed || (r.value && !r.dismiss))); });
    }
    if (opts.confirm) return Promise.resolve(window.confirm(opts.title + "\n\n" + opts.items.join("\n") + "\n\n" + opts.message));
    window.alert(opts.title + "\n\n" + opts.items.join("\n") + "\n\n" + opts.message);
    return Promise.resolve(false);
}
function doSubmit(force) {
    window.__edtForceSalle = !!force;
    ajouterEdt();
    window.__edtForceSalle = false;
}
$(document).on("change", "#corpsEdt .tache, #corpsEnseignant .salle", planifierVerifConflits);
$(document).on("input change", "#corpsEdt .horaireDebut, #corpsEdt .horaireFin", planifierVerifConflits);
$("#salles, #groupeSelect, #enseignants").on("change", planifierVerifConflits);
$("#recalculer, #add-row, #remove-row, #removeEnseignant").on("click", planifierVerifConflits);
</script>