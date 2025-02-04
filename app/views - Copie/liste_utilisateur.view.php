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

                                            <span class="align-middle">Modules</span>
                                        </a>
                                    </li>
                                    <li class="nav-item mb-1">
                                        <a class="nav-link  " href="<?= ROOT ?>/Semestre/Liste">
                                            <i class="fa-solid fa-user me-2"></i>
                                            <span class="align-middle">Semestre</span>
                                        </a>
                                    </li>
                                    <li class="nav-item mb-1">
                                        <a class="nav-link " href="<?= ROOT ?>/Periodes/Liste">
                                            <i class="fa-solid fa-calendar me-2"></i>
                                            <span class="align-middle">Période</span>
                                        </a>
                                    </li>
                                    <li class="nav-item mb-1">
                                        <a class="nav-link  active" href="<?= ROOT ?>/Utilisateurs/liste_utilisateur">
                                            <i class="fa-solid fa-calendar me-2"></i>
                                            <span class="align-middle">Utilisateur</span>
                                        </a>
                                    </li>
                                    <li class="nav-item mb-1">
                                        <a class="nav-link " href="<?= ROOT ?>/Salles/Liste">
                                            <i class="fa-solid fa-calendar me-2"></i>
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
                                        <div class="card-body">

                                            <!-- partie liste de l'école -->
                                            <table class="table zero-configuration table-bordered" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th>Nom && Prénom Utilisateur</th>
                                                        <th>Contact</th>
                                                        <th>E Mail</th>
                                                        <th class="text-center dt-no-sorting">Action</th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    <?php foreach ($liste as $listes): ?>
                                                        <tr>
                                                            <td><?= $listes->nom_prenom ?></td>
                                                            <td><?= $listes->contact_utilisateur ?></td>
                                                            <td><?= $listes->email_utilisateurs ?></td>
                                                            <td class="text-center ">
                                                                <div class=" dropdown">
                                                                    <span class="bx bx-dots-horizontal-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu">
                                                                    </span>
                                                                    <div class="dropdown-menu dropdown-menu-right">
                                                                        <a class="dropdown-item edit-btn" data-toggle="modal" data-target="#large1"
                                                                            href="#"
                                                                            data-id="<?= $listes->id_utilisateur ?>"
                                                                            data-nom_prenom="<?= $listes->nom_prenom ?>"
                                                                            data-contact_utilisateur="<?= $listes->contact_utilisateur ?>"
                                                                            data-email_utilisateurs="<?= $listes->email_utilisateurs ?>"
                                                                            data-mot_passe="<?= $listes->mot_passe ?>"
                                                                            data-role="<?= $listes->role ?>"><i class="bx bx-edit-alt mr-1"></i> edit</a>
                                                                        <a class="dropdown-item" href=""><i class="bx bx-trash mr-1"></i> delete</a>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach ?>
                                                </tbody>
                                            </table>
                                            <!-- fin de la  partie liste de l'école -->
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
                                                <h5 class="modal-title white" id="myModalLabel160"> Enregistre de l'Utilisateur</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <i class="bx bx-x"></i>
                                                </button>
                                            </div>
                                            <form action="" m method="post" enctype="multipart/form-data" action="<?= ROOT ?>/Utilisateurs/edit_utilisateurs">
                                                <div class="modal-body">

                                                    <div class="row">
                                                        <div class="col mb-4">
                                                            <label for="nameBasic" class="form-label">Nom && prénom<span class="text-danger fs-6">*</span></label>
                                                            <input type="text" id="" value="" class="form-control" name="nom_prenom" placeholder="Nom && prénom" required />
                                                        </div>

                                                        <div class="col mb-4">
                                                            <label for="nameBasic" class="form-label">E_Mail<span class="text-danger fs-6">*</span></label>
                                                            <input type="mail" id="" value="" class="form-control" name="email_utilisateurs" placeholder="E_Mail" required />
                                                        </div>
                                                        <div class="col mb-4">
                                                            <label for="nameBasic" class="form-label">Contact<span class="text-danger fs-6">*</span></label>
                                                            <input type="number" id="" value="" class="form-control" name="contact_utilisateur" placeholder="Contact" required />
                                                        </div>
                                                    </div>
                                                    <div class="row">

                                                        <div class="col mb-4">
                                                            <label for="nameBasic" class="form-label">Mot de passe<span class="text-danger fs-6">*</span></label>
                                                            <input type="text" id="" value="" class="form-control" name="mot_passe" placeholder="Mot de passe" required />
                                                        </div>

                                                        <div class="col mb-4">
                                                            <label for="nameBasic" class="form-label">Role<span class="text-danger fs-6">*</span></label>
                                                            <fieldset class="form-group">
                                                                <select name="role" id="" class="form-select form-control">
                                                                    <option value="" disabled>Choisissez votre Role</option>
                                                                    <option value="SupAdmin">SupAdmin</option>
                                                                    <option value="Administrateur">Administrateur</option>
                                                                    <option value="Sécretaire principale">Sécretaire principale</option>
                                                                    <option value="DR">DR</option>
                                                                </select>
                                                            </fieldset>
                                                        </div>
                                                        <div class="col  mb-4">
                                                            <label for="upload" class="form-label">Télécharger son signature :</label>
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
                                                            <input type="text" id="inputnom_Prenom" value="" class="form-control" name="nom_prenom" placeholder="Nom && prénom" required />
                                                        </div>

                                                        <div class="col mb-4">
                                                            <label for="nameBasic" class="form-label">E_Mail<span class="text-danger fs-6">*</span></label>
                                                            <input type="mail" id="inputemail_Utilisateurs" value="" class="form-control" name="email_utilisateurs" placeholder="E_Mail" required />
                                                        </div>

                                                    </div>
                                                    <div class="row">
                                                        <div class="col mb-4">
                                                            <label for="nameBasic" class="form-label">Contact<span class="text-danger fs-6">*</span></label>
                                                            <input type="number" id="inputcontact_Utilisateur" value="" class="form-control" name="contact_utilisateur" placeholder="Contact" required />
                                                        </div>
                                                        <div class="col mb-4">
                                                            <label for="nameBasic" class="form-label">Mot de passe<span class="text-danger fs-6">*</span></label>
                                                            <input type="text" id="inputmot_Passe" value="" class="form-control" name="mot_passe" placeholder="Mot de passe" required />
                                                        </div>

                                                        <div class="col mb-4">
                                                            <label for="nameBasic" class="form-label">Role<span class="text-danger fs-6">*</span></label>
                                                            <fieldset class="form-group">
                                                                <select name="role" id="inputRole" class="form-select form-control">
                                                                    <option value="" disabled>Choisissez votre Role</option>
                                                                    <option value="SupAdmin">SupAdmin</option>
                                                                    <option value="Administrateur">Administrateur</option>
                                                                    <option value="Sécretaire principale">Sécretaire principale</option>
                                                                    <option value="DR">DR</option>
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

</body>
<!-- END: Body-->

</html>