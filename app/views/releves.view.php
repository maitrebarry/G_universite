<?php $this->view("Partials/header") ?>

<style>
    #releveCenter .form-label { font-size: var(--fs-sm); font-weight: var(--fw-medium); color: var(--text-secondary); margin-bottom: 4px; }
    /* Modale d'aperçu (custom, indépendante de Bootstrap pour une fermeture fiable) */
    #relevePreviewModal { position: fixed; inset: 0; z-index: 1080; display: none; align-items: center; justify-content: center; }
    #relevePreviewModal.open { display: flex; }
    #relevePreviewModal .gu-modal-backdrop { position: absolute; inset: 0; background: rgba(15, 23, 42, .55); }
    #relevePreviewModal .gu-modal-dialog { position: relative; background: #fff; border-radius: 10px; width: min(900px, 94vw); max-height: 92vh; display: flex; flex-direction: column; box-shadow: 0 20px 60px rgba(0, 0, 0, .3); overflow: hidden; }
    #relevePreviewModal .gu-modal-head, #relevePreviewModal .gu-modal-foot { padding: 12px 16px; display: flex; align-items: center; flex: 0 0 auto; }
    #relevePreviewModal .gu-modal-head { justify-content: space-between; border-bottom: 1px solid #e5e7eb; }
    #relevePreviewModal .gu-modal-foot { justify-content: flex-end; gap: 8px; border-top: 1px solid #e5e7eb; }
    #relevePreviewModal .gu-modal-body { padding: 16px; overflow: auto; background: #fff; flex: 1 1 auto; }

    /* Overlay « le relevé se dessine en direct » */
    #printOverlay { position: fixed; inset: 0; z-index: 2000; display: none; align-items: center; justify-content: center; }
    #printOverlay.open { display: flex; }
    #printOverlay .po-backdrop { position: absolute; inset: 0; background: rgba(10, 20, 40, .74); }
    #printOverlay .po-card { position: relative; width: min(580px, 95vw); background: #fff; border-radius: 14px; box-shadow: 0 24px 70px rgba(0, 0, 0, .45); padding: 18px; }
    #printOverlay .po-head { display: flex; gap: 12px; align-items: center; margin-bottom: 12px; }
    #printOverlay .po-ico { width: 44px; height: 44px; border-radius: 11px; background: #1f4f9c; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 23px; flex: 0 0 auto; }
    #printOverlay .po-ico i { animation: poWiggle 1.1s ease-in-out infinite; }
    @keyframes poWiggle { 0%, 100% { transform: rotate(-14deg) } 50% { transform: rotate(14deg) } }
    #printOverlay .po-title { font-weight: 700; color: #14346b; font-size: 16px; line-height: 1.2; }
    #printOverlay .po-sub { font-size: 12.5px; color: #5a6b86; }
    #printOverlay .po-paper { height: 330px; overflow: hidden; border: 1px solid #e3e8f0; border-radius: 10px; background: #eef1f6; display: flex; justify-content: center; align-items: flex-start; padding: 10px; }
    #printStageWrap { position: relative; width: 794px; background: #fff; box-shadow: 0 4px 16px rgba(0, 0, 0, .12); transform-origin: top center; }
    #printShade { position: absolute; left: 0; right: 0; top: 0; bottom: 0; background: #fff; box-shadow: inset 0 7px 10px -7px rgba(31, 79, 156, .3); }
    #printScanLine { position: absolute; left: -1%; right: -1%; top: 0; height: 5px; border-radius: 5px; background: linear-gradient(90deg, transparent, #1f4f9c 28%, #9ec5ff 50%, #1f4f9c 72%, transparent); box-shadow: 0 0 18px 4px rgba(70, 130, 230, .85); opacity: 0; }
    #printOverlay.po-drawing #printShade { animation: poShade var(--draw-ms, 600ms) ease-out forwards; }
    #printOverlay.po-drawing #printScanLine { animation: poScan var(--draw-ms, 600ms) ease-out forwards; }
    @keyframes poShade { from { top: 0 } to { top: 100% } }
    @keyframes poScan { 0% { top: 0; opacity: 1 } 88% { opacity: 1 } 100% { top: 100%; opacity: 0 } }
    #printOverlay .po-progress { height: 9px; background: #e7ecf5; border-radius: 6px; overflow: hidden; margin-top: 14px; }
    #printOverlay .po-bar { height: 100%; width: 0; background: linear-gradient(90deg, #1f4f9c, #4f86d6); transition: width .35s ease; }
    #printOverlay .po-count { text-align: center; font-size: 12px; color: #5a6b86; margin-top: 7px; }
    #printOverlay.done .po-ico { background: #15803d; }
    #printOverlay.done .po-ico i { animation: none; }
    #printOverlay .po-actions { text-align: center; margin-top: 13px; }
    #printOverlay .po-cancel { border: 1px solid #d6deea; background: #fff; color: #b91c1c; border-radius: 8px; padding: 7px 20px; font-size: 13px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 5px; transition: all .15s ease; }
    #printOverlay .po-cancel:hover { background: #fdecec; border-color: #f0b4b4; }
    #printOverlay.done .po-actions { display: none; }
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
                            <h5 class="content-header-title float-left pr-1 mb-0">Notes</h5>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb p-0 mb-0">
                                    <li class="breadcrumb-item"><a href="<?= ROOT ?>/Homes"><i class="bx bx-home-alt"></i></a></li>
                                    <li class="breadcrumb-item">Notes</li>
                                    <li class="breadcrumb-item active">Relevés de notes</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-body">
                <div class="card" id="releveCenter">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="gu-section-title lg"><span class="gu-ico-chip"><i class="bx bx-file"></i></span> Centre des relevés de notes</div>

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
                                <div class="col-6 col-md-4">
                                    <label class="form-label" for="promotions">Classe</label>
                                    <select class="form-select" id="promotions" name="promotions">
                                        <option value="" disabled selected>Sélectionner une classe</option>
                                    </select>
                                </div>
                            </div>

                            <div id="rosterSection" class="mt-3">
                                <div class="gu-empty"><i class="bx bx-file"></i>Choisissez une classe pour afficher les étudiants et générer leurs relevés.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modale d'aperçu du relevé (custom — fermeture fiable, sans Bootstrap) -->
    <div id="relevePreviewModal" aria-hidden="true">
        <div class="gu-modal-backdrop" data-close-releve></div>
        <div class="gu-modal-dialog" role="dialog" aria-modal="true">
            <div class="gu-modal-head">
                <h5 style="margin:0;"><i class="bx bx-file"></i> Aperçu du relevé</h5>
                <button type="button" class="gu-icon-act" data-close-releve aria-label="Fermer"><i class="bx bx-x"></i></button>
            </div>
            <div class="gu-modal-body" id="relevePreviewBody"></div>
            <div class="gu-modal-foot">
                <button type="button" class="btn btn-ghost" data-close-releve>Fermer</button>
                <button type="button" class="btn btn-primary" id="downloadReleve"><i class="bx bx-download"></i> Télécharger le PDF</button>
            </div>
        </div>
    </div>

    <!-- Overlay « le relevé se dessine en direct » -->
    <div id="printOverlay" aria-hidden="true">
        <div class="po-backdrop"></div>
        <div class="po-card">
            <div class="po-head">
                <span class="po-ico"><i class="bx bx-pen" id="poIcon"></i></span>
                <div>
                    <div class="po-title" id="poTitle">Génération des relevés…</div>
                    <div class="po-sub" id="poStatus">Préparation…</div>
                </div>
            </div>
            <div class="po-paper">
                <div id="printStageWrap">
                    <div id="printStageReleve"></div>
                    <div id="printShade"></div>
                    <div id="printScanLine"></div>
                </div>
            </div>
            <div class="po-progress"><div class="po-bar" id="poBar"></div></div>
            <div class="po-count" id="poCount">0 / 0</div>
            <div class="po-actions"><button type="button" class="po-cancel" id="poCancel"><i class="bx bx-x"></i> Annuler</button></div>
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
    function rosterVide(msg) {
        $("#rosterSection").html('<div class="gu-empty"><i class="bx bx-file"></i>' + (msg || 'Choisissez une classe pour afficher les étudiants.') + '</div>');
    }

    $(document).ready(function () {
        classesAnneeUniversitaire($("#anneeUniversitaire option:selected").val());
        rosterVide();
    });

    $("#anneeUniversitaire").change(function () {
        classesAnneeUniversitaire($("#anneeUniversitaire option:selected").val());
        rosterVide();
    });

    $("#promotions").change(function () {
        var p = $("#promotions option:selected");
        if (!p.val()) { rosterVide(); return; }
        $("#rosterSection").html('<div class="gu-empty"><i class="bx bx-loader-alt bx-spin"></i>Chargement…</div>');
        $.post(ROOT_NOTES + "/get_releves_roster", { idPromotion: p.val(), idSemestre: p.data("semestre") }, function (r) {
            $("#rosterSection").html(r);
        });
    });

    function fetchReleve(idEtudiant, cb) {
        var p = $("#promotions option:selected");
        $.post(ROOT_NOTES + "/get_releve_etudiant", { idPromotion: p.val(), idSemestre: p.data("semestre"), idEtudiant: idEtudiant }, cb);
    }

    // Modale custom (ouverture/fermeture sans dépendance Bootstrap)
    function openReleveModal() { document.getElementById("relevePreviewModal").classList.add("open"); }
    function closeReleveModal() { document.getElementById("relevePreviewModal").classList.remove("open"); }
    $(document).on("click", "[data-close-releve]", closeReleveModal);
    $(document).on("keydown", function (e) { if (e.key === "Escape") closeReleveModal(); });

    // Aperçu écran — on garde la CHAÎNE HTML pour le PDF (capture fiable)
    $(document).on("click", ".btn-apercu-releve", function () {
        fetchReleve($(this).data("id"), function (html) {
            window.__releveHTML = html;
            document.getElementById("relevePreviewBody").innerHTML = html;
            openReleveModal();
        });
    });
    $("#downloadReleve").click(function () {
        if (window.__releveHTML) { closeReleveModal(); genererPdfReleves(splitReleves(window.__releveHTML), "releve"); }
    });

    // PDF individuel direct
    $(document).on("click", ".btn-pdf-releve", function () {
        var nom = $(this).data("nom");
        fetchReleve($(this).data("id"), function (html) {
            genererPdfReleves(splitReleves(html), "releve-" + nom);
        });
    });

    // Tout imprimer (classe)
    $(document).on("click", "#printAllReleves", function () {
        var p = $("#promotions option:selected");
        $("#loader").removeClass("d-none").addClass("d-flex");
        $.post(ROOT_NOTES + "/get_releves_notes", { idPromotion: p.val(), idSemestre: p.data("semestre") }, function (html) {
            $("#loader").removeClass("d-flex").addClass("d-none");
            genererPdfReleves(splitReleves(html), "releves-" + p.text());
        });
    });

    // ===== Génération PDF avec animation « le relevé se dessine en direct » =====
    function splitReleves(bulkHTML) {
        var tmp = document.createElement("div");
        tmp.innerHTML = bulkHTML;
        var styleEl = tmp.querySelector("style");
        var styleHTML = styleEl ? styleEl.outerHTML : "";
        return Array.prototype.map.call(tmp.querySelectorAll(".releve"), function (r) {
            return { html: styleHTML + r.outerHTML, nom: extraitNom(r) };
        });
    }
    function extraitNom(releveEl) {
        var m = (releveEl.textContent || "").match(/Nom\s*:\s*(.+?)\s+Pr[ée]nom/i);
        return m ? m[1].trim() : "";
    }
    function sleep(ms) { return new Promise(function (r) { setTimeout(r, ms); }); }

    function showPrintOverlay(total, mono) {
        var ov = document.getElementById("printOverlay");
        ov.classList.add("open"); ov.classList.remove("done");
        document.getElementById("poIcon").className = "bx bx-pen";
        document.getElementById("poTitle").textContent = mono ? "Génération du relevé…" : "Génération des relevés…";
        document.getElementById("poBar").style.width = "0%";
        document.getElementById("poCount").textContent = "0 / " + total;
        document.body.style.overflow = "hidden";
    }
    function hidePrintOverlay() {
        document.getElementById("printOverlay").classList.remove("open", "po-drawing", "done");
        document.body.style.overflow = "";
    }
    function fitStage() {
        var wrap = document.getElementById("printStageWrap");
        var paper = wrap.parentElement;
        wrap.style.transform = "none";
        var sh = wrap.offsetHeight || 1;
        var scale = Math.min((paper.clientWidth - 20) / 794, (paper.clientHeight - 20) / sh);
        if (scale > 0) wrap.style.transform = "scale(" + scale + ")";
    }
    function drawStage(html) {
        document.getElementById("printStageReleve").innerHTML = html;
        fitStage();
        var ov = document.getElementById("printOverlay");
        ov.classList.remove("po-drawing"); void ov.offsetWidth; ov.classList.add("po-drawing");
    }

    var printCancelled = false;
    $(document).on("click", "#poCancel", function () {
        printCancelled = true;
        document.getElementById("poStatus").textContent = "Annulation…";
    });

    async function genererPdfReleves(pages, filename) {
        if (!pages || !pages.length) return;
        var mono = pages.length === 1;
        showPrintOverlay(pages.length, mono);
        printCancelled = false;
        var drawMs = pages.length > 12 ? 360 : (pages.length > 4 ? 520 : 820);
        document.documentElement.style.setProperty("--draw-ms", drawMs + "ms");
        var opt = { margin: 10, image: { type: "jpeg", quality: 0.96 }, html2canvas: { scale: 2, backgroundColor: "#fff" }, jsPDF: { unit: "mm", format: "a4", orientation: "portrait" } };
        var usableW = 190, pdf = null;
        try {
            for (var i = 0; i < pages.length; i++) {
                if (printCancelled) break;
                document.getElementById("poStatus").textContent = pages[i].nom || ("Relevé " + (i + 1));
                document.getElementById("poCount").textContent = (i + 1) + " / " + pages.length;
                document.getElementById("poBar").style.width = Math.round((i / pages.length) * 100) + "%";
                drawStage(pages[i].html);
                var capture;
                if (i === 0) {
                    capture = html2pdf().set(opt).from(pages[0].html).toPdf().get("pdf").then(function (p) { pdf = p; });
                } else {
                    capture = (function (html) {
                        var w = html2pdf().set(opt).from(html).toContainer().toCanvas();
                        return w.then(function () { return w.get("canvas"); }).then(function (c) {
                            pdf.addPage();
                            pdf.addImage(c.toDataURL("image/jpeg", 0.96), "JPEG", 10, 10, usableW, usableW * c.height / c.width);
                        });
                    })(pages[i].html);
                }
                await Promise.all([capture, sleep(drawMs)]);
            }
            if (printCancelled) {
                document.getElementById("poTitle").textContent = "Annulé";
                document.getElementById("poStatus").textContent = "Génération interrompue";
                await sleep(650);
                return;
            }
            document.getElementById("poBar").style.width = "100%";
            document.getElementById("printOverlay").classList.add("done");
            document.getElementById("poIcon").className = "bx bx-check";
            document.getElementById("poTitle").textContent = "Terminé";
            document.getElementById("poStatus").textContent = pages.length + (pages.length > 1 ? " relevés générés" : " relevé généré");
            await sleep(750);
            pdf.save(filename + ".pdf");
        } catch (e) {
            console.error(e);
            alert("Erreur lors de la génération du PDF : " + (e && e.message ? e.message : e));
        } finally {
            hidePrintOverlay();
        }
    }

    // Recherche dans le roster
    $(document).on("input", "#rosterSearch", function () {
        var q = this.value.toLowerCase().trim();
        $("#rosterTable tbody tr").each(function () {
            $(this).toggle(!q || ("" + $(this).data("nom")).indexOf(q) > -1);
        });
    });
</script>
