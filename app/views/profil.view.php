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
    </script>
</body>

</html>
