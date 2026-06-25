<?php $this->view("Partials/header") ?>

<style>
    #ensForm .gu-card { background: #fff; border: 1px solid #e7ecf5; border-radius: 12px; padding: 18px; margin-bottom: 16px; }
    #ensForm .form-label { font-size: .85rem; font-weight: 500; color: #475569; margin-bottom: 4px; }
    #ensForm .btn-outline-primary { border: 1px solid #c7d2e6; color: #1f4f9c; background: #fff; }
    #ensForm .btn-outline-primary.active { background: #1f4f9c; color: #fff; border-color: #1f4f9c; }
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
                            <h5 class="content-header-title float-left pr-1 mb-0">Nouvel enseignant</h5>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb p-0 mb-0">
                                    <li class="breadcrumb-item"><a href="<?= ROOT ?>/Homes"><i class="bx bx-home-alt"></i></a></li>
                                    <li class="breadcrumb-item"><a href="<?= ROOT ?>/Enseignants">Enseignants</a></li>
                                    <li class="breadcrumb-item active">Enregistrement</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-body" id="ensForm">
                <?php $this->view("set_flash"); ?>
                <?php if (!empty($errors)): ?>
                    <div class="gu-card" style="border-color:#f0b4b4;background:#fdecec;">
                        <div style="color:#b91c1c;font-weight:600;"><i class="bx bx-error-circle"></i> Veuillez corriger :</div>
                        <ul style="margin:6px 0 0;color:#b91c1c;font-size:13px;">
                            <?php foreach ($errors as $error): ?><li><?= htmlspecialchars($error) ?></li><?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="POST" class="form" enctype="multipart/form-data">
                    <div class="gu-card">
                        <div class="gu-section-title"><span class="gu-ico-chip"><i class="bx bx-id-card"></i></span> Statut & documents</div>
                        <div class="row mt-1" style="row-gap:14px;">
                            <div class="col-md-6">
                                <label class="form-label">Statut <span class="text-danger">*</span></label>
                                <select name="statut" id="statut" class="form-select" onchange="toggleFields()">
                                    <option value="" disabled selected>Choisissez le statut</option>
                                    <option value="NON_PERMANANT">Non permanent (vacataire)</option>
                                    <option value="PERMANANT">Permanent</option>
                                </select>
                            </div>
                            <div class="col-md-6" id="cv-container">
                                <label class="form-label">CV <span class="text-danger">*</span></label>
                                <input type="file" name="cv" class="form-control">
                            </div>
                            <div class="col-md-6" id="contarat-container">
                                <label class="form-label">Contrat <span class="text-danger">*</span></label>
                                <input type="file" name="contrat" class="form-control">
                            </div>
                            <div class="col-md-6" id="code-container">
                                <label class="form-label">Code bancaire <span class="text-danger">*</span></label>
                                <input name="code" id="code" type="text" class="form-control" placeholder="Code bancaire" value="<?= htmlspecialchars($input_values['code'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                            </div>
                            <div class="col-md-3" id="administration-container" style="display:none;">
                                <label class="form-label">Administration</label>
                                <div class="btn-group w-100" role="group">
                                    <button type="button" class="btn btn-outline-primary active" data-value="0">Non</button>
                                    <button type="button" class="btn btn-outline-primary" data-value="1">Oui</button>
                                </div>
                                <input type="hidden" name="administration" id="administrationInput" value="0">
                            </div>
                            <div class="col-md-3" id="grade-container" style="display:none;">
                                <label class="form-label">Grade <span class="text-danger">*</span></label>
                                <select id="grade" name="grade" class="form-select">
                                    <option selected disabled>Choisissez le grade</option>
                                    <?php foreach ($filiere as $filieres): ?>
                                        <option value="<?= $filieres->id_grade ?>"><?= htmlspecialchars($filieres->nom_grade) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="gu-card">
                        <div class="gu-section-title"><span class="gu-ico-chip"><i class="bx bx-user"></i></span> Identité</div>
                        <div class="row mt-1" style="row-gap:14px;">
                            <div class="col-md-6">
                                <label class="form-label">Matricule <span class="text-danger">*</span></label>
                                <input type="text" name="matricule" id="matricule" class="form-control" placeholder="Matricule" value="<?= htmlspecialchars($input_values['matricule'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Date de naissance <span class="text-danger">*</span></label>
                                <input name="date_naissance" id="date_naissance" type="date" class="form-control" value="<?= htmlspecialchars($input_values['date_naissance'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nom <span class="text-danger">*</span></label>
                                <input name="nom" id="nom" type="text" class="form-control" placeholder="Nom" value="<?= htmlspecialchars($input_values['nom'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Prénom <span class="text-danger">*</span></label>
                                <input name="prenom" id="prenom" type="text" class="form-control" placeholder="Prénom" value="<?= htmlspecialchars($input_values['prenom'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                            </div>
                        </div>
                    </div>

                    <div class="gu-card">
                        <div class="gu-section-title"><span class="gu-ico-chip"><i class="bx bx-phone"></i></span> Contact & diplôme</div>
                        <div class="row mt-1" style="row-gap:14px;">
                            <div class="col-md-6">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input name="email" id="email" type="email" class="form-control" placeholder="email@exemple.com" value="<?= htmlspecialchars($input_values['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Téléphone <span class="text-danger">*</span></label>
                                <input name="telephone" id="telephone" type="text" class="form-control" placeholder="Téléphone" value="<?= htmlspecialchars($input_values['telephone'] ?? '', ENT_QUOTES, 'UTF-8') ?>" oninput="formatPhoneNumber(this)">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Diplôme <span class="text-danger">*</span></label>
                                <select name="diplome" id="diplome" class="form-select">
                                    <option value="" disabled selected>Choisissez le diplôme</option>
                                    <option value="Master">Master</option>
                                    <option value="Doctorat">Doctorat</option>
                                </select>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end mt-3" style="gap:8px;">
                            <a href="<?= ROOT ?>/Enseignants" class="btn btn-ghost">Annuler</a>
                            <button name="envoyer" type="submit" class="btn btn-primary"><i class="bx bx-save"></i> Enregistrer</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <?php $this->view("Partials/foot") ?>
    <?php $this->view("Partials/footer") ?>
    <script src="<?= ROOT ?>/assets/mon_js/validation_enseignant.js"></script>
    <script src="<?= ROOT ?>/assets/mon_js/formate_numberphone.js"></script>
    <?php
    // Groupes de grades (normal vs administration) pour le filtrage JS — défini AVANT le script.
    $gradesNormal = []; $gradesAdmin = [];
    foreach ($filiere as $g) { (strpos($g->nom_grade, '_admin') !== false) ? $gradesAdmin[] = $g : $gradesNormal[] = $g; }
    ?>
    <script>
        function toggleFields() {
            const statut = document.getElementById("statut").value;
            const show = (id, on) => document.getElementById(id).style.display = on ? "block" : "none";
            if (statut === "PERMANANT") {
                show("grade-container", true); show("administration-container", true);
                show("cv-container", false); show("contarat-container", false); show("code-container", false);
                document.getElementById('administrationInput').value = 0;
                document.querySelectorAll('#administration-container .btn').forEach(btn => {
                    btn.classList.toggle('active', btn.dataset.value === '0');
                });
                updateGradeOptions(0);
            } else {
                show("grade-container", false); show("administration-container", false);
                show("cv-container", true); show("contarat-container", true); show("code-container", true);
            }
        }

        document.querySelectorAll('#administration-container .btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const value = this.dataset.value;
                document.getElementById('administrationInput').value = value;
                this.parentNode.querySelectorAll('.btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                updateGradeOptions(value);
            });
        });

        function updateGradeOptions(isAdmin) {
            const gradeSelect = document.getElementById('grade');
            const currentValue = gradeSelect.value;
            gradeSelect.innerHTML = '<option selected disabled>Choisissez le grade</option>';
            const grades = isAdmin == 1 ? <?= json_encode($gradesAdmin ?? []) ?> : <?= json_encode($gradesNormal ?? []) ?>;
            (grades || []).forEach(grade => {
                const option = document.createElement('option');
                option.value = grade.id_grade; option.textContent = grade.nom_grade;
                if (grade.id_grade == currentValue) option.selected = true;
                gradeSelect.appendChild(option);
            });
        }

        document.addEventListener('DOMContentLoaded', function () {
            if (document.getElementById('statut').value === 'PERMANANT') toggleFields();
        });
    </script>
</body>

</html>
