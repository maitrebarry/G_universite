<?php
// ============ Traitement activation / désactivation (inchangé) ============
$update = new utilisateur();
if (isset($_POST['valider'])) {
    $update->insertion_update_simples(
        'UPDATE utilisateur SET statut=:statut where id_utilisateur=:id_utilisateur',
        [':id_utilisateur' => $_POST['id_utilisateur'], ':statut' => $_POST['statut']]
    );
    $update->set_flash("Statut du compte mis à jour.", 'primary');
    $update->redirect("Utilisateurs/liste_utilisateur");
}

// ============ Helpers d'affichage ============
// Rôles disponibles (clés = valeurs attendues par la logique de sauvegarde)
$roleOptions = [
    'SupAdmin'              => 'Super Admin',
    'DG'                   => 'Directeur Général',
    'DGA'                  => 'Directeur Général Adjoint',
    'Sécretaire principale' => 'Secrétaire principale',
    'Scolarite'            => 'Scolarité',
    'Chef DR'              => 'Chef de Département',
    'Enseignant'           => 'Enseignant',
];
// Un utilisateur ne peut assigner (creation/edition) que les rôles strictement
// en dessous du sien : $rolesAssignables vient du contrôleur (liste_utilisateur()).
if (isset($rolesAssignables)) {
    $roleOptions = array_intersect_key($roleOptions, array_flip($rolesAssignables));
}
function uRoleBadge($r)
{
    $r = strtoupper(str_replace(' ', '', (string) $r));
    if ($r === 'SUPADMIN') return 'danger';
    if ($r === 'DG' || $r === 'DGA') return 'primary';
    if ($r === 'CHEFDR') return 'warning';
    if ($r === 'ENSEIGNANT') return 'success';
    return 'secondary';
}

// ============ KPIs ============
$liste  = $liste ?? [];
$total  = count($liste);
$nbEns  = 0; $nbSimple = 0; $nbActifs = 0;
foreach ($liste as $l) {
    if (!empty($l->enseignant_id)) $nbEns++; else $nbSimple++;
    if ((int) ($l->statut ?? 0) === 1) $nbActifs++;
}
?>
<?php $this->view("Partials/header") ?>

