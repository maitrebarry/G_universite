
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
                                    <li class="breadcrumb-item"><a href="index.html"><i class="bx bx-home-alt"></i></a>
                                    </li>
                                    <li class="breadcrumb-item"><a href="#">Gestion Étudiants</a>
                                    </li>
                                    <li class="breadcrumb-item active">Paiement
                                    </li>
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
                                    <h3>Paiement pour : </h3>
                                    <form action="/process_payment" method="POST">
                                        <input type="hidden" name="student_id" value="">
                                        <div class="form-group">
                                            <label for="student-name">Nom de l'Étudiant</label>
                                            <input type="text" id="student-name" class="form-control" value="" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label for="total_frais">Montant Total Dû</label>
                                            <input type="number" id="total_frais" class="form-control" name="total_frais" value="22" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label for="montant_paye">Montant Payé</label>
                                            <input type="number" id="montant_paye" class="form-control" name="montant_paye" placeholder="Entrer le montant payé" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="frais-restant">Solde Restant</label>
                                            <input type="number" id="frais-restant" class="form-control" name="frais-restant" readonly>
                                        </div>
                                        <button type="submit" class="btn btn-success">Effectuer le Paiement</button>
                                    </form>
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

</body>

</html>
