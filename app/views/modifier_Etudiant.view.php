<?php $this->view("Partials/header") ?>

<body class="vertical-layout vertical-menu-modern boxicon-layout no-card-shadow 2-columns navbar-sticky footer-static"
    data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">

    <?php $this->view("Partials/navbar") ?>
    <?php $this->view("Partials/seibar") ?>

    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-12 mb-2 mt-1">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h5 class="content-header-title float-left pr-1 mb-0">Modifier l'étudiant</h5>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb p-0 mb-0">
                                    <li class="breadcrumb-item"><a href="<?= ROOT ?>/Homes"><i class="bx bx-home-alt"></i></a></li>
                                    <li class="breadcrumb-item"><a href="<?= ROOT ?>/Etudiants">Étudiants</a></li>
                                    <li class="breadcrumb-item active">Modifier</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-body">
                <?php $this->view('set_flash'); ?>
                <?php
                    $g = function ($k) use ($modif) { return htmlspecialchars($modif->$k ?? ''); };
                    $id = $modif->id_etudiant ?? '';
                    $genre = $modif->genre_etudiant ?? '';
                    $statut = $modif->id_statut ?? '';
                    $photo = trim($modif->profilname ?? '');
                ?>

                <form method="POST" action="<?= ROOT ?>/Etudiants/modifier/<?= $id ?>">
                    <!-- conserve la photo existante (le contrôleur lit $_POST['profilname']) -->
                    <input type="hidden" name="profilname" value="<?= htmlspecialchars($photo) ?>">

                    <!-- Informations personnelles -->
                    <div class="card"><div class="card-body">
                        <div class="gu-section-title lg"><span class="gu-ico-chip"><i class="bx bx-user"></i></span> Informations personnelles</div>
                        <div class="row">
                            <div class="col-md-3 mb-2"><label class="form-label">Nom</label><input type="text" class="form-control" name="nom_prenom_etudiant" value="<?= $g('nom_prenom_etudiant') ?>"></div>
                            <div class="col-md-3 mb-2"><label class="form-label">Prénom</label><input type="text" class="form-control" name="prenom" value="<?= $g('prenom') ?>"></div>
                            <div class="col-md-3 mb-2"><label class="form-label">Genre</label>
                                <select class="form-select" name="genre_etudiant">
                                    <option value="Masculin" <?= $genre === 'Masculin' ? 'selected' : '' ?>>Masculin</option>
                                    <option value="Féminin" <?= $genre === 'Féminin' ? 'selected' : '' ?>>Féminin</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-2"><label class="form-label">Date de naissance</label><input type="date" class="form-control" name="date_naissance_etudiant" value="<?= $g('date_naissance_etudiant') ?>"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 mb-2"><label class="form-label">Lieu de naissance</label><input type="text" class="form-control" name="lieu_naissance_etudiant" value="<?= $g('lieu_naissance_etudiant') ?>"></div>
                            <div class="col-md-3 mb-2"><label class="form-label">Cercle de naissance</label><input type="text" class="form-control" name="cercleNais" value="<?= $g('cercleNais') ?>"></div>
                            <div class="col-md-3 mb-2"><label class="form-label">Commune de naissance</label><input type="text" class="form-control" name="commNais" value="<?= $g('commNais') ?>"></div>
                            <div class="col-md-3 mb-2"><label class="form-label">Nationalité</label><input type="text" class="form-control" name="nationnalite" value="<?= $g('nationnalite') ?>"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-2"><label class="form-label">Contact</label>
                                <div class="gu-field"><i class="bx bx-phone gu-ico"></i><input type="text" class="form-control has-ico" name="contact_etudiant" value="<?= $g('contact_etudiant') ?>"></div>
                            </div>
                            <div class="col-md-8 mb-2"><label class="form-label">Adresse actuelle</label>
                                <div class="gu-field"><i class="bx bx-map gu-ico"></i><input type="text" class="form-control has-ico" name="adresseactuel" value="<?= $g('adresseactuel') ?>"></div>
                            </div>
                        </div>
                    </div></div>

                    <!-- Parents & diplôme -->
                    <div class="card"><div class="card-body">
                        <div class="gu-section-title lg"><span class="gu-ico-chip success"><i class="bx bx-group"></i></span> Parents &amp; diplôme</div>
                        <div class="row">
                            <div class="col-md-4 mb-2"><label class="form-label">Prénom du père</label><input type="text" class="form-control" name="prenompere" value="<?= $g('prenompere') ?>"></div>
                            <div class="col-md-4 mb-2"><label class="form-label">Nom &amp; prénom de la mère</label><input type="text" class="form-control" name="prenomnommere" value="<?= $g('prenomnommere') ?>"></div>
                            <div class="col-md-4 mb-2"><label class="form-label">Résidence des parents</label><input type="text" class="form-control" name="lieuresidenceparents" value="<?= $g('lieuresidenceparents') ?>"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-2"><label class="form-label">Diplôme</label><input type="text" class="form-control" name="diplome" value="<?= $g('diplome') ?>"></div>
                            <div class="col-md-2 mb-2"><label class="form-label">Série</label><input type="text" class="form-control" name="serie" value="<?= $g('serie') ?>"></div>
                            <div class="col-md-2 mb-2"><label class="form-label">Année du diplôme</label><input type="number" class="form-control" name="anneediplome" value="<?= $g('anneediplome') ?>"></div>
                            <div class="col-md-2 mb-2"><label class="form-label">N° de place</label><input type="number" class="form-control" name="numplace" value="<?= $g('numplace') ?>"></div>
                            <div class="col-md-2 mb-2"><label class="form-label">Pays</label><input type="text" class="form-control" name="pays" value="<?= $g('pays') ?>"></div>
                            <div class="col-md-4 mb-2"><label class="form-label">Académie</label><input type="text" class="form-control" name="academie" value="<?= $g('academie') ?>"></div>
                        </div>
                    </div></div>

                    <!-- Scolarité & profil -->
                    <div class="card"><div class="card-body">
                        <div class="gu-section-title lg"><span class="gu-ico-chip warning"><i class="bx bx-book"></i></span> Scolarité &amp; profil</div>
                        <div class="row align-items-start">
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-4 mb-2"><label class="form-label">Matricule</label><input type="text" class="form-control" name="matricule_etudiant" value="<?= $g('matricule_etudiant') ?>"></div>
                                    <div class="col-md-4 mb-2"><label class="form-label">N° étudiant</label><input type="text" class="form-control" name="numetudiant" value="<?= $g('numetudiant') ?>"></div>
                                    <div class="col-md-4 mb-2"><label class="form-label">Promotion (année)</label>
                                        <select class="form-select" name="id_promotion" id="id_promotion">
                                            <?php foreach ($filieres as $p): ?>
                                                <option value="<?= htmlspecialchars($p->id_promotion) ?>"><?= htmlspecialchars(($p->sigle_filiere ?? '') . '-' . ($p->nom_semestre ?? '') . ' (' . ($p->annee_universitaire ?? '') . ')') ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-2"><label class="form-label">Statut</label>
                                        <select class="form-select" name="id_statut" id="statut">
                                            <?php $statutCanon = gu_statut_normalize($statut); ?>
                                            <option value="">Choisir…</option>
                                            <?php foreach (gu_statuts() as $code => $lib): ?>
                                                <option value="<?= $code ?>" <?= $statutCanon === $code ? 'selected' : '' ?>><?= $lib ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-8 mb-2"><label class="form-label">Filière</label>
                                        <select class="form-select" name="id_filiere">
                                            <?php $seen = []; foreach ($filieres as $f): if (in_array($f->id_filiere, $seen, true)) continue; $seen[] = $f->id_filiere; ?>
                                                <option value="<?= htmlspecialchars($f->id_filiere) ?>" <?= (($modif->id_filiere ?? '') == $f->id_filiere) ? 'selected' : '' ?>><?= htmlspecialchars($f->nom_filiere ?? $f->sigle_filiere ?? '') ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-2 text-center">
                                <label class="form-label d-block text-start">Photo</label>
                                <div class="gu-profile-av" style="width:110px;height:110px;font-size:2rem;margin:0 auto;">
                                    <?php if ($photo): ?>
                                        <img src="<?= ROOT ?>/profile/<?= htmlspecialchars(rawurlencode($photo)) ?>" alt="Photo">
                                    <?php else: ?>
                                        <i class="bx bx-user"></i>
                                    <?php endif; ?>
                                </div>
                                <div class="form-text mt-1">Photo actuelle conservée</div>
                            </div>
                        </div>
                    </div></div>

                    <!-- Barre d'action -->
                    <div class="card"><div class="card-body d-flex justify-content-end" style="gap:10px;">
                        <a href="<?= ROOT ?>/Etudiants/apercu_etudiant/<?= $id ?>" class="btn btn-ghost"><i class="bx bx-x"></i> Annuler</a>
                        <button type="submit" name="modifier" class="btn btn-primary"><i class="bx bx-save"></i> Enregistrer les modifications</button>
                    </div></div>
                </form>
            </div>
        </div>
    </div>

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <?php $this->view("Partials/foot") ?>
    <?php $this->view("Partials/footer") ?>

</body>

</html>
