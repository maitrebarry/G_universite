<?php $this->view("Partials/header") ?>

<style>
    #reinscCard .form-label { font-size: var(--fs-sm); font-weight: var(--fw-medium); color: var(--text-secondary); margin-bottom: 4px; }
    .gu-action-bar { position: sticky; bottom: 0; background: var(--surface, #fff); border-top: 1px solid #e5e7eb; padding: 12px 4px; display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-top: 8px; }
    .gu-action-bar .hint { font-size: var(--fs-sm); color: var(--text-secondary); }
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
                            <h5 class="content-header-title float-left pr-1 mb-0">Étudiants</h5>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb p-0 mb-0">
                                    <li class="breadcrumb-item"><a href="<?= ROOT ?>/Homes"><i class="bx bx-home-alt"></i></a></li>
                                    <li class="breadcrumb-item">Gestion Étudiants</li>
                                    <li class="breadcrumb-item active">Réinscription</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-body">
                <div class="card" id="reinscCard">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="gu-section-title lg"><span class="gu-ico-chip"><i class="bx bx-user-plus"></i></span> Réinscription des étudiants</div>

                            <div class="row" style="row-gap:14px;">
                                <div class="col-6 col-md-3">
                                    <label class="form-label" for="anneeUniversitaire">Année universitaire</label>
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
                                    <label class="form-label" for="promotions">Classe actuelle</label>
                                    <select class="form-select" id="promotions" name="promotions">
                                        <option value="" disabled selected>Sélectionner une classe</option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-4">
                                    <label class="form-label" for="newPromotions"><i class="bx bx-right-arrow-alt"></i> Réinscrire vers la classe</label>
                                    <select class="form-select" id="newPromotions" name="newPromotions" disabled>
                                        <option value="" disabled selected>Choisir d'abord la classe actuelle</option>
                                    </select>
                                </div>
                            </div>

                            <div id="table_section" class="mt-3">
                                <div class="gu-empty"><i class="bx bx-user-plus"></i>Sélectionnez une classe pour afficher les étudiants à réinscrire.</div>
                            </div>

                            <div class="gu-action-bar">
                                <span class="hint" id="cibleHint">Choisissez la classe cible, cochez les étudiants, puis réinscrivez.</span>
                                <button type="button" class="btn btn-primary" id="btnReinscrire"><i class="bx bx-check-double"></i> Réinscrire la sélection</button>
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
<?php
// Toutes les promotions exposées au JS pour filtrer la classe cible (même filière + niveau supérieur).
$promosJs = array_map(function ($p) {
    return [
        'id' => (int) $p->id_promotion,
        'fil' => (int) $p->id_filiere,
        'niv' => (int) preg_replace('/\D/', '', $p->sigle_semestre),
        'an' => (int) substr($p->annee_universitaire, 0, 4),
        'label' => $p->sigle_filiere . ' · ' . $p->sigle_semestre . ' (' . $p->annee_universitaire . ')',
    ];
}, $promotions ?? []);
?>
<script src="<?= ROOT ?>/assets/mon_js/reinscription.js"></script>
<script>
    window.PROMOS = <?= json_encode($promosJs) ?>;
    function videTable(msg) {
        $("#table_section").html('<div class="gu-empty"><i class="bx bx-user-plus"></i>' + (msg || 'Sélectionnez une classe.') + '</div>');
    }
    function majSelection() {
        $("#selCount").text($("#reinscriptionTable .chk-etudiant:checked").length + " sélectionné(s)");
    }

    function chargerListe() {
        var p = $("#promotions option:selected");
        if (!p.val()) { videTable(); return; }
        $("#table_section").html('<div class="gu-empty"><i class="bx bx-loader-alt bx-spin"></i>Chargement…</div>');
        $.post(ROOT_REINSCRIPTIONS + "/get_etudiants", { annee_universitaire: $("#anneeUniversitaire").val(), id_promotion: p.val() }, function (html) {
            $("#table_section").html(html);
            marquerCible();
            majSelection();
        });
    }

    // Marque l'état cible (respecte la validation : un non-validé reste non sélectionnable)
    function marquerCible() {
        var t = $("#newPromotions option:selected");
        if (!$("#reinscriptionTable").length) return;
        if (!t.val()) {
            $("#reinscriptionTable tbody tr").each(function () {
                var valide = String($(this).data("valide")) === "1";
                $(this).find(".etat-cell").html('<span class="badge badge-soft-secondary">—</span>');
                $(this).find(".chk-etudiant").prop("disabled", !valide);
            });
            majSelection();
            return;
        }
        $.post(ROOT_REINSCRIPTIONS + "/deja_inscrits", { id_promotion: t.val() }, function (ids) {
            var set = {}; (ids || []).forEach(function (id) { set[id] = 1; });
            $("#reinscriptionTable tbody tr").each(function () {
                var $tr = $(this), id = $tr.data("id"), valide = String($tr.data("valide")) === "1";
                var cell = $tr.find(".etat-cell"), chk = $tr.find(".chk-etudiant");
                if (!valide) { cell.html('<span class="badge badge-soft-secondary">Non validé</span>'); chk.prop("checked", false).prop("disabled", true); return; }
                if (set[id]) { cell.html('<span class="badge badge-soft-success">Déjà inscrit</span>'); chk.prop("checked", false).prop("disabled", true); }
                else { cell.html('<span class="badge badge-soft-warning">À réinscrire</span>'); chk.prop("disabled", false); }
            });
            majSelection();
        }, "json");
    }

    $(document).ready(function () {
        classesAnneeUniversitaire($("#anneeUniversitaire option:selected").val());
        videTable();
    });
    $("#anneeUniversitaire").change(function () {
        classesAnneeUniversitaire($(this).val());
        videTable();
    });
    // Classe cible = même filière + niveau supérieur uniquement (pas de retour en arrière)
    function majCibles() {
        var cur = $("#promotions option:selected").val();
        var c = window.PROMOS.filter(function (p) { return String(p.id) === String(cur); })[0];
        var sel = $("#newPromotions");
        sel.empty().append('<option value="" disabled selected>Sélectionner la classe cible</option>');
        if (!c) { sel.prop("disabled", true); return; }
        var cibles = window.PROMOS.filter(function (p) { return p.fil === c.fil && p.niv > c.niv && p.an >= c.an; });
        if (!cibles.length) { sel.append('<option value="" disabled>Aucune classe de niveau supérieur</option>'); }
        else { cibles.forEach(function (p) { sel.append('<option value="' + p.id + '">' + p.label + '</option>'); }); }
        sel.prop("disabled", false);
    }
    $("#promotions").change(function () { majCibles(); chargerListe(); });
    $("#newPromotions").change(marquerCible);

    $(document).on("change", "#select-all", function () {
        $("#reinscriptionTable .chk-etudiant:not(:disabled)").prop("checked", this.checked);
        majSelection();
    });
    $(document).on("change", ".chk-etudiant", majSelection);
    $(document).on("input", "#reinscSearch", function () {
        var q = this.value.toLowerCase().trim();
        $("#reinscriptionTable tbody tr").each(function () {
            $(this).toggle(!q || ("" + $(this).data("nom")).indexOf(q) > -1);
        });
    });

    $("#btnReinscrire").click(function () {
        var target = $("#newPromotions option:selected");
        if (!target.val()) { Swal.fire("Classe cible requise", "Choisissez la classe dans laquelle réinscrire.", "warning"); return; }
        var ids = $("#reinscriptionTable .chk-etudiant:checked").map(function () { return this.value; }).get();
        if (!ids.length) { Swal.fire("Aucune sélection", "Cochez au moins un étudiant à réinscrire.", "warning"); return; }
        Swal.fire({
            title: "Confirmer la réinscription",
            html: "Réinscrire <b>" + ids.length + "</b> étudiant(s) dans <b>" + target.text().trim() + "</b> ?",
            icon: "question", showCancelButton: true, confirmButtonText: "Réinscrire", cancelButtonText: "Annuler"
        }).then(function (r) {
            if (!(r && (r.isConfirmed || (r.value && !r.dismiss)))) return;
            $("#loader").removeClass("d-none").addClass("d-flex");
            $.post(ROOT_REINSCRIPTIONS + "/reinscrire", { etudiants: ids, id_new_promotion: target.val(), id_old_promotion: $("#promotions").val() }, function (res) {
                $("#loader").removeClass("d-flex").addClass("d-none");
                if (res && res.ok) {
                    var msg = res.faits + " réinscrit(s)";
                    if (res.deja) msg += " · " + res.deja + " déjà inscrit(s)";
                    if (res.refuses) msg += " · " + res.refuses + " refusé(s) (non validé)";
                    Swal.fire("Réinscription effectuée", msg, "success");
                    marquerCible();
                } else {
                    Swal.fire("Erreur", (res && res.error) || "Échec de la réinscription.", "error");
                }
            }, "json").fail(function () {
                $("#loader").removeClass("d-flex").addClass("d-none");
                Swal.fire("Erreur", "Problème de communication avec le serveur.", "error");
            });
        });
    });
</script>
