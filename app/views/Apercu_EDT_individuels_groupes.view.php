
    <?php foreach ($apercus as $apercu): ?>
        <?php
            // On extrait les variables pour réutiliser le même template
            $enseignant = $apercu['enseignant'];
            $emplois_du_temps = $apercu['emplois_du_temps'];
            $heures_totales = $apercu['heures_totales'];
            $heures_dues = $apercu['heures_dues'];
            $heures_supp = $apercu['heures_supp'];
            $semestres_promotions = $apercu['semestres_promotions'];
            $date_debut = $apercu['date_debut'];
            $date_fin = $apercu['date_fin'];
            // $status = $apercu['status'];
            $mode_groupe = true; // Pour masquer le bouton PDF individuel
        ?>
        <div style="page-break-after: always;">
            <?php
            // Ici, copie/colle tout le code HTML complet de ton aperçu individuel
            // (header, styles, structure, etc.)
            // OU fais simplement :
            include __DIR__ . '/listeEDT_individuel.view.php';
            ?>
        </div>
    <?php endforeach; ?>

        <!-- <script>
    function imprimerToutEDTGroupes() {
        document.querySelectorAll('.edtIndiBloc').forEach(function(bloc, i) {
            const nom = bloc.getAttribute('data-name') || 'edtIndividuel_' + (i+1);
            imprimerEdtIndi(nom, bloc);
        });
    }
    </script> -->