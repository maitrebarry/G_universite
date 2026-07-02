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
<html lang="fr">
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
  <meta name="theme-color" content="#1a3f79">
  <meta name="mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-title" content="Kunafoni IUFP">
  <link rel="apple-touch-icon" href="<?= ROOT ?>/assets/images/pwa/apple-touch-icon.png?v=5">
  <link rel="icon" type="image/png" sizes="192x192" href="<?= ROOT ?>/assets/images/pwa/icon-192.png?v=5">

  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
  <link rel="stylesheet" href="<?= ROOT ?>/assets/css/theme.css">
  <link rel="stylesheet" href="<?= ROOT ?>/assets/css/gu-components.css">
  <style>
    :root {
      --theme-accent: #1a3f79;
      --theme-soft: rgba(26,63,121,.1);
      --theme-ring: rgba(26,63,121,.22);
      --theme-dark: #0f213a;
    }

    *, *::before, *::after { box-sizing: border-box; }
    html, body { height: 100%; overflow: hidden; }
    body { font-family: Inter, system-ui, -apple-system, "Segoe UI", sans-serif; }

    /* ── Full-screen photo carousel background ── */
    .bg-carousel { position: fixed; inset: 0; z-index: 0; }
    .bg-slide {
      position: absolute; inset: 0; opacity: 0; z-index: 1;
      transition: opacity 1.6s cubic-bezier(.4,0,.2,1); overflow: hidden;
    }
    .bg-slide.active { opacity: 1; z-index: 2; }
    .bg-slide img { width: 100%; height: 100%; object-fit: cover; object-position: center; display: block; }
    .bg-slide.active img { animation: kenBurns 9s ease-in-out forwards; }
    @keyframes kenBurns {
      from { transform: scale(1) translate(0, 0); }
      to   { transform: scale(1.08) translate(-1%, -.5%); }
    }
    .bg-slide::after {
      content: ""; position: absolute; inset: 0;
      background:
        linear-gradient(180deg, rgba(10,18,35,.55) 0%, rgba(10,18,35,.2) 40%, rgba(10,18,35,.45) 75%, rgba(10,18,35,.72) 100%),
        linear-gradient(105deg, rgba(10,18,35,.35) 0%, transparent 55%);
    }

    /* ── Carousel navigation (slide dots + progress) ── */
    .bg-progress { position: fixed; top: 0; left: 0; right: 0; height: 3px; z-index: 20; background: rgba(255,255,255,.15); }
    .bg-progress-bar { height: 100%; background: linear-gradient(90deg, var(--theme-accent), rgba(255,255,255,.7)); width: 0%; }
    .bg-dots { position: fixed; left: clamp(28px, 4vw, 64px); bottom: clamp(28px, 5vh, 56px); z-index: 20; display: flex; gap: 8px; }
    .bg-dot { height: 5px; width: 5px; border-radius: 3px; background: rgba(255,255,255,.38); border: none; padding: 0; cursor: pointer; transition: width .4s ease, background .4s ease; }
    .bg-dot.active { width: 26px; background: #fff; }

    /* ── Page layout ── */
    .login-page {
      position: relative; z-index: 10; min-height: 100vh;
      display: flex; align-items: center; justify-content: flex-end;
      padding: clamp(16px,3vh,36px) clamp(16px,5vw,72px);
      overflow: auto;
    }

    /* ── Login card ── */
    .login-card {
      width: 100%; max-width: 420px;
      padding: 32px 32px 26px;
      background: rgba(255,255,255,.94);
      backdrop-filter: blur(18px) saturate(1.4);
      -webkit-backdrop-filter: blur(18px) saturate(1.4);
      border: 1px solid rgba(255,255,255,.7);
      border-radius: 16px;
      box-shadow: 0 32px 80px rgba(10,18,35,.28), 0 0 0 1px rgba(255,255,255,.18) inset;
      position: relative; overflow: hidden;
    }
    .login-card::before {
      content: ""; position: absolute; top: 0; left: 0; right: 0; height: 4px;
      background: linear-gradient(90deg, var(--theme-accent), #4a83cf 60%, var(--theme-accent));
      border-radius: 16px 16px 0 0;
    }

    .brand-area { text-align: center; margin-bottom: 20px; }
    .brand-logo-img { width: 64px; height: 64px; object-fit: contain; background: #fff; border-radius: var(--radius-md); padding: 6px; box-shadow: var(--shadow-sm); margin-bottom: 12px; }
    .brand-title { margin: 0; font-size: 1.5rem; font-weight: 800; color: #0f172a; letter-spacing: -.3px; line-height: 1.15; }
    .brand-title span { color: var(--theme-accent); }
    .brand-subtitle { margin: 6px 0 0; color: #64748b; font-size: .8rem; font-weight: 600; letter-spacing: .2px; }

    .form-divider { display: flex; align-items: center; gap: 10px; margin-bottom: 18px; }
    .form-divider::before, .form-divider::after { content: ""; flex: 1; height: 1px; background: #e5e7eb; }
    .form-divider span { font-size: .75rem; font-weight: 600; color: #94a3b8; white-space: nowrap; }

    .login-card .form-label { font-size: .84rem; font-weight: 700; color: #334155; }
    .login-card .form-control { min-height: 46px; }
    .login-card .form-control:focus { border-color: var(--theme-accent); box-shadow: 0 0 0 .2rem var(--theme-ring); }
    .login-card .gu-field:focus-within .gu-ico { color: var(--theme-accent); }

    .login-button {
      min-height: 48px; border-radius: var(--radius-md); border: 0;
      background: linear-gradient(135deg, var(--theme-accent) 0%, var(--theme-dark) 100%);
      color: #fff; font-weight: 800; font-size: .95rem;
      box-shadow: 0 8px 24px var(--theme-ring);
      transition: opacity .2s, transform .1s, box-shadow .2s;
    }
    .login-button:hover  { opacity: .92; box-shadow: 0 12px 32px var(--theme-ring); color: #fff; }
    .login-button:active { transform: scale(.98); }
    .login-button:focus  { color: #fff; }

    .login-footer { margin-top: 18px; text-align: center; color: #94a3b8; font-size: .78rem; }

    @media (max-width: 600px) {
      html, body { height: auto; min-height: 100%; overflow-y: auto; overflow-x: hidden; }
      .login-page { align-items: center; justify-content: center; padding: 20px 14px; min-height: 100svh; }
      .login-card { padding: 22px 18px 20px; border-radius: 14px; max-width: 100%; width: 100%; }
      .bg-dots { bottom: 16px; }
    }
  </style>
</head>
<body>

  <!-- ════ Full-screen photo background (IUFP) ════ -->
  <div class="bg-carousel" id="bgCarousel" aria-hidden="true">
    <div class="bg-slide active" data-index="0">
      <img src="<?= ROOT ?>/assets/images/backgrounds/login-iufp1.jpeg" alt="" loading="eager">
    </div>
    <div class="bg-slide" data-index="1">
      <img src="<?= ROOT ?>/assets/images/backgrounds/login-iufp2.jpeg" alt="" loading="lazy">
    </div>
  </div>
  <div class="bg-progress" aria-hidden="true"><div class="bg-progress-bar" id="bgProgressBar"></div></div>
  <div class="bg-dots" aria-hidden="true">
    <button class="bg-dot active" data-slide="0"></button>
    <button class="bg-dot" data-slide="1"></button>
  </div>

  <!-- ════ Login card ════ -->
  <main class="login-page">
    <section class="login-card">

      <div class="brand-area">
        <img src="<?= ROOT ?>/assets/images/logo1.png" alt="Logo IUFP" class="brand-logo-img">
        <h1 class="brand-title">Kunafoni <span>IUFP</span></h1>
        <p class="brand-subtitle">Université de Ségou — plateforme de gestion de la scolarité</p>
      </div>

      <?php if (!empty($notif['message'])): ?>
        <div class="alert <?= $flashClass ?> d-flex align-items-center gap-2 mb-3" role="alert">
          <i class="<?= $flashIcon ?>"></i>
          <span><?= htmlspecialchars($notif['message']) ?></span>
          <button type="button" class="btn-ghost btn-icon btn-sm ms-auto" data-close aria-label="Fermer" style="color:inherit;"><i class="bx bx-x"></i></button>
        </div>
        <?php $_SESSION['notification'] = []; ?>
      <?php endif; ?>

      <div class="form-divider"><span>Connexion</span></div>

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

        <button type="submit" name="submit" class="btn login-button w-100">
          <i class="bx bx-log-in-circle"></i> Se connecter
        </button>
      </form>

      <p class="login-footer">&copy; <span id="yr"></span> IUFP · Université de Ségou</p>
    </section>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    sessionStorage.clear();
    document.getElementById('yr').textContent = new Date().getFullYear();

    var pw = document.getElementById('mot_passe'), pt = document.getElementById('pwToggle');
    pt.addEventListener('click', function(){
      var s = pw.type === 'password'; pw.type = s ? 'text' : 'password';
      pt.querySelector('i').className = s ? 'bx bx-hide' : 'bx bx-show';
    });

    document.addEventListener('click', function(e){
      var c = e.target.closest('[data-close]'); if (c) c.closest('.alert').remove();
    });

    /* ── Background carousel ── */
    (function () {
      var slides  = document.querySelectorAll('.bg-slide');
      var dots    = document.querySelectorAll('.bg-dot');
      var progBar = document.getElementById('bgProgressBar');
      var DURATION = 6000;
      var current = 0, timer = null;

      function goTo(idx) {
        slides[current].classList.remove('active');
        dots[current].classList.remove('active');
        current = (idx + slides.length) % slides.length;
        slides[current].classList.add('active');
        dots[current].classList.add('active');
        animateProgress();
      }
      function animateProgress() {
        progBar.style.transition = 'none';
        progBar.style.width = '0%';
        requestAnimationFrame(function () {
          requestAnimationFrame(function () {
            progBar.style.transition = 'width ' + DURATION + 'ms linear';
            progBar.style.width = '100%';
          });
        });
      }
      function startTimer() {
        clearInterval(timer);
        timer = setInterval(function () { goTo(current + 1); }, DURATION);
      }
      dots.forEach(function (btn, i) { btn.addEventListener('click', function () { goTo(i); startTimer(); }); });

      animateProgress();
      startTimer();
    })();
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
    style="display:none;position:fixed;left:18px;bottom:18px;z-index:1200;align-items:center;gap:8px;background:#1a3f79;color:#fff;border:0;border-radius:999px;padding:11px 18px;font:600 14px/1 Inter,Arial,sans-serif;box-shadow:0 8px 24px rgba(15,33,58,.35);cursor:pointer;">
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
