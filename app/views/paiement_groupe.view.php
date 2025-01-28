<?php $this->view("Partials/header") ?>
<style>
    /* Styles généraux */
    .content-body {
        padding: 2rem;
        background-color: #f8f9fa;
    }

    .card {
        margin-bottom: 2rem;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 10px;
        border-radius: 10px;
        overflow: hidden;
    }

    table th,
    table td {
        padding: 1rem;
        text-align: center;
        border: 1px solid #dee2e6;
        border-radius: 8px;
    }

    /* En-têtes */
    table th {
        background-color: rgb(53, 96, 237);
        color: white;
        font-weight: bold;
        font-size: 1.1rem;
        text-transform: uppercase;
    }

    /* Lignes paires */
    table tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    /* Lignes impaires */
    table tr:nth-child(odd) {
        background-color: #ffffff;
    }

    /* Lignes au survol */
    table tr:hover {
        background-color: #e9f7e9;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    /* Champs d'entrée */
    input.form-control {
        width: 100%;
        padding: 0.5rem;
        border-radius: 5px;
        border: 1px solid #ccc;
        font-size: 1rem;
        transition: border-color 0.3s ease;
    }

    input.form-control:focus {
        border-color:rgb(157, 175, 76);
    }

    /* Bouton de paiement */
    .text-right button {
        background-color: #28a745;
        color: white;
        padding: 12px 24px;
        border-radius: 5px;
        border: none;
        font-size: 1rem;
        transition: background-color 0.3s ease;
    }

    .text-right button:hover {
        background-color:rgb(71, 136, 33);
    }
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
                <div class="container mt-4">
                    <div class="card shadow rounded">
                        <div class="card-body">
                            <form action="<?= ROOT ?>/Etudiants/traiter_paiement_groupes" method="POST">
                                <table>
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
                                                <td><?= htmlspecialchars($etudiant->nom_prenom_etudiant) ?></td>
                                                <td><?= htmlspecialchars($etudiant->numetudiant) ?></td>
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
                                                    <input type="number" name="paiement[<?= $etudiant->id_etudiant ?>]"
                                                        class="form-control montant-paye"
                                                        placeholder="Montant" step="0.01" min="0" required
                                                        data-frais="<?= htmlspecialchars($etudiant->total_frais) ?>"
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
        </div>
    </div>

    <?php $this->view("Partials/foot") ?>
    <?php $this->view("Partials/footer") ?>

    <script>
        // Fonction qui calcule le reste à payer
        function calculerReste(input) {
            var frais = parseFloat(input.getAttribute("data-frais"));
            var totalPaye = parseFloat(input.getAttribute("data-total-paye"));
            var montantPaye = parseFloat(input.value);

            // Si le montant payé est supérieur au total des frais, on ne fait pas de calcul
            if (isNaN(montantPaye)) {
                montantPaye = 0;
            }

            var reste = frais - (totalPaye + montantPaye);

            // Mise à jour dynamique du champ du reste à payer
            var resteChamp = input.closest('tr').querySelector('.reste-a-payer');
            resteChamp.value = reste.toFixed(2); // Formater le résultat avec 2 décimales

            // Dynamique : Changer la couleur du champ si le reste est inférieur à zéro
            if (reste < 0) {
                resteChamp.style.backgroundColor = "#ffcccc"; // Couleur rouge clair
            } else {
                resteChamp.style.backgroundColor = ""; // Rétablir la couleur
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
    </script>
</body>