<body class="vertical-layout vertical-menu-modern boxicon-layout no-card-shadow 2-columns navbar-sticky footer-static"
    data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">

    <?php $this->view("Partials/navbar") ?>
    <?php $this->view("Partials/seibar") ?>

    <style>
        #usrKpis { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; margin-bottom: 14px; }
        @media (max-width: 1100px) { #usrKpis { grid-template-columns: repeat(2, 1fr); } }
        .usr-kpi { background: #fff; border: 1px solid #e7ecf5; border-radius: 12px; padding: 14px 16px; display: flex; align-items: center; gap: 12px; }
        .usr-kpi .ic { width: 42px; height: 42px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 22px; color: #fff; flex: 0 0 42px; }
        .usr-kpi .v { font-size: 1.5rem; font-weight: 800; line-height: 1; color: #0f2a52; }
        .usr-kpi .l { font-size: .78rem; color: #64748b; margin-top: 3px; }

        .seg { display: inline-flex; border: 1px solid #e3e8f2; border-radius: 9px; overflow: hidden; }
        .seg-btn { border: 0; background: #fff; color: #475569; padding: 7px 14px; font-size: 13px; font-weight: 600; cursor: pointer; }
        .seg-btn.active { background: #1f4f9c; color: #fff; }
        .seg-btn + .seg-btn { border-left: 1px solid #e3e8f2; }

        #usrSearchWrap { position: relative; min-width: 240px; }
        #usrSearchWrap i { position: absolute; left: .7rem; top: 50%; transform: translateY(-50%); color: #94a3b8; }
        #usrSearchWrap input { padding-left: 2rem; }

        .gu-conf-modal .radio-pill { display: flex; gap: 10px; }
        .radio-pill label { flex: 1; border: 1px solid #e3e8f2; border-radius: 9px; padding: 9px 12px; cursor: pointer; font-size: 13.5px; font-weight: 600; color: #475569; display: flex; align-items: center; gap: 8px; margin: 0; transition: all .12s; }
        .radio-pill input { display: none; }
        .radio-pill input:checked + label { border-color: #1f4f9c; background: #eef4fd; color: #14346b; }
        .u-avatar { width: 36px; height: 36px; border-radius: 9px; background: #eef2f9; color: #1f4f9c; display: inline-flex; align-items: center; justify-content: center; font-weight: 700; font-size: 14px; }

        /* Pavé de signature */
        .sig-pad-wrap { position: relative; border: 1px dashed #b9c4da; border-radius: 10px; overflow: hidden; background: repeating-linear-gradient(0deg, #fbfcfe, #fbfcfe 31px, #eef2f8 32px); }
        #sigCanvas { display: block; width: 100%; height: 160px; cursor: crosshair; touch-action: none; }
        .sig-hint { position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%); color: #aab7cc; font-size: 13px; pointer-events: none; user-select: none; }
        .btn.btn-sm { padding: 5px 12px; font-size: 12.5px; }
    </style>

    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-12 mb-2 mt-1">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h5 class="content-header-title float-left pr-1 mb-0">Configuration</h5>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb p-0 mb-0">
                                    <li class="breadcrumb-item"><a href="<?= ROOT ?>/Homes"><i class="bx bx-home-alt"></i></a></li>
                                    <li class="breadcrumb-item">Configuration</li>
                                    <li class="breadcrumb-item active">Utilisateurs</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-body">
                <div id="confLayout">
                    <?php $this->view("Partials/config_nav", ['active' => 'utilisateurs']) ?>
                    <div class="conf-main">
                        <?php $this->view("set_flash") ?>

                        <?php if (!empty($errors)): ?>
                            <div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert">
                                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                                <div class="d-flex align-items-center"><i class="bx bx-error"></i>&nbsp;
                                    <span><?php foreach ($errors as $error): ?><?= htmlspecialchars($error) ?><br><?php endforeach; ?></span>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- KPIs -->
                        <div id="usrKpis">
                            <div class="usr-kpi"><span class="ic" style="background:#1f4f9c;"><i class="bx bx-group"></i></span><div><div class="v"><?= $total ?></div><div class="l">Comptes au total</div></div></div>
                            <div class="usr-kpi"><span class="ic" style="background:#7c3aed;"><i class="bx bxs-graduation"></i></span><div><div class="v"><?= $nbEns ?></div><div class="l">Comptes enseignants</div></div></div>
                            <div class="usr-kpi"><span class="ic" style="background:#0ea5e9;"><i class="bx bx-user"></i></span><div><div class="v"><?= $nbSimple ?></div><div class="l">Comptes administratifs</div></div></div>
                            <div class="usr-kpi"><span class="ic" style="background:#16794a;"><i class="bx bx-check-shield"></i></span><div><div class="v"><?= $nbActifs ?></div><div class="l">Comptes actifs</div></div></div>
                        </div>

                        <div class="conf-card">
                            <div class="d-flex align-items-center justify-content-between flex-wrap mb-2" style="gap:10px;">
                                <div class="gu-section-title"><span class="gu-ico-chip"><i class="bx bx-user"></i></span> Comptes utilisateurs <span class="badge badge-soft-primary"><?= $total ?></span></div>
                                <button type="button" class="btn btn-primary" id="ajouterutilisateur" data-id="<?= htmlspecialchars($id_enseignant ?? '') ?>"><i class="bx bx-plus"></i> Ajouter un compte</button>
                            </div>

                            <div class="d-flex align-items-center justify-content-between flex-wrap mb-3" style="gap:10px;">
                                <div class="seg">
                                    <button type="button" class="seg-btn active" data-type="all">Tous</button>
                                    <button type="button" class="seg-btn" data-type="ens">Enseignants</button>
                                    <button type="button" class="seg-btn" data-type="simple">Administratifs</button>
                                </div>
                                <div id="usrSearchWrap"><i class="bx bx-search"></i><input type="text" class="form-control" id="usrSearch" placeholder="Rechercher un compte…"></div>
                            </div>

                            <?php if (empty($liste)): ?>
                                <div class="gu-empty"><i class="bx bx-user"></i>Aucun compte utilisateur.</div>
                            <?php else: ?>
                                <div class="gu-table-wrap" style="max-height:calc(100vh - 430px);">
                                    <table class="gu-table" id="usersTable" style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th style="width:46px;text-align:center;">N°</th>
                                                <th>Nom &amp; prénom</th>
                                                <th>Rôle</th>
                                                <th>Contact</th>
                                                <th>Email</th>
                                                <th class="text-center">Type</th>
                                                <th class="text-center">Signature</th>
                                                <th class="text-center">Statut</th>
                                                <th class="text-center" style="width:120px;">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i = 1; foreach ($liste as $l):
                                                $isEns   = !empty($l->enseignant_id);
                                                $nom     = $isEns ? trim(($l->enseignant_nom ?? '') . ' ' . ($l->enseignant_prenom ?? '')) : ($l->utilisateur_nom_prenom ?? '');
                                                $contact = $isEns ? ($l->enseignant_telephone ?? '') : ($l->utilisateur_contact ?? '');
                                                $email   = $isEns ? ($l->enseignant_email ?? '') : ($l->utilisateur_email ?? '');
                                                $role    = $l->role ?? '';
                                                $roleLbl = $roleOptions[$role] ?? ($role ?: '—');
                                                $actif   = (int) ($l->statut ?? 0) === 1;
                                                $ini     = strtoupper(mb_substr(trim($nom) ?: '?', 0, 1, 'UTF-8'));
                                                $search  = mb_strtolower(trim($nom . ' ' . $email . ' ' . $contact . ' ' . $roleLbl), 'UTF-8');
                                            ?>
                                                <tr data-type="<?= $isEns ? 'ens' : 'simple' ?>" data-search="<?= htmlspecialchars($search) ?>" <?= $actif ? '' : 'style="opacity:.6;"' ?>>
                                                    <td class="text-center"><?= $i++ ?></td>
                                                    <td style="font-weight:var(--fw-medium);">
                                                        <div class="d-flex align-items-center" style="gap:9px;">
                                                            <span class="u-avatar"><?= htmlspecialchars($ini) ?></span>
                                                            <span><?= $nom !== '' ? htmlspecialchars(mb_strtoupper($nom, 'UTF-8')) : '<span class="text-muted">—</span>' ?></span>
                                                        </div>
                                                    </td>
                                                    <td><span class="badge badge-soft-<?= uRoleBadge($role) ?>"><?= htmlspecialchars($roleLbl) ?></span></td>
                                                    <td><?= htmlspecialchars($contact ?: '—') ?></td>
                                                    <td><?= htmlspecialchars($email ?: '—') ?></td>
                                                    <td class="text-center">
                                                        <?php if ($isEns): ?><span class="badge badge-soft-primary"><i class="bx bxs-graduation"></i> Enseignant</span>
                                                        <?php else: ?><span class="badge badge-soft-secondary"><i class="bx bx-user"></i> Administratif</span><?php endif ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <?php if (!empty($l->signature)): ?>
                                                            <img src="<?= ROOT . htmlspecialchars($l->signature) ?>" alt="signature" style="height:32px;max-width:90px;border-radius:6px;border:1px solid #e3e8f2;background:#fff;">
                                                        <?php else: ?><span class="text-muted">—</span><?php endif ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <?php if ($actif): ?><span class="badge badge-soft-success">Actif</span>
                                                        <?php else: ?><span class="badge badge-soft-danger">Inactif</span><?php endif ?>
                                                    </td>
                                                    <td class="text-center" style="white-space:nowrap;">
                                                        <button type="button" class="conf-act btn-statut" title="<?= $actif ? 'Désactiver' : 'Activer' ?>"
                                                            data-id="<?= $l->id_utilisateur ?>" data-next="<?= $actif ? 0 : 1 ?>" data-active="<?= $actif ? 1 : 0 ?>" data-nom="<?= htmlspecialchars($nom) ?>">
                                                            <i class="bx <?= $actif ? 'bx-lock-open-alt' : 'bx-lock-alt' ?>" style="color:<?= $actif ? '#16794a' : '#b91c1c' ?>;"></i>
                                                        </button>
                                                        <button type="button" class="conf-act btn-edit-user" title="Modifier"
                                                            data-id="<?= $l->id_utilisateur ?>" data-nom="<?= htmlspecialchars($nom) ?>" data-email="<?= htmlspecialchars($email) ?>"
                                                            data-contact="<?= htmlspecialchars($contact) ?>" data-role="<?= htmlspecialchars($role) ?>" data-ens="<?= $isEns ? 1 : 0 ?>"><i class="bx bx-edit-alt"></i></button>
                                                        <a class="conf-act danger btn-conf-del" href="<?= ROOT ?>/Utilisateurs/delete/<?= $l->id_utilisateur ?>" data-nom="le compte de <?= htmlspecialchars($nom) ?>" title="Supprimer"><i class="bx bx-trash"></i></a>
                                                    </td>
                                                </tr>
                                            <?php endforeach ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="gu-empty" id="usrNoResult" style="display:none;"><i class="bx bx-search-alt"></i>Aucun compte ne correspond à la recherche.</div>
                            <?php endif ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulaire caché : activation / désactivation -->
    <form id="statutForm" method="post" action="" style="display:none;">
        <input type="hidden" name="valider" value="1">
        <input type="hidden" name="id_utilisateur" id="sf_id">
        <input type="hidden" name="statut" id="sf_statut">
    </form>

    <!-- ==================== MODALE : AJOUTER ==================== -->
    <div class="gu-conf-modal" id="addUserModal">
        <div class="gm-back" data-conf-close="addUserModal"></div>
        <div class="gm-card" style="width:min(820px,96vw);">
            <form action="<?= ROOT ?>/Utilisateurs/liste_utilisateur" method="post" enctype="multipart/form-data">
                <div class="gm-head"><h5><i class="bx bx-user-plus"></i> Enregistrement d'un compte</h5><button type="button" class="conf-act" data-conf-close="addUserModal" style="border:0;background:transparent;color:#fff;"><i class="bx bx-x"></i></button></div>
                <div class="gm-body" style="max-height:70vh;overflow:auto;">
                    <!-- Type de compte -->
                    <label class="form-label">Type de compte <span class="text-danger">*</span></label>
                    <div class="radio-pill mb-3">
                        <span><input type="radio" name="type_utilisateur" id="enseignant" class="type" value="0" checked onchange="toggleNomPrenom()"><label for="enseignant"><i class="bx bxs-graduation"></i> Compte enseignant</label></span>
                        <span><input type="radio" name="type_utilisateur" id="utilisateur" class="type" value="1" onchange="toggleNomPrenom()"><label for="utilisateur"><i class="bx bx-user"></i> Compte administratif</label></span>
                    </div>

                    <!-- Filtre statut (enseignants uniquement) -->
                    <div id="statutContainer" class="mb-3">
                        <label class="form-label">Filtrer par statut</label>
                        <div class="radio-pill">
                            <span><input type="radio" name="statut" id="PERMANANT" value="PERMANANT" onchange="filterEnseignants()"><label for="PERMANANT">Permanent</label></span>
                            <span><input type="radio" name="statut" id="NON_PERMANANT" value="NON_PERMANANT" onchange="filterEnseignants()"><label for="NON_PERMANANT">Non permanent</label></span>
                        </div>
                    </div>

                    <div class="row" style="row-gap:14px;">
                        <div class="col-md-4">
                            <label for="nom_prenom" class="form-label">Nom &amp; prénom <span class="text-danger">*</span></label>
                            <div id="nomPrenomContainer">
                                <select id="nom_prenom" name="nom_prenom" class="form-control" onchange="updateEmailAndContact()">
                                    <option value="" selected disabled>Choisissez un enseignant</option>
                                    <?php foreach ($enseignants as $enseignant): ?>
                                        <option value="<?= $enseignant->enseignant_id ?>"
                                            data-email="<?= htmlspecialchars($enseignant->enseignant_email) ?>"
                                            data-contact="<?= htmlspecialchars($enseignant->enseignant_telephone) ?>"
                                            data-statut="<?= htmlspecialchars($enseignant->enseignant_statut) ?>">
                                            <?= strtoupper(htmlspecialchars($enseignant->enseignant_nom . " " . $enseignant->enseignant_prenom)) ?>
                                        </option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="email_utilisateurs" class="form-label">E-mail <span class="text-danger">*</span></label>
                            <input type="email" id="email_utilisateurs" class="form-control" name="email_utilisateurs" placeholder="adresse@exemple.com" required />
                        </div>
                        <div class="col-md-4">
                            <label for="contact_utilisateur" class="form-label">Contact <span class="text-danger">*</span></label>
                            <input type="text" id="contact_utilisateur" class="form-control" name="contact_utilisateur" placeholder="Téléphone" required />
                        </div>

                        <div class="col-md-4">
                            <label for="mot_passe" class="form-label">Mot de passe <span class="text-danger">*</span></label>
                            <input type="password" id="mot_passe" class="form-control" name="mot_passe" placeholder="••••••••" required />
                        </div>
                        <div class="col-md-4">
                            <label for="role" class="form-label">Rôle <span class="text-danger">*</span></label>
                            <select name="role" id="role" class="form-select form-control" required>
                                <option value="" disabled selected>Choisissez un rôle</option>
                                <?php foreach ($roleOptions as $rv => $rl): ?>
                                    <option value="<?= htmlspecialchars($rv) ?>"><?= htmlspecialchars($rl) ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <div class="col-md-4 d-none" id="departement">
                            <label class="form-label">Département <span class="text-danger">*</span></label>
                            <select name="departement" class="form-select form-control">
                                <option value="" selected>Choisissez un département</option>
                                <?php foreach ($departements as $departement): ?>
                                    <option value="<?= $departement->id_departement ?>"><?= strtoupper(htmlspecialchars($departement->sigle_departement)) ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </div>

                    <div class="mt-2" id="c_signature">
                        <label class="form-label">Signature</label>
                        <div class="radio-pill mb-2" id="sigModeSwitch">
                            <span><input type="radio" name="sig_mode" id="sigModeUpload" value="upload" checked><label for="sigModeUpload"><i class="bx bx-upload"></i> Téléverser une image</label></span>
                            <span><input type="radio" name="sig_mode" id="sigModeDraw" value="draw"><label for="sigModeDraw"><i class="bx bx-pen"></i> Signer ici</label></span>
                        </div>

                        <!-- Mode : téléverser une image -->
                        <div id="sigUpload" class="row justify-content-between align-items-center">
                            <div class="col-7">
                                <input type="file" id="image" name="signature" class="form-control" accept="image/*" />
                            </div>
                            <div class="col-5 d-flex justify-content-end">
                                <img src="<?= ROOT ?>/assets/images/upload.svg" alt="aperçu" id="imagePreview" class="d-block" style="width:130px;max-height:90px;border:1px dashed #d7deea;border-radius:8px;padding:4px;" />
                            </div>
                        </div>

                        <!-- Mode : signer directement -->
                        <div id="sigDraw" style="display:none;">
                            <div class="sig-pad-wrap">
                                <canvas id="sigCanvas"></canvas>
                                <span class="sig-hint" id="sigHint">Signez dans le cadre (souris ou doigt)</span>
                            </div>
                            <div class="d-flex justify-content-end mt-1">
                                <button type="button" class="btn btn-ghost btn-sm" id="sigClear"><i class="bx bx-eraser"></i> Effacer</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="gm-foot">
                    <input type="hidden" name="save_user" value="1">
                    <button type="button" class="btn btn-ghost" data-conf-close="addUserModal">Annuler</button>
                    <button type="submit" class="btn btn-primary"><i class="bx bx-check"></i> Enregistrer</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ==================== MODALE : MODIFIER ==================== -->
    <div class="gu-conf-modal" id="editUserModal">
        <div class="gm-back" data-conf-close="editUserModal"></div>
        <div class="gm-card" style="width:min(640px,95vw);">
            <form action="<?= ROOT ?>/Utilisateurs/edit_utilisateurs" method="post">
                <div class="gm-head"><h5><i class="bx bx-edit-alt"></i> Modifier le compte</h5><button type="button" class="conf-act" data-conf-close="editUserModal" style="border:0;background:transparent;color:#fff;"><i class="bx bx-x"></i></button></div>
                <div class="gm-body">
                    <input type="hidden" id="eu_id" name="id_utilisateur">
                    <div class="row" style="row-gap:14px;">
                        <div class="col-md-6">
                            <label class="form-label">Nom &amp; prénom</label>
                            <input type="text" class="form-control" id="eu_nom" name="nom_prenom">
                            <small id="eu_ensNote" class="text-muted" style="display:none;"><i class="bx bx-info-circle"></i> Géré depuis la fiche enseignant.</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Rôle</label>
                            <select class="form-select form-control" id="eu_role" name="role">
                                <?php foreach ($roleOptions as $rv => $rl): ?>
                                    <option value="<?= htmlspecialchars($rv) ?>"><?= htmlspecialchars($rl) ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">E-mail</label>
                            <input type="email" class="form-control" id="eu_email" name="email_utilisateurs">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Contact</label>
                            <input type="text" class="form-control" id="eu_contact" name="contact_utilisateur">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Nouveau mot de passe</label>
                            <input type="password" class="form-control" id="eu_pass" name="mot_passe" placeholder="Laisser vide pour ne pas changer" autocomplete="new-password">
                        </div>
                    </div>
                </div>
                <div class="gm-foot">
                    <button type="button" class="btn btn-ghost" data-conf-close="editUserModal">Annuler</button>
                    <button type="submit" class="btn btn-primary" name="edit_user"><i class="bx bx-check"></i> Enregistrer</button>
                </div>
            </form>
        </div>
    </div>

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <?php $this->view("Partials/foot") ?>
    <?php $this->view("Partials/footer") ?>

    <script>
        // ============ Logique du formulaire d'ajout (préservée) ============
        function toggleNomPrenom() {
            var enseignantChecked = document.getElementById('enseignant').checked;
            var statutContainer = document.getElementById('statutContainer');
            var nomPrenomContainer = document.getElementById('nomPrenomContainer');

            if (enseignantChecked) {
                statutContainer.style.display = '';
                nomPrenomContainer.innerHTML = `
                    <select id="nom_prenom" name="nom_prenom" class="form-control" onchange="updateEmailAndContact()">
                        <option value="" disabled selected>Choisissez un enseignant</option>
                        <?php foreach ($enseignants as $enseignant): ?>
                            <option value="<?= $enseignant->enseignant_id ?>"
                                data-email="<?= htmlspecialchars($enseignant->enseignant_email) ?>"
                                data-contact="<?= htmlspecialchars($enseignant->enseignant_telephone) ?>"
                                data-statut="<?= htmlspecialchars($enseignant->enseignant_statut) ?>">
                                <?= strtoupper(htmlspecialchars($enseignant->enseignant_nom . " " . $enseignant->enseignant_prenom)) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>`;
            } else {
                statutContainer.style.display = 'none';
                nomPrenomContainer.innerHTML = `<input type="text" id="nom_prenom" class="form-control" name="nom_prenom" placeholder="Nom & prénom" required />`;
                document.getElementById('email_utilisateurs').value = '';
                document.getElementById('contact_utilisateur').value = '';
                document.getElementById('email_utilisateurs').disabled = false;
                document.getElementById('contact_utilisateur').disabled = false;
            }
        }

        function filterEnseignants() {
            var checked = document.querySelector('input[name="statut"]:checked');
            var statut = checked ? checked.value : null;
            document.querySelectorAll('#nom_prenom option').forEach(function (option) {
                var statutEnseignant = option.getAttribute('data-statut');
                if (statut && statutEnseignant !== statut && option.value != "") {
                    option.style.display = 'none';
                } else {
                    option.style.display = '';
                }
            });
        }

        function updateEmailAndContact() {
            var selectElement = document.getElementById('nom_prenom');
            if (!selectElement || selectElement.tagName !== 'SELECT') return;
            var selectedOption = selectElement.options[selectElement.selectedIndex];
            var email = selectedOption.getAttribute('data-email');
            var contact = selectedOption.getAttribute('data-contact');
            document.getElementById('email_utilisateurs').value = email || '';
            document.getElementById('contact_utilisateur').value = contact || '';
            document.getElementById('email_utilisateurs').disabled = !!email;
            document.getElementById('contact_utilisateur').disabled = !!contact;
        }

        $(document).ready(function () {
            // Ouverture de la modale d'ajout
            $('#ajouterutilisateur').on('click', function () { guConfOpen('addUserModal'); });

            // Pré-remplissage si on vient d'« Attribuer un compte » depuis la liste enseignants
            var idEns = $('#ajouterutilisateur').data('id');
            if (idEns !== null && idEns !== undefined && idEns !== '') {
                guConfOpen('addUserModal');
                document.getElementById('enseignant').checked = true;
                toggleNomPrenom();
                $('#nom_prenom').val(idEns);
                updateEmailAndContact();
            }

            // Affichage département / signature selon le rôle
            function signatureIsRequired() {
                var isEnseignantRole = $('#role').val() == 'Enseignant';
                var isUtilisateurSimple = $('#utilisateur').is(':checked');
                return !(isEnseignantRole || isUtilisateurSimple);
            }
            function manageSignatureRequirement() {
                // En mode « dessin » le champ fichier est masqué : jamais required (sinon submit bloqué)
                if ($('#sigModeDraw').is(':checked') || !signatureIsRequired()) { $("#image").removeAttr('required'); }
                else { $("#image").attr('required', 'required'); }
            }

            $('#role').change(function () {
                if ($(this).val() == 'Chef DR') $("#departement").removeClass('d-none');
                else $("#departement").addClass('d-none');
                if ($(this).val() == 'Enseignant') $("#c_signature").addClass('d-none');
                else $("#c_signature").removeClass('d-none');
                manageSignatureRequirement();
            });

            $('#utilisateur').on('change', function () { $("#c_signature").addClass('d-none'); manageSignatureRequirement(); });
            $('#enseignant').on('change', function () { $("#c_signature").removeClass('d-none'); manageSignatureRequirement(); });

            // Aperçu de la signature
            var image = document.getElementById('image');
            var imagePreview = document.getElementById('imagePreview');
            if (image) image.addEventListener("change", function () {
                var file = image.files[0];
                if (file) { var reader = new FileReader(); reader.onload = function (e) { imagePreview.src = e.target.result; }; reader.readAsDataURL(file); }
            });

            // ===== Pavé de signature (dessin direct) =====
            var sigCanvas = document.getElementById('sigCanvas');
            var sigCtx = sigCanvas ? sigCanvas.getContext('2d') : null;
            var sigDrawing = false, sigHasDrawn = false, sigLastX = 0, sigLastY = 0;

            function sigUpdateHint() {
                var h = document.getElementById('sigHint');
                if (h) h.style.display = sigHasDrawn ? 'none' : '';
            }
            function resizeSigCanvas() {
                if (!sigCanvas || !sigCtx) return;
                var ratio = window.devicePixelRatio || 1;
                var w = sigCanvas.parentElement.clientWidth || 420, h = 160;
                sigCanvas.width = w * ratio; sigCanvas.height = h * ratio;
                sigCanvas.style.width = w + 'px'; sigCanvas.style.height = h + 'px';
                sigCtx.setTransform(ratio, 0, 0, ratio, 0, 0);
                sigCtx.lineWidth = 2; sigCtx.lineCap = 'round'; sigCtx.lineJoin = 'round'; sigCtx.strokeStyle = '#0f2a52';
                sigHasDrawn = false; sigUpdateHint();
            }
            function sigPos(e) {
                var r = sigCanvas.getBoundingClientRect();
                var cx = e.touches ? e.touches[0].clientX : e.clientX;
                var cy = e.touches ? e.touches[0].clientY : e.clientY;
                return { x: cx - r.left, y: cy - r.top };
            }
            function sigStart(e) { sigDrawing = true; var p = sigPos(e); sigLastX = p.x; sigLastY = p.y; if (e.cancelable) e.preventDefault(); }
            function sigMove(e) {
                if (!sigDrawing) return;
                var p = sigPos(e);
                sigCtx.beginPath(); sigCtx.moveTo(sigLastX, sigLastY); sigCtx.lineTo(p.x, p.y); sigCtx.stroke();
                sigLastX = p.x; sigLastY = p.y; sigHasDrawn = true; sigUpdateHint();
                if (e.cancelable) e.preventDefault();
            }
            function sigEnd() { sigDrawing = false; }
            if (sigCanvas) {
                sigCanvas.addEventListener('mousedown', sigStart);
                sigCanvas.addEventListener('mousemove', sigMove);
                window.addEventListener('mouseup', sigEnd);
                sigCanvas.addEventListener('touchstart', sigStart, { passive: false });
                sigCanvas.addEventListener('touchmove', sigMove, { passive: false });
                sigCanvas.addEventListener('touchend', sigEnd);
            }
            $('#sigClear').on('click', function () {
                if (sigCtx) { sigCtx.clearRect(0, 0, sigCanvas.width, sigCanvas.height); sigHasDrawn = false; sigUpdateHint(); }
            });
            // Bascule téléverser / signer
            $('input[name="sig_mode"]').on('change', function () {
                var draw = $('#sigModeDraw').is(':checked');
                $('#sigDraw').toggle(draw);
                $('#sigUpload').toggle(!draw);
                if (draw) { $('#image').val('').removeAttr('required'); resizeSigCanvas(); }
                else { manageSignatureRequirement(); }
            });

            // Validation + conversion du dessin en image à l'envoi
            $('#addUserModal form').on('submit', function (e) {
                var form = this;
                var drawMode = $('#sigModeDraw').is(':checked');

                if (drawMode) {
                    if (!sigHasDrawn) {
                        if (signatureIsRequired()) { e.preventDefault(); alert('Veuillez signer dans le cadre.'); }
                        return; // signature non requise et vide → envoi sans signature
                    }
                    // Le dessin existe : conversion en PNG injecté dans le champ fichier #image
                    e.preventDefault();
                    sigCanvas.toBlob(function (blob) {
                        try {
                            var dt = new DataTransfer();
                            dt.items.add(new File([blob], 'signature.png', { type: 'image/png' }));
                            document.getElementById('image').files = dt.files;
                        } catch (err) { /* navigateur trop ancien : ignoré */ }
                        form.submit(); // validation HTML déjà passée ; save_user est en champ caché
                    }, 'image/png');
                    return;
                }

                // Mode téléverser (comportement d'origine)
                if ($('#image').is(':visible') && $('#image').prop('required') && !$('#image').val()) {
                    e.preventDefault(); alert('Veuillez téléverser une signature'); $('#image').focus();
                }
            });

            manageSignatureRequirement();
        });

        // ============ Filtre liste (recherche + segment) ============
        (function () {
            var typeFilter = 'all';
            function apply() {
                var q = (document.getElementById('usrSearch') ? document.getElementById('usrSearch').value : '').toLowerCase().trim();
                var rows = document.querySelectorAll('#usersTable tbody tr'), shown = 0;
                rows.forEach(function (tr) {
                    var okType = (typeFilter === 'all') || (tr.getAttribute('data-type') === typeFilter);
                    var okQ = !q || (tr.getAttribute('data-search') || '').indexOf(q) >= 0;
                    var ok = okType && okQ;
                    tr.style.display = ok ? '' : 'none';
                    if (ok) shown++;
                });
                var nr = document.getElementById('usrNoResult');
                if (nr) nr.style.display = (rows.length && shown === 0) ? '' : 'none';
            }
            var s = document.getElementById('usrSearch');
            if (s) s.addEventListener('keyup', apply);
            document.querySelectorAll('.seg-btn').forEach(function (b) {
                b.addEventListener('click', function () {
                    document.querySelectorAll('.seg-btn').forEach(function (x) { x.classList.remove('active'); });
                    this.classList.add('active'); typeFilter = this.dataset.type; apply();
                });
            });
        })();

        // ============ Activation / désactivation (Swal) ============
        document.querySelectorAll('.btn-statut').forEach(function (b) {
            b.addEventListener('click', function () {
                var id = this.dataset.id, next = this.dataset.next, nom = this.dataset.nom, act = this.dataset.active === '1';
                Swal.fire({
                    title: act ? 'Désactiver le compte ?' : 'Activer le compte ?',
                    html: 'Le compte de <b>' + nom + '</b> sera ' + (act ? 'désactivé' : 'activé') + '.',
                    icon: 'warning', showCancelButton: true,
                    confirmButtonText: act ? 'Désactiver' : 'Activer', cancelButtonText: 'Annuler',
                    confirmButtonColor: act ? '#b91c1c' : '#16794a'
                }).then(function (r) {
                    if (r && (r.isConfirmed || (r.value && !r.dismiss))) {
                        document.getElementById('sf_id').value = id;
                        document.getElementById('sf_statut').value = next;
                        document.getElementById('statutForm').submit();
                    }
                });
            });
        });

        // ============ Pré-remplissage de la modale d'édition ============
        document.querySelectorAll('.btn-edit-user').forEach(function (b) {
            b.addEventListener('click', function () {
                document.getElementById('eu_id').value = this.dataset.id;
                document.getElementById('eu_nom').value = this.dataset.nom || '';
                document.getElementById('eu_email').value = this.dataset.email || '';
                document.getElementById('eu_contact').value = this.dataset.contact || '';
                document.getElementById('eu_role').value = this.dataset.role || '';
                document.getElementById('eu_pass').value = '';
                var isEns = this.dataset.ens === '1';
                document.getElementById('eu_nom').readOnly = isEns;
                document.getElementById('eu_ensNote').style.display = isEns ? 'block' : 'none';
                guConfOpen('editUserModal');
            });
        });
    </script>
</body>

</html>
