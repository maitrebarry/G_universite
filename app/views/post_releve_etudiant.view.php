<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Relevé de notes - Université de Ségou</title>
    <link rel="stylesheet" type="text/css" href="<?= ROOT ?>/assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="<?= ROOT ?>/assets/css/bootstrap-extended.css">
    <style>
        /* RESET IMPORTANT pour html2pdf */
        body,
        html {
            margin: 0;
            padding: 0;
            width: 100%;
        }

        body {
            font-family: "Times New Roman", serif;
            font-size: 12px;
            color: #000;
            margin: 0;
            padding: 0;
        }

        /* Conteneur principal sans marges */
        .container.releve {
            margin: 0 !important;
            padding: 5mm 10mm;
            /* Petits paddings internes */
            width: 100%;
            box-sizing: border-box;
        }

        @media print {

            body,
            html {
                margin: 0;
                padding: 0;
            }



            .releve {
                page-break-inside: avoid;
                margin: 0 !important;
                padding: 0;
            }

            .page-break {
                page-break-before: always;
            }
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
            /* Réduit la marge */
            padding-top: 0;
            /* Supprime l'espace en haut */
        }

        .table th,
        .table td {
            font-size: 12px;
            text-align: center;
            vertical-align: middle;
            padding: 2px 4px;
            /* Réduit le padding des cellules */
        }

        .note {
            max-width: 60px;
        }

        .infos-etudiant h6 {
            margin: 0;
            font-size: 13px;
            line-height: 1.2;
            /* Réduit l'interligne */
        }

        .signature {
            text-align: right;
        }

        /* Réduire les marges Bootstrap */
        .row {
            margin-left: 0;
            margin-right: 0;
        }

        .mb-3 {
            margin-bottom: 0.5rem !important;
            /* Réduit la marge */
        }

        .mt-2 {
            margin-top: 0.5rem !important;
            /* Réduit la marge */
        }
    </style>
</head>

