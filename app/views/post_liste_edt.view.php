<div class="gu-table-wrap">
    <table class="gu-table" id="edts" style="width:100%;">
        <thead>
            <tr>
                <th style="width:42px;text-align:center;"><input type="checkbox" class="form-check-input isSelected" id="allSelect"></th>
                <th>Filière</th>
                <th>Promotion</th>
                <th>Module</th>
                <th class="d-none d-lg-table-cell">Salle(s)</th>
                <th class="d-none d-md-table-cell">Début</th>
                <th>Statut</th>
                <th style="width:120px;text-align:right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($edts)): ?>
                <tr><td colspan="8"><div class="gu-empty"><i class="bx bx-calendar-x"></i>Aucun emploi du temps pour cette sélection.</div></td></tr>
            <?php else: $index = 0; foreach ($edts as $edt):
                $edtInfo = $edt->edt;
                $promotion = $edt->promotion;
                $module = $edt->module;
                $ens = $edt->enseignants;
                $nomEdt = 'EDT-' . $module->sigle_module . '-' . $promotion->sigle_filiere . '-' . $promotion->sigle_semestre . '-' . $promotion->annee_universitaire;
            ?>
                <tr>
                    <td style="text-align:center;">
                        <input type="checkbox" class="form-check-input isSelected" id="checkbox<?= $index ?>"
                            data-id="<?= $edtInfo->id_edt ?>" data-nom="<?= htmlspecialchars($nomEdt) ?>" data-statut="<?= $edtInfo->statut ?>">
                    </td>
                    <td><span class="gu-chip"><?= strtoupper(htmlspecialchars($promotion->sigle_filiere)) ?></span></td>
                    <td><?= strtoupper(htmlspecialchars($promotion->sigle_semestre)) ?> <span class="text-tertiary">(<?= htmlspecialchars($promotion->annee_universitaire) ?>)</span></td>
                    <td style="font-weight:var(--fw-medium);"><?= ucwords(strtolower(htmlspecialchars($module->nom_module))) ?></td>
                    <td class="d-none d-lg-table-cell" style="font-size:var(--fs-sm);">
                        <?php if (!empty($ens)): ?>
                            <?php if (in_array(trim(strtoupper($ens[0]->groupe)), ['GP', 'GROUPE PRINCIPAL'], true)): ?>
                                <?= strtoupper(htmlspecialchars($ens[0]->nom_salle)) ?>
                            <?php else: foreach ($ens as $e): ?>
                                <span class="d-block"><?= htmlspecialchars($e->groupe) . ' (' . strtoupper(htmlspecialchars($e->nom_salle)) . ')' ?></span>
                            <?php endforeach; endif; ?>
                        <?php else: ?>—<?php endif; ?>
                    </td>
                    <td class="d-none d-md-table-cell" style="font-size:var(--fs-sm);"><?= htmlspecialchars($edtInfo->date_debut) ?></td>
                    <td>
                        <?php if ((int) $edtInfo->statut === 1): ?>
                            <span class="badge badge-soft-success"><i class="bx bx-check-circle"></i> Validé</span>
                        <?php else: ?>
                            <span class="badge badge-soft-warning"><i class="bx bx-time"></i> En cours</span>
                        <?php endif; ?>
                    </td>
                    <td style="text-align:right;white-space:nowrap;">
                        <a href="<?= ROOT ?>/Emploi_du_temps/apercu_edt/<?= $edtInfo->id_edt ?>" class="gu-icon-act" title="Aperçu"><i class="bx bx-show"></i></a>
                        <?php if ((int) $edtInfo->statut === 0): ?>
                            <a href="<?= ROOT ?>/Emploi_du_temps/editer_edt/<?= $edtInfo->id_edt ?>" class="gu-icon-act" title="Éditer"><i class="bx bx-edit-alt"></i></a>
                        <?php endif; ?>
                        <a href="#" class="gu-icon-act imprimerEdt" data-id="<?= $edtInfo->id_edt ?>" data-nom="<?= htmlspecialchars($nomEdt) ?>" title="Imprimer (PDF)"><i class="bx bx-printer"></i></a>
                    </td>
                </tr>
            <?php $index++; endforeach; endif; ?>
        </tbody>
    </table>
</div>
