<?php foreach ($edts as $edt): ?>
    <?php
    $edtInfo = $edt->edt;
    $promotion = $edt->promotion;
    $module = $edt->module;
    ?>
    <tr style="font-size: 13px;">
        <td class="h6 d-flex" style="font-weight: bolder;">
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
        </td>
        <td>
            <?php echo strtoupper($promotion->sigle_filiere . '-' . $promotion->sigle_semestre . '( ' . $promotion->annee_universitaire . ' )') ?>
        </td>
        <td>
            <?php echo strtoupper($module->nom_module) ?>
        </td>
        <td class="h6 text-bold-700 text-italic" style="font-size: 13px;">
            <?php echo strtoupper($edtInfo->enseignant_prenom . ' ' . $edtInfo->enseignant_nom) ?>
        </td>

        <td>
            <?php echo strtoupper($edtInfo->nom_salle) ?>
        </td>
        <td>
            <div class="badge badge-light-primary mr-1 mb-1">
                <?php echo strtoupper($edtInfo->date_debut) ?>
            </div>
        </td>
        <td class="text-center dt-no-sorting">
            <div class="dropdown">
                <span class="bx bx-dots-horizontal-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu">
                </span>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="<?= ROOT ?>/Emploi_du_temps/apercu_EDT/<?php echo $edtInfo->id_edt ?>">
                        <i class="bx bx-edit-alt mr-1"></i> Aperçu
                    </a>
                    <a class="dropdown-item" href="#"><i class="bx bx-edit-alt mr-1"></i> Editer</a>
                    <a class="dropdown-item" href="#"><i class="bx bx-trash mr-1"></i> Supprimer</a>
                </div>
            </div>
        </td>
    </tr>
<?php endforeach ?>