<!-- inclusion du partie header -->
<?php $this->view("Partials/header") ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<body
    class="vertical-layout vertical-menu-modern boxicon-layout no-card-shadow 2-columns  navbar-sticky footer-static  "
    data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">

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
                            <h5 class="content-header-title float-left pr-1 mb-0">Incription des Etudiants</h5>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb p-0 mb-0">
                                    <li class="breadcrumb-item"><a href="index.html"><i class="bx bx-home-alt"></i></a>
                                    </li>
                                    <li class="breadcrumb-item"><a href="#">Gestion Etudiants</a>
                                    </li>
                                    <li class="breadcrumb-item active">Liste des élèves a inscrire
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <!-- formulaire -->
                <section id="table-chechbox">
                    <div class="row">
                        <?php $this->view("set_flash") ?>
                        <!-- HTML avec classes Bootstrap -->
                        <!-- HTML pour la barre de progression -->
                        <div id="progressContainer" style="display: none;">
                            <div id="progressBar"></div>
                        </div>
                        <div class="col-12">
                            <div class="card card-animated-border-top">
                                <form id="form_assoc" action="<?= ROOT ?>/EtudiantPargroupes/importerEnChunks"
                                    method="post" enctype="multipart/form-data">
                                    <div class="card-content">
                                        <div class="card-body">
                                            <div class="row g-4">
                                                <!-- Colonne gauche : Import fichier et mapping -->
                                                <div class="col-md-6">
                                                    <div class="card shadow-sm">
                                                        <div class="card-header bg-light  text-white">
                                                            <i class="fas fa-file-excel me-2"></i> Importer un fichier
                                                            Excel
                                                        </div>
                                                        <div class="card-body">
                                                            <!-- Message de chargement -->
                                                            <div id="loadingMessage" class="alert alert-info d-none">
                                                                <i class="fas fa-spinner fa-spin me-2"></i>Chargement du
                                                                fichier en cours, veuillez patienter...
                                                            </div>

                                                            <!-- Input fichier Excel -->
                                                            <div class="mb-3">
                                                                <label for="excel_file" class="form-label">Choisir un
                                                                    fichier Excel :</label>
                                                                <input type="file" name="excel_file" id="excel_file"
                                                                    accept=".xlsx,.xls" class="form-control" required>
                                                            </div>

                                                            <!-- Section mapping -->
                                                            <div id="mappingSection" style="display: none;">
                                                                <h5 class="mt-4 mb-3">Associer les colonnes du fichier
                                                                    aux champs de la base</h5>
                                                                <div class="table-responsive">
                                                                    <table class="table table-bordered align-middle">
                                                                        <thead class="table-light">
                                                                            <tr>
                                                                                <th>Colonne Excel</th>
                                                                                <th>Champ Base de Données</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody id="mapping_table_body">
                                                                            <!-- Remplissage dynamique via JS -->
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Colonne droite : Année et Promotion -->
                                                <div class="col-md-6">
                                                    <div class="card shadow-sm">
                                                        <div class="card-header bg-light text-white">
                                                            <i class="fas fa-graduation-cap me-2"></i> Informations de
                                                            l'importation
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <!-- Année universitaire -->
                                                                <div class="col-md-6 mb-3">
                                                                    <label for="annee_universitaire"
                                                                        class="form-label">Année universitaire <span
                                                                            class="text-danger">*</span></label>
                                                                    <select class="form-select" id="annee_universitaire"
                                                                        required>
                                                                        <option value="">-- Sélectionner l'année --
                                                                        </option>
                                                                        <?php foreach ($listeParAnnee as $annee => $promos): ?>
                                                                        <option value="<?= htmlspecialchars($annee) ?>">
                                                                            <?= htmlspecialchars($annee) ?></option>
                                                                        <?php endforeach; ?>
                                                                    </select>
                                                                </div>

                                                                <!-- Promotion -->
                                                                <div class="col-md-6 mb-3">
                                                                    <label for="id_promotion"
                                                                        class="form-label">Promotion <span
                                                                            class="text-danger">*</span></label>
                                                                    <select class="form-select" id="id_promotion"
                                                                        name="id_promotion" required>
                                                                        <option value="">-- Sélectionner la promotion --
                                                                        </option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <!-- Bouton Valider -->
                                                            <div class="text-end">
                                                                <button type="submit"
                                                                    class="btn bg-success text-white ">
                                                                    <i class="fas fa-check-circle me-1"></i> Valider
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div> <!-- fin card-body -->
                                    </div> <!-- fin card-content -->
                                </form>
                            </div> <!-- fin card -->
                        </div> <!-- fin col-12 -->

                    </div>

                </section>

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
    <script>
    const promotionsParAnnee = <?= json_encode($listeParAnnee) ?>;

    const selectAnnee = document.getElementById('annee_universitaire');
    const selectPromotion = document.getElementById('id_promotion');

    selectAnnee.addEventListener('change', function() {
        const anneeSelectionnee = this.value;

        // Réinitialiser les promotions
        selectPromotion.innerHTML = '<option value="">-- Sélectionner la promotion --</option>';

        if (anneeSelectionnee && promotionsParAnnee[anneeSelectionnee]) {
            promotionsParAnnee[anneeSelectionnee].forEach(promo => {
                const option = document.createElement('option');
                option.value = promo.id_promotion;
                option.textContent =
                    `${promo.sigle_filiere}-${promo.sigle_semestre} (${promo.annee_universitaire})`;
                selectPromotion.appendChild(option);
            });
        }
    });
    </script>
    <script>
    document.getElementById('form_assoc').addEventListener('submit', function(e) {
        // Empêcher la soumission immédiate pour voir l'animation
        e.preventDefault();

        // Récupérer les éléments
        let progressContainer = document.getElementById('progressContainer');
        let progressBar = document.getElementById('progressBar');

        // Styliser le conteneur pour le rendre bien visible
        progressContainer.style.display = 'block';
        progressContainer.style.margin = '20px 0';
        progressContainer.style.width = '100%';
        progressContainer.style.backgroundColor = '#f1f1f1';
        progressContainer.style.borderRadius = '5px';

        // Styliser la barre de progression
        progressBar.style.height = '30px';
        progressBar.style.borderRadius = '5px';
        progressBar.style.backgroundColor = '#4CAF50';
        progressBar.style.textAlign = 'center';
        progressBar.style.lineHeight = '30px';
        progressBar.style.color = 'white';
        progressBar.style.fontWeight = 'bold';
        progressBar.style.transition = 'width 0.3s ease';

        // Initialiser la progression
        let width = 0;
        progressBar.style.width = width + '%';
        progressBar.innerText = width + '%';

        // Animation de progression
        let interval = setInterval(() => {
            if (width >= 95) {
                clearInterval(interval);
                // Soumettre le formulaire après l'animation
                setTimeout(() => {
                    this.submit();
                }, 500);
            } else {
                width += 5;
                progressBar.style.width = width + '%';
                progressBar.innerText = width + '%';
            }
        }, 300);
    });
    </script>

    </script>
    <script>
    $(document).ready(function() {
        $('#id_promotion').change(function() {
            const id_promotion = $('#id_promotion').val();


            if (id_promotion != null) {
                $.ajax({
                    url: '<?= ROOT ?>/EtudiantPargroupes/trier_liste_etudiants',
                    type: 'POST',
                    data: {
                        id_promotion: id_promotion
                    },
                    success: function(response) {
                        // console.log(response);
                        $('#table_etudiant').html(response);

                    },
                    error: function(xhr) {
                        alert("Erreur AJAX : " + xhr.responseText);
                    }
                });
            }
        });

        // Suppression des lignes du tableau
        $(document).on('click', '.remove', function(e) {
            e.preventDefault();
            $(this).closest("tr").remove();
        });
    });
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
    <script src="<?= ROOT ?>/assets/mon_js/script_extration.js"></script>

    <script>
    document.getElementById('excel_file').addEventListener('change', function() {
        const loadingMessage = document.getElementById('loadingMessage');
        const mappingSection = document.getElementById('mappingSection');

        // Affiche le message de chargement
        loadingMessage.classList.remove('d-none');
        mappingSection.style.display = 'none';

        // Simule le chargement du fichier (à remplacer par ton vrai traitement si besoin)
        setTimeout(() => {
            loadingMessage.classList.add('d-none'); // Cache le message
            mappingSection.style.display = 'block'; // Affiche la suite
        }, 1500); // 1.5 secondes d'attente
    });
    </script>

    <script>
    const champsBdd = <?= json_encode($champsBdd) ?>; // Les champs de la BDD fournis depuis le serveur

    document.getElementById('excel_file').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;

        const reader = new FileReader();

        reader.onload = function(e) {
            const data = new Uint8Array(e.target.result);
            const workbook = XLSX.read(data, {
                type: 'array'
            });

            const sheetName = workbook.SheetNames[0];
            const worksheet = workbook.Sheets[sheetName];
            const rows = XLSX.utils.sheet_to_json(worksheet, {
                header: 1
            });

            const entetes = rows[0]; // première ligne : entêtes
            const tableBody = document.getElementById('mapping_table_body');
            tableBody.innerHTML = '';

            entetes.forEach((entete, index) => {
                const row = document.createElement('tr');

                const colExcel = document.createElement('td');
                colExcel.textContent = entete;

                const colBdd = document.createElement('td');
                const select = document.createElement('select');
                select.name = `correspondances[${index}]`;

                const optionNone = document.createElement('option');
                optionNone.value = '';
                optionNone.textContent = '-- Ne pas associer --';
                select.appendChild(optionNone);

                champsBdd.forEach(champ => {
                    const opt = document.createElement('option');
                    opt.value = champ;
                    opt.textContent = champ;
                    select.appendChild(opt);
                });

                colBdd.appendChild(select);
                row.appendChild(colExcel);
                row.appendChild(colBdd);

                tableBody.appendChild(row);
            });
        };

        reader.readAsArrayBuffer(file);
    });
    </script>
    <script>
    document.getElementById('excel_file').addEventListener('change', function() {
        const loadingMessage = document.getElementById('loadingMessage');
        const mappingSection = document.getElementById('mappingSection');

        // Affiche le message de chargement
        loadingMessage.classList.remove('d-none');
        mappingSection.style.display = 'none';

        // Simule le chargement du fichier (à remplacer par ton vrai traitement si besoin)
        setTimeout(() => {
            loadingMessage.classList.add('d-none'); // Cache le message
            mappingSection.style.display = 'block'; // Affiche la suite
        }, 1500); // 1.5 secondes d'attente
    });
    </script>

</body>
<!-- END: Body-->

</html>