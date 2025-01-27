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
                                    <button type="button" class="btn btn-primary" style="float: right;" data-toggle="modal" data-target="#large">
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
                                                                            <td class="text-center">
                                                                                <div class="dropdown">
                                                                                    <span class="bx bx-dots-horizontal-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
                                                                                    <div class="dropdown-menu dropdown-menu-right">
                                                                                        <!-- <a class="dropdown-item edit-btn" data-toggle="modal" data-target="#large1" href="#"
                                                                                            data-id="<?= $listes->id_utilisateur ?>"
                                                                                            data-nom_prenom="<?= $listes->enseignant_nom . ' ' . $listes->enseignant_prenom ?>"
                                                                                            data-contact_utilisateur="<?= $listes->enseignant_telephone ?>"
                                                                                            data-email_utilisateurs="<?= $listes->enseignant_email ?>"
                                                                                            data-role_utilisateur="<?= $listes->role ?>">
                                                                                            <i class="bx bx-edit-alt mr-1"></i> Modifier</a> -->
                                                                                        <a class="dropdown-item" href="#"><i class="bx bx-trash mr-1"></i> Supprimer</a>
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
                                                                            <td class="text-center">
                                                                                <div class="dropdown">
                                                                                    <span class="bx bx-dots-horizontal-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
                                                                                    <div class="dropdown-menu dropdown-menu-right">
                                                                                        <!-- <a class="dropdown-item edit-btn" data-toggle="modal" data-target="#large1" href="#"
                                                                                            data-id="<?= $listes->id_utilisateur ?>"
                                                                                            data-nom_prenom="<?= $listes->utilisateur_nom_prenom ?>"
                                                                                            data-contact_utilisateur="<?= $listes->utilisateur_contact ?>"
                                                                                            data-email_utilisateurs="<?= $listes->utilisateur_email ?>"
                                                                                            data-role_utilisateur="<?= $listes->role ?>">
                                                                                            <i class="bx bx-edit-alt mr-1"></i> Modifier</a> -->
                                                                                        <a class="dropdown-item" href="#"><i class="bx bx-trash mr-1"></i> Supprimer</a>
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
                            <div class="modal-primary mr-1 mb-1 d-inline-block">
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

                                                    <div class="row">
                                                        <div class="col mb-4">
                                                            <label for="nom_prenom" class="form-label">Nom && prénom<span class="text-danger fs-6">*</span></label>
                                                            <div id="nomPrenomContainer">
                                                                <!-- Par défaut, c'est un champ select pour enseignant -->
                                                                <select id="nom_prenom" name="nom_prenom" class="select2 form-control" onchange="updateEmailAndContact()">
                                                                    <option value="" disabled selected>Choisissez un enseignant</option>
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

                                                        <div class="col mb-4">
                                                            <label for="email_utilisateurs" class="form-label">E_Mail<span class="text-danger fs-6">*</span></label>
                                                            <input type="email" id="email_utilisateurs" class="form-control" name="email_utilisateurs" placeholder="E_Mail" required />
                                                        </div>
                                                        <div class="col mb-4">
                                                            <label for="contact_utilisateur" class="form-label">Contact<span class="text-danger fs-6">*</span></label>
                                                            <input type="text" id="contact_utilisateur" class="form-control" name="contact_utilisateur" placeholder="Contact" required />
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col mb-4">
                                                            <label for="mot_passe" class="form-label">Mot de passe<span class="text-danger fs-6">*</span></label>
                                                            <input type="password" id="mot_passe" class="form-control" name="mot_passe" placeholder="Mot de passe" required />
                                                        </div>
                                                        <div class="col mb-4">
                                                            <label for="role" class=" form-label">Role<span class="text-danger fs-6">*</span></label>
                                                            <select name="role" id="role" class="form-select form-control">
                                                                <option value="" disabled>Choisissez votre Rôle</option>
                                                                <option value="SupAdmin">SupAdmin</option>
                                                                <option value="DG">DG</option>
                                                                <option value="DGA">DGA</option>
                                                                <option value="Sécretaire principale">SP</option>
                                                                <option value="Chef DR"> Chef Dr</option>
                                                                <option value="Enseignant">Enseignant</option>
                                                            </select>
                                                        </div>
                                                        <div class="col mb-4">
                                                            <label for="signature" class="form-label">Télécharger son signature :</label>
                                                            <input type="file" name="signature" class="form-control" required />
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
                            <!--  fin insertion des données-->
                            <!-- partie modification des données -->
                            <div class="modal-primary mr-1 mb-1 d-inline-block">
                                <div class="modal fade text-left" id="large1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel160" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary">
                                                <h5 class="modal-title white" id="myModalLabel160"> Modification de l'Utilisateur</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <i class="bx bx-x"></i>
                                                </button>
                                            </div>
                                            <form method="post" action="<?= ROOT ?>/Utilisateurs/edit_utilisateurs" id="imageForm" enctype="multipart/form-data">
                                                <div class="modal-body">
                                                    <input type="hidden" name="id_utilisateur" id="inputid_Utilisateur">
                                                    <div class="row">
                                                        <div class="col mb-4">
                                                            <label for="nameBasic" class="form-label">Nom && prénom<span class="text-danger fs-6">*</span></label>
                                                            <input type="text" id="inputnom_Prenom" value="" class="form-control" name="nom_prenom" placeholder="Nom && prénom" />
                                                        </div>

                                                        <div class="col mb-4">
                                                            <label for="nameBasic" class="form-label">E_Mail<span class="text-danger fs-6">*</span></label>
                                                            <input type="mail" id="inputemail_Utilisateurs" value="" class="form-control" name="email_utilisateurs" placeholder="E_Mail" />
                                                        </div>

                                                    </div>
                                                    <div class="row">
                                                        <div class="col mb-4">
                                                            <label for="nameBasic" class="form-label">Contact<span class="text-danger fs-6">*</span></label>
                                                            <input type="number" id="inputcontact_Utilisateur" value="" class="form-control" name="contact_utilisateur" placeholder="Contact" />
                                                        </div>
                                                        <div class="col mb-4">
                                                            <label for="nameBasic" class="form-label">Mot de passe<span class="text-danger fs-6">*</span></label>
                                                            <input type="text" id="inputmot_Passe" value="" class="form-control" name="mot_passe" placeholder="Mot de passe" />
                                                        </div>

                                                        <div class="col mb-4">
                                                            <label for="nameBasic" class="form-label">Rôle<span class="text-danger fs-6">*</span></label>
                                                            <fieldset class="form-group">
                                                                <select name="role" id="inputRole" class="form-select form-control">
                                                                    <option value="" disabled>Choisissez votre Rôle</option>
                                                                    <option value="SupAdmin">SupAdmin</option>
                                                                    <option value="Administrateur">Administrateur</option>
                                                                    <option value="DG">DG</option>
                                                                    <option value="DGA">DGA</option>
                                                                    <option value="SP">SP</option>
                                                                    <option value="DR">Chef DR</option>
                                                                    <option value="Enseignant">Enseignant</option>
                                                                </select>
                                                            </fieldset>
                                                        </div>

                                                    </div>

                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                                                        <i class="bx bx-x d-block d-sm-none"></i>
                                                        <span class="d-none d-sm-block">Annuler</span>
                                                    </button>
                                                    <button type="submit" class="btn btn-primary ml-1" name="edit_user">
                                                        <i class="bx bx-check d-block d-sm-none"></i>
                                                        <span class="d-none d-sm-block">Envoyer</span>
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- partie fin modification des données -->
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
    <script src="<?= ROOT ?>/assets/mon_js/modification_utilisateur.js"></script>
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
                if (statut && statutEnseignant !== statut) {
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
        }

        // Initialisation des éléments à la première ouverture
        document.addEventListener('DOMContentLoaded', () => {
            toggleNomPrenom(); // Affiche ou cache les éléments selon le type d'utilisateur
            filterEnseignants(); // Applique le filtre sur les enseignants si un statut est déjà sélectionné
        });
    </script>

</body>
<!-- END: Body-->

</html>