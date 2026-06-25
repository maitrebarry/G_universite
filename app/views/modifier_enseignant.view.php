<?php $this->view("Partials/header") ?>

<style>
    #ensEdit .gu-card { background: #fff; border: 1px solid #e7ecf5; border-radius: 12px; padding: 18px; margin-bottom: 16px; }
    #ensEdit .form-label { font-size: .85rem; font-weight: 500; color: #475569; margin-bottom: 4px; }
</style>

<body class="vertical-layout vertical-menu-modern boxicon-layout no-card-shadow 2-columns navbar-sticky footer-static"
    data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">

    <?php $this->view("Partials/navbar") ?>
    <?php $this->view("Partials/seibar") ?>

    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-12 mb-2 mt-1">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h5 class="content-header-title float-left pr-1 mb-0">Modifier un enseignant</h5>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb p-0 mb-0">
                                    <li class="breadcrumb-item"><a href="<?= ROOT ?>/Homes"><i class="bx bx-home-alt"></i></a></li>
                                    <li class="breadcrumb-item"><a href="<?= ROOT ?>/Enseignants">Enseignants</a></li>
                                    <li class="breadcrumb-item active">Modification</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-body" id="ensEdit">
                <?php $this->view("set_flash"); ?>
                <?php if (!empty($errors)): ?>
                    <div class="gu-card" style="border-color:#f0b4b4;background:#fdecec;">
                        <div style="color:#b91c1c;font-weight:600;"><i class="bx bx-error-circle"></i> Veuillez corriger :</div>
                        <ul style="margin:6px 0 0;color:#b91c1c;font-size:13px;">
                            <?php foreach ($errors as $error): ?><li><?= htmlspecialchars($error) ?></li><?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php if (!empty($enseignant)): ?>
                <form method="POST" enctype="multipart/form-data" id="updateForm">
                    <div class="gu-card">
                        <div class="gu-section-title"><span class="gu-ico-chip"><i class="bx bx-user"></i></span>
                            <?= htmlspecialchars($enseignant->enseignant_prenom . ' ' . $enseignant->enseignant_nom) ?>
                        </div>
                        <div class="row mt-1" style="row-gap:14px;">
                            <div class="col-12">
                                <label class="form-label" for="statut">Statut</label>
                                <select class="form-select" name="enseignant_statut" id="statut" onchange="toggleFields()">
                                    <option value="NON_PERMANANT" <?= $enseignant->enseignant_statut === 'NON_PERMANANT' ? 'selected' : '' ?>>Non permanent (vacataire)</option>
                                    <option value="PERMANANT" <?= $enseignant->enseignant_statut === 'PERMANANT' ? 'selected' : '' ?>>Permanent</option>
                                </select>
                            </div>
                            <div class="col-sm-6"><label class="form-label">Nom</label><input type="text" name="enseignant_nom" class="form-control" value="<?= htmlspecialchars($enseignant->enseignant_nom) ?>"></div>
                            <div class="col-sm-6"><label class="form-label">Prénom</label><input type="text" name="enseignant_prenom" class="form-control" value="<?= htmlspecialchars($enseignant->enseignant_prenom) ?>"></div>
                            <div class="col-sm-6"><label class="form-label">Matricule</label><input type="text" name="enseignant_matricule" class="form-control" value="<?= htmlspecialchars($enseignant->enseignant_matricule) ?>"></div>
                            <div class="col-sm-6"><label class="form-label">Date de naissance</label><input type="date" name="enseignant_date_naissance" class="form-control" value="<?= htmlspecialchars($enseignant->enseignant_date_naissance) ?>"></div>
                            <div class="col-sm-6"><label class="form-label">Email</label><input type="email" name="enseignant_email" class="form-control" value="<?= htmlspecialchars($enseignant->enseignant_email) ?>"></div>
                            <div class="col-sm-6"><label class="form-label">Téléphone</label><input type="text" name="enseignant_telephone" class="form-control" value="<?= htmlspecialchars($enseignant->enseignant_telephone) ?>" oninput="formatPhoneNumber(this)"></div>
                        </div>
                    </div>

                    <div class="gu-card">
                        <div class="gu-section-title"><span class="gu-ico-chip"><i class="bx bx-file"></i></span> Diplôme & documents</div>
                        <div class="row mt-1" style="row-gap:14px;">
                            <div class="col-sm-6">
                                <label class="form-label">Diplôme</label>
                                <select class="form-select" name="enseignant_diplome">
                                    <option value="Master" <?= $enseignant->enseignant_diplome === 'Master' ? 'selected' : '' ?>>Master</option>
                                    <option value="Doctorat" <?= $enseignant->enseignant_diplome === 'Doctorat' ? 'selected' : '' ?>>Doctorat</option>
                                </select>
                            </div>
                            <div class="col-sm-6" id="cv-container">
                                <label class="form-label">CV</label>
                                <input type="file" name="enseignant_cv" class="form-control">
                                <?php if (!empty($enseignant->enseignant_cv)): ?><small>Fichier existant : <a href="<?= ROOT ?>/<?= htmlspecialchars(ltrim($enseignant->enseignant_cv, '/')) ?>" target="_blank">Voir</a></small><?php endif; ?>
                            </div>
                            <!-- PERMANANT : grade -->
                            <div id="grade-container" class="col-sm-6" style="display: <?= $enseignant->enseignant_statut === 'PERMANANT' ? 'block' : 'none' ?>;">
                                <label class="form-label">Grade</label>
                                <select class="form-select" name="id_grade">
                                    <?php foreach ($grades as $grade): ?>
                                        <option value="<?= $grade->id_grade ?>" <?= $enseignant->id_grade == $grade->id_grade ? 'selected' : '' ?>><?= htmlspecialchars($grade->nom_grade, ENT_QUOTES, 'UTF-8') ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <!-- NON PERMANANT : contrat + code bancaire -->
                            <div id="non-permanent-fields" class="col-12" style="display: <?= $enseignant->enseignant_statut === 'NON_PERMANANT' ? 'block' : 'none' ?>;">
                                <div class="row" style="row-gap:14px;">
                                    <div class="col-sm-6">
                                        <label class="form-label">Contrat</label>
                                        <input type="file" name="contrat" class="form-control">
                                        <?php if (!empty($enseignant->contrat)): ?><small>Fichier existant : <a href="<?= ROOT ?>/<?= htmlspecialchars(ltrim($enseignant->contrat, '/')) ?>" target="_blank">Voir</a></small><?php endif; ?>
                                    </div>
                                    <div class="col-sm-6"><label class="form-label">Code bancaire</label><input type="text" name="code_bancaire" class="form-control" value="<?= htmlspecialchars($enseignant->code_bancaire ?? '') ?>"></div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end mt-3" style="gap:8px;">
                            <a href="<?= ROOT ?>/Enseignants" class="btn btn-ghost">Annuler</a>
                            <button name="submit" type="submit" class="btn btn-primary"><i class="bx bx-save"></i> Enregistrer</button>
                        </div>
                    </div>
                </form>
                <?php else: ?>
                    <div class="gu-empty"><i class="bx bx-error-circle"></i>Enseignant introuvable.</div>
                <?php endif ?>
            </div>
        </div>
    </div>

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <?php $this->view("Partials/foot") ?>
    <?php $this->view("Partials/footer") ?>
    <script>
        function toggleFields() {
            const statut = document.getElementById("statut").value;
            document.getElementById("grade-container").style.display = (statut === "PERMANANT") ? "block" : "none";
            document.getElementById("non-permanent-fields").style.display = (statut === "PERMANANT") ? "none" : "block";
            document.getElementById("cv-container").style.display = (statut === "PERMANANT") ? "none" : "block";
        }
        document.addEventListener('DOMContentLoaded', toggleFields);
    </script>
    <script src="<?= ROOT ?>/assets/mon_js/formate_numberphone.js"></script>
</body>

</html>
