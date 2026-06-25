<?php
$ensActuel = (isset($infosEdt->enseignants) && !empty($infosEdt->enseignants)) ? $infosEdt->enseignants[0] : null;
$salleActuelle = $ensActuel->id_salle ?? null;
$enseignantActuel = $ensActuel->id_enseignant ?? null;
?>
<?php $this->view("Partials/header") ?>

<style>
    #edtForm .gu-section-title { margin-bottom: 14px; }
    #edtForm label.form-label { font-size: var(--fs-sm); font-weight: var(--fw-medium); color: var(--text-secondary); margin-bottom: 4px; }
    #table-extended-chechbox th, #table-extended-chechbox td { text-align: center; vertical-align: middle; }
    #table-extended-chechbox input { text-align: center; }
    #model-row, #model-column { cursor: pointer; border: 2px solid var(--border) !important; border-radius: var(--radius-md); transition: var(--transition); }
    #model-row.border-primary, #model-column.border-primary { border-color: var(--brand-600) !important; box-shadow: var(--shadow-sm); }
</style>

<body class="vertical-layout vertical-menu-modern boxicon-layout no-card-shadow 2-columns navbar-sticky footer-static"
    data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">

    <?php $this->view("Partials/navbar") ?>
    <?php $this->view("Partials/seibar") ?>

    <div class="app-content content">
        <div id="loader" class="w-100 position-absolute d-none justify-content-center align-items-center" style="height:100vh;z-index:100">
            <div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div>
        </div>

        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-12 mb-2 mt-1">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h5 class="content-header-title float-left pr-1 mb-0">Emploi du temps</h5>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb p-0 mb-0">
                                    <li class="breadcrumb-item"><a href="<?= ROOT ?>/Homes"><i class="bx bx-home-alt"></i></a></li>
                                    <li class="breadcrumb-item"><a href="<?= ROOT ?>/Emploi_du_temps">Gestion EDT</a></li>
                                    <li class="breadcrumb-item active">Modification</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-body">
                <div id="message"></div>

                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="gu-section-title lg"><span class="gu-ico-chip"><i class="bx bx-calendar-edit"></i></span> Modification d'emploi du temps</div>
                            <form method="POST" novalidate id="edtForm">
                                <div class="row" style="row-gap:14px;">
                                    <div class="col-6 col-lg-3">
                                        <label class="form-label" for="filiere">Filière</label>
                                        <select class="form-select" id="filiere">
                                            <option value="0" disabled>Sélectionner une filière</option>
                                            <?php foreach ($filieres as $filiere): ?>
                                                <option value="<?php echo $filiere->id_filiere ?>" <?= ($infosEdt->promotion->id_filiere == $filiere->id_filiere) ? 'selected' : '' ?>>
                                                    <?php echo strtoupper($filiere->sigle_filiere) ?>
                                                </option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>
                                    <div class="col-6 col-lg-3">
                                        <label class="form-label" for="promotions">Classe</label>
                                        <select class="form-select" id="promotions" data-id="<?php echo $infosEdt->promotion->id_promotion ?>">
                                            <option value="" disabled selected>Sélectionner une classe</option>
                                        </select>
                                    </div>
                                    <div class="col-6 col-lg-3">
                                        <label class="form-label" for="modules">Module</label>
                                        <select class="form-select champ" id="modules" name="modules" data-id="<?php echo $infosEdt->module->id_ue_module ?>">
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
                                    <div style="width:88px;"><label class="form-label text-center d-block">CM</label><input type="number" class="form-control text-center cm" disabled></div>
                                    <div style="width:88px;"><label class="form-label text-center d-block">TD</label><input type="number" class="form-control text-center td" disabled></div>
                                    <div style="width:88px;"><label class="form-label text-center d-block">TP</label><input type="number" class="form-control text-center tp" disabled></div>
                                    <div style="width:88px;"><label class="form-label text-center d-block">TPE</label><input type="number" class="form-control text-center tpe" disabled></div>
                                </div>

                                <div class="gu-table-wrap">
                                    <table id="table-extended-chechbox" class="gu-table" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Horaire</th>
                                                <?php foreach ($jours as $jour): ?>
                                                    <th class="jour text-center" data-id="<?php echo $jour->id_jour ?>"><?php echo strtoupper($jour->nom_jour) ?></th>
                                                <?php endforeach ?>
                                            </tr>
                                        </thead>
                                        <tbody id="corpsEdt">
                                            <?php foreach ($horairesEdt as $horaire): ?>
                                                <tr>
                                                    <td style="min-width:200px;">
                                                        <div class="d-flex" style="gap:6px;">
                                                            <input type="time" class="form-control horaireDebut" value="<?php echo substr($horaire->heure_debut, 0, 5) ?>">
                                                            <input type="time" class="form-control horaireFin" value="<?php echo substr($horaire->heure_fin, 0, 5) ?>">
                                                        </div>
                                                    </td>
                                                    <?php foreach ($horaire->taches as $tache): $t = strtolower($tache->type_tache); ?>
                                                        <td>
                                                            <select class="form-select tache">
                                                                <option value="x" <?= $t == 'x' ? 'selected' : '' ?>>X</option>
                                                                <option value="cm" <?= $t == 'cm' ? 'selected' : '' ?>>CM</option>
                                                                <option value="td" <?= $t == 'td' ? 'selected' : '' ?>>TD</option>
                                                                <option value="tp" <?= $t == 'tp' ? 'selected' : '' ?>>TP</option>
                                                                <option value="tpe" <?= $t == 'tpe' ? 'selected' : '' ?>>TPE</option>
                                                                <option value="examen" <?= $t == 'examen' ? 'selected' : '' ?>>EXAMEN</option>
                                                            </select>
                                                        </td>
                                                    <?php endforeach ?>
                                                </tr>
                                            <?php endforeach ?>
                                        </tbody>
                                    </table>
                                </div>

                                <hr style="border:0;border-top:1px solid var(--border);margin:18px 0 14px;">
                                <div class="gu-section-title"><span class="gu-ico-chip warning"><i class="bx bx-user-voice"></i></span> Affectation</div>
                                <div class="row" style="row-gap:14px;">
                                    <div class="col-12 col-sm-4">
                                        <label class="form-label" for="enseignants">Enseignant</label>
                                        <select class="form-select champ" id="enseignants" name="enseignants" data-smart>
                                            <option value="" disabled <?= $enseignantActuel ? '' : 'selected' ?>>Sélectionner un enseignant</option>
                                            <?php foreach ($enseignants as $enseignant): ?>
                                                <option value="<?php echo $enseignant->enseignant_id ?>" <?= ($enseignantActuel == $enseignant->enseignant_id) ? 'selected' : '' ?>>
                                                    <?php echo ucwords(strtolower($enseignant->enseignant_nom . ' ' . $enseignant->enseignant_prenom)) . ' (' . $enseignant->enseignant_telephone . ')' ?>
                                                </option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>
                                    <div class="col-12 col-sm-4">
                                        <label class="form-label" for="salles">Salle de cours</label>
                                        <select class="form-select champ" name="salles" id="salles" data-smart>
                                            <option value="" disabled <?= $salleActuelle ? '' : 'selected' ?>>Sélectionner une salle</option>
                                            <?php foreach ($salles as $salle): ?>
                                                <option value="<?php echo $salle->id_salle ?>" <?= ($salleActuelle == $salle->id_salle) ? 'selected' : '' ?>>
                                                    <?php echo strtoupper($salle->nom_salle) . ' (' . $salle->capacite_salle . ')' ?>
                                                </option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>
                                    <div class="col-12 col-sm-4">
                                        <label class="form-label" for="dateDebut">Date de début</label>
                                        <input type="date" class="form-control" name="dateDebut" id="dateDebut" value="<?php echo date_format(new DateTime($infosEdt->edt->date_debut), 'Y-m-d') ?>">
                                    </div>
                                </div>

                                <div class="gu-edt-actions">
                                    <div id="conflitsBox" class="gu-edt-actions__msg"></div>
                                    <a href="<?= ROOT ?>/Emploi_du_temps/apercu_edt/<?= $infosEdt->edt->id_edt ?>" class="btn btn-ghost"><i class="bx bx-arrow-back"></i> Annuler</a>
                                    <button type="submit" class="btn btn-gradient" data-id="<?php echo $infosEdt->edt->id_edt ?>" id="valider"><i class="bx bx-save"></i> Enregistrer les modifications</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade text-left" id="menuConfig" tabindex="-1" role="dialog" aria-labelledby="myModalLabel160" aria-hidden="true">
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

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <?php $this->view("Partials/foot") ?>
    <?php $this->view("Partials/footer") ?>
