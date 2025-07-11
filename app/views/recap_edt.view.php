<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau Récapitulatif des EDT</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h1 { text-align: center; border-bottom: 2px solid black; padding-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; table-layout: fixed; }
        th, td { border: 1px solid black; padding: 6px; text-align: center; word-wrap: break-word; }
        th { background-color: #f4f4f4; font-weight: bold; }
        td { font-size: 11px; }
        .footer {
            margin-top: 30px;
            font-size: 12px;
        }
        .signature {
            text-align: right;
            margin-top: 40px;
        }
    </style>
</head>
<body>
    <h1>Tableau Récapitulatif des EDT Individuels</h1>
    <h2>Enseignants Permanents</h2>
    <table>
        <tr>
            <th style="width:12%;">Prénom</th>
            <th style="width:12%;">Nom</th>
            <th style="width:10%;">Matricule</th>
            <th style="width:10%;">Grade</th>
            <th style="width:25%;">Matières</th>
            <th style="width:10%;">Vol. Hor. Effectué</th>
            <th style="width:10%;">Vol. Hor. Dû</th>
            <th style="width:10%;">Vol. Hor. Sup</th>
        </tr>
            <?php foreach ($permanents as $enseignant): ?>
                <tr>
                    <td><?= $enseignant->enseignant_prenom ?></td>
                    <td><?= $enseignant->enseignant_nom ?></td>
                    <td><?= !empty($enseignant->enseignant_matricule) ? $enseignant->enseignant_matricule : "Matricule inconnu" ?></td>
                    <td><?= $enseignant->nom_grade?></td>
                    <td>
                        <?php
                            $matieres = array_unique($enseignant->emplois_du_temps);
                            echo implode("<br>- ", array_map(fn($m) => $m . ',', $matieres));
                        ?>
                    </td>

                    <td><?= $enseignant->heures_effectuees ?? '-' ?></td>
                    <td><?= $enseignant->heures_dues ?? '-' ?></td>
                    <td><?= $enseignant->heures_supp ?? '-' ?></td>
                </tr>
            <?php endforeach; ?>
     </table>

     <h2>Enseignants Non Permanents</h2>
    <table>
        <tr>
            <th style="width:15%;">Prénom</th>
            <th style="width:15%;">Nom</th>
            <th style="width:15%;">Matricule/Nais.</th>
            <th style="width:20%;">Grade/Diplôme</th>
            <th style="width:25%;">Matières</th>
            <th style="width:10%;">Vol. Hor. Sup</th>
        </tr>
        <?php foreach ($non_permanents as $enseignant): ?>
            <tr>
                <td><?= $enseignant->enseignant_prenom ?></td>
                <td><?= $enseignant->enseignant_nom ?></td>
                <td>
                    <?= !empty($enseignant->enseignant_matricule) 
                        ? htmlspecialchars($enseignant->enseignant_matricule) 
                        : date('d-m-Y', strtotime($enseignant->enseignant_date_naissance)) ?>
                </td>
                <td><?= $enseignant->nom_grade . " / " . $enseignant->enseignant_diplome ?></td>
                <td>
                    <?php
                        $matieres = array_unique($enseignant->emplois_du_temps);
                        echo implode("<br>- ", array_map(fn($m) => $m . ',', $matieres));
                    ?>
                </td>
                <td><?= $enseignant->heures_supp ?? '-' ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
    <div class="footer">
        <div style="float:left;">
            <strong>Segou, le <?= date('d-m-Y') ?></strong>
        </div>
        <div class="signature">
            <div>
                <strong>Le Chef de DER</strong>
                <br>
                <?= isset($_SESSION['sigle_departement']) ? strtoupper($_SESSION['sigle_departement']) : "" ?>
            </div>
            <div>
                <?php if (!empty($_SESSION['signature'])): ?>
                    <img src="<?= ROOT . $_SESSION['signature'] ?>" alt="Signature" style="width: 120px; max-height: 50px;">
                <?php endif; ?>
            </div>
            <div>
                Dr <?= isset($_SESSION['nom_prenom']) ? strtoupper($_SESSION['nom_prenom']) : "" ?><br>
                <?= isset($_SESSION['nom_grade']) ? strtoupper($_SESSION['nom_grade']) : "" ?>
            </div>
        </div>
    </div>
</body>
</html>