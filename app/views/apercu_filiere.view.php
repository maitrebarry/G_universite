<!-- inclusion du partie header -->
<?php $this->view("Partials/header") ?>
<?php
$filiere = $infoFiliere['filiere'];
$semestres = $infoFiliere['semestres'];
$ues = $infoFiliere['ues'];
$modules = $infoFiliere['modules'];
?>
<style>
    body {
        background-color: #f8f9fa;
    }

    .ue-name,
    .ecue {
        transform: rotate(90deg);
    }

    .container {
        margin-top: 30px;
    }

    .section {
        background-color: #ffffff;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        margin-top: 20px;
    }


    .module-item input:last-child {
        margin-right: 0;
    }

    .btn-custom {
        background-color: #007bff;
        color: white;
        border-radius: 6px;
    }



    .module-list {
        list-style-type: none;
        padding-left: 0;

    }

    .module-list li {
        padding: 10px;
        border-radius: 4px;
        margin: 5px 0;
    }

    .module-list li label {
        display: block;
        text-align: center;
    }

    .delete-btn {
        color: red;
        cursor: pointer;
        font-size: 14px;
    }

    .delete-btn:hover {
        color: darkred;
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
                                    <li class="breadcrumb-item"><a
                                            href="<?php echo ROOT . '/Emploi_du_temps/' ?>">Gestion Filière</a>
                                    </li>
                                    <li class="breadcrumb-item active">Aperçu
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <!-- MAQUETTE -->
                <section id="basic-datatable">
                    <div class="row">
                        <div class="col-12">
                            <div class="card card-animated-border-top">
                                <div class="card-content">
                                    <div class="card-body">
                                        <div class="row w-100 d-flex justify-content-center align-items-center">
                                            <div class="col-6 col-md-4 d-flex justify-content-center">
                                                <a href="<?= ROOT ?>/Filieres/editer_Filiere/<?php echo $filiere->id_filiere ?>"
                                                    class="btn btn-primary ">
                                                    <i class="bx bx-edit-alt mr-1"></i> Editer
                                                </a>
                                            </div>


                                            <div class="col-6 col-md-4">
                                                <button type="button" class=" btn btn-primary" id="print"
                                                    data-nom="<?php echo 'Maquette-' . strtoupper($filiere->sigle_filiere); ?>"><i
                                                        class=" bx bx-printer"></i>
                                                    Imprimer</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 w-100  pb-1 d-flex justify-content-center align-items-center row">
                            <?php foreach ($semestres as $semestre): ?>
                                <div class="col-2 d-flex justify-content-center align-items-center">
                                    <input type="checkbox" class="form-check showOption"
                                        data-id="s_<?php echo $semestre->id_parcours ?>" checked>
                                    <label class="form-label pl-2" for="s_<?php echo $semestre->id_parcours ?>"
                                        style="font-size: 16px;"><?php echo $semestre->sigle_semestre ?></label>
                                </div>
                            <?php endforeach ?>
                        </div>


                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="card card-animated-border-top">
                                <div class="card-content">
                                    <div class="card-body card-dashboard">
                                        <div class="container">

                                            <div id="semestresTable">
                                                <div id="infoFiliere"
                                                    class="row w-100 d-flex justify-content-around align-items-center mb-2 ">

                                                    <div class="col-12 d-flex justify-content-center mb-2 w-100">
                                                        <img src="<?= ROOT ?>/assets/images/logo.jpg" alt=""
                                                            class=" img-thumbnail mr-1 d-block" style="width: 100px;">
                                                    </div>

                                                    <div
                                                        class="col-12 col-md-6 d-flex justify-content-center justify-content-md-start">
                                                        <h6 class="text-bold-600 text-success">
                                                            <?php echo strtoupper($filiere->nom_filiere) ?></h6>
                                                    </div>

                                                    <div
                                                        class="col-12 col-md-6 d-flex justify-content-center justify-content-md-end">
                                                        <h6 class="text-bold-600 text-success">
                                                            <?php echo strtoupper($filiere->sigle_filiere) ?></h6>
                                                    </div>
                                                </div>

                                                <?php foreach ($semestres as $semestre): ?>
                                                    <?php $nbrUe = 0;
                                                    $nbrModule = 0;
                                                    $totalCredit = 0;
                                                    $totalHeure = 0 ?>
                                                    <!-- SEMESTRE -->
                                                    <div class='semestre' id="s_<?php echo $semestre->id_parcours ?>">
                                                        <h4 class='text-center'>
                                                            <?php echo $semestre->nom_semestre ?>
                                                        </h4>
                                                        <table class='table table-bordered table-responsive'>
                                                            <thead>
                                                                <tr class="">
                                                                    <th class="text-center ue-section col-4">UE</th>
                                                                    <th class="text-center col-8">Modules</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php foreach ($ues as $ue): ?>
                                                                    <?php if ($ue->id_parcours == $semestre->id_parcours): ?>
                                                                        <?php $nbrUe++;
                                                                        $totalCredit += $ue->ue_credit;
                                                                        $totalHeure += $ue->ue_cm + $ue->ue_td + $ue->ue_tp + $ue->ue_tpe ?>
                                                                        <!-- UE -->
                                                                        <tr class='ue-item '>
                                                                            <!-- INFO UE -->
                                                                            <td class="p-1 ue-section col-4"
                                                                                style="min-width: 230px;">
                                                                                <div class="">
                                                                                    <h5 class="ue-name mt-2 text-center">
                                                                                        <?php echo $ue->nom_ue ?></h5>
                                                                                </div>

                                                                                <!-- STATISTIQUE -->
                                                                                <div class="row mt-4 d-flex  align-items-center">
                                                                                    <!-- CM -->
                                                                                    <div class=' col-6 col-xl-3 '>
                                                                                        <label
                                                                                            class="d-block text-center">CREDIT</label>
                                                                                        <input type='number'
                                                                                            class='form-control text-center ueCredit'
                                                                                            disabled
                                                                                            value="<?php echo $ue->ue_credit ?>">
                                                                                    </div class=' col-6 col-xl-3'>
                                                                                    <!-- TD -->
                                                                                    <div class=' col-6 col-xl-3'>
                                                                                        <label
                                                                                            class="d-block text-center">VHT</label>
                                                                                        <input type='number'
                                                                                            class='form-control text-center ueVht'
                                                                                            disabled
                                                                                            value="<?php echo $ue->ue_cm + $ue->ue_td + $ue->ue_tp + $ue->ue_tpe ?>">
                                                                                    </div>
                                                                                    <!-- TP -->
                                                                                    <div class=' col-6 col-xl-3'>
                                                                                        <label
                                                                                            class="d-block text-center ueTpe">TPE</label>
                                                                                        <input type='number'
                                                                                            class='form-control text-center'
                                                                                            disabled
                                                                                            value="<?php echo $ue->ue_tpe ?>">
                                                                                    </div>
                                                                                    <!-- TPE -->
                                                                                    <div class=' col-6 col-xl-3'>
                                                                                        <label class="d-block text-center">CM TD
                                                                                            TP</label>
                                                                                        <input type='number'
                                                                                            class='form-control text-center ueHeure'
                                                                                            disabled
                                                                                            value="<?php echo $ue->ue_cm + $ue->ue_td + $ue->ue_tp ?>">
                                                                                    </div>
                                                                                </div>
                                                                            </td>



                                                                            <!-- MODULES -->
                                                                            <td class="col-8" style="min-width:445px">
                                                                                <ul class='module-list' id="module-list">
                                                                                    <?php foreach ($modules as $module): ?>
                                                                                        <?php if ($module->id_ue == $ue->id_ue): ?>
                                                                                            <?php $nbrModule++ ?>
                                                                                            <!-- MODULE -->
                                                                                            <li class='module-item row'>
                                                                                                <!-- LES HEURES DU MOULE -->
                                                                                                <div
                                                                                                    class="heure border m-0 col-12 row p-1">
                                                                                                    <!-- CM -->
                                                                                                    <div class='col-6 col-lg-3'>
                                                                                                        <label
                                                                                                            class="d-block text-center">CM</label>
                                                                                                        <input type='number'
                                                                                                            class='form-control text-center cm'
                                                                                                            disabled
                                                                                                            value="<?php echo $module->cm ?>">
                                                                                                    </div>
                                                                                                    <!-- TD -->
                                                                                                    <div class='col-6 col-lg-3'>
                                                                                                        <label
                                                                                                            class="d-block text-center">TD</label>
                                                                                                        <input type='number'
                                                                                                            class='form-control text-center td'
                                                                                                            disabled
                                                                                                            value="<?php echo $module->td ?>">
                                                                                                    </div>
                                                                                                    <!-- TP -->
                                                                                                    <div class='col-6 col-lg-3'>
                                                                                                        <label
                                                                                                            class="d-block text-center">TP</label>
                                                                                                        <input type='number'
                                                                                                            class='form-control text-center tp'
                                                                                                            disabled
                                                                                                            value="<?php echo $module->tp ?>">
                                                                                                    </div>
                                                                                                    <!-- TPE -->
                                                                                                    <div class='col-6 col-lg-3'>
                                                                                                        <label
                                                                                                            class="d-block text-center">TPE</label>
                                                                                                        <input type='number'
                                                                                                            class='form-control text-center tpe'
                                                                                                            disabled
                                                                                                            value="<?php echo $module->tpe ?>">
                                                                                                    </div>
                                                                                                </div>

                                                                                                <!-- NOM DU MODULE ECUE ET COEFICIENT -->
                                                                                                <div
                                                                                                    class="module border col-12 p-1 row m-auto">
                                                                                                    <h6 class="col-6">
                                                                                                        <?php echo $module->nom_module ?>
                                                                                                    </h6>
                                                                                                    <h6 class="col-4">
                                                                                                        <?php echo $module->code_module ?>
                                                                                                    </h6>
                                                                                                    <h6 class="col-2 text-right credit">
                                                                                                        <?php echo $module->coeficient ?>
                                                                                                    </h6>
                                                                                                </div>
                                                                                            </li>
                                                                                        <?php endif ?>
                                                                                    <?php endforeach ?>
                                                                                </ul>
                                                                            </td>
                                                                        </tr>
                                                                    <?php endif ?>
                                                                <?php endforeach ?>
                                                            </tbody>
                                                            <!-- STATISTIQUE DU SEMESTRE -->
                                                            <caption class="border p-1 text-center">
                                                                <div class=" W-100 row m-auto">
                                                                    <!-- NOMBRE UE -->
                                                                    <div class='col-3'>
                                                                        <label class="d-block text-center">NBR
                                                                            UE</label>
                                                                        <input type='number'
                                                                            class='form-control nbrUe text-center' disabled
                                                                            value="<?php echo $nbrUe ?>">
                                                                    </div class='col-3'>
                                                                    <!-- NOMBRE MODULE -->
                                                                    <div class='col-3'>
                                                                        <label class="d-block text-center">NBR
                                                                            MODULE</label>
                                                                        <input type='number'
                                                                            class='form-control nbrModule text-center'
                                                                            disabled value="<?php echo $nbrModule ?>">
                                                                    </div>
                                                                    <!-- TOTAL CREDIT -->
                                                                    <div class='col-3'>
                                                                        <label class="d-block text-center totalCredit">T
                                                                            CREDIT</label>
                                                                        <input type='number'
                                                                            class='form-control text-center' disabled
                                                                            value="<?php echo $totalCredit ?>">
                                                                    </div>
                                                                    <!-- TOTAL HEURE -->
                                                                    <div class='col-3'>
                                                                        <label class="d-block text-center totalHeure">T
                                                                            HEURE</label>
                                                                        <input type='number'
                                                                            class='form-control text-center' disabled
                                                                            value="<?php echo $totalHeure ?>">
                                                                    </div>
                                                                </div>
                                                            </caption>


                                                        </table>
                                                    </div>
                                                <?php endforeach ?>
                                            </div>

                                            <!-- Sauvegarde de la filière -->
                                            <div class="mt-4" style="float: right;">
                                                <a href="<?= ROOT ?>/Filieres/editer_Filiere/<?php echo $filiere->id_filiere ?>"
                                                    class="btn btn-primary ">
                                                    <i class="bx bx-edit-alt mr-1"></i> Editer la Filiere
                                                </a>
                                            </div>
                                        </div>

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
    <script src="<?= ROOT ?>/assets/mon_js/filiere.js"></script>
    <script>
        $(document).ready(function() {

            $(".showOption").change(function() {
                idSemestre = $(this).data('id');
                console.log("hsihis");

                $("#" + idSemestre).toggle($(this).is(":checked"));
            });

            statistique();
        })
        $('#print').click(function() {
            nomFiliere = $(this).data('nom');
            event.preventDefault();
            window.scrollTo({
                top: 0,
                behavior: "smooth",
            });
            var element = $(this);
            setTimeout(function() {
                imprimer2(nomFiliere);
            }, 500);
        })
    </script>
</body>
<!-- END: Body-->

</html>