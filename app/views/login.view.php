
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Connexion - G-UNIVERSITE</title>
 
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?= ROOT ?>/assets/css/colors.css">
    <link rel="shortcut icon" type="image/x-icon" href="<?= ROOT ?>/assets/images/ico/favicon.ico">
    <link href="https://fonts.googleapis.com/css?family=Rubik:300,400,500,600%7CIBM+Plex+Sans:300,400,500,600,700"
        rel="stylesheet">
  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <style>
    body {
        background-image: url('http://localhost/G_universite/public/assets/images/OIP (1).jpeg') !important;
      background-size: cover;
      font-family: 'Segoe UI', sans-serif;
    }

    .overlay {
      background-color: rgba(255, 255, 255, 0.88);
      backdrop-filter: blur(6px);
      border-radius: 16px;
      padding: 30px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25);
      max-width: 420px;
      width: 100%;
    }

    .logo-msis {
      width: 80px;
      margin-bottom: 10px;
    }

    .form-title {
      font-weight: bold;
      font-size: 1.6rem;
      color: #003366;
    }

    .form-subtitle {
      font-size: 0.95em;
      color: #555;
    }

    .alert ul {
      margin: 0;
      padding: 0;
      list-style: none;
    }
  </style>
</head>
<body>
  <div class="d-flex justify-content-center align-items-center vh-100">
    <div class="overlay text-center">
      <!-- Logo MSIS -->
      <img src="<?= ROOT ?>/assets/images/logo1.png" alt="Logo de G-UNIVERSITER" class="logo-msis">

      <!-- Titre -->
      <h4 class="form-title">G-UNIVERSITE</h4>

      <!-- Message d'erreur -->
        <?php if (isset($_SESSION['notification']['message'])): ?>
    <div class="container">
        <div class="alert <?=$_SESSION['notification']['class']?> alert-dismissible mb-2" role="alert">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            <div class="d-flex align-items-center">
                <i class="<?=$_SESSION['notification']['icon']?>"></i>
                <span>
                    <?=$_SESSION['notification']['message']?>
                </span>
            </div>
        </div>
    </div>
    <?php $_SESSION['notification'] = []; ?>
<?php endif; ?>
 

      <!-- Formulaire -->
      <form method="POST" class="row g-3 mt-3 text-start">
        <div class="col-12 ">
          <label class="form-label">E-mail utilisateur</label>
          <input type="text" class="form-control" name="email_utilisateurs" >
        </div>
        <div class="col-12">
          <label class="form-label">Mot de passe</label>
          <input type="password" class="form-control" name="mot_passe">
        </div>
        <div class="col-12 mt-4 ">
          <div class="d-grid">
            <button type="submit" name="submit" class="btn btn-primary">Se Connecter</button>
          </div>
        </div>
      </form>
    </div>
  </div>

   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        sessionStorage.clear();
    </script>
</body>
</html>
