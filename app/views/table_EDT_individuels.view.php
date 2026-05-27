
<?php if (empty($liste)): ?>
    
    <div class="alert alert-warning">Aucun EDT individuel trouvé pour cette période.</div>
<?php else: ?>
<form id="form-impression-edt" method="post" action="<?= ROOT ?>/Enseignants/imprimerEDTIndividuels" target="_blank">
    <input type="hidden" name="date_debut" value="<?= $date_debut ?>">
    <input type="hidden" name="date_fin" value="<?= $date_fin ?>">
    <input type="hidden" name="periode_id" value="<?= $periode_id ?>">
    <div class="d-flex justify-content-end mb-2">
        <button type="button" class="btn btn-success mr-2" id="voirEDTBtn">Voir les EDT individuels</button>
        <button type="submit" class="btn btn-primary mr-2">
            <i class="bx bx-printer"></i> Imprimer tout
        </button>
        <button type="submit" class="btn btn-info" formaction="<?= ROOT ?>/Enseignants/genererPDF">
            <i class="bx bx-file"></i> Récap EDT 📄
        </button>

    </div>   
    <table class="table table-bordered">
        <thead>
            <tr>
                <th style="width:4%;">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="allSelectEDT">
                        <label class="custom-control-label" for="allSelectEDT"></label>
                    </div>
                </th>
                <th style="width:8%;">Filière</th>
                <th style="width:12%;">Classe</th>
                <th style="width:18%;">Modules</th>
                <th style="width:18%;">Professeurs</th>
                <th style="width:8%;">Statut</th>
                <th style="width:10%;">Salle</th>
                <th style="width:10%;">Heure/Supp</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $enseignant_cumul = [];
        $index = 0;
        foreach ($liste as $item):
            $eid = $item->enseignant_id;
            $heures_dues = $item->heures_dues ?? 0;
            $heures_ligne = $item->heures_total ?? 0;
        
            // Initialiser le cumul pour chaque enseignant
            if (!isset($enseignant_cumul[$eid])) {
                $enseignant_cumul[$eid] = 0;
            }
        
            // Ajouter les heures de la ligne au cumul AVANT affichage
            $enseignant_cumul[$eid] += $heures_ligne;
        ?>
            <tr>
                <td>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input rowCheckboxEDT" id="checkboxEDT<?= $index ?>" name="enseignants[]" value="<?= $item->enseignant_id ?>">
                        <label class="custom-control-label" for="checkboxEDT<?= $index ?>"></label>
                    </div>
                </td>
                <td><span style="font-weight:bold;"><?= htmlspecialchars($item->sigle_filiere ?? '') ?></span></td>
                <td><span style="font-size:0.9em;"><?= htmlspecialchars($item->classe ?? '') ?></span></td>
                <td><?= htmlspecialchars($item->modules ?? '') ?></td>
                <td><span style="font-weight:bold;font-style:italic;"><?= htmlspecialchars($item->enseignant_prenom ?? '') . ' ' . htmlspecialchars($item->enseignant_nom ?? '') ?></span></td>
                <td><?= htmlspecialchars($item->enseignant_statut ?? '') ?></td>
                <td><?= htmlspecialchars($item->salle ?? '') ?></td>
                <td>
                    <?php
                        if (($item->enseignant_statut ?? '') == 'PERMANANT') {
                            $heure_supp = $enseignant_cumul[$eid] - $heures_dues;
                            echo ($heure_supp > 0 ? '+' : '') . $heure_supp . " h";
                        } else {
                            echo $heures_ligne . " h";
                        }
                    ?>
                </td>
            </tr>
        <?php
            $index++;
        endforeach;
        ?>
        </tbody>
    </table>    
    <div class="d-flex justify-content mt-2">
        <button type="button" class="btn btn-success mr-2" id="voirEDTBtnBottom">Voir les EDT individuels</button>
        <button type="submit" class="btn btn-primary mr-2">
            <i class="bx bx-printer"></i> Imprimer tout
        </button>
        <button type="submit" class="btn btn-info" formaction="<?= ROOT ?>/Enseignants/genererPDF">
            <i class="bx bx-file"></i> Récap EDT 📄
        </button>

    </div>
</form>
<script src="<?= ROOT ?>/assets/vendors/js/tables/datatable/datatables.min.js"></script>
<script src="<?= ROOT ?>/assets/vendors/js/tables/datatable/dataTables.bootstrap4.min.js"></script>
<script src="<?= ROOT ?>/assets/vendors/js/tables/datatable/dataTables.buttons.min.js"></script>
<script src="<?= ROOT ?>/assets/vendors/js/tables/datatable/datatables.checkboxes.min.js"></script>
<script src="<?= ROOT ?>/assets/js/scripts/datatables/datatable.js"></script>
<script>
    // Sélectionner/désélectionner toutes les cases
    document.getElementById('allSelectEDT').addEventListener('change', function() {
        const checked = this.checked;
        document.querySelectorAll('.rowCheckboxEDT').forEach(cb => cb.checked = checked);
    });
    function ouvrirApercusEDT() {
        const checked = document.querySelectorAll('.rowCheckboxEDT:checked');
        if (checked.length === 0) {
            alert('Veuillez sélectionner au moins un enseignant.');
            return;
        }
        
        let ids = [];
        checked.forEach(cb => ids.push(cb.value));

        const date_debut = "<?= $date_debut ?>";
        const date_fin = "<?= $date_fin ?>";
        const periode_id = document.getElementById("periode_debut").value; // 🔹 Récupérer `periode_id`

        if (!periode_id) {
            alert('Veuillez sélectionner une période.');
            return;
        }

        const url = "<?= ROOT ?>/index.php?url=Enseignants/apercuEDTIndividuelsGroupes"
            + "&enseignants=" + encodeURIComponent(ids.join(','))
            + "&date_debut=" + encodeURIComponent(date_debut)
            + "&date_fin=" + encodeURIComponent(date_fin)
            + "&periode_id=" + encodeURIComponent(periode_id); // 🔹 Ajout de `periode_id`

        window.open(url, '_blank');
    }
    document.getElementById('voirEDTBtn').addEventListener('click', ouvrirApercusEDT);
    document.getElementById('voirEDTBtnBottom').addEventListener('click', ouvrirApercusEDT);
</script>
<?php endif; ?>
