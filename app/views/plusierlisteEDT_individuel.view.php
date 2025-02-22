<?php $this->view( 'Partials/header' ) ?>
<style>
body {
    font-family: Arial, sans-serif;
    margin: 20px;
    font-size: 12px;
    /* Réduction de la taille de police */
}

.header {
    text-align: center;
    margin-bottom: 20px;
}

.header h1 {
    margin: 0;
    font-size: 20px;
    text-decoration: underline;
}

.header h2 {
    margin: 5px 0;
    font-size: 16px;
    text-decoration: underline;
}

.table-container {
    margin-bottom: 20px;
}

table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
}

table,
th,
td {
    border: 1px solid black;
}

th,
td {
    text-align: center;
    padding: 8px;
    word-wrap: break-word;
    /* Ajout du word-wrap pour les cellules de tableau */
}

.footer {
    font-size: 14px;
    margin-top: 20px;
}

.footer div {
    margin-bottom: 5px;
}

.signature {
    text-align: right;
    margin-top: 20px;
}

.signature div {
    margin-bottom: 5px;
}

.notes {
    margin-top: 30px;
    font-size: 14px;
}

.notes h3 {
    font-size: 16px;
    margin-bottom: 10px;
}

.notes p {
    margin: 5px 0;
}

input {
    padding: 8px;
    font-size: 16px;
    text-align: center;
}

td {
    padding: 15px 5px !important;
    word-wrap: break-word;
    /* Ajout du word-wrap pour les cellules de tableau */
}

.row {
    display: flex;
    flex-wrap: wrap;
}

.col-md-4 {
    flex: 0 0 33.3333%;
    max-width: 33.3333%;
    padding: 0 10px;
    box-sizing: border-box;
    word-wrap: break-word;
    /* Ajout du word-wrap pour les colonnes */
    white-space: normal;
}

p {
    word-wrap: break-word;
    white-space: normal;
    margin: 0;
    /* Ajouter une marge de zéro pour ajuster l'alignement */
}

@media print {
    .no-print {
        display: none !important;
        visibility: hidden !important;
    }
}
</style>
<div class="row">
    <div class="col-12">
        <div class="card card-animated-border-top" id="edtIndi">
            <div class="card-body">

                <div class=" d-flex align-items-lg-center">
                    <img src="<?= ROOT ?>/assets/images/logo.jpg" alt="" class=" img-thumbnail mr-1 d-block"
                        style="width: 100px;">
                </div>
                <div class="header">
                    <h1>INSTITUT UNIVERSITAIRE DE LA FORMATION PROFESSIONNELLE (IUFP)</h1>
                    <h2>EMPLOI DU TEMPS INDIVIDUEL DE M.
                        <?= isset($enseignant->enseignant_prenom) ? htmlspecialchars($enseignant->enseignant_prenom) : 'Non spécifié'; ?>
                        <?= isset($enseignant->enseignant_nom)? htmlspecialchars($enseignant->enseignant_nom) : 'Non spécifié'; ?>
                        : allant du <?= date('d-m-Y', strtotime($date_debut)); ?> au
                        <?= date('d-m-Y', strtotime($date_fin)); ?></h2>
                    <p>
                        <strong>Semestres et Promotions :</strong>
                        <?php if (!empty($semestres_promotions)): ?>
                        <?= implode(', ', array_map('htmlspecialchars', $semestres_promotions)); ?>
                        <?php else: ?>
                        Aucune promotion spécifiée.
                        <?php endif; ?>
                    </p>
                </div>
                <div class="container">
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <p><strong>Nom :</strong>
                                <?= isset($enseignant->enseignant_nom) ? htmlspecialchars($enseignant->enseignant_nom) : 'Non spécifié'; ?>
                            </p>
                        </div>
                        <div class="col-md-4">
                            <p><strong>Prénom :</strong>
                                <?= isset($enseignant->enseignant_prenom) ? htmlspecialchars($enseignant->enseignant_prenom) : 'Non spécifié'; ?>
                            </p>
                        </div>
                        <div class="col-md-4">
                            <p><strong>Grade :</strong>
                                <?= isset($enseignant->nom_grade) ? htmlspecialchars($enseignant->nom_grade) : 'Non spécifié'; ?>
                            </p>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <p><strong>Statut :</strong>
                                <?= isset($enseignant->enseignant_statut) ? htmlspecialchars($enseignant->enseignant_statut) : 'Non spécifié'; ?>
                            </p>
                        </div>
                        <div class="col-md-4">
                            <p><strong>Date de naissance :</strong>
                                <?= isset($enseignant->enseignant_date_naissance) ? date('d-m-Y', strtotime($enseignant->enseignant_date_naissance)) : 'Non spécifiée'; ?>
                            </p>
                        </div>
                        <div class="col-md-4">
                            <p><strong>Année Universitaire :</strong>
                                <?php if (!empty($emplois_du_temps)): ?>
                                <?= implode(', ', array_unique(array_map(function($edt) {
                                                return htmlspecialchars($edt->annee_universitaire);
                                            }, $emplois_du_temps))); ?>
                                <?php else: ?>
                                Non spécifiée
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                    <div class="form-group mb-3 text-center no-print">
                        <label style="font-weight: bold;">Afficher l'emploi du temps :</label>
                        <div
                            style='display: flex; justify-content: center; gap: 20px; align-items: center; margin-top: 10px;'>
                            <div>
                                <input type='radio' name='affichage' id='edt_actuel' value='actuel' <?=( $status
                                    !='achevé' ) ? 'checked' : '' ?> style='transform: scale(1.3); margin-right: 5px;'>
                                <label for='edt_actuel' style='font-size: 0.7rem; font-style: italic;'>Actuel</label>
                            </div>
                            <div>
                                <input type='radio' name='affichage' id='edt_passe' value='passe' <?=(
                                    $status=='achevé' ) ? 'checked' : '' ?>
                                    style='transform: scale(1.3); margin-right: 5px;'>
                                <label for='edt_passe' style='font-size: 0.7rem; font-style: italic;'>Passé</label>
                            </div>
                        </div>
                    </div>

                    <table class='table-bordered-responsive'>
                        <thead>
                            <tr>
                                <th>Semaine</th>
                                <th>Module1( VH )/Classe</th>
                                <th>Module2( VH )/Classe</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ( !empty( $emplois_du_temps ) ): ?>
                            <?php
        $traited_dates = [];
        foreach ( $emplois_du_temps as $edt ) {
            $date_debut_str = $edt->date_debut;
            $date_fin_str = $edt->date_fin;
            $formatted_date_debut = date( 'd-m-Y', strtotime( $date_debut_str ) );
            $formatted_date_fin = date( 'd-m-Y', strtotime( $date_fin_str ) );

            if ( isset( $traited_dates[ $date_debut_str ][ $date_fin_str ] ) ) {
                $traited_dates[ $date_debut_str ][ $date_fin_str ][] = [
                    'module' => htmlspecialchars( $edt->nom_module ) . ' (' . htmlspecialchars( $edt->heure_total ) . 'h)',
                    'class' => htmlspecialchars( $edt->nom_salle )
                ];
            } else {
                $traited_dates[ $date_debut_str ][ $date_fin_str ] = [
                    [
                        'module' => htmlspecialchars( $edt->nom_module ) . ' (' . htmlspecialchars( $edt->heure_total ) . 'h)',
                        'class' => htmlspecialchars( $edt->nom_salle )
                    ]
                ];
            }
        }

        foreach ( $traited_dates as $date_debut_str => $fin_dates ) {
            foreach ( $fin_dates as $date_fin_str => $modules_classes ) {
                ?>
                            <tr>
                                <td>Du <?=date( 'd-m-Y' , strtotime( $date_debut_str ) ); ?> au
                                    <?=date( 'd-m-Y' , strtotime( $date_fin_str ) ); ?>
                                </td>
                                <td>
                                    <?=$modules_classes[ 0 ][ 'module' ]; ?>
                                    <?=isset( $modules_classes[ 0 ][ 'class' ] ) ? ' / ' . $modules_classes[ 0
                                            ][ 'class' ] : '' ; ?>
                                </td>
                                <td>
                                    <?=isset( $modules_classes[ 1 ][ 'module' ] ) ? $modules_classes[ 1 ][ 'module' ]
                                        : '' ; ?>
                                    <?=isset( $modules_classes[ 1 ][ 'class' ] ) ? ' / ' . $modules_classes[ 1
                                            ][ 'class' ] : '' ; ?>
                                </td>
                            </tr>
                            <?php
    }
}
?>
                            <?php else: ?>
                            <tr>
                                <td colspan='3' class='text-center'>Aucun emploi du temps disponible.</td>
                            </tr>
                            <?php endif;
