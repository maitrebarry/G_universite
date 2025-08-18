<!-- inclusion du partie header -->
<?php $this->view("Partials/header") ?>

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
                            <h5 class="content-header-title float-left pr-1 mb-0">Réinscription des Etudiants</h5>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb p-0 mb-0">
                                    <li class="breadcrumb-item"><a href="index.html"><i class="bx bx-home-alt"></i></a>
                                    </li>
                                    <li class="breadcrumb-item"><a href="#">Gestion Etudiants</a>
                                    </li>
                                    <li class="breadcrumb-item active">Réinscription
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
                        <div class="col-12">
                            <div class="card card-animated-border-top ">
                                <div class="card-content">
                                    <div class="card-body">
                                        <form action="">
                                            <div class="row w-100 m-auto">
                                                <div class="col-sm-2">
                                                    <label class="form-label">Année Universitaire</label>
                                                    <select class="select2 form-control disabled"
                                                        id="anneeUniversitaire" name="anneeUniversitaire">
                                                        <?php
                                                        $annee_debut = 2012;
                                                        $annee_actuelle = date('Y');
                                                        $mois_actuel = date('n');

                                                        // Si on est avant septembre, l'année universitaire en cours commence l'année précédente
                                                        if ($mois_actuel <= 8) {
                                                            $annee_actuelle--;
                                                        }

                                                        $annee_universitaire_courante = $annee_actuelle . '-' . ($annee_actuelle + 1);

                                                        for ($annee = $annee_debut; $annee <= $annee_actuelle; $annee++) {
                                                            $annee_suivante = $annee + 1;
                                                            $valeur = $annee . '-' . $annee_suivante;

                                                            // Si cette valeur correspond à l'année universitaire en cours, on ajoute "selected"
                                                            $selected = ($valeur == $annee_universitaire_courante) ? 'selected' : '';
                                                            echo "<option value=\"$valeur\" $selected>$valeur</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-sm-3 ">
                                                    <label class="form-label">Classe</label>
                                                    <select class="select2 form-control disabled" id="promotions"
                                                        name="promotions">
                                                        <option value="" disabled selected>Selectionner une
                                                            Classe
                                                        </option>
                                                    </select>
                                                </div>

                                                <div class="col-sm-6 float-left row  d-flex justify-content-end">
                                                    <div class="col-sm-6">
                                                        <label class="form-label">Classe Suivante</label>
                                                        <select class="select2 form-control disabled" id="newPromotions"
                                                            disabled>
                                                            <option value="" disabled selected>Selectionner une
                                                                Classe
                                                            </option>
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
                                    <div class="card-body card-dashboard">
                                        <!-- Cadre du tableau -->
                                        <div class="table-container " id="table_section">
                                            <h6 class=" text-center text-bold-600 text-warning ">
                                                Selectionner une classe et les étudiants vont apparaître &#x1F603
                                            </h6>
                                        </div>
                                        <div class="col-12 d-flex justify-content-end mt-4">
                                            <button name="envoyer" type="submit"
                                                class="btn btn-primary">Envoyer</button>
                                        </div>
                                    </div>
                                    </form>
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
    <script src="<?= ROOT ?>/assets/mon_js/reinscription.js"></script>
    <script>
    var infoFiliere = [];

    $(document).ready(function() {
        classesAnneeUniversitaire($("#anneeUniversitaire option:selected").val());

        // $("#table_section").html(
        //     "<h6 class='text-center text-bold-600 text-warning'>" +
        //     "Selectionner l'ue et les notes vont apparaître &#x1F603</h6>"
        // );

    })

    $("#anneeUniversitaire").change(async function() {

        classesAnneeUniversitaire($("#anneeUniversitaire option:selected").val());

        idSemestre = $("#promotions option:selected").data("semestre");


        $("#table_section").html(
            "<h6 class='text-center text-bold-600 text-warning'>" +
            "Selectionner l'ue et les notes vont apparaître &#x1F603</h6>"
        );

    })

    $("#promotions").change(async function() {
        infoFiliere = await infosFiliere($("#promotions option:selected").data("filiere"), "all");
        idSemestre = $("#promotions option:selected").data("semestre");

        getEtudiants();

    })
    </script>
</body>
<!-- END: Body-->

</html>