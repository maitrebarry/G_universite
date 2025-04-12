<?php $this->view("Partials/header") ?>

<body class="vertical-layout vertical-menu-modern boxicon-layout no-card-shadow 2-columns navbar-sticky footer-static" data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">

    <?php $this->view("Partials/navbar") ?>
    <?php $this->view("Partials/seibar") ?>

    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-12 mb-2 mt-1">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h5 class="content-header-title float-left pr-1 mb-0">Modification des Etudiants</h5>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb p-0 mb-0">
                                    <li class="breadcrumb-item"><a href="index.html"><i class="bx bx-home-alt"></i></a>
                                    </li>
                                    <li class="breadcrumb-item"><a href="#">Gestion Etudiants</a>
                                    </li>
                                    <li class="breadcrumb-item active">Modification
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">

                <section id="info-tabs-">
                    <div class="row">
                        <div class="col-12">
                            <div class="card icon-tab card-animated-border-top">
                                <div class="card-content mt-2">
                                    <div class="card-body">
                                        <div class="bs-stepper stepper-form-one">
                                            <div class="bs-stepper-header" role="tablist">
                                                <div class="step" data-target="#defaultStep-one">
                                                    <button type="button" class="step-trigger" role="tab">
                                                        <span class="bs-stepper-circle"> <i class="fa-solid fa-restroom fa-2xl"></i></span>
                                                        <span class="bs-stepper-label">Informations de l'Etudiants
                                                        </span>
                                                    </button>
                                                </div>
                                                <div class="line"></div>
                                                <div class="step" data-target="#defaultStep-two">
                                                    <button type="button" class="step-trigger" role="tab">
                                                        <span class="bs-stepper-circle">
                                                            <i class="fa-solid fa-graduation-cap fa-xl"></i></span>
                                                        <span class="bs-stepper-label">Parents et Diplome
                                                        </span>
                                                    </button>
                                                </div>
                                                <div class="line"></div>
                                                <div class="step" data-target="#defaultStep-three">
                                                    <button type="button" class="step-trigger" role="tab">
                                                        <span class="bs-stepper-circle">
                                                            <i class="fa-solid fa-user  fa-xl"></i></span>
                                                        <span class="bs-stepper-label">
                                                            <span class="bs-stepper-title">Profile et universiter
                                                            </span>
                                                        </span>
                                                    </button>
                                                </div>
                                            </div>
                                            <form method="POST" class="wizard-validation">
                                                <div class="bs-stepper-content  ">
                                                    <div id="defaultStep-one" class="content" role="" style="margin:0;">
                                                        <fieldset>
                                                            <div class="row">
                                                                <div class="col-sm-4">
                                                                    <div class="form-group">
                                                                        <label>Nom && Prénom<span class="text-danger">*</span></label>
                                                                        <input type="text" class="form-control required" name="nom_prenom_etudiant" placeholder="Nom && Prénom" value="<?= $modif->nom_prenom_etudiant ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-4">
                                                                    <div class="form-group">
                                                                        <label>Genre<span class="text-danger">*</span></label>
                                                                        <select class="form-select form-control " name="genre_etudiant" value="<?= $modif->genre_etudiant ?>">
                                                                            <option value="" disabled>Choisissez le sexe</option>
                                                                            <option value="Féminin">Féminin</option>
                                                                            <option value="Masculin">Masculin</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-4">
                                                                    <div class="form-group">
                                                                        <label>Date de naissance<span class="text-danger">*</span></label>
                                                                        <input type="date" class="form-control" name="date_naissance_etudiant" placeholder="Date de naissance" value="<?= $modif->date_naissance_etudiant ?>">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-sm-4">
                                                                    <div class="form-group">
                                                                        <label>Lieu de naissance<span class="text-danger">*</span></label>
                                                                        <input type="text" class="form-control" name="lieu_naissance_etudiant" placeholder="Lieu de naissance" value="<?= $modif->lieu_naissance_etudiant ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-4">
                                                                    <div class="form-group">
                                                                        <label>Cercle de Naissance<span class="text-danger">*</span></label>
                                                                        <input type="text" class="form-control" name="cercleNais" placeholder="Cercle de Naissance" value="<?= $modif->cercleNais ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-4">
                                                                    <div class="form-group">
                                                                        <label>Commune de Naissance<span class="text-danger">*</span></label>
                                                                        <input type="text" class="form-control" name="commNais" placeholder="Commune de Naissance" value="<?= $modif->commNais ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-4">
                                                                    <div class="form-group">
                                                                        <label>Nationalité<span class="text-danger">*</span></label>
                                                                        <input type="text" class="form-control" name="nationnalite" placeholder="Nationalité" value="<?= $modif->nationnalite ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>Numero Télephone<span class="text-danger">*</span> </label>
                                                                        <input type="number" class="form-control" name="contact_etudiant" placeholder="Numero Télephone " value="<?= $modif->contact_etudiant ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>Email<span class="text-danger">*</span></label>
                                                                        <input type="email" class="form-control" name="adresseactuel" placeholder="Email" value="<?= $modif->adresseactuel ?>">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </fieldset>
                                                        <div class="button-action mt-5">
                                                            <button type="button"
                                                                class="btn bg-secondary btn-prev me-3"
                                                                style="color:white" disabled>Prev</button>
                                                            <button type="button" class="btn bg-primary btn-nxt"
                                                                style="color:white;float:right;">Next</button>
                                                        </div>
                                                    </div>
                                                    <div id="defaultStep-two" class="content" role="tabpanel" style="margin:0;">
                                                        <fieldset>
                                                            <div class="row">
                                                                <div class="col-sm-4">
                                                                    <div class="form-group">
                                                                        <label>Prénom père<span class="text-danger">*</span></label>
                                                                        <input type="text" class="form-control " name="prenompere" placeholder="Prénom père" value="<?= $modif->prenompere ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-4">
                                                                    <div class="form-group">
                                                                        <label>Nom && Prenom mère<span class="text-danger">*</span></label>
                                                                        <input type="text" class="form-control" name="prenomnommere" placeholder="Nom && Prenom mère" value="<?= $modif->prenomnommere ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-4">
                                                                    <div class="form-group">
                                                                        <label>Lieu résidence des parents<span class="text-danger">*</span></label>
                                                                        <input type="text" class="form-control" name="lieuresidenceparents" placeholder="résidence" value="<?= $modif->lieuresidenceparents ?>">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <hr>
                                                            <div class="col-12">
                                                                <h6 class="py-50">Diplome</h6>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-sm-4">
                                                                    <div class="form-group">
                                                                        <label>Diplome<span class="text-danger">*</span></label>
                                                                        <input type="text" class="form-control " name="diplome" placeholder="Diplome" value="<?= $modif->diplome ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-4">
                                                                    <div class="form-group">
                                                                        <label>Numero de place<span class="text-danger">*</span></label>
                                                                        <input type="number" class="form-control" name="numplace" placeholder="Numero de place" value="<?= $modif->numplace ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>Serie<span class="text-danger">*</span> </label>
                                                                        <input type="text" class="form-control" name="serie" placeholder="Serie " value="<?= $modif->serie ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-4">
                                                                    <div class="form-group">
                                                                        <label>Anneés diplome<span class="text-danger">*</span></label>
                                                                        <input type="number" class="form-control" name="anneediplome" placeholder="Anneés diplome" value="<?= $modif->anneediplome ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-4">
                                                                    <div class="form-group">
                                                                        <label>Pays<span class="text-danger">*</span></label>
                                                                        <input type="text" class="form-control" name="pays" placeholder="Pays" value="<?= $modif->pays ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>Academie<span class="text-danger">*</span></label>
                                                                        <input type="text" class="form-control" name="academie" placeholder="Academie" value="<?= $modif->academie ?>">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </fieldset>
                                                        <div class="button-action mt-5">
                                                            <button type="button"
                                                                class="btn bg-secondary btn-prev me-3"
                                                                style="color:white">Prev</button>
                                                            <button type="button" class="btn bg-primary btn-nxt"
                                                                style="color:white;float:right;">Next</button>
                                                        </div>
                                                    </div>
                                                    <div id="defaultStep-three" class="content" role="tabpanel" style="margin:0;">
                                                        <fieldset>
                                                            <!-- <div class="col-12">
                                                                <h6 class="py-50">Profile et universiter</h6>
                                                            </div> -->

                                                            <div class="row">
                                                                <div class="col-md-4 mb-2">
                                                                    <label>Matricule<span class="text-danger">*</span></label>
                                                                    <input type="text" class="form-control required" name="matricule_etudiant" placeholder="Matricule" value="<?= $modif->matricule_etudiant ?>">

                                                                </div>
                                                                <div class="col-md-4  mb-2">
                                                                    <label>N étudiants<span class="text-danger">*</span></label>
                                                                    <input type="text" class="form-control" name="numetudiant" placeholder="N étudiants" value="<?= $modif->numetudiant ?>">
                                                                </div>
                                                                <div class="col-md-4  mb-2">
                                                                    <label>Années univertisaire<span class="text-danger">*</span></label>
                                                                    <select class="form-select form-control" name="id_promotion">
                                                                        <option value="" disabled>Choisissez l'année</option>
                                                                        <? // if(isset($filieres)):
                                                                        ?>
                                                                        <?php foreach ($promotions as $etudier): ?>
                                                                            <option value="<?= $etudier->id_promotion ?>">
                                                                                <?= $etudier->annee_universitaire ?>
                                                                            </option>
                                                                        <?php endforeach; ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-4  mb-2">
                                                                    <div class="form-group">
                                                                        <label>Statut<span class="text-danger">*</span></label>
                                                                        <select class="form-select form-control" name="id_statut">
                                                                            <option value="" disabled>Choisissez la Statut</option>
                                                                            <?php foreach ($statut as $etudier): ?>
                                                                                <option value="<?= $etudier->id_statut ?>">
                                                                                    <?= $etudier->libelle ?>
                                                                                </option>
                                                                            <?php endforeach; ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4  mb-2">
                                                                    <div class="form-group">
                                                                        <label>Filière<span class="text-danger">*</span></label>
                                                                        <select class="form-select form-control" name="id_filiere">
                                                                            <option value="" disabled>Choisissez la Filière</option>
                                                                            <?php foreach ($filieres as $etudier): ?>
                                                                                <option value="<?= $etudier->id_filiere ?>">
                                                                                    <?= $etudier->nom_filiere ?>
                                                                                </option>
                                                                            <?php endforeach; ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4  mb-2">
                                                                    <label class="form-label">Profile<span class="text-danger">*</span></label>
                                                                    <input type="file" name="profilname" class="form-control" value="<?= $modif->profilname ?>">
                                                                </div>
                                                            </div>
                                                        </fieldset>
                                                        <div class="button-action mt-3">
                                                            <button type="button"
                                                                class="btn bg-primary btn-prev me-3"
                                                                style="color:white">Prev</button>
                                                            <button class="btn bg-success me-3" type="submit" name="modifier"
                                                                style="color:white;float:right;">Modification</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- Form wizard with icon tabs section end -->
            </div>
        </div>
    </div>

    <?php $this->view("Partials/foot") ?>
    <?php $this->view("Partials/footer") ?>
</body>

</html>