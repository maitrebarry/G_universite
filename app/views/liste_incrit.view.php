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
                            <h5 class="content-header-title float-left pr-1 mb-0">Étudiants</h5>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb p-0 mb-0">
                                    <li class="breadcrumb-item"><a href="<?= ROOT ?>/Homes"><i class="bx bx-home-alt"></i></a></li>
                                    <li class="breadcrumb-item"><a href="#">Gestion des étudiants</a></li>
                                    <li class="breadcrumb-item active">Liste</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-body">
                <section id="basic-datatable">

                    <!-- Filtres -->
                    <div class="card mb-2">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-1" style="gap:8px;">
                                <i class="bx bx-filter-alt" style="font-size:1.2rem;color:var(--brand-600);"></i>
                                <span style="font-weight:600;">Filtrer la liste</span>
                            </div>
                            <form method="POST" action="<?= ROOT ?>/Etudiants/filtrer_etudiants">
                                <div class="row">
                                    <div class="col-md-4 mb-1">
                                        <label for="annee_universitaire" class="form-label">Année universitaire <span class="text-danger">*</span></label>
                                        <select class="form-select select2" id="annee_universitaire" required>
                                            <option value="">-- Sélectionner l'année --</option>
                                            <?php foreach ($listeParAnnee as $annee => $promos): ?>
                                                <option value="<?= htmlspecialchars($annee) ?>"><?= htmlspecialchars($annee) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-1">
                                        <label for="id_filiere" class="form-label">Filière <span class="text-danger">*</span></label>
                                        <select class="form-select select2" id="id_filiere" required>
                                            <option value="">-- Sélectionner la filière --</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-1">
                                        <label for="id_semestre" class="form-label">Semestre <span class="text-danger">*</span></label>
                                        <select class="form-select select2" id="id_semestre" required>
                                            <option value="">-- Sélectionner le semestre --</option>
                                        </select>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Liste -->
                    <div class="gu-table-card">
                        <div class="gu-toolbar">
                            <span class="gu-title"><i class="bx bx-group"></i> Liste des étudiants</span>
                            <div class="gu-field gu-search ms-auto" style="min-width:220px;">
                                <i class="bx bx-search gu-ico"></i>
                                <input type="text" id="customSearch" class="form-control has-ico" placeholder="Rechercher un étudiant...">
                            </div>
                            <a href="<?= ROOT ?>/Etudiants/export_liste" target="_blank" class="btn btn-soft-primary">
                                <i class="bx bx-download"></i> Exporter
                            </a>
                            <a href="<?= ROOT ?>/Etudiants/incrit_etudiant" class="btn btn-primary">
                                <i class="bx bx-plus"></i> Inscrire
                            </a>
                        </div>

                        <form action="<?= ROOT ?>/Etudiants/paiement_groupe" method="POST">
                            <div class="gu-table-wrap">
                                <table class="gu-table">
                                    <thead>
                                        <tr>
                                            <th style="width:42px;text-align:center;">
                                                <input class="form-check-input" type="checkbox" id="select-all" title="Tout sélectionner">
                                            </th>
                                            <th>Nom &amp; prénom</th>
                                            <th>Matricule</th>
                                            <th>Statut</th>
                                            <th>Filière</th>
                                            <th>Diplôme</th>
                                            <th style="text-align:right;">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="table_etudiant"></tbody>
                                </table>
                                <div id="liste_empty" class="gu-empty">
                                    <i class="bx bx-filter-alt"></i>
                                    Sélectionnez une année, une filière et un semestre pour afficher les étudiants.
                                </div>
                            </div>
                            <div class="gu-foot">
                                <span class="text-tertiary" style="font-size:var(--fs-sm);">Cochez des étudiants pour un paiement groupé.</span>
                                <button type="submit" class="btn btn-primary"><i class="bx bx-wallet"></i> Paiement en groupe</button>
                            </div>
                        </form>
                    </div>

                </section>
            </div>
        </div>
    </div>

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <?php $this->view("Partials/foot") ?>
    <?php $this->view("Partials/footer") ?>

    <script>
        document.getElementById('customSearch').addEventListener('input', function () {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll('#table_etudiant tr');
            rows.forEach(row => {
                let text = row.innerText.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        });
    </script>
    <script>
        document.getElementById('select-all').addEventListener('click', function (event) {
            event.stopPropagation();
        });
        document.getElementById('select-all').addEventListener('change', function () {
            const checkboxes = document.querySelectorAll('input[name="paie[]"]');
            checkboxes.forEach(checkbox => checkbox.checked = this.checked);
        });

        $(document).ready(function () {
            $('.select2').select2({ width: '100%' });

            const dataParAnnee = <?= json_encode($listeParAnnee) ?>;

            $('#annee_universitaire').on('change', function () {
                const annee = $(this).val();
                const $filiere = $('#id_filiere');
                const $semestre = $('#id_semestre');

                $filiere.empty().append('<option value="">-- Sélectionner la filière --</option>');
                $semestre.empty().append('<option value="">-- Sélectionner le semestre --</option>');

                if (dataParAnnee[annee]) {
                    const promos = dataParAnnee[annee];
                    const filieres = {};
                    promos.forEach(promo => { filieres[promo.id_filiere] = promo.sigle_filiere; });
                    for (const id in filieres) { $filiere.append(new Option(filieres[id], id)); }
                }
                $filiere.trigger('change.select2');
            });

            $('#id_filiere').on('change', function () {
                const annee = $('#annee_universitaire').val();
                const id_filiere = $(this).val();
                const $semestre = $('#id_semestre');

                $semestre.empty().append('<option value="">-- Sélectionner le semestre --</option>');

                if (dataParAnnee[annee]) {
                    const promos = dataParAnnee[annee];
                    const semestres = [];
                    promos.forEach(promo => {
                        if (promo.id_filiere == id_filiere) {
                            semestres.push({ id: promo.id_semestre, sigle: promo.sigle_semestre });
                        }
                    });
                    const unique = {};
                    semestres.forEach(s => {
                        if (!unique[s.id]) { unique[s.id] = true; $semestre.append(new Option(s.sigle, s.id)); }
                    });
                }
                $semestre.trigger('change.select2');
            });

            $('#id_semestre').on('change', function () {
                const annee = $('#annee_universitaire').val();
                const id_filiere = $('#id_filiere').val();
                const id_semestre = $(this).val();

                if (annee && id_filiere && id_semestre) {
                    $.ajax({
                        url: '<?= ROOT ?>/Etudiants/trier_liste_etudiant',
                        type: 'POST',
                        data: { annee_universitaire: annee, id_filiere: id_filiere, id_semestre: id_semestre },
                        success: function (response) {
                            $('#table_etudiant').html(response);
                            $('#liste_empty').hide();
                        },
                        error: function (xhr) {
                            alert("Erreur AJAX : " + xhr.responseText);
                        }
                    });
                }
            });
        });
    </script>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

</body>

</html>
