<?php
$update = new utilisateur();
if (isset($_POST['valider'])) {
    $id_utilisateur = $_POST['id_utilisateur'];
    $statut = $_POST['statut'];
    $update->insertion_update_simples(
        'UPDATE utilisateur SET statut=:statut where id_utilisateur=:id_utilisateur',
        [
            ':id_utilisateur' => $id_utilisateur,
            ':statut' => $statut
        ]
    );
    if ($update == true) {
        $update->set_flash("status modifier avec succèes.", 'primary');
        $update->redirect("Utilisateurs/liste_utilisateur");
    }
}


?>
<!-- inclusion du partie header -->
<?php $this->view("Partials/header") ?>

<body class="vertical-layout vertical-menu-modern boxicon-layout no-card-shadow 2-columns  navbar-sticky footer-static  " data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">

    <!-- inclusion du partie header -->
    <?php $this->view("Partials/navbar") ?>
    <!-- inclusion du partie header fin-->

    <!-- inclusion du partie seibar-->
    <?php $this->view("Partials/seibar") ?>
    <!-- inclusion du partie seibar fin-->

    <!--  Content-->
    <div class="app-content content">

        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-12 mb-2 mt-1">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h5 class="content-header-title float-left pr-1 mb-0">Configuration</h5>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb p-0 mb-0">
                                    <li class="breadcrumb-item"><a href="index.html"><i class="bx bx-home-alt"></i></a>
                                    </li>
                                    <li class="breadcrumb-item"><a href="#">Gestion des Utilisateurs</a>
                                    </li>
                                    <li class="breadcrumb-item active">Liste
                                    </li>
                                </ol>
                                <div class="ms-auto">
                                    <button type="button" class="btn btn-primary" style="float: right;" id="ajouterutilisateur" data-toggle="modal" data-target="#large" data-id="<?= $id_enseignant ?>">
                                        <i class="bx bx-plus"></i>Utilisateur
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
            <div class="row g-4">

                <!-- Navigation -->
                <div class="col-12 col-lg-3">
                    <div class="card card-animated-border-top  ">
                        <div class="card-body">
                            <div class="d-flex justify-content-between flex-column mb-3 mb-md-0">
                                <ul class="nav nav-align-left nav-pills flex-column">

                                    <li class="nav-item mb-1">
                                        <a class="nav-link   radius-10 " href="<?= ROOT ?>/Modules/listeModule">
                                            <i class="fa-solid fa-book-open-reader me-2"></i>
                                            <span class="align-middle">Modules</span>
                                        </a>
                                    </li>
                                    <li class="nav-item mb-1">
                                        <a class="nav-link  " href="<?= ROOT ?>/Semestre/Liste">
                                            <i class="fa-solid fa-calendar-day me-2"></i>
                                            <span class="align-middle">Semestre</span>
                                        </a>
                                    </li>
                                    <li class="nav-item mb-1">
                                        <a class="nav-link " href="<?= ROOT ?>/Periodes/Liste">
                                            <i class="fa-solid fa-calendar-alt me-2"></i>
                                            <span class="align-middle">Période</span>
                                        </a>
                                    </li>
                                    <li class="nav-item mb-1">
                                        <a class="nav-link  active" href="<?= ROOT ?>/Utilisateurs/liste_utilisateur">
                                            <i class="fa-solid fa-users me-2"></i>
                                            <span class="align-middle">Utilisateur</span>
                                        </a>
                                    </li>
                                    <li class="nav-item mb-1">
                                        <a class="nav-link " href="<?= ROOT ?>/Salles/Liste">
                                            <i class="fa-solid fa-door-open me-2"></i>
                                            <span class="align-middle">Salles</span>
                                        </a>
                                    </li>
                                    <li class="nav-item mb-1">
                                        <a class="nav-link   " href="<?= ROOT ?>/Departements/listeDepartements">
                                            <i class="fa-solid fa-building me-2"></i>

                                            <span class="align-middle">departements</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Navigation -->
                <!-- Options -->
                <div class="col-12 col-lg-9 pt-4 pt-lg-0">
                    <?php $this->view("set_flash") ?>
                    <?php if (!empty($errors)): ?>
                        <div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <div class="d-flex align-items-center">
                                <i class="bx bx-error"></i>
                                <span>
                                    <?php foreach ($errors as $error): ?>
                                        <?= htmlspecialchars($error) ?><br>
                                    <?php endforeach; ?>
                                </span>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="tab-content p-0">
                        <!-- Store Details Tab -->
                        <div class="tab-pane fade show active" id="store_details" role="tabpanel">

                            <div class="row mb-4">

                                <div class="col-12 ">

                                    <div class="card card-animated-border-top ">
                                        <div class="card-body card-dashboard">
                                            <!-- Nav tabs -->
                                            <ul class="nav nav-pills nav-justified" role="tablist">
                                                <li class="nav-item waves-effect waves-light">
                                                    <a class="nav-link active" data-toggle="tab" href="#enseignants" role="tab">
                                                        <span class="d-none d-sm-block">Liste des enseignants utilisateur</span>
                                                    </a>
                                                </li>
                                                <li class="nav-item waves-effect waves-light">
                                                    <a class="nav-link" data-toggle="tab" href="#utilisateurs" role="tab">
                                                        <span class="d-none d-sm-block">Liste des utilisateurs simples</span>
                                                    </a>
                                                </li>
                                            </ul>

                                            <!-- Tab panes -->
                                            <div class="tab-content mt-3">
                                                <!-- Liste des enseignants -->
                                                <div class="tab-pane active" id="enseignants" role="tabpanel">
                                                    <div class="table-responsive">
                                                        <table id="table_enseignants" class="table table-striped zero-configuration table-bordered" style="width:100%">
                                                            <thead>
                                                                <tr>
                                                                    <th>Nom & Prénom</th>
                                                                    <th>Téléphone</th>
                                                                    <th>Email</th>
                                                                    <th>signature</th>
                                                                    <th width='1%'>Opération</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php foreach ($liste as $listes): ?>
                                                                    <?php if ($listes->enseignant_id): ?>
                                                                        <tr>
                                                                            <td><?= $listes->enseignant_nom . ' ' . $listes->enseignant_prenom ?></td>
                                                                            <td><?= $listes->enseignant_telephone ?></td>
                                                                            <td><?= $listes->enseignant_email ?></td>
                                                                            <td>
                                                                                <img src="<?= ROOT ?><?= $listes->signature ?>" alt="user image"
                                                                                    class="d-block rounded img-thumbnail " style="width: 150px;" />
                                                                            </td>
                                                                            <td class="text-center">
                                                                                <div class="mb-3">

                                                                                    <?php if ($listes->statut == 1) { ?>
                                                                                        <span class="fa-solid fa-user-check text-success" data-bs-toggle="modal" data-bs-target="#activer<?= $listes->id_utilisateur  ?>"></span>
                                                                                    <?php } else { ?>

                                                                                        <span class="fa-solid fa-user-lock text-danger" data-bs-toggle="modal" data-bs-target="#activer<?= $listes->id_utilisateur  ?>"></span>
                                                                                    <?php }  ?>

                                                                                    <a href="modifieutilisateur.php?id=<?= $listes->id_utilisateur  ?>"><i class="mdi mdi-square-edit-outline text-info"></i></a>

                                                                                </div>
                                                                                <div class="modal-primary mr-1 mb-1 d-inline-block ">
                                                                                    <div class="modal fade text-left" id="activer<?= $listes->id_utilisateur  ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel160" aria-hidden="true">
                                                                                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable " role="document">
                                                                                            <div class="modal-content">

                                                                                                <form action="" method="post">
                                                                                                    <div class="modal-header">

                                                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                            <i class="bx bx-x"></i>
                                                                                                        </button>
                                                                                                    </div>
                                                                                                    <div class="modal-body">
                                                                                                        <div class="row d-flex  justify-content-center align-content-center">
                                                                                                            <i class="nav-icon fa fa-exclamation-triangle text-danger  " style=" font-size: 100px;"></i>

                                                                                                        </div>
                                                                                                        <p class="text-danger " style="text-align: center;">Voulez-vous vraiment <?php if ($listes->statut == 1) { ?>désactiver<?php } else { ?> activer <?php } ?>

                                                                                                        <?= $listes->enseignant_nom ?>
                                                                                                        <?= $listes->enseignant_prenom ?>?</p>
                                                                                                        <!-- <i class="nav-icon fas fa-tachometer-alt"></i> -->
                                                                                                        <input type="hidden" value=" <?= $listes->id_utilisateur ?>" name="id_utilisateur">
                                                                                                        <?php if ($listes->statut == 1) { ?>
                                                                                                            <input type="hidden" value="0" name="statut">
                                                                                                        <?php } else { ?>
                                                                                                            <input type="hidden" value="1" name="statut">
                                                                                                        <?php } ?>
                                                                                                    </div>
                                                                                                    <div class="modal-footer">
                                                                                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                                                                                                        <button type="submit" name="valider" class="btn btn-primary"><?php if ($listes->statut == 1) { ?> Oui Desactiver<?php } else { ?>Oui Activer <?php } ?></button>
                                                                                                    </div>

                                                                                                </form>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>

                                                                            </td>
                                                                        </tr>
                                                                    <?php endif; ?>
                                                                <?php endforeach; ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>

                                                <!-- Liste des utilisateurs simples -->
                                                <div class="tab-pane" id="utilisateurs" role="tabpanel">
                                                    <div class="table-responsive">
                                                        <table id="table_utilisateurs" class="table table-striped zero-configuration table-bordered" style="width:100%">
                                                            <thead>
                                                                <tr>
                                                                    <th>Nom & Prénom</th>
                                                                    <th>Téléphone</th>
                                                                    <th>Email</th>
                                                                    <th>signature</th>
                                                                    <th width='1%'>Opération</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php foreach ($liste as $listes): ?>
                                                                    <?php if (!$listes->enseignant_id): ?>
                                                                        <tr>
                                                                            <td><?= $listes->utilisateur_nom_prenom ?></td>
                                                                            <td><?= $listes->utilisateur_contact ?></td>
                                                                            <td><?= $listes->utilisateur_email ?></td>
                                                                            <td>
                                                                                <img src="<?= ROOT ?><?= $listes->signature ?>" alt="user image"
                                                                                    class="d-block rounded img-thumbnail " style="width: 150px;" />
                                                                            </td>
                                                                            <td class="text-center">
                                                                                <div class="mb-3">

                                                                                    <?php if ($listes->statut == 1) { ?>

                                                                                        <span class="fa-solid fa-user-check text-success" data-bs-toggle="modal" data-bs-target="#activer<?= $listes->id_utilisateur  ?>"></span>
                                                                                    <?php } else { ?>
                                                                                        <span class="fa-solid fa-user-lock text-danger" data-bs-toggle="modal" data-bs-target="#activer<?= $listes->id_utilisateur  ?>"></span>
                                                                                    <?php }  ?>

                                                                                    <a href="modifieutilisateur.php?id=<?= $listes->id_utilisateur  ?>"><i class="mdi mdi-square-edit-outline text-info"></i></a>

                                                                                </div>
                                                                                <div class="modal-primary mr-1 mb-1 d-inline-block ">
                                                                                    <div class="modal fade text-left" id="activer<?= $listes->id_utilisateur  ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel160" aria-hidden="true">
                                                                                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable " role="document">
                                                                                            <div class="modal-content">

                                                                                                <form action="" method="post">
                                                                                                    <div class="modal-header">
                                                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                            <i class="bx bx-x"></i>
                                                                                                        </button>
                                                                                                    </div>
                                                                                                    <div class="modal-body">


                                                                                                        <div class="row d-flex  justify-content-center align-content-center">
                                                                                                            <i class="nav-icon fa fa-exclamation-triangle text-danger  " style=" font-size: 100px;"></i>

                                                                                                        </div>
                                                                                                        <p class="text-danger " style="text-align: center;">Voulez-vous vraiment <?php if ($listes->statut == 1) { ?>désactiver<?php } else { ?> activer <?php } ?>


                                                                                                        <?= $listes->utilisateur_nom_prenom ?> ?</p>

                                                                                                        <!-- <i class="nav-icon fas fa-tachometer-alt"></i> -->
                                                                                                        <input type="hidden" value=" <?= $listes->id_utilisateur ?>" name="id_utilisateur">
                                                                                                        <?php if ($listes->statut == 1) { ?>
                                                                                                            <input type="hidden" value="0" name="statut">
                                                                                                        <?php } else { ?>
                                                                                                            <input type="hidden" value="1" name="statut">
                                                                                                        <?php } ?>
                                                                                                    </div>
                                                                                                    <div class="modal-footer">
                                                                                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                                                                                                        <button type="submit" name="valider" class="btn btn-primary"><?php if ($listes->statut == 1) { ?> Oui Desactiver<?php } else { ?>Oui Activer <?php } ?></button>
                                                                                                    </div>

                                                                                                </form>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>

                                                                            </td>
                                                                        </tr>
                                                                    <?php endif; ?>
                                                                <?php endforeach; ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <!--  partie insertion des données-->
                            <div class="modal-primary mr-1 mb-1 d-inline-block modal-lg">
                                <div class="modal fade text-left" id="large" tabindex="-1" role="dialog" aria-labelledby="myModalLabel160" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary">
                                                <h5 class="modal-title white" id="myModalLabel160"> Enregistrement de l'Utilisateur</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <i class="bx bx-x"></i>
                                                </button>
                                            </div>
                                            <form action="<?= ROOT ?>/Utilisateurs/liste_utilisateur" method="post" enctype="multipart/form-data">
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <h6 class="col-12 text-center">Type de Utilisateur</h6>
                                                        <div class="col-12 border d-flex justify-content-center p-2 mb-2">
                                                            <div class="radio radio-primary mr-4">
                                                                <input type="radio" name="type_utilisateur" id="enseignant" class="type" value="0" checked onchange="toggleNomPrenom()">
                                                                <label for="enseignant">Utilisateur Enseignant</label>
                                                            </div>
                                                            <div class="radio radio-primary mr-4">
                                                                <input type="radio" name="type_utilisateur" id="utilisateur" class="type" value="1" onchange="toggleNomPrenom()">
                                                                <label for="utilisateur">Utilisateur simple</label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Section des statuts (apparaît seulement si l'utilisateur est un enseignant) -->
                                                    <div id="statutContainer" class="col-12 d-flex justify-content-center d-none">
                                                        <div class="radio radio-primary mr-4">
                                                            <input type="radio" name="statut" id="PERMANANT" class="type" value="PERMANANT" onchange="filterEnseignants()">
                                                            <label for="PERMANANT">Permanent</label>
                                                        </div>
                                                        <div class="radio radio-primary mr-4">
                                                            <input type="radio" name="statut" id="NON_PERMANANT" class="type" value="NON_PERMANANT" onchange="filterEnseignants()">
                                                            <label for="NON_PERMANANT">Non Permanent</label>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-2  ">
                                                        <div class="col-4">
                                                            <label for="nom_prenom" class="form-label">Nom && prénom<span class="text-danger fs-6">*</span></label>
                                                            <div id="nomPrenomContainer">
                                                                <!-- Par défaut, c'est un champ select pour enseignant -->
                                                                <select id="nom_prenom" name="nom_prenom" class=" form-control" onchange="updateEmailAndContact()">
                                                                    <option value="" selected disabled>Choisissez un enseignant</option>
                                                                    <?php foreach ($enseignants as $enseignant): ?>
                                                                        <option value="<?php echo $enseignant->enseignant_id ?>"
                                                                            data-email="<?php echo $enseignant->enseignant_email ?>"
                                                                            data-contact="<?php echo $enseignant->enseignant_telephone ?>"
                                                                            data-statut="<?php echo $enseignant->enseignant_statut ?>">
                                                                            <?php echo strtoupper($enseignant->enseignant_nom . " " . $enseignant->enseignant_prenom) ?>

                                                                        </option>
                                                                    <?php endforeach ?>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-4">
                                                            <label for="email_utilisateurs" class="form-label">E_Mail<span class="text-danger fs-6">*</span></label>
                                                            <input type="email" id="email_utilisateurs" class="form-control" name="email_utilisateurs" placeholder="E_Mail" required />
                                                        </div>
                                                        <div class="col-4">
                                                            <label for="contact_utilisateur" class="form-label">Contact<span class="text-danger fs-6">*</span></label>
                                                            <input type="text" id="contact_utilisateur" class="form-control" name="contact_utilisateur" placeholder="Contact" required />
                                                        </div>
                                                    </div>
                                                    <div class="row mb-2 d-flex justify-content-center ">
                                                        <div class="col-4 ">
                                                            <label for="mot_passe" class="form-label">Mot de passe<span class="text-danger fs-6">*</span></label>
                                                            <input type="password" id="mot_passe" class="form-control" name="mot_passe" placeholder="Mot de passe" required />
                                                        </div>
                                                        <div class="col-4">
                                                            <label for="role" class=" form-label">Role<span class="text-danger fs-6">*</span></label>
                                                            <select name="role" id="role" class="form-select form-control">
                                                                <option value="" disabled>Choisissez votre Rôle</option>
                                                                <option value="SupAdmin">SupAdmin</option>
                                                                <option value="DG">DG</option>
                                                                <option value="DGA">DGA</option>
                                                                <option value="Sécretaire principale">SP</option>
                                                                <option value="Scolarite">Scolarite</option>
                                                                <option value="Chef DR"> Chef Dr</option>
                                                                <option value="Enseignant">Enseignant</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-4 d-none" id="departement">
                                                            <label for="role" class=" form-label">Département<span class="text-danger fs-6">*</span></label>
                                                            <select name="departement" class="form-select form-control ">

                                                                <option value="" seleted>Choisissez votre departements</option>

                                                                <?php foreach ($departements as $departement): ?>
                                                                    <option value="<?php echo $departement->id_departement ?>">
                                                                        <?= strtoupper($departement->sigle_departement)   ?>
                                                                    </option>
                                                                <?php endforeach ?>
                                                            </select>
                                                        </div>


                                                    </div>
                                                    <div class="row d-flex justify-content-between align-items-center" id="c_signature">
                                                        <div class="col-6">
                                                            <label for="signature" class="form-label">Télécharger sa signature :</label>
                                                            <input type="file" id="image" name="signature" class="form-control" />
                                                        </div>
                                                        <div class="col-6 d-flex justify-content-end">
                                                            <img src="<?= ROOT ?>/assets/images/upload.svg" alt="user image" id="imagePreview"
                                                                class="d-block" style="width: 150px;max-height:100px" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                                                        <i class="bx bx-x d-block d-sm-none"></i>
                                                        <span class="d-none d-sm-block">Annuler</span>
                                                    </button>
                                                    <button type="submit" class="btn btn-primary ml-1" name="save_user">
                                                        <i class="bx bx-check d-block d-sm-none"></i>
                                                        <span class="d-none d-sm-block">Envoyer</span>
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- fin: Content-->
    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>
    <!-- inclusion du partie foot-->
    <?php $this->view("Partials/foot") ?>
    <!-- inclusion du partie foot fin-->
    <!-- inclusion du partie footer-->
    <?php $this->view("Partials/footer") ?>
    <!-- inclusion du partie footer fin-->
    <script src="<?= ROOT ?>/assets/mon_js/utilisateur.js"></script>
    <script>
        // Fonction pour afficher/masquer la section en fonction de l'option sélectionnée
        function toggleNomPrenom() {
            const enseignantChecked = document.getElementById('enseignant').checked;
            const statutContainer = document.getElementById('statutContainer');
            const nomPrenomContainer = document.getElementById('nomPrenomContainer');

            if (enseignantChecked) {
                // Afficher la section Permanent et Non Permanent
                statutContainer.classList.remove('d-none');
                statutContainer.classList.add('d-flex');

                // Afficher les enseignants et leurs statuts
                nomPrenomContainer.innerHTML = `
        <select id="nom_prenom" name="nom_prenom" class="form-control" onchange="updateEmailAndContact()">
            <option value="" disabled selected>Choisissez un enseignant</option>
            <?php foreach ($enseignants as $enseignant): ?>
                <option value="<?php echo $enseignant->enseignant_id ?>"
                    data-email="<?php echo $enseignant->enseignant_email ?>"
                    data-contact="<?php echo $enseignant->enseignant_telephone ?>"
                    data-statut="<?php echo $enseignant->enseignant_statut ?>">
                    <?php echo strtoupper($enseignant->enseignant_nom . " " . $enseignant->enseignant_prenom) ?>
                </option>
            <?php endforeach; ?>
        </select>
        `;
                } else {
                    // Masquer la section Permanent et Non Permanent
                    statutContainer.classList.remove('d-flex');
                    statutContainer.classList.add('d-none');

                    // Remplacer par un champ texte pour un utilisateur simple
                    nomPrenomContainer.innerHTML = `
            <input type="text" id="nom_prenom" class="form-control" name="nom_prenom" placeholder="Nom && prénom" required />
        `;
                // Réinitialiser les champs email et contact
                document.getElementById('email_utilisateurs').value = '';
                document.getElementById('contact_utilisateur').value = '';
                document.getElementById('email_utilisateurs').disabled = false;
                document.getElementById('contact_utilisateur').disabled = false;
            }
        }

        // Fonction pour filtrer les enseignants en fonction du statut sélectionné
        function filterEnseignants() {
            const statut = document.querySelector('input[name="statut"]:checked') ?
                document.querySelector('input[name="statut"]:checked').value :
                null;

            const enseignants = document.querySelectorAll('#nom_prenom option');

            enseignants.forEach(option => {
                const statutEnseignant = option.getAttribute('data-statut');
                if (statut && statutEnseignant !== statut && option.value != "") {
                    option.style.display = 'none'; // Masquer l'enseignant
                } else {
                    option.style.display = ''; // Afficher l'enseignant
                }
            });
        }

        // Fonction pour mettre à jour les champs email et contact
        function updateEmailAndContact() {
            const selectElement = document.getElementById('nom_prenom');
            const selectedOption = selectElement.options[selectElement.selectedIndex];
            const email = selectedOption.getAttribute('data-email');
            const contact = selectedOption.getAttribute('data-contact');

            // Mettre à jour les champs email et contact
            document.getElementById('email_utilisateurs').value = email || '';
            document.getElementById('contact_utilisateur').value = contact || '';

            // Désactiver les champs si des données existent
            document.getElementById('email_utilisateurs').disabled = !!email;
            document.getElementById('contact_utilisateur').disabled = !!contact;
            // Initialisation des éléments à la première ouverture
            document.addEventListener('DOMContentLoaded', () => {
                toggleNomPrenom(); // Affiche ou cache les éléments selon le type d'utilisateur
                filterEnseignants(); // Applique le filtre sur les enseignants si un statut est déjà sélectionné
            });
        }
    </script>
    <script>
        // $(document).ready(function() {
        //     id_utilisateur = $('#ajouterutilisateur').data('id');
        //     if (id_utilisateur !== null && id_utilisateur !== '') {
        //         $('#ajouterutilisateur').click();
        //         $('#nom_prenom').val(id_utilisateur);
        //         updateEmailAndContact();
        //     }

        //     $('#role').change(function() {
        //         if ($(this).val() == 'Chef DR') {
        //             $("#departement").removeClass('d-none');

        //         } else {
        //             $("#departement").addClass('d-none');
        //         }
        //         if ($(this).val() == 'Enseignant') {

        //             $("#c_signature").removeClass('d-flex');
        //             $("#c_signature").addClass('d-none');

        //         } else {
        //             $("#c_signature").removeClass('d-none');
        //             $("#c_signature").addClass('d-flex');
        //         }
        //     })
        // })
        // image = document.getElementById('image')
        // imagePreview = document.getElementById('imagePreview')
        // // Affichage d'une image lors de la seclection
        // image.addEventListener("change", function() {
        //     //recuperation du fichier choisi
        //     const file = image.files[0];
        //     if (file) {
        //         const reader = new FileReader();
        //         reader.onload = function(event) {
        //             imagePreview.src = event.target.result;
        //         };
        //         //recuperation
        //         reader.readAsDataURL(file);
        //     }
        // });
        // $('#utilisateur').click(function() {
        //     $("#c_signature").removeClass('d-flex');
        //     $("#c_signature").addClass('d-none');

        // })
        // $('#enseignant').click(function() {
        //     $("#c_signature").removeClass('d-none');
        //     $("#c_signature").addClass('d-flex');

        // })
        $(document).ready(function() {
            // Initialisation
            id_utilisateur = $('#ajouterutilisateur').data('id');
            if (id_utilisateur !== null && id_utilisateur !== '') {
                $('#ajouterutilisateur').click();
                $('#nom_prenom').val(id_utilisateur);
                updateEmailAndContact();
            }

            // Gestion dynamique du required pour la signature
            function manageSignatureRequirement() {
                const isEnseignantRole = $('#role').val() == 'Enseignant';
                const isUtilisateurSimple = $('#utilisateur').is(':checked');
                
                if (isEnseignantRole || isUtilisateurSimple) {
                    $("#image").removeAttr('required');
                } else {
                    $("#image").attr('required', 'required');
                }
            }

            // Gestion du rôle
            $('#role').change(function() {
                // Gestion du département
                if ($(this).val() == 'Chef DR') {
                    $("#departement").removeClass('d-none');
                } else {
                    $("#departement").addClass('d-none');
                }
                
                // Gestion de la signature
                if ($(this).val() == 'Enseignant') {
                    $("#c_signature").removeClass('d-flex').addClass('d-none');
                } else {
                    $("#c_signature").removeClass('d-none').addClass('d-flex');
                }
                
                manageSignatureRequirement();
            });

            // Affichage de l'image de prévisualisation
            const image = document.getElementById('image');
            const imagePreview = document.getElementById('imagePreview');
            image.addEventListener("change", function() {
                const file = image.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        imagePreview.src = event.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Gestion du type d'utilisateur
            $('#utilisateur').click(function() {
                $("#c_signature").removeClass('d-flex').addClass('d-none');
                manageSignatureRequirement();
            });

            $('#enseignant').click(function() {
                $("#c_signature").removeClass('d-none').addClass('d-flex');
                manageSignatureRequirement();
            });

            // Validation du formulaire
            $('form').on('submit', function(e) {
                if ($('#image').is(':visible') && $('#image').prop('required') && !$('#image').val()) {
                    e.preventDefault();
                    alert('Veuillez télécharger une signature');
                    $('#image').focus();
                }
            });

            // Initialiser l'état au chargement
            manageSignatureRequirement();
    });
    </script>

</body>
<!-- END: Body-->

</html>