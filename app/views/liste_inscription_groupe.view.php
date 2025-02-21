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
                        <div class="col-12">
                            <div class="card card-animated-border-top ">

                                <form method="POST" enctype="multipart/form-data">
                                    <div class="card-content">
                                        <div class="card-body">

                                            <div class="row ">
                                                <div class="col-md-6 ">

                                                    <div class="form-group">
                                                        <label class="form-label" for="single-select">Exporter à
                                                            partir de Excel</label>
                                                        <input type="file" name="excelFile" id="excelFile"
                                                            accept=".xlsx, .xls" class="form-control" required />

                                                    </div>

                                                </div>
                                                <div class="col-md-6">
                                                    <div class="row">
                                                        <div class="col-md-10">
                                                            <label class="form-label"> Filtre la liste par
                                                                promotion<span class="text-danger">*</span></label>
                                                            <select class="form-select form-control" id="id_promotion"
                                                                name="id_promotion" required>
                                                                <option value="">Promotion</option>
                                                                <?php foreach ($listeFilieres as $listeFiliere): ?>
                                                                <option value="
                                                            <?= htmlspecialchars($listeFiliere->id_promotion); ?>">
                                                                    <?= htmlspecialchars($listeFiliere->sigle_filiere."-".$listeFiliere->sigle_semestre ."(".$listeFiliere->annee_universitaire.")"); ?>
                                                                </option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-2 mt-2">
                                                            <button type="submit" name="envoie" class="btn btn-primary"
                                                                id="validateBtn">Valider</button>
                                                        </div>
                                                    </div>



                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="card card-animated-border-top ">
                                <div class="card-content">
                                    <div class="card-body card-dashboard">
                                        <div class="table-responsive">
                                            <table class="table zero-configuration">
                                                <thead>
                                                    <tr>
                                                        <th>Nom & Prénom</th>
                                                        <th>Date de naissance</th>
                                                        <th>Lieu de naissance</th>
                                                        <th>Filière</th>
                                                        <th>Matricule</th>
                                                        <th>Bac</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="dataTableBody">
                                                </tbody>
                                            </table>
                                            <input type="hidden" id="hiddenData" value="">


                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    </form>
                    <div class="modal-primary mr-1 mb-1 d-inline-block ">
                        <div class="modal fade text-left" id="menuConfig" tabindex="-1" role="dialog"
                            aria-labelledby="myModalLabel160" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg"
                                role="document">
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-12 row mb-1">
                                                <h6 class="col-12 text-center">Modèle du fichier Excel</h6>
                                                <div class="col-12 border p-2 d-flex justify-content-center">
                                                    <div style="width: 600px" class="cursor-pointer">
                                                        <span class="text-center">Le fichier doit respecter ce modèle
                                                            :</span>
                                                        <img class="img img-thumbnail d-block border border-primary"
                                                            src="<?= ROOT ?>/assets/images/exemple.png"
                                                            alt="modèle de fichier" id="model-row"
                                                            style="border-width: 2px !important;" data-model="edt-row">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-link" data-dismiss="modal">
                                            <i class="bx bx-x d-block d-sm-none"></i>
                                            <span class="d-none d-sm-block">
                                                <a href="<?=ROOT?>/assets/fichier_excel/Classeur1GI.xlsx"
                                                    download="Classeur1GI.xlsx" id="downloadModel">
                                                    Télécharger le modèle
                                                </a>

                                            </span>
                                        </button>
                                        <button type="button" id="continueBtn" class="btn btn-primary">
                                            Si le fichier correspond au modèle, continuer
                                        </button>
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
    $(document).ready(function() {
        $('#id_promotion').change(function() {
            const id_promotion = $('#id_promotion').val();


            if (id_promotion != null) {
                $.ajax({
                    url: '<?=ROOT?>/EtudiantPargroupes/trier_liste_etudiants',
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
    <script src="<?=ROOT?>/assets/mon_js/script_extration.js"></script>

</body>
<!-- END: Body-->

</html>