<?php $this->view("Partials/header") ?>

<style>
    .hidden { display: none !important; }
    #ajoutNotes .form-label { font-size: var(--fs-sm); font-weight: var(--fw-medium); color: var(--text-secondary); margin-bottom: 4px; }

    /* En-tête module */
    .gu-note-head { display: flex; flex-wrap: wrap; gap: 28px; align-items: center; padding: 4px 4px 16px; border-bottom: 1px solid var(--border); margin-bottom: 16px; }
    .gu-note-head .lbl { display: block; font-size: var(--fs-xs); text-transform: uppercase; letter-spacing: .04em; color: var(--text-tertiary); }
    .gu-note-head .val { font-weight: var(--fw-semibold); color: var(--text-primary); }

    /* Tableau de bord */
    .gu-note-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(155px, 1fr)); gap: 12px; margin-bottom: 16px; }
    .gu-stat { display: flex; align-items: center; gap: 12px; padding: 12px 14px; border: 1px solid var(--border); border-radius: var(--radius-md); background: var(--surface-card); }
    .gu-stat i { font-size: 1.5rem; width: 42px; height: 42px; border-radius: var(--radius-md); display: inline-flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .gu-stat .v { font-weight: var(--fw-bold); font-size: 1.25rem; line-height: 1.1; }
    .gu-stat .v small { font-size: .68rem; font-weight: var(--fw-medium); color: var(--text-tertiary); }
    .gu-stat .l { font-size: var(--fs-xs); color: var(--text-secondary); margin-top: 3px; }
    .gu-stat-info i { background: var(--info-bg); color: var(--info-text); }
    .gu-stat-success i { background: var(--success-bg); color: var(--success-text); }
    .gu-stat-danger i { background: var(--danger-bg); color: var(--danger-text); }
    .gu-stat-warning i { background: var(--warning-bg); color: var(--warning-text); }
    .gu-stat-secondary i { background: var(--surface-muted); color: var(--text-secondary); }

    /* Barre recherche + état */
    .gu-note-toolbar { display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; margin-bottom: 10px; }
    .gu-note-saveall { font-size: var(--fs-sm); font-weight: var(--fw-medium); padding: 5px 12px; border-radius: var(--radius-pill); display: inline-flex; align-items: center; gap: 6px; }
    .gu-note-saveall.ok { background: var(--success-bg); color: var(--success-text); }
    .gu-note-saveall.busy { background: var(--warning-bg); color: var(--warning-text); }

    /* Tableau des notes */
    #notesTable thead th { position: sticky; top: 0; z-index: 1; }
    #notesTable td .note { width: 74px; text-align: center; margin: 0 auto; padding: .3rem .35rem; }
    #notesTable .moyenneBadge { font-size: var(--fs-sm); min-width: 50px; display: inline-block; }
    .badge-soft-secondary { background: var(--surface-muted); color: var(--text-secondary); }
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
                                    <li class="breadcrumb-item active">Saisie</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-body">
                <div id="message" class="d-none"></div>

                <div class="card" id="ajoutNotes">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="gu-section-title lg"><span class="gu-ico-chip"><i class="bx bx-edit"></i></span> Saisie des notes</div>

                            <div id="session_info">
                                <div class="row" style="row-gap:14px;">
                                    <div class="col-12 col-md-3">
                                        <label class="form-label" for="anneeUniversitaire">Année universitaire</label>
                                        <select class="form-select" id="anneeUniversitaire" name="anneeUniversitaire"></select>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <label class="form-label" for="promotions">Classe</label>
                                        <select class="form-select" id="promotions" name="promotions">
                                            <option value="" disabled selected>Sélectionner une classe</option>
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label" for="modules">Module</label>
                                        <select class="form-select" id="modules">
                                            <option value="" disabled selected>Sélectionner un module</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div id="loadingSpinner" class="text-center my-3" style="display:none;">
                                <i class="bx bx-loader-alt bx-spin" style="font-size:1.6rem;color:var(--color-primary);"></i>
                            </div>

                            <div id="table_section" class="mt-3">
                                <div class="gu-empty"><i class="bx bx-list-ul"></i>Sélectionnez une classe puis un module pour saisir les notes.</div>
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
<script src="<?= ROOT ?>/assets/mon_js/note.js"></script>
<script>
    var infoFiliere = [];

    $(document).ready(async function () {
        var anneeSaved = sessionStorage.getItem('annee');
        var debut = 2012;
        var today = new Date();
        var annee_actuelle = today.getFullYear();
        if ((today.getMonth() + 1) <= 8) annee_actuelle -= 1;
        var courante = annee_actuelle + '-' + (annee_actuelle + 1);
        if (!anneeSaved) anneeSaved = courante;

        for (var annee = debut; annee <= annee_actuelle; annee++) {
            var valeur = annee + '-' + (annee + 1);
            $('#anneeUniversitaire').append('<option value="' + valeur + '"' + (valeur === anneeSaved ? ' selected' : '') + '>' + valeur + '</option>');
        }

        await classesAnneeUniversitaire(anneeSaved);
        try { infoFiliere = JSON.parse(sessionStorage.getItem('infoFiliere')); } catch (e) { infoFiliere = null; }
        var idSemestre = sessionStorage.getItem('semestre');
        if (infoFiliere) {
            modulesSemestre(idSemestre, infoFiliere);
            if ($("#modules option:selected").val()) loadEtudiants(true);
        }
    });

    function viderTable() {
        $("#table_section").html('<div class="gu-empty"><i class="bx bx-list-ul"></i>Sélectionnez un module pour saisir les notes.</div>');
    }

    $("#anneeUniversitaire").change(function () {
        classesAnneeUniversitaire($("#anneeUniversitaire option:selected").val());
        $("#modules").empty().append('<option value="" disabled selected>Sélectionner un module</option>');
        viderTable();
        sessionStorage.setItem("annee", $("#anneeUniversitaire option:selected").val());
    });

    $("#promotions").change(async function () {
        sessionStorage.setItem("classe", $("#promotions option:selected").val());
        sessionStorage.setItem("semestre", $("#promotions option:selected").data('semestre'));
        sessionStorage.setItem("filiere", $("#promotions option:selected").data('filiere'));
        infoFiliere = await infosFiliere($("#promotions option:selected").data("filiere"), "all");
        var idSemestre = $("#promotions option:selected").data("semestre");
        sessionStorage.setItem("infoFiliere", JSON.stringify(infoFiliere));
        modulesSemestre(idSemestre, infoFiliere);
        viderTable();
    });

    $("#modules").change(function () {
        if ($("#modules").val()) loadEtudiants();
        else viderTable();
        sessionStorage.setItem("module", $("#modules option:selected").val());
    });
</script>