<body>
    <?php for ($i = 0; $i < count($moyennesSemestre); $i++): ?>
        <?php
        $etudiant = $moyennesSemestre[$i]['etudiant'];
        $note = $moyennesSemestre[$i]['moyenne'];
        $ues = $moyennesUe[$i]['ues'];
        ?>

        <div class="container releve m-2 p-0">


            <!-- En-tête -->
            <div class="header  d-flex justify-content-around align-items-center text-success "
                style="margin-top: 0 !important; padding-top: 0 !important;">
                <div class="d-flex">
                    <img src="" alt="">
                    <h5 class="fw-bold" style="margin-bottom: 2px;">Université de Ségou</h5>
                </div>
                <h6 class="fw-bold" style="margin-bottom: 2px;">Relevé de notes</h6>
                <h6 class="fw-bold" style="margin-bottom: 2px;">Institut Universitaire de Formation Professionnelle</h6>
            </div>

            <!-- Infos générales -->
            <div class="row mb-4 infos-etudiant mt-4">
                <!-- mb-2 au lieu de mb-3 -->
                <div class="col-md-6">
                    <h6><strong>Année universitaire :</strong><?= $promotion->annee_universitaire ?></h6>
                    <h6><strong>Semestre :</strong> (<?= strtoupper($semestre->sigle_semestre) ?>)</h6>
                    <h6><strong>Mention :</strong class="text-uppercase"> <?= $promotion->nom_filiere ?></h6>
                </div>
                <div class="col-md-6 float-end text-end">
                    <h6><strong>Nom :</strong> <?= $etudiant->nom_prenom_etudiant ?></h6>
                    <h6><strong>Prénoms :</strong> <?= $etudiant->nom_prenom_etudiant ?>.</h6>
                    <h6><strong>Date de naissance :</strong> <?= $etudiant->date_naissance_etudiant ?></h6>
                    <h6><strong>Lieu de naissance :</strong> <?= $etudiant->lieu_naissance_etudiant ?></h6>
                </div>
            </div>

            <!-- Tableau -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped" style="margin-bottom: 5px;">
                    <thead class="table-secondary">
                        <tr>
                            <th>UE</th>
                            <th class="module" style="max-width: 120px;">ECUE</th>
                            <th class="note">Crédits</th>
                            <th class="note">Session</th>
                            <th class="note">Moyenne</th>
                            <th class="note">PJ ECUE</th>
                            <th class="note">Moyenne UE</th>
                            <th class="note">PJ UE</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $number = 0 ?>

                        <?php foreach ($infosSemestre as $ue): ?>

                            <tr>
                                <td class="fw-bold"><?= $ue[0]->nom_ue ?></td>

                                <td style="text-wrap: no-wrap;">
                                    <?php foreach ($ue as $module): ?>
                                        <div class="" title="<?= $module->nom_module ?>" style="margin-bottom: 1px;">
                                            <?php echo (strlen($module->nom_module) < 100) ? strtoupper($module->nom_module) : strtoupper($module->sigle_module) ?>
                                        </div>
                                    <?php endforeach ?>
                                </td>

                                <?php $modules = $ues[$number]['modules']; ?>

                                <td class="note">
                                    <?php foreach ($modules as $module): ?>
                                        <div class="" style="margin-bottom: 1px;">
                                            <?= (isset($module->coeficient)) ? $module->coeficient : "X" ?>
                                        </div>
                                    <?php endforeach ?>
                                </td>

                                <td class="note">
                                    <?php foreach ($modules as $module): ?>
                                        <div class="" style="margin-bottom: 1px;">
                                            <?= (isset($module->note_session) && $module->note_session == 0) ? "1" : "2" ?>
                                        </div>
                                    <?php endforeach ?>
                                </td>

                                <td class="note">
                                    <?php foreach ($modules as $module): ?>
                                        <div class="" style="margin-bottom: 1px;">
                                            <?= ($module->moyenne_module == 0) ? "X" : number_format($module->moyenne_module, 2) ?>
                                        </div>
                                    <?php endforeach ?>
                                </td>
                                <td class="note"></td>
                                <td class="note">
                                    <?= ($ues[$number]['moyenne'] == 0) ? "X" : number_format($ues[$number]['moyenne'], 2) ?>
                                </td>
                                <td class="note"></td>
                            </tr>

                            <?php $number++ ?>

                        <?php endforeach ?>

                    </tbody>
                </table>
            </div>

            <!-- Résultats -->
            <div class="row mt-4 footer" style="margin-top: 15px !important;">
                <!-- mt-1 au lieu de mt-2 -->
                <div class="col-md-6 float-start text-start">
                    <h6><strong>Moyenne du semestre :</strong> <?= number_format($note, 2) ?></h6>
                    <h6><strong>Point Jury :</strong> </h6>
                    <h6><strong>Validation du semestre :</strong> Admis</h6>
                </div>

                <!-- Signature -->
                <div class="signature col-md-6 ">
                    <p style="margin-bottom: 2px;">
                        <?php
                        date_default_timezone_set("Africa/Bamako");
                        echo "Ségou, le " . date("d F Y");
                        ?>
                    </p>
                    <p style="margin-bottom: 2px;"><strong>Le
                            <?php echo (isset($_SESSION['role'])) ? strtoupper($_SESSION['role']) : "" ?>

                        </strong></p>
                    <p style="margin-bottom: 2px;">
                        <strong>Dr
                            <?php echo (isset($_SESSION['nom_prenom'])) ? strtoupper($_SESSION['nom_prenom']) : "" ?>
                        </strong><br>
                        <?php echo (isset($_SESSION['nom_grade'])) ? strtoupper($_SESSION['nom_grade']) : "" ?>
                    </p>
                </div>
            </div>

        </div>

        <!-- Saut de page amélioré -->
        <div style="page-break-after: always;"></div>
    <?php endfor ?>

</body>

</html>