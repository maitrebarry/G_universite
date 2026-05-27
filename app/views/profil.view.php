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
?>

<body class="vertical-layout vertical-menu-modern boxicon-layout no-card-shadow navbar-sticky footer-static 2-columns">
    <?php $this->view("Partials/navbar") ?>
    <?php $this->view("Partials/seibar") ?>

    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="col-12">
                    <h5 class="content-header-title float-left pr-1 mb-0">Profil</h5>
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb p-0 mb-0">
                            <li class="breadcrumb-item"><a href="<?= ROOT ?>/Homes">Accueil</a></li>
                            <li class="breadcrumb-item active">Profil</li>
                        </ol>
                    </div>
                </div>
            </div>

            <div class="content-body">
                <?php $this->view("set_flash") ?>

                <section>
                    <div class="row">
                        <div class="col-lg-4 col-md-5">
                            <div class="card">
                                <div class="card-body text-center">
                                    <img class="rounded-circle mb-1" src="<?= ROOT ?>/assets/images/portrait/small/avatar-s-11.jpg" alt="avatar" height="96" width="96">
                                    <h4 class="mb-25"><?= htmlspecialchars((string) $profil['nom_prenom'], ENT_QUOTES, 'UTF-8') ?></h4>
                                    <p class="text-muted mb-0"><?= htmlspecialchars((string) $profil['role'], ENT_QUOTES, 'UTF-8') ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-8 col-md-7">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Parametres du compte</h4>
                                </div>
                                <div class="card-body">
                                    <ul class="nav nav-tabs" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link <?= $activeTab === 'infos' ? 'active' : '' ?>" data-toggle="tab" href="#profil-infos" role="tab">
                                                <i class="bx bx-user mr-50"></i> Informations
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link <?= $activeTab === 'password' ? 'active' : '' ?>" data-toggle="tab" href="#profil-password" role="tab">
                                                <i class="bx bx-lock-alt mr-50"></i> Mot de passe
                                            </a>
                                        </li>
                                    </ul>

                                    <div class="tab-content pt-2">
                                        <div class="tab-pane fade <?= $activeTab === 'infos' ? 'show active' : '' ?>" id="profil-infos" role="tabpanel">
                                            <form method="POST" action="<?= ROOT ?>/Utilisateurs/update_profil">
                                                <div class="form-group">
                                                    <label for="nom_prenom">Nom et prenom</label>
                                                    <input type="text" id="nom_prenom" name="nom_prenom" class="form-control" value="<?= htmlspecialchars((string) $profil['nom_prenom'], ENT_QUOTES, 'UTF-8') ?>" required>
                                                </div>

                                                <div class="form-group">
                                                    <label for="email_utilisateurs">Email</label>
                                                    <input type="email" id="email_utilisateurs" name="email_utilisateurs" class="form-control" value="<?= htmlspecialchars((string) $profil['email_utilisateurs'], ENT_QUOTES, 'UTF-8') ?>" required>
                                                </div>

                                                <div class="form-group">
                                                    <label for="contact_utilisateur">Contact</label>
                                                    <input type="text" id="contact_utilisateur" name="contact_utilisateur" class="form-control" value="<?= htmlspecialchars((string) $profil['contact_utilisateur'], ENT_QUOTES, 'UTF-8') ?>" required>
                                                </div>

                                                <div class="text-right">
                                                    <button type="submit" class="btn btn-primary">
                                                        <i class="bx bx-save mr-50"></i> Enregistrer
                                                    </button>
                                                </div>
                                            </form>
                                        </div>

                                        <div class="tab-pane fade <?= $activeTab === 'password' ? 'show active' : '' ?>" id="profil-password" role="tabpanel">
                                            <form method="POST" action="<?= ROOT ?>/Utilisateurs/update_mot_passe">
                                                <div class="form-group">
                                                    <label for="ancien_mot_passe">Ancien mot de passe</label>
                                                    <input type="password" id="ancien_mot_passe" name="ancien_mot_passe" class="form-control" required>
                                                </div>

                                                <div class="form-group">
                                                    <label for="nouveau_mot_passe">Nouveau mot de passe</label>
                                                    <input type="password" id="nouveau_mot_passe" name="nouveau_mot_passe" class="form-control" minlength="8" required>
                                                </div>

                                                <div class="form-group">
                                                    <label for="confirmation_mot_passe">Confirmer le nouveau mot de passe</label>
                                                    <input type="password" id="confirmation_mot_passe" name="confirmation_mot_passe" class="form-control" minlength="8" required>
                                                </div>

                                                <div class="text-right">
                                                    <button type="submit" class="btn btn-primary">
                                                        <i class="bx bx-lock-alt mr-50"></i> Modifier le mot de passe
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>

    <?php $this->view("Partials/foot") ?>
    <?php $this->view("Partials/footer") ?>
</body>

</html>
