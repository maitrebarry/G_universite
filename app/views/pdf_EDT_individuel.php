
<!-- filepath: c:\xampp\htdocs\G_universite\app\views\pdf_EDT_individuel.php -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Emploi du temps individuel</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            text-decoration: underline;
        }
        .header h2 {
            margin: 5px 0;
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #222;
            padding: 6px 4px;
            text-align: center;
            word-break: break-word;
        }
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
    <div class="header">
        <img src="<?= ROOT ?>/assets/images/logo.jpg" alt="Logo" style="width:80px;float:left;">
        <h1>INSTITUT UNIVERSITAIRE DE LA FORMATION PROFESSIONNELLE (IUFP)</h1>
        <h2>
            EMPLOI DU TEMPS INDIVIDUEL DE M. 
            <?= isset($enseignant->enseignant_prenom) ? htmlspecialchars($enseignant->enseignant_prenom) : 'Non spécifié'; ?>
            <?= isset($enseignant->enseignant_nom) ? htmlspecialchars($enseignant->enseignant_nom) : 'Non spécifié'; ?>
            <br>
            <small>Du <?= date('d-m-Y', strtotime($date_debut)); ?> au <?= date('d-m-Y', strtotime($date_fin)); ?></small>
        </h2>
        <p>
            <strong>Semestres et Promotions :</strong>
            <?php if (!empty($semestres_promotions)): ?>
                <?= implode(', ', array_map('htmlspecialchars', $semestres_promotions)); ?>
            <?php else: ?>
                Aucune promotion spécifiée.
            <?php endif; ?>
        </p>
    </div>

    <table>
        <tr>
            <th>Nom</th>
            <td><?= isset($enseignant->enseignant_nom) ? htmlspecialchars($enseignant->enseignant_nom) : 'Non spécifié'; ?></td>
            <th>Prénom</th>
            <td><?= isset($enseignant->enseignant_prenom) ? htmlspecialchars($enseignant->enseignant_prenom) : 'Non spécifié'; ?></td>
            <th>Grade</th>
            <td><?= isset($enseignant->nom_grade) ? htmlspecialchars($enseignant->nom_grade) : 'Non spécifié'; ?></td>
        </tr>
        <tr>
            <th>Statut</th>
            <td><?= isset($enseignant->enseignant_statut) ? htmlspecialchars($enseignant->enseignant_statut) : 'Non spécifié'; ?></td>
            <th>Date de naissance</th>
            <td><?= isset($enseignant->enseignant_date_naissance) ? date('d-m-Y', strtotime($enseignant->enseignant_date_naissance)) : 'Non spécifiée'; ?></td>
            <th>Année Universitaire</th>
            <td>
                <?php if (!empty($emplois_du_temps)): ?>
                    <?= implode(', ', array_unique(array_map(function($edt) {
                        return htmlspecialchars($edt->annee_universitaire);
                    }, $emplois_du_temps))); ?>
                <?php else: ?>
                    Non spécifiée
                <?php endif; ?>
            </td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>Semaine</th>
                <th>Module1(VH)/Classe</th>
                <th>Module2(VH)/Classe</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($emplois_du_temps)): ?>
                <?php 
                $traited_dates = [];
                foreach ($emplois_du_temps as $edt) {
                    $date_debut_str = $edt->date_debut;
                    $date_fin_str = $edt->date_fin;
                    $niveau = htmlspecialchars($edt->nom_parcours . $edt->sigle_semestre);

                    if (isset($traited_dates[$date_debut_str][$date_fin_str])) {
                        $traited_dates[$date_debut_str][$date_fin_str][] = [
                            'module' => htmlspecialchars($edt->nom_module) . " (" . htmlspecialchars($edt->heure_total) . "h)",
                            'niveau' => $niveau
                        ];
                    } else {
                        $traited_dates[$date_debut_str][$date_fin_str] = [
                            [
                                'module' => htmlspecialchars($edt->nom_module) . " (" . htmlspecialchars($edt->heure_total) . "h)",
                                'niveau' => $niveau
                            ]
                        ];
                    }
                }

                foreach ($traited_dates as $date_debut_str => $fin_dates) {
                    foreach ($fin_dates as $date_fin_str => $modules_niveaux) {
                        ?>
                        <tr>
                            <td>Du <?= date('d-m-Y', strtotime($date_debut_str)); ?> au <?= date('d-m-Y', strtotime($date_fin_str)); ?></td>
                            <td><?= $modules_niveaux[0]['module']; ?> <?= isset($modules_niveaux[0]['niveau']) ? " / " . $modules_niveaux[0]['niveau'] : ''; ?></td>
                            <td><?= isset($modules_niveaux[1]['module']) ? $modules_niveaux[1]['module'] : ''; ?> <?= isset($modules_niveaux[1]['niveau']) ? " / " . $modules_niveaux[1]['niveau'] : ''; ?></td>
                        </tr>
                        <?php
                    }
                }
                ?>
            <?php else: ?>
                <tr>
                    <td colspan="3" class="text-center">Aucun emploi du temps disponible.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <table>
        <tr>
            <th>Heures totales</th>
            <td><?= isset($heures_totales) ? htmlspecialchars($heures_totales) : 0; ?></td>
            <th>Heures dues</th>
            <td><?= isset($heures_dues) ? htmlspecialchars($heures_dues) : 0; ?></td>
            <th>Supplémentaires</th>
            <td><?= isset($heures_supp) ? htmlspecialchars($heures_supp) : 0; ?></td>
        </tr>
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