</body>

</html>
<script src="<?= ROOT ?>/assets/mon_js/edt.js"></script>
<script src="<?= ROOT ?>/assets/mon_js/contrainte_date_edt.js"></script>
<script src="<?= ROOT ?>/assets/mon_js/gu-smart-select.js"></script>
<script>
    var infoFiliere = [];

    $("#filiere").change(async function () {
        infoFiliere = await infosFiliere($(this).val());
        promotionsFiliere(infoFiliere);
        idSemestre = $("#promotions option:selected").data("id");
        modulesSemestre(idSemestre, infoFiliere);
        infoModule($("#modules").val(), infoFiliere);
    });

    $("#promotions").change(function () {
        idSemestre = $("#promotions option:selected").data("id");
        modulesSemestre(idSemestre, infoFiliere);
        infoModule($("#modules").val(), infoFiliere);
    });

    $("#modules").change(function () {
        infoModule($(this).val(), infoFiliere);
        getDefaultEnseignantAndSalleModule($("#filiere").val(), $(this).val());
    });

    $(document).ready(async function () {
        $('#edtForm').submit(function (event) { event.preventDefault(); submitEditer(); });

        infoFiliere = await infosFiliere($("#filiere").val());
        var idPromotion = $("#promotions").data('id');
        promotionsFiliere(infoFiliere, idPromotion);
        idSemestre = $("#promotions option:selected").data("id");
        var idModule = $("#modules").data("id");
        modulesSemestre(idSemestre, infoFiliere, idModule);
        infoModule($("#modules").val(), infoFiliere, false);

        if (window.guSmartSelectAll) guSmartSelectAll();
    });

    // Modèle horizontal / vertical / type
    $('#model-row').click(function () {
        $('#model-column').removeClass('border-primary'); $(this).addClass("border-primary");
        genererEdt(calculerHeuresModuleEdt(), $(this).data('model'), parseInt($('input[name="type"]:checked').val(), 10));
    });
    $('#model-column').click(function () {
        $('#model-row').removeClass('border-primary'); $(this).addClass("border-primary");
        genererEdt(calculerHeuresModuleEdt(), $(this).data('model'), parseInt($('input[name="type"]:checked').val(), 10));
    });
    $('.type').click(function () {
        var model = $('#model-row').hasClass("border-primary") ? $('#model-row').data('model') : $('#model-column').data('model');
        genererEdt(calculerHeuresModuleEdt(), model, parseInt($('input[name="type"]:checked').val(), 10));
    });

    // Ajouter / supprimer une ligne
    document.getElementById('add-row').addEventListener('click', function () {
        $('#table-extended-chechbox tbody tr').each(function (index) {
            if (index == $('#table-extended-chechbox tbody tr').length - 1) { horaireDebut = $(this).find('.horaireFin').val(); }
        });
        heure = horaireDebut.split(':');
        horaireFin = (parseInt(heure[0], 10) + 2) + ':' + heure[1];
        genererCoursEdt(typeEdt[parseInt($('input[name="type"]:checked').val(), 10)]);
        addHeure(horaireDebut, horaireFin, coursJour);
    });
    document.getElementById('remove-row').addEventListener('click', function () { removeHeure(); });

    // ============ Conflits (salle confirmable, enseignant bloquant) ============
    function conflitListe(items) {
        return '<ul style="margin:.35rem 0 0;padding-left:1.1rem;">' + items.map(function (x) { return '<li>' + String(x).replace(/</g, "&lt;") + '</li>'; }).join('') + '</ul>';
    }
    function renderConflits(box, res) {
        res = res || {}; var salle = res.salle || [], ens = res.enseignant || [], html = '';
        if (ens.length) html += '<div class="alert alert-soft-danger"><b><i class="bx bx-error-circle"></i> ' + ens.length + ' conflit(s) d\'enseignant — bloquant</b>' + conflitListe(ens) + '</div>';
        if (salle.length) html += '<div class="alert alert-soft-warning"><b><i class="bx bx-error"></i> ' + salle.length + ' conflit(s) de salle — confirmation à l\'enregistrement</b>' + conflitListe(salle) + '</div>';
        if (!html) html = '<div class="alert alert-soft-success d-flex align-items-center gap-2"><i class="bx bx-check-circle"></i> Aucun conflit sur ces créneaux.</div>';
        box.innerHTML = html;
    }
    function conflitModal(opts) {
        var liste = '<ul style="text-align:left;margin:.4rem 0;padding-left:1.2rem;">' + opts.items.map(function (x) { return '<li>' + String(x).replace(/</g, "&lt;") + '</li>'; }).join('') + '</ul>';
        var html = liste + '<p style="margin-top:.6rem;">' + opts.message + '</p>';
        if (window.Swal) {
            return Swal.fire({
                icon: opts.confirm ? 'warning' : 'error', title: opts.title, html: html,
                showCancelButton: !!opts.confirm, confirmButtonText: opts.confirm ? 'Modifier quand même' : 'Compris',
                cancelButtonText: 'Annuler', confirmButtonColor: opts.confirm ? '#d97706' : '#1f4f9c', cancelButtonColor: '#64748b', reverseButtons: true
            }).then(function (r) { return !!(r && (r.isConfirmed || (r.value && !r.dismiss))); });
        }
        if (opts.confirm) return Promise.resolve(window.confirm(opts.title + "\n\n" + opts.items.join("\n") + "\n\n" + opts.message));
        window.alert(opts.title + "\n\n" + opts.items.join("\n") + "\n\n" + opts.message);
        return Promise.resolve(false);
    }
    function collecterConflits() {
        var cells = [];
        $("#corpsEdt tr").each(function () {
            var d = $(this).find(".horaireDebut").val(), f = $(this).find(".horaireFin").val();
            if (!d || !f) return;
            $(this).find("td").each(function (index) {
                if (index === 0) return;
                var t = ($(this).find(".tache").val() || "").toLowerCase();
                if (!t || t === "x") return;
                var idJour = $("#table-extended-chechbox thead th").eq(index).data("id");
                if (idJour) cells.push({ jour: idJour, debut: d, fin: f });
            });
        });
        var salles = [], ens = [], s = $("#salles").val(), e = $("#enseignants").val();
        if (s) salles.push(s); if (e) ens.push(e);
        return { cells: cells, salles: salles, enseignants: ens };
    }
    function envoyer(force) {
        window.__edtForceSalle = !!force;
        ajouterEdt(window.APP_ROUTE("Emploi_du_temps/editer_edt"), "editer_edt");
        window.__edtForceSalle = false;
    }
    function submitEditer() {
        var data = collecterConflits();
        var exclude = $("#valider").data("id") || "";
        if (!data.cells.length || (!data.salles.length && !data.enseignants.length)) { envoyer(false); return; }
        $.ajax({
            method: "POST", url: window.ROOT_EDT + "/conflits", dataType: "json",
            data: { cells: data.cells, salles: data.salles, enseignants: data.enseignants, excludeEdtId: exclude },
            success: function (res) {
                res = res || {}; var salle = res.salle || [], ens = res.enseignant || [];
                if (ens.length) { conflitModal({ confirm: false, title: "Conflit d'enseignant", items: ens, message: "Un enseignant ne peut pas être à deux endroits au même moment." }); return; }
                if (salle.length) { conflitModal({ confirm: true, title: "Salle déjà occupée", items: salle, message: "Cette salle est déjà utilisée sur ce créneau. Enregistrer les modifications malgré ce conflit ?" }).then(function (ok) { if (ok) envoyer(true); }); return; }
                envoyer(false);
            },
            error: function () { envoyer(false); }
        });
    }
    var _conflitTimer = null;
    function verifierConflits() {
        var box = document.getElementById("conflitsBox"); if (!box) return;
        var data = collecterConflits();
        if (!data.cells.length || (!data.salles.length && !data.enseignants.length)) { box.innerHTML = ""; return; }
        box.innerHTML = '<div class="alert alert-soft-info d-flex align-items-center gap-2"><i class="bx bx-loader-alt bx-spin"></i> Vérification des disponibilités…</div>';
        $.ajax({
            method: "POST", url: window.ROOT_EDT + "/conflits", dataType: "json",
            data: { cells: data.cells, salles: data.salles, enseignants: data.enseignants, excludeEdtId: $("#valider").data("id") || "" },
            success: function (res) { renderConflits(box, res); }, error: function () { box.innerHTML = ""; }
        });
    }
    function planifierVerifConflits() { clearTimeout(_conflitTimer); _conflitTimer = setTimeout(verifierConflits, 450); }
    $(document).on("change", "#corpsEdt .tache", planifierVerifConflits);
    $(document).on("input change", "#corpsEdt .horaireDebut, #corpsEdt .horaireFin", planifierVerifConflits);
    $("#salles, #enseignants").on("change", planifierVerifConflits);
    $("#add-row, #remove-row, #model-row, #model-column").on("click", planifierVerifConflits);
    $(".type").on("click", planifierVerifConflits);
</script>
