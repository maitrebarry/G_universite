<!-- inclusion du partie header -->
<?php $this->view("Partials/header") ?>
<style>.table-paiement th {
    cursor: default; /* Empêche le clic */
    user-select: none; /* Désactive la sélection de texte */
    background-color: #343a40;
    color: white;
    text-align: center;
    vertical-align: middle;
}
th.text-center {
    text-align: center; /* Centrer le texte */
    vertical-align: middle; /* Aligner verticalement */
    padding: 10px; /* Ajoutez un peu d'espace intérieur */
}

th.text-center input[type="checkbox"] {
    margin-top: 5px; /* Espacer la case à cocher du texte */
    cursor: pointer; /* Ajouter un curseur pour indiquer que c'est cliquable */
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
                            <div class="card card-animated-border-top ">

                                <div class="card-content">
                                    <div class="card-body">
                                        <p class="mb-1">Filtré par</p>
                                        <div class="row ">
                                            <div class="col-md-6 m-auto">
                                                <div class="form-group">
                                                    <label class="form-label" for="single-select ">Promotion</label>
                                                    <select class="select2 form-control" id="id_promotion"
                                                        name="id_promotion">
                                                        <option value="">Promotion</option>
                                                        <?php foreach ($listeFilieres as $listeFiliere): ?>
                                                        <option
                                                            value="<?= htmlspecialchars($listeFiliere->id_promotion); ?>">
                                                            <?= htmlspecialchars($listeFiliere->sigle_filiere."-".$listeFiliere->sigle_semestre ."(".$listeFiliere->annee_universitaire.")"); ?>
                                                        </option>
                                                        <?php endforeach; ?>
                                                    </select>
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
                                        <a href="<?= ROOT ?>/Etudiants/incrit_etudiant"><button class="btn btn-primary"
                                                style="float:right;"><i class="bx bx-plus"></i>&nbsp; Nouveau </button></a>
                                        <div class="card-body card-dashboard">
                                        <form action="<?= ROOT ?>/Etudiants/paiement_groupe" method="POST">
                                            <div class="table-responsive">
                                            
                                                 <table class="table zero-configuration">
                                                    <thead class="text-center">
                                                        <tr>
                                                        <th class="text-center">
                                                            Paiement
                                                            <br> <!-- Saut de ligne pour séparer le texte de la case à cocher -->
                                                            <input type="checkbox" id="select-all" title="Sélectionner tout" style="margin-top: 5px;">
                                                        </th>
                                                           
                                                            <th>Nom && Prénom</th>
                                                            <th>Matricule</th>
                                                            <th>Status</th>
                                                            <th>Filliere</th>
                                                            <th>Diplome</th>
                                                            
                                                            <th> Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="table_etudiant" class="text-center">

                                                    </tbody>
                                                </table>
                                               

                                                <!-- Bouton pour effectuer le paiement -->
                                                <button type="submit" class="btn btn-primary">Paiement en Groupe</button>
                                            
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
        //pour eviter de clicker sur les th
  document.getElementById('select-all').addEventListener('click', function(event) {
    event.stopPropagation();
});
          document.getElementById('select-all').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('input[name="paie[]"]');
        checkboxes.forEach(checkbox => checkbox.checked = this.checked);
        });

        $(document).ready(function() {
        $('#id_promotion').change(function() {
            const id_promotion = $('#id_promotion').val();


            if (id_promotion != null) {
                $.ajax({
                    url: '<?=ROOT?>/Etudiants/trier_liste_etudiant',
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
</body>
<!-- END: Body-->

</html>