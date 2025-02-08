<link rel="stylesheet" type="text/css" href="<?= ROOT ?>/assets/vendors/css/tables/datatable/datatables.min.css">

<table class="table zero-configuration " id="edts">
    <thead>
        <tr>
            <th>
                <div class="checkbox">
                    <input type="checkbox" class="checkbox-input isSelected" id="allSelect">
                    <label for="allSelect"></label>
                </div>
            </th>
            <th>Filière</th>
            <th>Promotion</th>
            <th>Module</th>
            <th class="d-none d-lg-table-cell">Professeur</th>
            <th class=" d-none d-xl-table-cell">Salle</th>
            <th class=" d-none d-xxl-table-cell">Date Debut</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php $index = 0 ?>
        <?php foreach ($edts as $edt): ?>
            <?php
            $edtInfo = $edt->edt;
            $promotion = $edt->promotion;
            $module = $edt->module;
            ?>
            <tr style="font-size: 13px;">
                <td>
                    <div class="checkbox">
                        <input type="checkbox" class="checkbox-input isSelected" id="checkbox<?= $index ?>"
                            data-id="<?php echo $edtInfo->id_edt ?>"
                            data-nom="<?php echo 'edt-' . $module->sigle_module . '-' . $promotion->sigle_filiere . '-' .  $promotion->sigle_semestre . '-' . $promotion->annee_universitaire . '_' . strtoupper($edtInfo->enseignant_nom); ?>"
                            data-statut="<?php echo $edtInfo->statut ?>">

                        <label for="checkbox<?= $index ?>"></label>
                    </div>
                </td>
                <td>
                    <a class="h6 d-flex text-bold-600 imprimerEdt" href="#" data-id="<?php echo $edtInfo->id_edt ?>"
                        data-nom="<?php echo 'edt-' . $module->sigle_module . '-' . $promotion->sigle_filiere . '-' .  $promotion->sigle_semestre . '-' . $promotion->annee_universitaire . '_' . strtoupper($edtInfo->enseignant_nom); ?>">
                        <?php if ($edtInfo->statut == 0): ?>
                            <div class="badge badge-warning badge-icon">
                                <span>x</span>
                            </div>
                        <?php endif ?>
                        <?php if ($edtInfo->statut == 1): ?>
                            <div class="badge badge-success badge-icon">
                                <span>v</span>
                            </div>
                        <?php endif ?>
                        <span class="px-1"><?php echo strtoupper($promotion->sigle_filiere) ?></span>
                    </a>
                </td>
                <td>
                    <?php echo strtoupper($promotion->sigle_filiere . '-' . $promotion->sigle_semestre . '( ' . $promotion->annee_universitaire . ' )') ?>
                </td>
                <td>
                    <?php echo strtoupper($module->nom_module) ?>
                </td>
                <td class="h6 text-bold-700 text-italic d-none d-lg-table-cell" style="font-size: 13px;">
                    <?php echo strtoupper($edtInfo->enseignant_prenom . ' ' . $edtInfo->enseignant_nom) ?>
                </td>

                <td class="d-none d-xl-table-cell">
                    <?php echo strtoupper($edtInfo->nom_salle) ?>
                </td>
                <td class="d-none d-xxl-table-cell">
                    <div class="badge badge-light-primary mr-1 mb-1">
                        <?php echo strtoupper($edtInfo->date_debut) ?>
                    </div>
                </td>
                <td class="text-center dt-no-sorting">
                    <div class="dropdown">
                        <span
                            class="bx bx-dots-horizontal-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu">
                        </span>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item"
                                href="<?= ROOT ?>/Emploi_du_temps/apercu_EDT/<?php echo $edtInfo->id_edt ?>">
                                <i class="bx bx-edit-alt mr-1"></i> Aperçu
                            </a>
                            <?php if (($edtInfo->statut == 0)): ?>
                                <a class="dropdown-item"
                                    href="<?= ROOT ?>/Emploi_du_temps/editer_edt/<?php echo $edtInfo->id_edt ?>"><i
                                        class="bx bx-edit-alt mr-1"></i> Editer</a>
                            <?php endif ?>
                            <a class="dropdown-item imprimerEdt" href="#" data-id="<?php echo $edtInfo->id_edt ?>"
                                data-nom="<?php echo 'edt-' . $module->sigle_module . '-' . $promotion->sigle_filiere . '-' .  $promotion->sigle_semestre . '-' . $promotion->annee_universitaire . '_' . strtoupper($edtInfo->enseignant_nom); ?>">
                                <i class=" bx bx-printer mr-1"></i>Imprimer</a>

                        </div>
                    </div>
                </td>
            </tr>
            <?php $index++ ?>
        <?php endforeach ?>
    </tbody>

</table>

<script src="<?= ROOT ?>/assets/vendors/js/tables/datatable/datatables.min.js"></script>
<script src="<?= ROOT ?>/assets/vendors/js/tables/datatable/dataTables.bootstrap4.min.js"></script>
<script src="<?= ROOT ?>/assets/vendors/js/tables/datatable/dataTables.buttons.min.js"></script>
<script src="<?= ROOT ?>/assets/vendors/js/tables/datatable/datatables.checkboxes.min.js"></script>
<script src="<?= ROOT ?>/assets/js/scripts/datatables/datatable.js"></script>