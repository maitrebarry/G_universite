<?php
$this->view("Partials/header");
$activeTab = $_SESSION['profil_tab'] ?? 'infos';
unset($_SESSION['profil_tab']);
$profil = $profil ?? [
    'nom_prenom' => $_SESSION['nom_prenom'] ?? '',
    'email_utilisateurs' => $_SESSION['email_utilisateurs'] ?? '',
    'contact_utilisateur' => $_SESSION['contact_utilisateur'] ?? '',
    'role' => $_SESSION['role'] ?? '',
];
// Initiales pour l'avatar
$gp = trim((string) $profil['nom_prenom']);
$ini = '';
foreach (preg_split('/\s+/', $gp) as $w) { if ($w !== '') $ini .= mb_strtoupper(mb_substr($w, 0, 1, 'UTF-8'), 'UTF-8'); }
$ini = mb_substr($ini, 0, 2, 'UTF-8') ?: 'U';
?>

<body class="vertical-layout vertical-menu-modern boxicon-layout no-card-shadow navbar-sticky footer-static 2-columns">
    <?php $this->view("Partials/navbar") ?>
    <?php $this->view("Partials/seibar") ?>

    <style>
        #profilPage { display: grid; grid-template-columns: 320px 1fr; gap: 18px; align-items: start; }
        @media (max-width: 900px) { #profilPage { grid-template-columns: 1fr; } }
        .pf-card { background: #fff; border: 1px solid #e7ecf5; border-radius: 14px; padding: 20px; }
        .pf-id { text-align: center; }
        .pf-avatar { width: 92px; height: 92px; border-radius: 50%; margin: 0 auto 14px; background: linear-gradient(135deg, #2a5fb0, #14346b);
            color: #fff; display: flex; align-items: center; justify-content: center; font-size: 34px; font-weight: 800; }
        .pf-name { font-size: 1.15rem; font-weight: 700; color: #14346b; margin: 0; }
        .pf-meta { color: #5a6b86; font-size: .85rem; margin-top: 10px; display: flex; align-items: center; gap: 8px; justify-content: center; flex-wrap: wrap; }
        .pf-meta i { color: #1f4f9c; }
        .pf-tabs { display: flex; gap: 6px; border-bottom: 1px solid #eef2f9; margin-bottom: 18px; }
        .pf-tab { border: 0; background: transparent; padding: 10px 14px; font-weight: 600; font-size: 14px; color: #5a6b86; cursor: pointer; border-bottom: 2px solid transparent; }
        .pf-tab.active { color: #14346b; border-bottom-color: #1f4f9c; }
        .pf-pane { display: none; } .pf-pane.active { display: block; }
        .pf-card .form-label { font-size: .85rem; color: #475569; font-weight: 500; margin-bottom: 4px; }
        .radio-pill { display: flex; gap: 10px; }
        .radio-pill label { flex: 1; border: 1px solid #e3e8f2; border-radius: 9px; padding: 9px 12px; cursor: pointer; font-size: 13.5px; font-weight: 600; color: #475569; display: flex; align-items: center; gap: 8px; margin: 0; transition: all .12s; }
        .radio-pill input { display: none; }
        .radio-pill input:checked + label { border-color: #1f4f9c; background: #eef4fd; color: #14346b; }
        .sig-pad-wrap { position: relative; border: 1px dashed #b9c4da; border-radius: 10px; overflow: hidden; background: repeating-linear-gradient(0deg, #fbfcfe, #fbfcfe 31px, #eef2f8 32px); }
        #pfSigCanvas { display: block; width: 100%; height: 160px; cursor: crosshair; touch-action: none; }
        .sig-hint { position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%); color: #aab7cc; font-size: 13px; pointer-events: none; user-select: none; }
    </style>

    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="col-12 mb-1 mt-1">
                    <h5 class="content-header-title float-left pr-1 mb-0">Mon profil</h5>
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb p-0 mb-0">
                            <li class="breadcrumb-item"><a href="<?= ROOT ?>/Homes"><i class="bx bx-home-alt"></i></a></li>
                            <li class="breadcrumb-item active">Profil</li>
                        </ol>
                    </div>
                </div>
            </div>

            <div class="content-body">
                <?php $this->view("set_flash") ?>

                <div id="profilPage">
                    <!-- Carte identité -->
                    <div class="pf-card pf-id">
                        <div class="pf-avatar"><?= htmlspecialchars($ini) ?></div>
                        <h4 class="pf-name"><?= htmlspecialchars((string) $profil['nom_prenom']) ?: 'Utilisateur' ?></h4>
                        <span class="badge badge-soft-primary" style="margin-top:6px;"><?= htmlspecialchars((string) $profil['role']) ?: '—' ?></span>
                        <div class="pf-meta"><i class="bx bx-envelope"></i> <?= htmlspecialchars((string) $profil['email_utilisateurs']) ?: '—' ?></div>
                        <div class="pf-meta"><i class="bx bx-phone"></i> <?= htmlspecialchars((string) $profil['contact_utilisateur']) ?: '—' ?></div>
                        <div class="pf-meta">
                            <i class="bx bx-pen"></i>
                            <?php if (!empty($profil['signature'])): ?>
                                <img src="<?= ROOT . htmlspecialchars((string) $profil['signature']) ?>" alt="Signature" style="height:32px;max-width:120px;border-radius:6px;border:1px solid #e3e8f2;background:#fff;">
                            <?php else: ?>
                                <span>Aucune signature enregistrée</span>
                            <?php endif ?>
                        </div>
                    </div>

                    <!-- Carte paramètres -->
                    <div class="pf-card">
                        <div class="pf-tabs">
                            <button type="button" class="pf-tab <?= $activeTab === 'infos' ? 'active' : '' ?>" data-tab="infos"><i class="bx bx-user"></i> Informations</button>
                            <button type="button" class="pf-tab <?= $activeTab === 'password' ? 'active' : '' ?>" data-tab="password"><i class="bx bx-lock-alt"></i> Mot de passe</button>
                            <button type="button" class="pf-tab <?= $activeTab === 'signature' ? 'active' : '' ?>" data-tab="signature"><i class="bx bx-pen"></i> Signature</button>
                        </div>

                        <!-- Informations -->
                        <div class="pf-pane <?= $activeTab === 'infos' ? 'active' : '' ?>" data-pane="infos">
                            <form method="POST" action="<?= ROOT ?>/Utilisateurs/update_profil">
                                <div class="row" style="row-gap:14px;">
                                    <div class="col-12">
                                        <label class="form-label" for="nom_prenom">Nom et prénom</label>
                                        <input type="text" id="nom_prenom" name="nom_prenom" class="form-control" value="<?= htmlspecialchars((string) $profil['nom_prenom']) ?>" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="email_utilisateurs">Email</label>
                                        <input type="email" id="email_utilisateurs" name="email_utilisateurs" class="form-control" value="<?= htmlspecialchars((string) $profil['email_utilisateurs']) ?>" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="contact_utilisateur">Contact</label>
                                        <input type="text" id="contact_utilisateur" name="contact_utilisateur" class="form-control" value="<?= htmlspecialchars((string) $profil['contact_utilisateur']) ?>" required>
                                    </div>
                                </div>
                                <div class="text-right mt-3">
                                    <button type="submit" class="btn btn-primary"><i class="bx bx-save"></i> Enregistrer</button>
                                </div>
                            </form>
                        </div>

                        <!-- Mot de passe -->
                        <div class="pf-pane <?= $activeTab === 'password' ? 'active' : '' ?>" data-pane="password">
                            <form method="POST" action="<?= ROOT ?>/Utilisateurs/update_mot_passe" id="pfPwdForm">
                                <div class="row" style="row-gap:14px;">
                                    <div class="col-12">
                                        <label class="form-label" for="ancien_mot_passe">Ancien mot de passe</label>
                                        <input type="password" id="ancien_mot_passe" name="ancien_mot_passe" class="form-control" required autocomplete="current-password">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="nouveau_mot_passe">Nouveau mot de passe</label>
                                        <input type="password" id="nouveau_mot_passe" name="nouveau_mot_passe" class="form-control" minlength="8" required autocomplete="new-password">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="confirmation_mot_passe">Confirmer le mot de passe</label>
                                        <input type="password" id="confirmation_mot_passe" name="confirmation_mot_passe" class="form-control" minlength="8" required autocomplete="new-password">
                                    </div>
                                </div>
                                <small class="text-muted" style="display:block;margin-top:8px;"><i class="bx bx-info-circle"></i> Au moins 8 caractères. Choisissez un mot de passe fort.</small>
                                <div class="text-right mt-3">
                                    <button type="submit" class="btn btn-primary"><i class="bx bx-lock-alt"></i> Modifier le mot de passe</button>
                                </div>
                            </form>
                        </div>

                        <!-- Signature -->
                        <div class="pf-pane <?= $activeTab === 'signature' ? 'active' : '' ?>" data-pane="signature">
                            <form method="POST" action="<?= ROOT ?>/Utilisateurs/update_signature" enctype="multipart/form-data" id="pfSigForm">
                                <label class="form-label">Signature</label>
                                <div class="radio-pill mb-2">
                                    <span><input type="radio" name="sig_mode" id="pfSigModeUpload" value="upload" checked><label for="pfSigModeUpload"><i class="bx bx-upload"></i> Téléverser une image</label></span>
                                    <span><input type="radio" name="sig_mode" id="pfSigModeDraw" value="draw"><label for="pfSigModeDraw"><i class="bx bx-pen"></i> Signer ici</label></span>
                                </div>

                                <!-- Mode : téléverser une image -->
                                <div id="pfSigUpload" class="row justify-content-between align-items-center" style="row-gap:14px;">
                                    <div class="col-md-7">
                                        <input type="file" id="pfSigFile" name="signature" class="form-control" accept="image/*">
                                    </div>
                                    <div class="col-md-5 d-flex justify-content-end">
                                        <?php if (!empty($profil['signature'])): ?>
                                            <img id="pfSigCurrent" src="<?= ROOT . htmlspecialchars((string) $profil['signature']) ?>" alt="Signature actuelle" style="height:60px;max-width:140px;border:1px dashed #d7deea;border-radius:8px;padding:4px;background:#fff;">
                                        <?php endif ?>
                                    </div>
                                </div>

                                <!-- Mode : signer directement -->
                                <div id="pfSigDraw" style="display:none;">
                                    <div class="sig-pad-wrap">
                                        <canvas id="pfSigCanvas"></canvas>
                                        <span class="sig-hint" id="pfSigHint">Signez dans le cadre (souris ou doigt)</span>
                                    </div>
                                    <div class="d-flex justify-content-end mt-1">
                                        <button type="button" class="btn btn-ghost btn-sm" id="pfSigClear"><i class="bx bx-eraser"></i> Effacer</button>
                                    </div>
                                </div>

                                <div class="text-right mt-3">
                                    <button type="submit" class="btn btn-primary"><i class="bx bx-save"></i> Enregistrer la signature</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php $this->view("Partials/foot") ?>
    <?php $this->view("Partials/footer") ?>
    <script>
        // Onglets custom (indépendants de Bootstrap)
        document.querySelectorAll('.pf-tab').forEach(function (b) {
            b.addEventListener('click', function () {
                var t = this.dataset.tab;
                document.querySelectorAll('.pf-tab').forEach(function (x) { x.classList.toggle('active', x === b); });
                document.querySelectorAll('.pf-pane').forEach(function (p) { p.classList.toggle('active', p.dataset.pane === t); });
            });
        });
        // Vérification : confirmation = nouveau mot de passe
        var pf = document.getElementById('pfPwdForm');
        if (pf) pf.addEventListener('submit', function (e) {
            var n = document.getElementById('nouveau_mot_passe').value;
            var c = document.getElementById('confirmation_mot_passe').value;
            if (n !== c) { e.preventDefault(); alert('La confirmation ne correspond pas au nouveau mot de passe.'); }
        });

        // ===== Signature : bascule téléverser / signer + pavé de dessin =====
        (function () {
            var sigCanvas = document.getElementById('pfSigCanvas');
            var sigCtx = sigCanvas ? sigCanvas.getContext('2d') : null;
            var sigDrawing = false, sigHasDrawn = false, sigLastX = 0, sigLastY = 0;

            function sigUpdateHint() {
                var h = document.getElementById('pfSigHint');
                if (h) h.style.display = sigHasDrawn ? 'none' : '';
            }
            function resizeSigCanvas() {
                if (!sigCanvas || !sigCtx) return;
                var ratio = window.devicePixelRatio || 1;
                var w = sigCanvas.parentElement.clientWidth || 420, h = 160;
                sigCanvas.width = w * ratio; sigCanvas.height = h * ratio;
                sigCanvas.style.width = w + 'px'; sigCanvas.style.height = h + 'px';
                sigCtx.setTransform(ratio, 0, 0, ratio, 0, 0);
                sigCtx.lineWidth = 2; sigCtx.lineCap = 'round'; sigCtx.lineJoin = 'round'; sigCtx.strokeStyle = '#0f2a52';
                sigHasDrawn = false; sigUpdateHint();
            }
            function sigPos(e) {
                var r = sigCanvas.getBoundingClientRect();
                var cx = e.touches ? e.touches[0].clientX : e.clientX;
                var cy = e.touches ? e.touches[0].clientY : e.clientY;
                return { x: cx - r.left, y: cy - r.top };
            }
            function sigStart(e) { sigDrawing = true; var p = sigPos(e); sigLastX = p.x; sigLastY = p.y; if (e.cancelable) e.preventDefault(); }
            function sigMove(e) {
                if (!sigDrawing) return;
                var p = sigPos(e);
                sigCtx.beginPath(); sigCtx.moveTo(sigLastX, sigLastY); sigCtx.lineTo(p.x, p.y); sigCtx.stroke();
                sigLastX = p.x; sigLastY = p.y; sigHasDrawn = true; sigUpdateHint();
                if (e.cancelable) e.preventDefault();
            }
            function sigEnd() { sigDrawing = false; }
            if (sigCanvas) {
                sigCanvas.addEventListener('mousedown', sigStart);
                sigCanvas.addEventListener('mousemove', sigMove);
                window.addEventListener('mouseup', sigEnd);
                sigCanvas.addEventListener('touchstart', sigStart, { passive: false });
                sigCanvas.addEventListener('touchmove', sigMove, { passive: false });
                sigCanvas.addEventListener('touchend', sigEnd);
            }
            var sigClear = document.getElementById('pfSigClear');
            if (sigClear) sigClear.addEventListener('click', function () {
                if (sigCtx) { sigCtx.clearRect(0, 0, sigCanvas.width, sigCanvas.height); sigHasDrawn = false; sigUpdateHint(); }
            });

            var sigUpload = document.getElementById('pfSigUpload');
            var sigDraw = document.getElementById('pfSigDraw');
            document.querySelectorAll('input[name="sig_mode"]').forEach(function (r) {
                r.addEventListener('change', function () {
                    var draw = document.getElementById('pfSigModeDraw').checked;
                    if (sigDraw) sigDraw.style.display = draw ? '' : 'none';
                    if (sigUpload) sigUpload.style.display = draw ? 'none' : '';
                    if (draw) { document.getElementById('pfSigFile').value = ''; resizeSigCanvas(); }
                });
            });

            var sigForm = document.getElementById('pfSigForm');
            if (sigForm) sigForm.addEventListener('submit', function (e) {
                var form = this;
                var drawMode = document.getElementById('pfSigModeDraw').checked;

                if (drawMode) {
                    if (!sigHasDrawn) { e.preventDefault(); alert('Veuillez signer dans le cadre.'); return; }
                    e.preventDefault();
                    sigCanvas.toBlob(function (blob) {
                        try {
                            var dt = new DataTransfer();
                            dt.items.add(new File([blob], 'signature.png', { type: 'image/png' }));
                            document.getElementById('pfSigFile').files = dt.files;
                        } catch (err) { /* navigateur trop ancien : ignoré */ }
                        form.submit();
                    }, 'image/png');
                    return;
                }

                if (!document.getElementById('pfSigFile').value) {
                    e.preventDefault(); alert('Veuillez téléverser une signature ou choisir « Signer ici ».');
                }
            });
        })();
    </script>
</body>

</html>
