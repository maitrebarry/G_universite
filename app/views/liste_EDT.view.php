<!-- inclusion du partie header -->
<?php $this->view("Partials/header") ?>
<style>
    td {
        padding: 0 !;
        margin: 0 !important;
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
        <!-- Loader -->
        <div id="loader" class="w-100 position-absolute d-none justify-content-center align-items-center"
            style="height:100vh;z-index:100">
            <div class="spinner-border  " role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>

        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-12 mb-2 mt-1">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h5 class="content-header-title float-left pr-1 mb-0">
                                <?php
                                echo (isset($_SESSION['nom_departement']))
                                    ? strtoupper($_SESSION['nom_departement'] . ' (' . $_SESSION['sigle_departement'] . ')')
                                    : "IUFP"
                                ?>
                            </h5>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb p-0 mb-0">
                                    <li class="breadcrumb-item"><a href="index.html"><i class="bx bx-home-alt"></i></a>
                                    </li>
                                    <li class="breadcrumb-item"><a href="#">Gestion EDT</a>
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
                    <?php $this->view('set_flash'); ?>
                    <div class="row">
                        <div class="col-12">
                            <div class="card card-animated-border-top ">

                                <div class="card-content row">
                                    <div class="col-12 row mt-1 px-2 ">
                                        <div
                                            class="card-body card-dashboard col-12 row d-flex justify-content-around align-items-center">
                                            <div
                                                class="col-12 col-md-10 row d-flex justify-content-start align-items-center mb-1 mb-md-0">
                                                <div class="col-1">
                                                    <i class="bx bx-dialpad text-primary"></i>
                                                </div>
                                                <div class="col-4 ">
                                                    <select class="select2 form-control text-center" id="filieres">
                                                        <option value="0" disabled selected>Filieres</option>
                                                        <?php foreach ($filieres as $filiere): ?>
                                                            <option value="<?php echo $filiere->id_filiere ?>">
                                                                <?php echo strtoupper($filiere->sigle_filiere) ?>
                                                            </option>
                                                        <?php endforeach ?>
                                                    </select>
                                                </div>
                                                <div class="col-4">
                                                    <select class="select2 form-control text-center" id="promotions">
                                                        <option value="" disabled selected>Promotions
                                                        </option>
                                                    </select>
                                                </div>



                                            </div>
                                            <div class="col-12 d-flex justify-content-end">
                                                <a href="<?= ROOT ?>/Emploi_du_temps/ajouter_EDT" id="nouveauEdt">
                                                    <button class="btn btn-primary" style="float:right;">
                                                        <i class="bx bx-plus"></i>&nbsp; Nouveau
                                                    </button>
                                                </a>
                                            </div>
                                        </div>


                                        <div class="table-responsive col-12" id="listeEdts">
                                            <h6 class=" text-center text-bold-500 text-success">
                                                Selectionner la filière et les edts vont apparaître &#x1F603
                                            </h6>
                                        </div>
                                    </div>

                                    <div class="col-12 d-flex justify-content-end my-1 pr-2">
                                        <button type="button" class=" btn btn-primary" id="print"><i
                                                class=" bx bx-printer"></i>
                                            Imprimer</button>
                                        </d>
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
</body>
<!-- END: Body-->
<script src="<?= ROOT ?>/assets/mon_js/edt.js"></script>
<script>
    var infoFiliere = [];

    // $(document).ready(async function() {
    //     trierListeEdt($("#filieres").val(), $("#promotions").val());
    //     infoFiliere = await infosFiliere($(this).val(), "all");
    //     promotionsFiliere(infoFiliere);
    // })
    $("#filieres").change(async function() {
        infoFiliere = await infosFiliere($(this).val(), "all");
        promotionsFiliere(infoFiliere);
        trierListeEdt($(this).val(), $("#promotions").val());
        url = "http://localhost/G_universite/public/Emploi_du_temps/ajouter_EDT"
        url += '/' + $(this).val() + '/';
        $('#nouveauEdt').attr('href', url);
    })
    $("#promotions").change(async function() {
        await trierListeEdt($("#filieres").val(), $(this).val());
        url = "http://localhost/G_universite/public/Emploi_du_temps/ajouter_EDT"
        url += '/' + $("#filieres").val() + '/' + $(this).val();
        $('#nouveauEdt').attr('href', url);
    })

    $('#print').click(function() {
        window.scrollTo({
            top: 0,
            behavior: "smooth",
        });
        setTimeout(function() {
            $('#edts tbody tr').each(function() {
                if ($(this).find('.isSelected').prop("checked")) {
                    imprimerEdt($(this).find('.isSelected').data("id"), $(this).find(
                            '.isSelected')
                        .data(
                            "nom"))
                }
            })
        }, 600)
    })
</script>

</html>