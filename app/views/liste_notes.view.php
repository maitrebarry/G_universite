<?php $this->view("Partials/header") ?>

<style>
    #listeNotes .form-label { font-size: var(--fs-sm); font-weight: var(--fw-medium); color: var(--text-secondary); margin-bottom: 4px; }

    /* Tableau de résultats (rendu par les fragments + capturé en PDF -> couleurs concrètes) */
    #notesTable { width: 100%; border-collapse: collapse; margin: auto; font-size: 13px; }
    #notesTable th, #notesTable td { padding: 5px 6px; text-align: center; border: 1px solid #b8b8b8; }
    #notesTable thead th { background: #e7ecf5; color: #1f2937; font-weight: 600; font-size: 12px; }
    #notesTable th.vertical-header { height: 150px; width: 30px; padding: 0; }
    #notesTable th.vertical-header > div { display: inline-block; transform: rotate(-90deg); transform-origin: center center; font-size: 12px; font-weight: 600; }
    #notesTable .note_ue { background: #dde3ee; font-weight: 600; }
    #notesTable tbody tr:nth-child(even) td { background: #f6f8fb; }
    @media print { #notesTable th.vertical-header > div { overflow: visible !important; max-width: none !important; } }
</style>

<body class="vertical-layout vertical-menu-modern boxicon-layout no-card-shadow 2-columns navbar-sticky footer-static"
    data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">

    <?php $this->view("Partials/navbar") ?>
    <?php $this->view("Partials/seibar") ?>

    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-12 mb-2 mt-1">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h5 class="content-header-title float-left pr-1 mb-0">Notes</h5>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb p-0 mb-0">
                                    <li class="breadcrumb-item"><a href="<?= ROOT ?>/Homes"><i class="bx bx-home-alt"></i></a></li>
                                    <li class="breadcrumb-item">Notes</li>
                                    <li class="breadcrumb-item active">Résultat annuel</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-body">
                <div id="message" class="d-none"></div>

                <div class="card" id="listeNotes">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between flex-wrap mb-1" style="gap:10px;">
                                <div class="gu-section-title lg" style="margin:0;"><span class="gu-ico-chip"><i class="bx bx-bar-chart-alt-2"></i></span> Résultats &amp; relevés</div>
                                <div class="d-none align-items-center" id="printSection" style="gap:10px;">
                                    <button id="print" class="btn btn-soft-primary"><i class="bx bx-printer"></i> Imprimer (résultats)</button>
                                    <button id="printReleve" class="btn btn-primary"><i class="bx bx-file"></i> Relevés de notes</button>
                                </div>
                            </div>

                            <div id="session_info">
                                <div class="row" style="row-gap:14px;">
                                    <div class="col-6 col-md-2">
                                        <label class="form-label" for="anneeUniversitaire">Année</label>
                                        <select class="form-select" id="anneeUniversitaire" name="anneeUniversitaire">
                                            <?php
                                            $annee_debut = 2012;
                                            $annee_actuelle = (int) date('Y');
                                            if ((int) date('n') <= 8) { $annee_actuelle--; }
                                            $courante = $annee_actuelle . '-' . ($annee_actuelle + 1);
                                            for ($a = $annee_debut; $a <= $annee_actuelle; $a++) {
                                                $v = $a . '-' . ($a + 1);
                                                echo '<option value="' . $v . '"' . ($v === $courante ? ' selected' : '') . '>' . $v . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <label class="form-label" for="promotions">Classe</label>
                                        <select class="form-select" id="promotions" name="promotions">
                                            <option value="" disabled selected>Sélectionner une classe</option>
                                        </select>
                                    </div>
                                    <div class="col-6 col-md-3" id="ueContainer">
                                        <label class="form-label" for="ues">UE</label>
                                        <select class="form-select" id="ues">
                                            <option value="" selected>Toutes les UE</option>
                                        </select>
                                    </div>
                                    <div class="col-6 col-md-4" id="moduleContainer">
                                        <label class="form-label" for="modules">Module</label>
                                        <select class="form-select" id="modules">
                                            <option value="" selected>Tous les modules</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div id="table_section" class="mt-3">
                                <div class="gu-empty"><i class="bx bx-bar-chart-alt-2"></i>Choisissez une classe (puis une UE/un module) pour afficher les résultats.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <?php $this->view("Partials/foot") ?>
    <?php $this->view("Partials/footer") ?>
</body>

</html>
<script src="<?= ROOT ?>/assets/mon_js/liste_note.js"></script>
<script>
    var infoFiliere = [];

    function viderResultats() {
        $("#table_section").html('<div class="gu-empty"><i class="bx bx-bar-chart-alt-2"></i>Choisissez une classe (puis une UE/un module) pour afficher les résultats.</div>');
    }

    $(document).ready(function () {
        classesAnneeUniversitaire($("#anneeUniversitaire option:selected").val());
        viderResultats();
    });

    $("#anneeUniversitaire").change(function () {
        classesAnneeUniversitaire($("#anneeUniversitaire option:selected").val());
        var idSemestre = $("#promotions option:selected").data("semestre");
        ueSemestre(idSemestre, infoFiliere);
        $('#ues').val("");
        moduleUe($("#ues option:selected").data("id"), infoFiliere);
        $("#printSection").removeClass("d-flex").addClass("d-none");
        viderResultats();
    });

    $("#promotions").change(async function () {
        infoFiliere = await infosFiliere($("#promotions option:selected").data("filiere"), "all");
        var idSemestre = $("#promotions option:selected").data("semestre");
        ueSemestre(idSemestre, infoFiliere);
        moduleUe($("#ues option:selected").data("id"), infoFiliere);
        $("#printSection").removeClass("d-none").addClass("d-flex");
        if (!$("#promotions option:selected").text().includes("S")) {
            loadEtudiants(window.APP_ROUTE("Notes/get_moyenne_licence_etudiant"));
            $("#ues").empty().append('<option value="">Sélectionner une UE</option>');
        } else {
            loadEtudiants();
        }
    });

    $("#ues").change(function () {
        moduleUe($("#ues option:selected").data("id"), infoFiliere);
        loadEtudiants();
        $("#printSection").removeClass("d-none").addClass("d-flex");
    });

    $("#modules").change(function () {
        loadEtudiants();
        $("#printSection").removeClass("d-none").addClass("d-flex");
    });

    $("#print").click(function () {
        var nom = "RES " + $("#promotions option:selected").text() + " PROV";
        imprimer(nom, document.getElementById("table_section").innerHTML);
    });
    $("#printReleve").click(function () { imprimerReleve(); });
</script>