?>
                        </tbody>
                    </table>
                    <div class='footer'>
                        <div class='row mb-4'>
                            <div class='col-md-4'>
                                <p><strong>Heures totales :</strong>
                                    <?=isset( $heures_totales ) ? htmlspecialchars( $heures_totales ) : 0; ?>
                                </p>
                            </div>
                            <div class='col-md-4'>
                                <p><strong>Heures dues :</strong>
                                    <?=isset( $heures_dues ) ? htmlspecialchars( $heures_dues ) : 0; ?>
                                </p>
                            </div>
                            <div class='col-md-4'>
                                <p><strong>Supplémentaires :</strong>
                                    <?=isset( $heures_supp ) ? htmlspecialchars( $heures_supp ) : 0; ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class='text-right mr-2' style='min-width:100%'>
                        <div class='d-flex justify-content-end' style='min-width:100%'>
                            <h6 class='text-muted text-right' style='min-width:100%'>Segou, le
                                <?=date( 'd-m-Y' ) ?>
                            </h6>
                        </div>
                        <div class='' style='min-width:100%'>
                            <h6 class='text-bold-600 text-center d-flex justify-content-end w-100'
                                style='min-width:100%'>
                                Le
                                Chef de DER
                                <?=isset( $_SESSION[ 'sigle_departement' ] ) ? strtoupper(
                                    $_SESSION[ 'sigle_departement' ] ) : '' ?>
                            </h6>
                            <h6 class='d-flex text-center justify-content-end w-100' style='min-width:100%'>
                                <img src="<?= ROOT . $_SESSION['signature'] ?>" alt='user image' class='d-block rounded'
                                    style='width: 150px; max-height: 60px;' />
                            </h6>
                        </div>
                        <div class='d-flex justify-content-end' style='min-width:100%'>
                            <h6 class='text-right' style='min-width:100%'>Dr
                                <?=isset( $_SESSION[ 'nom_prenom' ] ) ? strtoupper( $_SESSION[ 'nom_prenom' ] ) : '' ?>
                                <br>
                                <?=isset( $_SESSION[ 'nom_grade' ] ) ? strtoupper( $_SESSION[ 'nom_grade' ] ) : ''
                                        ?>
                            </h6>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->view( 'Partials/footer' ) ?>