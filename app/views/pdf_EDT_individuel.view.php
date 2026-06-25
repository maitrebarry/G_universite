<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Emploi du temps individuel</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #111; margin: 0; }
        .band { background-color: #14346b; color: #ffffff; padding: 12px 16px; text-align: center; }
        .band h1 { margin: 0; font-size: 15px; }
        .band h2 { margin: 4px 0 0; font-size: 12px; font-weight: normal; }
        .wrap { padding: 16px 20px; }
        .sub { font-size: 11px; color: #444; margin: 6px 0 14px; }
        table { width: 100%; border-collapse: collapse; font-size: 11px; margin-bottom: 14px; }
        th, td { border: 1px solid #9aa3b2; padding: 6px 5px; text-align: center; word-break: break-word; }
        th { background-color: #e7ecf5; color: #14346b; }
        .info th { width: 90px; text-align: left; }
        .info td { text-align: left; }
        .hrs td { font-size: 16px; font-weight: bold; color: #14346b; }
        .hrs th { background-color: #14346b; color: #fff; }
        .sched thead th { background-color: #14346b; color: #fff; }
        .sched td.wk { text-align: left; font-weight: bold; white-space: nowrap; }
        .sched td.cr { text-align: left; }
        .footer { padding: 0 20px; }
        .sign { float: right; text-align: right; }
    </style>
</head>
<body>
    <div class="band">
        <h1>INSTITUT UNIVERSITAIRE DE LA FORMATION PROFESSIONNELLE (IUFP)</h1>
        <h2>Emploi du temps individuel — <?= htmlspecialchars(($enseignant->enseignant_prenom ?? '') . ' ' . ($enseignant->enseignant_nom ?? '')) ?></h2>
    </div>

    <div class="wrap">
        <div class="sub">
            Du <?= date('d-m-Y', strtotime($date_debut)) ?> au <?= date('d-m-Y', strtotime($date_fin)) ?>
            &nbsp;·&nbsp; <strong>Semestres/Promotions :</strong>
            <?= !empty($semestres_promotions) ? implode(', ', array_map('htmlspecialchars', $semestres_promotions)) : 'Non spécifié' ?>
        </div>

        <table class="info">
            <tr>
                <th>Nom</th><td><?= htmlspecialchars($enseignant->enseignant_nom ?? '—') ?></td>
                <th>Prénom</th><td><?= htmlspecialchars($enseignant->enseignant_prenom ?? '—') ?></td>
            </tr>
            <tr>
                <th>Grade</th><td><?= htmlspecialchars($enseignant->nom_grade ?? '—') ?></td>
                <th>Statut</th><td><?= htmlspecialchars(($enseignant->enseignant_statut ?? '') === 'PERMANANT' ? 'Permanent' : 'Vacataire') ?></td>
            </tr>
            <tr>
                <th>Matricule</th><td><?= htmlspecialchars($enseignant->enseignant_matricule ?? '—') ?></td>
                <th>Année univ.</th>
                <td><?= !empty($emplois_du_temps) ? implode(', ', array_unique(array_map(fn($e) => htmlspecialchars($e->annee_universitaire), $emplois_du_temps))) : '—' ?></td>
            </tr>
        </table>

        <table class="hrs">
            <tr><th>Heures effectuées</th><th>Heures dues</th><th>Heures supplémentaires</th></tr>
            <tr><td><?= (int) ($heures_totales ?? 0) ?> h</td><td><?= (int) ($heures_dues ?? 0) ?> h</td><td><?= (int) ($heures_supp ?? 0) ?> h</td></tr>
        </table>

        <table class="sched">
            <thead><tr><th style="width:180px;">Semaine</th><th>Cours (module · VH · classe)</th><th style="width:60px;">Total</th></tr></thead>
            <tbody>
                <?php if (!empty($emplois_du_temps)):
                    $weeks = [];
                    foreach ($emplois_du_temps as $edt) {
                        $k = $edt->date_debut . '|' . $edt->date_fin;
                        if (!isset($weeks[$k])) $weeks[$k] = ['d' => $edt->date_debut, 'f' => $edt->date_fin, 'm' => [], 'h' => 0];
                        $weeks[$k]['m'][] = htmlspecialchars($edt->nom_module) . ' (' . (int) $edt->heure_total . 'h) · ' . htmlspecialchars(($edt->sigle_filiere ?? '') . '-' . ($edt->sigle_semestre ?? ''));
                        $weeks[$k]['h'] += (int) $edt->heure_total;
                    }
                    foreach ($weeks as $w): ?>
                        <tr>
                            <td class="wk">Du <?= date('d-m-Y', strtotime($w['d'])) ?><br>au <?= date('d-m-Y', strtotime($w['f'])) ?></td>
                            <td class="cr"><?= implode('<br>', $w['m']) ?></td>
                            <td><?= $w['h'] ?> h</td>
                        </tr>
                    <?php endforeach;
                else: ?>
                    <tr><td colspan="3">Aucun cours sur cette période.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="footer">
        <div style="float:left;"><strong>Ségou, le <?= date('d-m-Y') ?></strong></div>
        <div class="sign">
            <div><strong>Le Chef de Département <?= isset($_SESSION['sigle_departement']) ? strtoupper(htmlspecialchars($_SESSION['sigle_departement'])) : '' ?></strong></div>
            <?php if (!empty($_SESSION['signature'])): ?><div><img src="<?= ROOT . htmlspecialchars($_SESSION['signature']) ?>" alt="Signature" style="width:120px;max-height:50px;"></div><?php endif; ?>
            <div>Dr <?= isset($_SESSION['nom_prenom']) ? strtoupper(htmlspecialchars($_SESSION['nom_prenom'])) : '' ?></div>
        </div>
    </div>
</body>
</html>
