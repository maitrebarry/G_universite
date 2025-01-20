<?php $this->view("Partials/header") ?>

<body class="vertical-layout vertical-menu-modern boxicon-layout no-card-shadow 2-columns navbar-sticky footer-static" data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">

    <?php $this->view("Partials/navbar") ?>
    <?php $this->view("Partials/seibar") ?>

    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <?php $this->view("set_flash") ?>

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
            </div>

            <div class="content-body">
                <section id="student-payment">
                    <div class="row">
                        <div class="col-md-8 offset-md-2">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Formulaire de Paiement</h4>
                                </div>
                                <div class="card-body">
                                    <div class="container mt-4">
                                        <!-- Affichage des détails de l'étudiant -->
                                        <h3>Paiement pour : <?= htmlspecialchars($etudiant['nom_prenom_etudiant']) ?></h3>
                                        <form action="" method="POST">
                                            <input type="hidden" name="id_etudiant" value="<?= htmlspecialchars($etudiant['id_etudiant']) ?>">

                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="total-due">Montant Total Dû</label>
                                                        <input type="number" id="total-due" class="form-control bg-primary text-white" value="<?= htmlspecialchars($etudiant['total_frais']) ?>" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="total-paid">Montant Total Payé</label>
                                                        <?php 
                                                        $totalPaid = 0;
                                                        foreach ($payments as $payment) {
                                                            $totalPaid += $payment['montant_paye'];
                                                        }
                                                        ?>
                                                        <input type="number" id="total-paid" class="form-control bg-secondary text-white" value="<?= htmlspecialchars($totalPaid) ?>" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="amount-paid">Montant Payé (Nouveau)</label>
                                                        <input type="number" id="amount-paid" class="form-control bg-light" name="montant_paye" placeholder="Entrer le montant payé" required oninput="updateRemaining()">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="remaining-amount">Montant Restant à Payer</label>
                                                        <input type="number" id="remaining-amount" class="form-control bg-danger text-white" value="<?= htmlspecialchars($etudiant['total_frais'] - $totalPaid) ?>" readonly>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Bouton centré -->
                                            <div class="text-center mt-3">
                                                <button type="submit" name="paie" class="btn btn-success">Effectuer le Paiement</button>
                                            </div>
                                        </form>

                                        <!-- Historique des paiements -->
                                        <div class="mt-4">
                                            <h4>Historique des Paiements</h4>
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Montant</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($payments as $payment): ?>
                                                        <tr>
                                                            <td><?= htmlspecialchars($payment['date']) ?></td>
                                                            <td><?= htmlspecialchars($payment['montant_paye']) ?> FCFA</td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
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

    <!-- Script JavaScript pour calculer le montant restant -->
    <script>
       // Script JavaScript pour mettre à jour le montant restant
function updateRemaining() {
    var totalDue = parseFloat(document.getElementById('total-due').value);
    var amountPaid = parseFloat(document.getElementById('amount-paid').value) || 0;
    var totalPaid = parseFloat(document.getElementById('total-paid').value);

    var remainingAmount = totalDue - (totalPaid + amountPaid);
    document.getElementById('remaining-amount').value = remainingAmount.toFixed(2);
}

    </script>

</body>

</html>
