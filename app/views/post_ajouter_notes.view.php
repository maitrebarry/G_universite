<!-- En-tête module -->
<div class="gu-note-head">
    <div><span class="lbl">UE</span><span class="val"><?= htmlspecialchars($infosModule->nom_ue ?? '—') ?></span></div>
    <div><span class="lbl">Module</span><span class="val"><?= htmlspecialchars($infosModule->nom_module ?? '—') ?></span></div>
    <div><span class="lbl">Crédit</span><span class="val coeficient"><?= htmlspecialchars($infosModule->coeficient ?? '—') ?></span></div>
</div>

<!-- Tableau de bord (mis à jour en direct par note.js) -->
<div class="gu-note-stats" id="noteStats"></div>

<!-- Barre : recherche + état global -->
<div class="gu-note-toolbar">
    <div class="gu-field gu-search">
        <i class="bx bx-search gu-ico"></i>
        <input type="text" class="form-control has-ico" id="noteSearch" placeholder="Rechercher (nom, matricule…)">
    </div>
    <span class="gu-note-saveall ok" id="saveAll"><i class="bx bx-check-circle"></i> Tout est enregistré</span>
</div>

<div class="gu-table-wrap" style="max-height:calc(100vh - 430px);">
    <table class="gu-table gu-card-mobile" id="notesTable" style="width:100%;">
        <thead>
            <tr>
                <th>Matricule</th>
                <th>Nom &amp; prénom</th>
                <th class="text-center">Genre</th>
                <th class="text-center">Devoir</th>
                <th class="text-center">Évaluation</th>
                <th class="text-center">Session</th>
                <th class="text-center">Moyenne</th>
                <th class="text-center">État</th>
            </tr>
        </thead>
        <tbody id="tableBody">
            <?php foreach ($note_des_etudiants as $note): ?>
                <tr class="rowt" data-id="<?= $note->id_note ?>"
                    data-nom="<?= htmlspecialchars(strtolower($note->prenom . ' ' . $note->nom_prenom_etudiant)) ?>"
                    data-mat="<?= htmlspecialchars(strtolower($note->matricule_etudiant)) ?>">
                    <td data-label="Matricule"><span class="text-secondary"><?= htmlspecialchars($note->matricule_etudiant) ?></span></td>
                    <td data-label="Nom" style="font-weight:var(--fw-medium);"><?= ucwords(strtolower(htmlspecialchars($note->prenom . ' ' . $note->nom_prenom_etudiant))) ?></td>
                    <td data-label="Genre" class="text-center"><?= ($note->genre_etudiant == 'Féminin') ? 'F' : 'M' ?></td>
                    <td data-label="Devoir" class="text-center"><input type="number" class="form-control note noteDevoir" min="0" max="20" step="0.5" value="<?= $note->note_devoir ?? '' ?>"></td>
                    <td data-label="Évaluation" class="text-center"><input type="number" class="form-control note noteEvaluation" min="0" max="20" step="0.5" value="<?= $note->note_evaluation ?? '' ?>"></td>
                    <td data-label="Session" class="text-center"><input type="number" class="form-control note noteSession" min="0" max="20" step="0.5" value="<?= $note->note_session ?? '' ?>"></td>
                    <td data-label="Moyenne" class="text-center"><span class="moyenneBadge badge badge-soft-secondary">—</span></td>
                    <td data-label="État" class="text-center"><span class="saveStatus"></span></td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>

<div id="loadingSpinner" style="display:none;"></div>
<div id="message" class="d-none"></div>
