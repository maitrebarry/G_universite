<?php $this->view("Partials/header") ?>

<style>
    #guWizard .gu-card { background: #fff; border: 1px solid #e7ecf5; border-radius: 12px; padding: 18px; margin-bottom: 16px; }
    #guWizard .form-label { font-size: .85rem; font-weight: 500; color: #475569; margin-bottom: 4px; }
    #guWizard .char-count { font-size: 11px; color: #8a97ad; text-align: right; }
    #guWizard .error { font-size: 12px; color: #b91c1c; }

    /* Bloc semestre */
    #guWizard .section { background: #f6f8fc; border: 1px solid #dbe3f0; border-radius: 12px; padding: 14px 16px; margin-top: 14px; }
    #guWizard .section > h4 { color: #14346b; font-weight: 700; font-size: 15px; text-align: center; background: #14346b; color: #fff; padding: 6px; border-radius: 8px; }
    #guWizard table { width: 100%; border-collapse: collapse; background: #fff; }
    #guWizard table thead th { background: #e7ecf5; color: #14346b; font-size: 12px; padding: 6px 8px; border: 1px solid #cfd8e8; text-align: left; }
    #guWizard table td { border: 1px solid #e3e8f2; padding: 8px; vertical-align: top; }
    #guWizard .nomUe { font-weight: 600; }

    /* Module item */
    #guWizard .module-list { list-style: none; padding-left: 0; margin: 8px 0 0; }
    #guWizard .module-item { border: 1px solid #dbe3f0; border-radius: 10px; padding: 12px; margin: 8px 0; background: #f9fbfe; }
    #guWizard .module-item label { display: block; font-size: 10.5px; color: #5a6b86; text-transform: uppercase; letter-spacing: .3px; margin-bottom: 2px; text-align: center; }
    #guWizard .module-item .form-control { text-align: center; }
    #guWizard .btn-danger { background: #fdecec; color: #b91c1c; border: 1px solid #f0b4b4; }
    #guWizard .btn-danger:hover { background: #f8d7d7; }
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
                            <h5 class="content-header-title float-left pr-1 mb-0">Créer une filière</h5>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb p-0 mb-0">
                                    <li class="breadcrumb-item"><a href="<?= ROOT ?>/Homes"><i class="bx bx-home-alt"></i></a></li>
                                    <li class="breadcrumb-item"><a href="<?= ROOT ?>/Filieres">Filières</a></li>
                                    <li class="breadcrumb-item active">Nouvelle</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-body" id="guWizard">
                <!-- Étape 1 : Département -->
                <div class="gu-card">
                    <div class="gu-section-title"><span class="gu-ico-chip"><i class="bx bx-building"></i></span> Département de rattachement</div>
                    <div class="row mt-1" style="row-gap:12px;">
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="idDepartement">Département <span class="text-danger">*</span></label>
                            <select name="idDepartement" id="idDepartement" class="form-select">
                                <option value="">Choisir un département…</option>
                                <?php foreach ($departements as $departement): ?>
                                    <option value="<?= $departement->id_departement ?>" data-sigle="<?= strtoupper(htmlspecialchars($departement->sigle_departement)) ?>">
                                        <?= strtoupper(htmlspecialchars($departement->nom_departement)) ?> (<?= strtoupper(htmlspecialchars($departement->sigle_departement)) ?>)
                                    </option>
                                <?php endforeach ?>
                            </select>
                            <small class="text-muted">Sélectionnez d'abord le département pour continuer.</small>
                        </div>
                    </div>
                </div>

                <!-- Étape 2 : la filière (révélée après choix du département) -->
                <div id="containerFiliere" class="d-none">
                    <div id="message"></div>

                    <div class="gu-card">
                        <div class="gu-section-title"><span class="gu-ico-chip"><i class="bx bx-bookmark-alt"></i></span> Identité de la filière</div>
                        <div class="row mt-1" style="row-gap:12px;">
                            <div class="col-12 col-md-7">
                                <label class="form-label" for="nomFiliere">Nom de la filière</label>
                                <input type="text" id="nomFiliere" class="form-control" placeholder="ex : Génie Informatique" maxlength="101">
                                <div class="char-count" id="nomFiliereCount">0/100</div>
                                <div class="error" id="nomFiliereError"></div>
                            </div>
                            <div class="col-12 col-md-5">
                                <label class="form-label" for="sigleFiliere">Code de la filière</label>
                                <input type="text" id="sigleFiliere" class="form-control" placeholder="ex : GI" maxlength="50">
                            </div>
                        </div>
                    </div>

                    <div class="gu-card">
                        <div class="gu-section-title"><span class="gu-ico-chip"><i class="bx bx-layer"></i></span> Programme — semestres, UE & modules</div>
                        <div class="row align-items-end mt-1" style="row-gap:12px;">
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="idSemestre">Ajouter un semestre</label>
                                <select id="idSemestre" class="form-select" onchange="addSemestre()">
                                    <option value="">Choisir un semestre…</option>
                                    <?php foreach ($semestres as $semestre): ?>
                                        <option value="<?= $semestre->id_semestre ?>"><?= strtoupper(htmlspecialchars($semestre->nom_semestre)) ?> (<?= strtoupper(htmlspecialchars($semestre->sigle_semestre)) ?>)</option>
                                    <?php endforeach ?>
                                </select>
                                <div class="error" id="idSemestreError"></div>
                            </div>
                        </div>

                        <div id="semestresTable"></div>

                        <div class="d-flex justify-content-end mt-3">
                            <button type="button" class="btn btn-primary" id="btnEnregistrer" onclick="ajouterFiliere()"><i class="bx bx-save"></i> Enregistrer la filière</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <?php $this->view("Partials/foot") ?>
    <?php $this->view("Partials/footer") ?>
    <script>const ROOT = "<?= ROOT ?>";</script>
    <script src="<?= ROOT ?>/assets/mon_js/filiere.js"></script>
    <script>
        $(document).ready(function () {
            // Révéler le formulaire dès qu'un département est choisi
            $('#idDepartement').change(function () {
                if ($(this).val() && $(this).val().trim() !== "") {
                    $('#containerFiliere').removeClass('d-none');
                } else {
                    $('#containerFiliere').addClass('d-none');
                }
            });
            // Compteur de caractères
            $('#nomFiliere').on('input', function () {
                $('#nomFiliereCount').text($(this).val().length + '/100');
            });
        });
    </script>
</body>

</html>
