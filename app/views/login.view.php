<?php
$notif = $_SESSION['notification'] ?? null;
$flashType = $notif['type'] ?? 'danger';
$flashMap = [
  'success' => 'alert-soft-success',
  'warning' => 'alert-soft-warning',
  'danger'  => 'alert-soft-danger',
  'info'    => 'alert-soft-info',
  'primary' => 'alert-soft-info',
];
$flashClass = $flashMap[$flashType] ?? 'alert-soft-danger';
$flashIcon  = $notif['icon'] ?? 'bx bx-error';
?>
<!DOCTYPE html>
<html lang="fr" data-bs-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Connexion — Kunafoni IUFP</title>
  <link rel="icon" type="image/png" sizes="48x48" href="<?= ROOT ?>/assets/images/pwa/favicon-48.png?v=5">
  <link rel="icon" type="image/png" sizes="32x32" href="<?= ROOT ?>/assets/images/pwa/favicon-32.png?v=5">
  <link rel="icon" type="image/png" sizes="16x16" href="<?= ROOT ?>/assets/images/pwa/favicon-16.png?v=5">
  <link rel="shortcut icon" href="<?= ROOT ?>/assets/images/pwa/favicon-32.png?v=5">

  <!-- PWA -->
  <link rel="manifest" href="<?= ROOT ?>/manifest.webmanifest">
  <meta name="theme-color" content="#14346b">
  <meta name="mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-title" content="Kunafoni IUFP">
  <link rel="apple-touch-icon" href="<?= ROOT ?>/assets/images/pwa/apple-touch-icon.png?v=5">
  <link rel="icon" type="image/png" sizes="192x192" href="<?= ROOT ?>/assets/images/pwa/icon-192.png?v=5">

  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
  <link rel="stylesheet" href="<?= ROOT ?>/assets/css/theme.css">
  <link rel="stylesheet" href="<?= ROOT ?>/assets/css/gu-components.css">
  <style>
    html, body { overflow-x: hidden; }
    .login-split { display: flex; min-height: 100vh; }
    .login-brand {
      background: linear-gradient(150deg, var(--brand-700), var(--brand-600) 55%, var(--brand-500));
      color: #fff; position: relative; overflow: hidden;
    }
    .login-brand .brand-inner { position: relative; z-index: 1; max-width: 420px; }
    .login-brand .circle { position: absolute; border-radius: 50%; background: rgba(255,255,255,.07); }
    .login-brand .c1 { width: 340px; height: 340px; top: -120px; right: -120px; }
    .login-brand .c2 { width: 220px; height: 220px; bottom: -80px; left: -60px; background: rgba(255,255,255,.05); }
    .login-brand .feat { display: flex; align-items: center; gap: 12px; color: rgba(255,255,255,.92); }
    .login-brand .feat .ic { width: 42px; height: 42px; border-radius: var(--radius-md);
      background: rgba(255,255,255,.14); display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0; }
    .login-pane { background: var(--surface-page); }
    .login-card { max-width: 420px; width: 100%; }
    .login-logo { width: 60px; height: 60px; border-radius: var(--radius-md); object-fit: contain; background: #fff; padding: 6px; box-shadow: var(--shadow-sm); }
    .theme-toggle { position: fixed; top: 16px; right: 16px; z-index: 10; }
  </style>
</head>
<body>
  <button type="button" id="themeToggle" class="btn btn-ghost btn-icon btn-round theme-toggle" aria-label="Basculer le thème" title="Thème clair / sombre"><i class="bx bx-moon"></i></button>

  <div class="login-split">

      <div class="login-brand d-none d-lg-flex align-items-center justify-content-center p-5" style="flex:1;min-width:0;">
        <span class="circle c1"></span><span class="circle c2"></span>
        <div class="brand-inner">
          <img src="<?= ROOT ?>/assets/images/logo1.png" alt="Logo IUFP" class="login-logo mb-4" style="background:#fff;">
          <h1 style="font-size:2rem;font-weight:600;line-height:1.2;margin-bottom:.5rem;">Institut Universitaire de Formation Professionnelle</h1>
          <p style="color:rgba(255,255,255,.85);font-size:1.05rem;margin-bottom:2.5rem;">Université de Ségou — plateforme de gestion de la scolarité.</p>
          <div class="d-flex flex-column gap-3">
            <div class="feat"><span class="ic"><i class="bx bx-group"></i></span><span>Étudiants, inscriptions &amp; réinscriptions</span></div>
            <div class="feat"><span class="ic"><i class="bx bx-book"></i></span><span>Notes, moyennes &amp; relevés</span></div>
            <div class="feat"><span class="ic"><i class="bx bx-calendar"></i></span><span>Emplois du temps &amp; salles</span></div>
          </div>
        </div>
      </div>

      <div class="login-pane d-flex align-items-center justify-content-center p-4" style="flex:1;min-width:0;">
        <div class="login-card">
          <div class="text-center mb-4 d-lg-none">
            <img src="<?= ROOT ?>/assets/images/logo1.png" alt="Logo IUFP" class="login-logo mb-2">
          </div>

          <h2 style="font-size:var(--fs-2xl);font-weight:600;margin-bottom:.25rem;">Bienvenue 👋</h2>
          <p class="text-secondary-2" style="margin-bottom:1.75rem;">Connectez-vous à votre espace Kunafoni IUFP.</p>

          <?php if (!empty($notif['message'])): ?>
            <div class="alert <?= $flashClass ?> d-flex align-items-center gap-2 mb-3" role="alert">
              <i class="<?= $flashIcon ?>"></i>
              <span><?= htmlspecialchars($notif['message']) ?></span>
              <button type="button" class="btn-ghost btn-icon btn-sm ms-auto" data-close aria-label="Fermer" style="color:inherit;"><i class="bx bx-x"></i></button>
            </div>
            <?php $_SESSION['notification'] = []; ?>
          <?php endif; ?>

          <form method="POST" novalidate>
            <div class="mb-3">
              <label class="form-label" for="email_utilisateurs">Adresse e-mail</label>
              <div class="gu-field">
                <i class="bx bx-envelope gu-ico"></i>
                <input type="email" class="form-control has-ico" id="email_utilisateurs" name="email_utilisateurs"
                       placeholder="prenom.nom@iufp.ml" autocomplete="username" autofocus required>
              </div>
            </div>

            <div class="mb-2">
              <label class="form-label" for="mot_passe">Mot de passe</label>
              <div class="gu-field">
                <i class="bx bx-lock-alt gu-ico"></i>
                <input type="password" class="form-control has-ico has-ico-r" id="mot_passe" name="mot_passe"
                       placeholder="••••••••" autocomplete="current-password" required>
                <button type="button" class="gu-ico-r" id="pwToggle" aria-label="Afficher le mot de passe"><i class="bx bx-show"></i></button>
              </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="remember">
                <label class="form-check-label" for="remember" style="font-size:var(--fs-sm);">Se souvenir de moi</label>
              </div>
              <span class="text-tertiary" style="font-size:var(--fs-sm);" title="Contactez l'administrateur">Mot de passe oublié ?</span>
            </div>

            <button type="submit" name="submit" class="btn btn-gradient btn-lg w-100">
              <i class="bx bx-log-in-circle"></i> Se connecter
            </button>
          </form>

          <p class="text-center text-tertiary mt-4 mb-0" style="font-size:var(--fs-xs);">
            © <span id="yr"></span> IUFP · Université de Ségou
          </p>
        </div>
      </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    sessionStorage.clear();
    document.getElementById('yr').textContent = new Date().getFullYear();

    var saved = localStorage.getItem('gu-theme');
    if (saved) document.documentElement.setAttribute('data-bs-theme', saved);
    var tg = document.getElementById('themeToggle');
    function syncTg(){ var d = document.documentElement.getAttribute('data-bs-theme') === 'dark'; tg.querySelector('i').className = d ? 'bx bx-sun' : 'bx bx-moon'; }
    syncTg();
    tg.addEventListener('click', function(){
      var d = document.documentElement.getAttribute('data-bs-theme') === 'dark';
      var n = d ? 'light' : 'dark';
      document.documentElement.setAttribute('data-bs-theme', n);
      localStorage.setItem('gu-theme', n); syncTg();
    });

    var pw = document.getElementById('mot_passe'), pt = document.getElementById('pwToggle');
    pt.addEventListener('click', function(){
      var s = pw.type === 'password'; pw.type = s ? 'text' : 'password';
      pt.querySelector('i').className = s ? 'bx bx-hide' : 'bx bx-show';
    });

    document.addEventListener('click', function(e){
      var c = e.target.closest('[data-close]'); if (c) c.closest('.alert').remove();
    });
  </script>

  <script>
    if ('serviceWorker' in navigator) {
      window.addEventListener('load', function () {
        navigator.serviceWorker.register('<?= ROOT ?>/sw.js')
          .catch(function (e) { console.warn('Service worker non enregistré :', e); });
      });
    }
  </script>

  <!-- PWA : bouton d'installation -->
  <button id="guInstallBtn" type="button" aria-label="Installer l'application"
    style="display:none;position:fixed;left:18px;bottom:18px;z-index:1200;align-items:center;gap:8px;background:#1f4f9c;color:#fff;border:0;border-radius:999px;padding:11px 18px;font:600 14px/1 Inter,Arial,sans-serif;box-shadow:0 8px 24px rgba(20,52,107,.35);cursor:pointer;">
    <i class="bx bx-download" style="font-size:18px;"></i> Installer l'application
  </button>
  <script>
    (function () {
      var deferred = null, btn = document.getElementById('guInstallBtn');
      function show() { if (btn) btn.style.display = 'inline-flex'; }
      function hide() { if (btn) btn.style.display = 'none'; }
      if (window.matchMedia && window.matchMedia('(display-mode: standalone)').matches) { hide(); return; }
      window.addEventListener('beforeinstallprompt', function (e) { e.preventDefault(); deferred = e; show(); });
      if (btn) btn.addEventListener('click', function () {
        if (!deferred) return;
        deferred.prompt();
        deferred.userChoice.then(function () { deferred = null; hide(); });
      });
      window.addEventListener('appinstalled', function () { hide(); deferred = null; });
    })();
  </script>
</body>
</html>
