<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau Récapitulatif des EDT</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #111; margin: 0; }
        .band { background-color: #14346b; color: #ffffff; padding: 12px 16px; text-align: center; }
        .band h1 { margin: 0; font-size: 15px; }
        .wrap { padding: 16px 20px; }
        h2 { font-size: 13px; color: #14346b; border-bottom: 2px solid #14346b; padding-bottom: 4px; margin: 18px 0 8px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 16px; table-layout: fixed; }
        th, td { border: 1px solid #9aa3b2; padding: 6px; text-align: center; word-wrap: break-word; font-size: 11px; }
        th { background-color: #14346b; color: #ffffff; }
        tbody tr:nth-child(even) { background-color: #f6f8fc; }
        td.mat { text-align: left; }
        .name { text-align: left; font-weight: bold; }
        .footer { padding: 0 20px; }
        .sign { float: right; text-align: right; }
    </style>
</head>
<body>
    <div class="band"><h1>INSTITUT UNIVERSITAIRE DE LA FORMATION PROFESSIONNELLE (IUFP) — Récapitulatif des EDT</h1></div>

    <div class="wrap">
        <h2>Enseignants permanents</h2>
        <table>
            <tr>
                <th style="width:24%;">Enseignant</th>
                <th style="width:11%;">Matricule</th>
                <th style="width:13%;">Grade</th>
                <th style="width:28%;">Matières</th>
                <th style="width:8%;">VH effectué</th>
                <th style="width:8%;">VH dû</th>
                <th style="width:8%;">VH supp.</th>
            </tr>
            <?php if (empty($permanents)): ?>
                <tr><td colspan="7" style="color:#888;">Aucun enseignant permanent.</td></tr>
            <?php else: foreach ($permanents as $e): ?>
                <tr>
                    <td class="name"><?= htmlspecialchars(($e->enseignant_prenom ?? '') . ' ' . ($e->enseignant_nom ?? '')) ?></td>
                    <td><?= !empty($e->enseignant_matricule) ? htmlspecialchars($e->enseignant_matricule) : '—' ?></td>
                    <td><?= htmlspecialchars($e->nom_grade ?? '—') ?></td>
                    <td class="mat"><?= implode('<br>', array_map(fn($m) => '· ' . htmlspecialchars($m), array_unique($e->emplois_du_temps ?? []))) ?></td>
                    <td><?= (int) ($e->heures_effectuees ?? 0) ?> h</td>
                    <td><?= (int) ($e->heures_dues ?? 0) ?> h</td>
                    <td><strong><?= (int) ($e->heures_supp ?? 0) ?> h</strong></td>
                </tr>
            <?php endforeach; endif; ?>
        </table>

        <h2>Enseignants non permanents (vacataires)</h2>
        <table>
            <tr>
                <th style="width:26%;">Enseignant</th>
                <th style="width:16%;">Matricule / Naiss.</th>
                <th style="width:22%;">Grade / Diplôme</th>
                <th style="width:26%;">Matières</th>
                <th style="width:10%;">VH effectué</th>
            </tr>
            <?php if (empty($non_permanents)): ?>
                <tr><td colspan="5" style="color:#888;">Aucun enseignant vacataire.</td></tr>
            <?php else: foreach ($non_permanents as $e): ?>
                <tr>
                    <td class="name"><?= htmlspecialchars(($e->enseignant_prenom ?? '') . ' ' . ($e->enseignant_nom ?? '')) ?></td>
                    <td><?= !empty($e->enseignant_matricule) ? htmlspecialchars($e->enseignant_matricule) : (!empty($e->enseignant_date_naissance) ? date('d-m-Y', strtotime($e->enseignant_date_naissance)) : '—') ?></td>
                    <td><?= htmlspecialchars(($e->nom_grade ?? '—') . ' / ' . ($e->enseignant_diplome ?? '')) ?></td>
                    <td class="mat"><?= implode('<br>', array_map(fn($m) => '· ' . htmlspecialchars($m), array_unique($e->emplois_du_temps ?? []))) ?></td>
                    <td><strong><?= (int) ($e->heures_supp ?? 0) ?> h</strong></td>
                </tr>
            <?php endforeach; endif; ?>
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
