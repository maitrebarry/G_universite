<!-- inclusion du partie header -->
<?php $this->view("Partials/header") ?>
<style>
    .table-paiement th {
        cursor: default;
        /* Empêche le clic */
        user-select: none;
        /* Désactive la sélection de texte */
        background-color: #343a40;
        color: white;
        text-align: center;
        vertical-align: middle;
    }

    th.text-center {
        text-align: center;
        /* Centrer le texte */
        vertical-align: middle;
        /* Aligner verticalement */
        padding: 10px;
        /* Ajoutez un peu d'espace intérieur */
    }

    th.text-center input[type="checkbox"] {
        margin-top: 5px;
        /* Espacer la case à cocher du texte */
        cursor: pointer;
        /* Ajouter un curseur pour indiquer que c'est cliquable */
    }
</style>

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
                                    <li class="breadcrumb-item active">Liste
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <!-- formulaire -->
                <section id="basic-datatable">
                    <div class="row">
                        <div class="col-12">
                            <div class="card card-animated-border-top">
                                <div class="card-content">
                                    <div class="card-body">
                                        <p class="mb-1">Filtré par</p>
                                        <form method="POST" action="<?= ROOT ?>/Etudiants/filtrer_etudiants">
                                            <div class="row">
                                                <!-- Année universitaire -->
                                                <div class="col-md-4 mb-3">
                                                    <label for="annee_universitaire" class="form-label fw-bold">Année
                                                        universitaire <span class="text-danger">*</span></label>
                                                    <select class="form-select select2" id="annee_universitaire"
                                                        required>
                                                        <option value="">-- Sélectionner l'année --</option>
                                                        <?php foreach ($listeParAnnee as $annee => $promos): ?>
                                                            <option value="<?= htmlspecialchars($annee) ?>">
                                                                <?= htmlspecialchars($annee) ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>

                                                <div class="col-md-4 mb-3">
                                                    <label for="id_filiere" class="form-label fw-bold">Filière <span
                                                            class="text-danger">*</span></label>
                                                    <select class="form-select select2" id="id_filiere" required>
                                                        <option value="">-- Sélectionner la filière --</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-4 mb-3">
                                                    <label for="id_semestre" class="form-label fw-bold">Semestre <span
                                                            class="text-danger">*</span></label>
                                                    <select class="form-select select2" id="id_semestre" required>
                                                        <option value="">-- Sélectionner le semestre --</option>
                                                    </select>
                                                </div>



                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Code pour afficher les étudiants et effectuer l'action -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card card-animated-border-top">
                                <div class="card-content mt-1 mr-1">
                                    <!-- Nouveau étudiant Button -->
                                    <a href="<?= ROOT ?>/Etudiants/incrit_etudiant">
                                        <button class="btn btn-primary" style="float:right;">
                                            <i class="bx bx-plus"></i>&nbsp; Nouveau
                                        </button>
                                    </a>
                                    <div class="card-body card-dashboard">
                                        <form action="<?= ROOT ?>/Etudiants/paiement_groupe" method="POST">
                                            <div class="table-responsive">
                                                <!-- Affichage des étudiants -->
                                                <div id="liste_etudiants" class="mt-4">
                                                    <div class="mb-3">
                                                    <input type="text" id="customSearch" class="form-control" placeholder="Rechercher un étudiant...">
                                                </div>
                                                            <table class="table zze">
                                                            <thead class="text-center">
                                                                <tr>
                                                                    <th class="text-center">
                                                                        Tout<br>
                                                                        <input type="checkbox" id="select-all"
                                                                            title="Sélectionner tout"
                                                                            style="margin-top: 5px;">
                                                                    </th>
                                                                    <th>Nom & Prénom</th>
                                                                    <th>Matricule</th>
                                                                    <th>Status</th>
                                                                    <th>Filière</th>
                                                                    <th>Diplôme</th>
                                                                    <th>Action</th>
                                                                </tr>
                                                            </thead>
                                                        <tbody id="table_etudiant" class="text-center">
                                                            <!-- Les données AJAX seront insérées ici -->
                                                        </tbody>
                                                    </table>
                                                </div>

                                                <button type="submit" class="btn btn-primary">Paiement en
                                                    Groupe</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                </section>


                <!-- formulaire -->

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
    document.getElementById('customSearch').addEventListener('input', function() {
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll('#table_etudiant tr');
    rows.forEach(row => {
        let text = row.innerText.toLowerCase();
        row.style.display = text.includes(filter) ? '' : 'none';
    });
});
</script>
    <script>
        $('.zero-configuration').DataTable({
            ordering: false
        });
        //pour eviter de clicker sur les th
        document.getElementById('select-all').addEventListener('click', function(event) {
            event.stopPropagation();
        });
        document.getElementById('select-all').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('input[name="paie[]"]');
            checkboxes.forEach(checkbox => checkbox.checked = this.checked);
        });

        $(document).ready(function() {
            $('.select2').select2({
                width: '100%'
            });

            const dataParAnnee = <?= json_encode($listeParAnnee) ?>;

            // Quand une année est sélectionnée
            $('#annee_universitaire').on('change', function() {
                const annee = $(this).val();
                const $filiere = $('#id_filiere');
                const $semestre = $('#id_semestre');

                $filiere.empty().append('<option value="">-- Sélectionner la filière --</option>');
                $semestre.empty().append('<option value="">-- Sélectionner le semestre --</option>');

                if (dataParAnnee[annee]) {
                    const promos = dataParAnnee[annee];

                    const filieres = {};

                    promos.forEach(promo => {
                        filieres[promo.id_filiere] = promo.sigle_filiere;
                    });

                    for (const id in filieres) {
                        $filiere.append(new Option(filieres[id], id));
                    }
                }

                $filiere.trigger('change.select2');
            });

            // Quand une filière est sélectionnée
            $('#id_filiere').on('change', function() {
                const annee = $('#annee_universitaire').val();
                const id_filiere = $(this).val();
                const $semestre = $('#id_semestre');

                $semestre.empty().append('<option value="">-- Sélectionner le semestre --</option>');

                if (dataParAnnee[annee]) {
                    const promos = dataParAnnee[annee];

                    const semestres = [];

                    promos.forEach(promo => {
                        if (promo.id_filiere == id_filiere) {
                            semestres.push({
                                id: promo.id_semestre,
                                sigle: promo.sigle_semestre
                            });
                        }
                    });

                    // Éviter les doublons
                    const unique = {};
                    semestres.forEach(s => {
                        if (!unique[s.id]) {
                            unique[s.id] = true;
                            $semestre.append(new Option(s.sigle, s.id));
                        }
                    });
                }

                $semestre.trigger('change.select2');
            });

            // Quand un semestre est sélectionné => charger la liste des étudiants
            $('#id_semestre').on('change', function() {
                const annee = $('#annee_universitaire').val();
                const id_filiere = $('#id_filiere').val();
                const id_semestre = $(this).val();

                if (annee && id_filiere && id_semestre) {
                    $.ajax({
                        url: '<?= ROOT ?>/Etudiants/trier_liste_etudiant',
                        type: 'POST',
                        data: {
                            annee_universitaire: annee,
                            id_filiere: id_filiere,
                            id_semestre: id_semestre
                        },
                        success: function(response) {
                            $('#table_etudiant').html(response);
                        },
                        error: function(xhr) {
                            alert("Erreur AJAX : " + xhr.responseText);
                        }
                    });
                }
            });
        });
    </script>

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

</body>
<!-- END: Body-->

</html>