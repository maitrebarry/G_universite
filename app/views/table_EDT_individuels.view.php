<?php if (empty($liste)): ?>
    <div class="gu-empty"><i class="bx bx-calendar-x"></i>Aucun emploi du temps individuel trouvé pour cette période.</div>
<?php else: ?>
<?php
$nbEnseignants = count(array_unique(array_map(fn($i) => $i->enseignant_id, $liste)));
?>
<form id="form-impression-edt" method="post" action="<?= ROOT ?>/Enseignants/imprimerEDTIndividuels" target="_blank">
    <input type="hidden" name="date_debut" value="<?= htmlspecialchars($date_debut) ?>">
    <input type="hidden" name="date_fin" value="<?= htmlspecialchars($date_fin) ?>">
    <input type="hidden" name="periode_id" value="<?= htmlspecialchars($periode_id) ?>">

    <div class="d-flex align-items-center justify-content-between flex-wrap mb-2" style="gap:10px;">
        <span class="text-muted" style="font-size:.85rem;"><i class="bx bx-user-voice"></i> <b><?= $nbEnseignants ?></b> enseignant(s) · <b><?= count($liste) ?></b> ligne(s) — cochez puis exportez.</span>
        <div style="display:flex;gap:8px;flex-wrap:wrap;">
            <button type="button" class="btn btn-soft-primary" id="voirEDTBtn"><i class="bx bx-show"></i> Voir les EDT</button>
            <button type="submit" class="btn btn-primary"><i class="bx bx-printer"></i> Imprimer (PDF)</button>
            <button type="submit" class="btn btn-soft-success" formaction="<?= ROOT ?>/Enseignants/genererPDF"><i class="bx bx-file"></i> Récap PDF</button>
        </div>
    </div>

    <div class="gu-table-wrap" style="max-height:calc(100vh - 380px);">
        <table class="gu-table" style="width:100%;">
            <thead>
                <tr>
                    <th style="width:40px;text-align:center;"><input type="checkbox" id="allSelectEDT"></th>
                    <th>Filière</th>
                    <th>Classe</th>
                    <th>Modules</th>
                    <th>Enseignant</th>
                    <th class="text-center">Statut</th>
                    <th>Salle</th>
                    <th class="text-center">Heures / Supp.</th>
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
                    if (!isset($enseignant_cumul[$eid])) $enseignant_cumul[$eid] = 0;
                    $enseignant_cumul[$eid] += $heures_ligne;
                    $perm = ($item->enseignant_statut ?? '') == 'PERMANANT';
                ?>
                    <tr>
                        <td class="text-center"><input type="checkbox" class="rowCheckboxEDT" id="checkboxEDT<?= $index ?>" name="enseignants[]" value="<?= $item->enseignant_id ?>"></td>
                        <td style="font-weight:var(--fw-medium);"><?= htmlspecialchars($item->sigle_filiere ?? '') ?></td>
                        <td><?= htmlspecialchars($item->classe ?? '') ?></td>
                        <td style="font-size:.9em;"><?= htmlspecialchars($item->modules ?? '') ?></td>
                        <td style="font-weight:var(--fw-medium);"><?= htmlspecialchars(($item->enseignant_prenom ?? '') . ' ' . ($item->enseignant_nom ?? '')) ?></td>
                        <td class="text-center"><span class="badge badge-soft-<?= $perm ? 'success' : 'warning' ?>"><?= $perm ? 'Permanent' : 'Vacataire' ?></span></td>
                        <td><?= htmlspecialchars($item->salle ?? '') ?></td>
                        <td class="text-center" style="font-weight:600;">
                            <?php
                            if ($perm) {
                                $heure_supp = $enseignant_cumul[$eid] - $heures_dues;
                                echo '<span style="color:' . ($heure_supp > 0 ? '#15803d' : '#5a6b86') . ';">' . ($heure_supp > 0 ? '+' : '') . $heure_supp . ' h</span>';
                            } else {
                                echo $heures_ligne . ' h';
                            }
                            ?>
                        </td>
                    </tr>
                <?php $index++; endforeach; ?>
            </tbody>
        </table>
    </div>
</form>

<script>
    document.getElementById('allSelectEDT').addEventListener('change', function () {
        const checked = this.checked;
        document.querySelectorAll('.rowCheckboxEDT').forEach(cb => cb.checked = checked);
    });
    function ouvrirApercusEDT() {
        const checked = document.querySelectorAll('.rowCheckboxEDT:checked');
        if (checked.length === 0) { alert('Veuillez sélectionner au moins un enseignant.'); return; }
        let ids = [];
        checked.forEach(cb => ids.push(cb.value));
        const date_debut = "<?= htmlspecialchars($date_debut) ?>";
        const date_fin = "<?= htmlspecialchars($date_fin) ?>";
        const periode_id = document.getElementById("periode_debut").value;
        if (!periode_id) { alert('Veuillez sélectionner une période.'); return; }
        const url = "<?= ROOT ?>/index.php?url=Enseignants/apercuEDTIndividuelsGroupes"
            + "&enseignants=" + encodeURIComponent(ids.join(','))
            + "&date_debut=" + encodeURIComponent(date_debut)
            + "&date_fin=" + encodeURIComponent(date_fin)
            + "&periode_id=" + encodeURIComponent(periode_id);
        window.open(url, '_blank');
    }
    document.getElementById('voirEDTBtn').addEventListener('click', ouvrirApercusEDT);
</script>
<?php endif; ?>
