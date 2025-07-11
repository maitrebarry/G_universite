<?php $this->view("Partials/header") ?>
<style>

</style>

<body class="vertical-layout vertical-menu-modern boxicon-layout no-card-shadow 2-columns navbar-sticky footer-static"
    data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">

    <?php $this->view("Partials/navbar") ?>
    <?php $this->view("Partials/seibar") ?>
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-header-left col-12 mb-2 mt-1">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h5 class="content-header-title float-left pr-1 mb-0">Paiement des Étudiants</h5>
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb p-0 mb-0">
                                <li class="breadcrumb-item"><a href="/"><i class="bx bx-home-alt"></i></a></li>
                                <li class="breadcrumb-item"><a href="/etudiants">Gestion Étudiants</a></li>
                                <li class="breadcrumb-item active">Paiement</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <section id="table-chechbox">
                    <div class="row">
                        <div class="col-12">

                            <div class="card shadow rounded">
                                <div class="card-body  card-dashboard">
                                    <form action="<?= ROOT ?>/Etudiants/traiter_paiement_groupes" method="POST">
                                        <table class="table zero-configuration table-bordered" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>Nom de l'étudiant</th>
                                                    <th>Matricule</th>
                                                    <th>Paiement total</th>
                                                    <th>Total frais</th>
                                                    <th>Montant à payer</th>
                                                    <th>Reste à payer</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($etudiants as $etudiant): ?>
                                                    <tr>
                                                        <td><?= htmlspecialchars($etudiant->nom_prenom_etudiant) ?> <?= htmlspecialchars($etudiant->prenom) ?></td>
                                                        <td><?= htmlspecialchars($etudiant->matricule_etudiant) ?></td>
                                                        <td>
                                                            <?php
                                                            $total_paye = 0;
                                                            foreach ($paiements as $paiement) {
                                                                if ($paiement['idEtudt'] == $etudiant->id_etudiant) {
                                                                    $total_paye += $paiement['total_paye'];
                                                                }
                                                            }
                                                            echo htmlspecialchars($total_paye);
                                                            ?>
                                                        </td>
                                                        <td><?= htmlspecialchars($etudiant->total_frais) ?></td>
                                                        <td>
                                                            <input type="number"
                                                                name="paiement[<?= $etudiant->id_etudiant ?>]"
                                                                class="form-control montant-paye" placeholder="Montant"
                                                                step="0.01" min="0"
                                                                max="<?= $etudiant->total_frais - $total_paye ?>" required
                                                                data-frais="<?= $etudiant->total_frais ?>"
                                                                data-total-paye="<?= $total_paye ?>"
                                                                oninput="calculerReste(this)">


                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control reste-a-payer" readonly>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                        <div class="text-right mt-3">
                                            <button type="submit" class="btn btn-primary">Effectuer le Paiement</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                        </div>
                    </div>

                </section>
            </div>
        </div>
    </div>


    <?php $this->view("Partials/foot") ?>
    <?php $this->view("Partials/footer") ?>

    <script>
        // Fonction qui calcule le reste à payer
        function calculerReste(input) {
            var frais = parseFloat(input.getAttribute("data-frais")) || 0;
            var totalPaye = parseFloat(input.getAttribute("data-total-paye")) || 0;
            var montantPaye = parseFloat(input.value) || 0;

            var reste = frais - (totalPaye + montantPaye);

            // Mise à jour du champ reste à payer
            var resteChamp = input.closest('tr').querySelector('.reste-a-payer');

            // Vérification de dépassement
            if (montantPaye + totalPaye > frais) {
                resteChamp.value = ''; // Efface le champ de reste
                resteChamp.style.backgroundColor = "#ffcccc";

                alert("Le montant saisi dépasse le total des frais à payer !");
                input.value = ''; // Réinitialiser le champ de saisie
                return;
            }

            resteChamp.value = reste.toFixed(2); // Formater à 2 décimales

            // Couleur si reste < 0
            if (reste < 0) {
                resteChamp.style.backgroundColor = "#ffcccc";
            } else {
                resteChamp.style.backgroundColor = "";
            }
        }


        // Fonction pour calculer et afficher le reste à payer au chargement de la page
        window.onload = function() {
            var inputs = document.querySelectorAll('.montant-paye');
            inputs.forEach(function(input) {
                calculerReste(input); // Appeler la fonction pour chaque champ de paiement
            });
        };

        // Ajouter un effet d'animation lors de la saisie dans les champs de montant
        var inputs = document.querySelectorAll('.montant-paye');
        inputs.forEach(function(input) {
            input.addEventListener('focus', function() {
                input.style.transition = "border-color 0.5s ease";
            });
        });

        function calculerReste(input) {
            var frais = parseFloat(input.getAttribute("data-frais"));
            var totalPaye = parseFloat(input.getAttribute("data-total-paye"));
            var montantPaye = parseFloat(input.value);

            if (isNaN(montantPaye)) {
                montantPaye = 0;
            }

            var resteAvantPaiement = frais - totalPaye;
            var reste = resteAvantPaiement - montantPaye;

            // Si le montant dépasse le reste à payer, on bloque
            if (montantPaye > resteAvantPaiement) {
                alert("Le montant saisi dépasse le reste à payer (" + resteAvantPaiement + " F).");
                input.value = resteAvantPaiement; // Forcer la valeur maximale possible
                montantPaye = resteAvantPaiement;
                reste = 0;
            }

            // Mise à jour du champ de reste
            var resteChamp = input.closest('tr').querySelector('.reste-a-payer');
            resteChamp.value = reste.toFixed(0);

            // Couleur selon le solde
            if (reste < 0) {
                resteChamp.style.backgroundColor = "#ffcccc";
            } else {
                resteChamp.style.backgroundColor = "";
            }
        }
    </script>
</body>