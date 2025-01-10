<!-- inclusion du partie header -->
<?php $this->view("Partials/header") ?>

<body class="vertical-layout vertical-menu-modern boxicon-layout no-card-shadow 2-columns  navbar-sticky footer-static" data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">

    <!-- inclusion du partie header -->
    <?php $this->view("Partials/navbar") ?>
    <!-- inclusion du partie header fin-->

    <!-- inclusion du partie sidebar-->
    <?php $this->view("Partials/seibar") ?>
    <!-- inclusion du partie sidebar fin-->

    <!-- Content -->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-12 mb-2 mt-1">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h5 class="content-header-title float-left pr-1 mb-0">Enregistrements de l'Enseignant</h5>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb p-0 mb-0">
                                    <li class="breadcrumb-item"><a href="index.html"><i class="bx bx-home-alt"></i></a></li>
                                    <li class="breadcrumb-item"><a href="#">Gestion Enseignant</a></li>
                                    <li class="breadcrumb-item active">Liste</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <!-- Formulaire -->
                <section id="table-chechbox">
                <?php $this->view("set_flash"); ?>
                    <div class="row">
                        <div class="col-12">
                            <div class="card card-animated-border-top">
                                <?php if (!empty($errors)): ?>
                                    <div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        <div class="d-flex align-items-center">
                                            <i class="bx bx-error"></i>
                                            <span>
                                                <?php foreach ($errors as $error): ?>
                                                    <?= htmlspecialchars($error) ?><br>
                                                <?php endforeach; ?>
                                            </span>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <div class="card-content">
                                    <div class="d-flex justify-content-end mb-2">
                                        <a href="<?= ROOT ?>/Enseignants/ajouter_enseignant">
                                            <button class="btn btn-primary"><i class="bx bx-plus"></i>&nbsp; Enseignant</button>
                                        </a>
                                    </div>
                                    <div class="card-body card-dashboard">
                                        <!-- Nav tabs -->
                                        <ul class="nav nav-pills nav-justified" role="tablist">
                                            <li class="nav-item waves-effect waves-light">
                                                <a class="nav-link active" data-toggle="tab" href="#contractuels" role="tab">
                                                    <span class="d-none d-sm-block">Liste des enseignants vacataires </span>
                                                </a>
                                            </li>
                                            <li class="nav-item waves-effect waves-light">
                                                <a class="nav-link" data-toggle="tab" href="#vacataires" role="tab">
                                                    <span class="d-none d-sm-block">Liste des enseignants contractuels </span>
                                                </a>
                                            </li>
                                        </ul>

                                        <!-- Tab panes -->
                                        <div class="tab-content mt-3">
                                            <!-- Liste des enseignants contractuels -->
                                            <div class="tab-pane active" id="contractuels" role="tabpanel">
                                                <div class="table-responsive">
                                                    <table id="table_contractuels" class="table table-striped table-bordered" style="width:100%">
                                                        <thead>
                                                            <tr>
                                                                <th>NOM & PRÉNOM</th>
                                                                <th>TÉLÉPHONE</th>
                                                                <th>DIPLÔME</th>
                                                                <th>CV</th>
                                                                <th width='1%'>OPÉRATION</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach ($enseignat_VCT as $enseignant): ?>
                                                            <tr>
                                                                <td><?= htmlspecialchars($enseignant->enseignant_nom . ' ' . $enseignant->enseignant_prenom, ENT_QUOTES, 'UTF-8'); ?></td>
                                                                <td><?= htmlspecialchars($enseignant->enseignant_telephone, ENT_QUOTES, 'UTF-8'); ?></td>
                                                                <td><?= htmlspecialchars($enseignant->enseignant_diplome, ENT_QUOTES, 'UTF-8'); ?></td>
                                                                <td>
                                                                    <?php if (!empty($enseignant->enseignant_cv)): ?>
                                                                        <a href="<?= ROOT ?>/<?= htmlspecialchars(str_replace("C:\\xampp\\htdocs\\G_universite\\public\\", "", $enseignant->enseignant_cv), ENT_QUOTES, 'UTF-8'); ?>" target="_blank">
                                                                            <i class="bx bx-file" title="Voir le CV"></i>
                                                                        </a>
                                                                    <?php else: ?>
                                                                        <i class="bx bx-block" title="Aucun CV disponible"></i>
                                                                    <?php endif; ?>
                                                                </td>
                                                                <td>
                                                                    <a href="<?= ROOT ?>/Enseignants/update/<?= $enseignant->enseignant_id; ?>" title="Modifier">
                                                                        <i class="bx bx-edit" style="color: #5A8DEE; cursor: pointer;"></i>
                                                                    </a>
                                                                        <a href="<?= ROOT ?>/Enseignants/delete/<?= $enseignant->enseignant_id; ?>" onclick="return confirm('Voulez-vous vraiment supprimer cet enseignant ?');" title="Supprimer">
                                                                            <i class="bx bx-trash" style="color: #EA5455; cursor: pointer;"></i>
                                                                        </a>

                                                                </td>
                                                            </tr>
                                                            <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                            <!-- Liste des enseignants vacataires -->
                                            <div class="tab-pane" id="vacataires" role="tabpanel">
                                                <div class="table-responsive">
                                                    <table id="table_vacataires" class="table table-striped table-bordered" style="width:100%">
                                                        <thead>
                                                            <tr>   
                                                                <th>NOM & PRÉNOM</th> 
                                                                <th>MATRICULE</th>
                                                                <th>GRADE</th>
                                                                <th>TÉLÉPHONE</th>
                                                                <th>DIPLÔME</th>
                                                                <th width='1%'>OPÉRATION</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach ($enseignat_CDI as $enseignant): ?>
                                                            <tr> 
                                                                <td><?= htmlspecialchars($enseignant->enseignant_nom . ' ' . $enseignant->enseignant_prenom, ENT_QUOTES, 'UTF-8'); ?></td>
                                                                <td><?= htmlspecialchars($enseignant->enseignant_matricule, ENT_QUOTES, 'UTF-8'); ?></td>
                                                                <td><?= htmlspecialchars($enseignant->enseignant_grade, ENT_QUOTES, 'UTF-8'); ?></td>
                                                                <td><?= htmlspecialchars($enseignant->enseignant_telephone, ENT_QUOTES, 'UTF-8'); ?></td>
                                                                <td><?= htmlspecialchars($enseignant->enseignant_diplome, ENT_QUOTES, 'UTF-8'); ?></td>
                                                                <td>
                                                                     <a href="<?= ROOT ?>/Enseignants/update/<?= $enseignant->enseignant_id; ?>" title="Modifier">
                                                                        <i class="bx bx-edit" style="color: #5A8DEE; cursor: pointer;"></i>
                                                                    </a>
                                                                        <a  href="<?= ROOT ?>/Enseignants/delete/<?= $enseignant->enseignant_id; ?>" onclick="return confirm('Voulez-vous vraiment supprimer cet enseignant ?');" title="Supprimer">
                                                                            <i class="bx bx-trash" style="color: #EA5455; cursor: pointer;"></i>
                                                                        </a>
                                                                </td>
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
                        </div>
                    </div>
                </section>
                <!-- Formulaire -->
            </div>
        </div>
    </div>
    <!-- Fin Content -->

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <!-- inclusion du partie footer -->
    <?php $this->view("Partials/foot") ?>
    <?php $this->view("Partials/footer") ?>
    <script>
        $(document).ready(function() {
            $('#table_contractuels').DataTable();
            $('#table_vacataires').DataTable();
        });
    </script>
    <script src="<?= ROOT ?>/assets/mon_js/sweet_alert_suppression.js">></script>
</body>
</html>
