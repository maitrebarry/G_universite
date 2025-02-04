<!-- inclusion du partie header -->
<?php $this->view("Partials/header") ?>
<?php
$filiere = $infoFiliere['filiere'];
$semestres = $infoFiliere['semestres'];
$ues = $infoFiliere['ues'];
$modules = $infoFiliere['modules'];
?>
<div id="semestresTable">
    <div id="infoFiliere" class="row w-100 d-flex justify-content-around align-items-center mb-2 ">

        <div class="col-12 d-flex justify-content-center mb-2 w-100">
            <img src="<?= ROOT ?>/assets/images/logo.jpg" alt="" class=" img-thumbnail  d-block" style="width: 100px;">
        </div>

        <div class="col-md-6 d-flex justify-content-start">
            <h6 class="text-bold-600 text-success">
                <?php echo strtoupper($filiere->nom_filiere) ?></h6>
        </div>

        <div class="col-md-6 d-flex justify-content-end">
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
            <table class='table table-bordered'>
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
                                <td class="p-1 ue-section col-4">
                                    <div class="">
                                        <h5 class="ue-name mt-2 text-center">
                                            <?php echo $ue->nom_ue ?></h5>
                                    </div>

                                    <!-- STATISTIQUE -->
                                    <div class="row mt-4 d-flex  align-items-center">
                                        <!-- CM -->
                                        <div class=' col-6 col-xl-3 '>
                                            <label class="d-block text-center">CREDIT</label>
                                            <input type='number' class='form-control text-center ueCredit' disabled
                                                value="<?php echo $ue->ue_credit ?>">
                                        </div class=' col-6 col-xl-3'>
                                        <!-- TD -->
                                        <div class=' col-6 col-xl-3'>
                                            <label class="d-block text-center">VHT</label>
                                            <input type='number' class='form-control text-center ueVht' disabled
                                                value="<?php echo $ue->ue_cm + $ue->ue_td + $ue->ue_tp + $ue->ue_tpe ?>">
                                        </div>
                                        <!-- TP -->
                                        <div class=' col-6 col-xl-3'>
                                            <label class="d-block text-center ueTpe">TPE</label>
                                            <input type='number' class='form-control text-center' disabled
                                                value="<?php echo $ue->ue_tpe ?>">
                                        </div>
                                        <!-- TPE -->
                                        <div class=' col-6 col-xl-3'>
                                            <label class="d-block text-center">CM TD
                                                TP</label>
                                            <input type='number' class='form-control text-center ueHeure' disabled
                                                value="<?php echo $ue->ue_cm + $ue->ue_td + $ue->ue_tp ?>">
                                        </div>
                                    </div>
                                </td>



                                <!-- MODULES -->
                                <td class="col-8">
                                    <ul class='module-list' id="module-list">
                                        <?php foreach ($modules as $module): ?>
                                            <?php if ($module->id_ue == $ue->id_ue): ?>
                                                <?php $nbrModule++ ?>
                                                <!-- MODULE -->
                                                <li class='module-item row'>
                                                    <!-- LES HEURES DU MOULE -->
                                                    <div class="heure border m-0 col-12 row p-1">
                                                        <!-- CM -->
                                                        <div class='col-6 col-lg-3'>
                                                            <label class="d-block text-center">CM</label>
                                                            <input type='number' class='form-control text-center cm' disabled
                                                                value="<?php echo $module->cm ?>">
                                                        </div>
                                                        <!-- TD -->
                                                        <div class='col-6 col-lg-3'>
                                                            <label class="d-block text-center">TD</label>
                                                            <input type='number' class='form-control text-center td' disabled
                                                                value="<?php echo $module->td ?>">
                                                        </div>
                                                        <!-- TP -->
                                                        <div class='col-6 col-lg-3'>
                                                            <label class="d-block text-center">TP</label>
                                                            <input type='number' class='form-control text-center tp' disabled
                                                                value="<?php echo $module->tp ?>">
                                                        </div>
                                                        <!-- TPE -->
                                                        <div class='col-6 col-lg-3'>
                                                            <label class="d-block text-center">TPE</label>
                                                            <input type='number' class='form-control text-center tpe' disabled
                                                                value="<?php echo $module->tpe ?>">
                                                        </div>
                                                    </div>

                                                    <!-- NOM DU MODULE ECUE ET COEFICIENT -->
                                                    <div class="module border col-12 p-1 row m-auto">
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
                            <input type='number' class='form-control nbrUe text-center' disabled
                                value="<?php echo $nbrUe ?>">
                        </div class='col-3'>
                        <!-- NOMBRE MODULE -->
                        <div class='col-3'>
                            <label class="d-block text-center">NBR
                                MODULE</label>
                            <input type='number' class='form-control nbrModule text-center' disabled
                                value="<?php echo $nbrModule ?>">
                        </div>
                        <!-- TOTAL CREDIT -->
                        <div class='col-3'>
                            <label class="d-block text-center totalCredit">T
                                CREDIT</label>
                            <input type='number' class='form-control text-center' disabled
                                value="<?php echo $totalCredit ?>">
                        </div>
                        <!-- TOTAL HEURE -->
                        <div class='col-3'>
                            <label class="d-block text-center totalHeure">T
                                HEURE</label>
                            <input type='number' class='form-control text-center' disabled
                                value="<?php echo $totalHeure ?>">
                        </div>
                    </div>
                </caption>


            </table>
        </div>
    <?php endforeach ?>
</div>

<!-- inclusion du partie footer-->
<?php $this->view("Partials/footer") ?>