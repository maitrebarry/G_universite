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
                            <h5 class="content-header-title float-left pr-1 mb-0">Fiche étudiant</h5>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb p-0 mb-0">
                                    <li class="breadcrumb-item"><a href="<?= ROOT ?>/Homes"><i class="bx bx-home-alt"></i></a></li>
                                    <li class="breadcrumb-item"><a href="<?= ROOT ?>/Etudiants">Étudiants</a></li>
                                    <li class="breadcrumb-item active">Fiche</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-body">
                <?php $this->view('set_flash'); ?>

                <?php if (empty($etudiant)): ?>
                    <div class="card"><div class="card-body">
                        <div class="gu-empty"><i class="bx bx-user-x"></i>Étudiant introuvable.</div>
                        <div class="text-center"><a href="<?= ROOT ?>/Etudiants" class="btn btn-primary"><i class="bx bx-arrow-back"></i> Retour à la liste</a></div>
                    </div></div>
                <?php else:
                    $val = function ($k) use ($etudiant) {
                        $x = isset($etudiant[$k]) ? trim((string) $etudiant[$k]) : '';
                        return $x !== '' ? '<span class="v">' . htmlspecialchars($x) . '</span>' : '<span class="v empty">—</span>';
                    };
                    $nom = trim(($etudiant['nom_prenom_etudiant'] ?? '') . ' ' . ($etudiant['prenom'] ?? ''));
                    $ini = '';
                    foreach (preg_split('/\s+/', $nom) as $w) { if ($w !== '') $ini .= mb_strtoupper(mb_substr($w, 0, 1)); }
                    $ini = htmlspecialchars(mb_substr($ini, 0, 2) ?: 'E');
                    $photo = trim($etudiant['profilname'] ?? '');
                    $actif = ((int) ($etudiant['id_statut'] ?? 0) === 1);
                    $id = $etudiant['id_etudiant'] ?? '';
                ?>

                <!-- En-tête profil (hero) -->
                <div class="card gu-hero">
                    <div class="gu-hero-cover"></div>
                    <div class="gu-hero-body">
                        <div class="gu-hero-av">
                            <?php if ($photo): ?>
                                <img src="<?= ROOT ?>/profile/<?= htmlspecialchars(rawurlencode($photo)) ?>" alt="Photo"
                                     onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                                <span style="display:none;width:100%;height:100%;align-items:center;justify-content:center;"><?= $ini ?></span>
                            <?php else: ?>
                                <?= $ini ?>
                            <?php endif; ?>
                            <span class="dot on" title="<?= htmlspecialchars(gu_statut_label($etudiant['id_statut'] ?? '')) ?>"></span>
                        </div>

                        <div class="gu-hero-main">
                            <div class="d-flex align-items-center flex-wrap" style="gap:10px;">
                                <h3 style="margin:0;font-weight:600;"><?= htmlspecialchars($nom) ?></h3>
                                <span class="badge badge-soft-info"><?= htmlspecialchars(gu_statut_label($etudiant['id_statut'] ?? '')) ?></span>
                            </div>
                            <div class="text-secondary-2" style="margin:5px 0 10px;">
                                <i class="bx bx-id-card"></i> <?= htmlspecialchars($etudiant['matricule_etudiant'] ?? '—') ?>
                                <span style="opacity:.4;">·</span>
                                <i class="bx bx-bookmark-alt"></i> <?= htmlspecialchars($etudiant['sigle_filiere'] ?? '—') ?>
                            </div>
                            <div class="d-flex flex-wrap" style="gap:6px;">
                                <?php if (!empty($filieres)): foreach ($filieres as $p): ?>
                                    <span class="gu-chip"><?= htmlspecialchars(($p->sigle_filiere ?? '') . ' · ' . ($p->nom_semestre ?? '') . ' (' . ($p->annee_universitaire ?? '') . ')') ?></span>
                                <?php endforeach; endif; ?>
                            </div>
                        </div>

                        <div class="gu-hero-actions">
                            <a href="<?= ROOT ?>/Etudiants" class="btn btn-ghost"><i class="bx bx-arrow-back"></i> Retour</a>
                            <a href="<?= ROOT ?>/Etudiants/paiement_etudiant/<?= $id ?>" class="btn btn-soft-primary"><i class="bx bx-wallet"></i> Paiement</a>
                            <a href="<?= ROOT ?>/Etudiants/modifier/<?= $id ?>" class="btn btn-primary"><i class="bx bx-edit"></i> Modifier</a>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- État civil -->
                    <div class="col-lg-6">
                        <div class="card"><div class="card-body">
                            <div class="gu-section-title lg"><span class="gu-ico-chip"><i class="bx bx-user"></i></span> État civil</div>
                            <div class="gu-kv">
                                <div class="kv"><div class="k">Date de naissance</div><?= $val('date_naissance_etudiant') ?></div>
                                <div class="kv"><div class="k">Lieu de naissance</div><?= $val('lieu_naissance_etudiant') ?></div>
                                <div class="kv"><div class="k">Genre</div><?= $val('genre_etudiant') ?></div>
                                <div class="kv"><div class="k">Nationalité</div><?= $val('nationnalite') ?></div>
                                <div class="kv"><div class="k">Cercle de naissance</div><?= $val('cercleNais') ?></div>
                                <div class="kv"><div class="k">Commune de naissance</div><?= $val('commNais') ?></div>
                                <div class="kv"><div class="k">Pays</div><?= $val('pays') ?></div>
                            </div>
                        </div></div>
                    </div>

                    <!-- Scolarité -->
                    <div class="col-lg-6">
                        <div class="card"><div class="card-body">
                            <div class="gu-section-title lg"><span class="gu-ico-chip success"><i class="bx bx-book"></i></span> Scolarité</div>
                            <div class="gu-kv">
                                <div class="kv"><div class="k">Matricule</div><?= $val('matricule_etudiant') ?></div>
                                <div class="kv"><div class="k">N° étudiant</div><?= $val('numetudiant') ?></div>
                                <div class="kv"><div class="k">Filière</div><?= $val('sigle_filiere') ?></div>
                                <div class="kv"><div class="k">Diplôme</div><?= $val('diplome') ?></div>
                                <div class="kv"><div class="k">Série</div><?= $val('serie') ?></div>
                                <div class="kv"><div class="k">Année du diplôme</div><?= $val('anneediplome') ?></div>
                                <div class="kv"><div class="k">Académie</div><?= $val('academie') ?></div>
                                <div class="kv"><div class="k">N° de place</div><?= $val('numplace') ?></div>
                            </div>
                        </div></div>
                    </div>
                </div>

                <div class="row">
                    <!-- Contacts & adresse -->
                    <div class="col-lg-6">
                        <div class="card"><div class="card-body">
                            <div class="gu-section-title lg"><span class="gu-ico-chip warning"><i class="bx bx-map"></i></span> Contacts &amp; adresse</div>
                            <div class="gu-kv">
                                <div class="kv"><div class="k">Contact</div><?= $val('contact_etudiant') ?></div>
                                <div class="kv"><div class="k">Adresse actuelle</div><?= $val('adresseactuel') ?></div>
                                <div class="kv"><div class="k">Résidence des parents</div><?= $val('lieuresidenceparents') ?></div>
                            </div>
                        </div></div>
                    </div>

                    <!-- Parents -->
                    <div class="col-lg-6">
                        <div class="card"><div class="card-body">
                            <div class="gu-section-title lg"><span class="gu-ico-chip"><i class="bx bx-group"></i></span> Parents</div>
                            <div class="gu-kv">
                                <div class="kv"><div class="k">Prénom du père</div><?= $val('prenompere') ?></div>
                                <div class="kv"><div class="k">Nom &amp; prénom de la mère</div><?= $val('prenomnommere') ?></div>
                            </div>
                        </div></div>
                    </div>
                </div>

                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <?php $this->view("Partials/foot") ?>
    <?php $this->view("Partials/footer") ?>

</body>

</html>
