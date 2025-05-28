<!-- inclusion du partie header -->
<?php $this->view("Partials/header") ?>

<body class="vertical-layout vertical-menu-modern boxicon-layout no-card-shadow 2-columns  navbar-sticky footer-static"
    data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">

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
                            <h5 class="content-header-title float-left pr-1 mb-0">ENSEIGNANTS</h5>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb p-0 mb-0">
                                    <li class="breadcrumb-item"><a href="index.html"><i class="bx bx-home-alt"></i></a>
                                    </li>
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
                                <div class="card-content mt-1 mr-1">
                                    <div class="d-flex justify-content-end mb-2">
                                        <a href="<?= ROOT ?>/Enseignants/ajouter_enseignant">
                                            <button class="btn btn-primary"><i class="bx bx-plus"></i>&nbsp;
                                                Enseignant</button>
                                        </a>
                                    </div>
                                    <div class="card-body card-dashboard">
                                        <!-- Nav tabs -->
                                        <ul class="nav nav-pills nav-justified" role="tablist">
                                            <li class="nav-item waves-effect waves-light">
                                                <a class="nav-link active" data-toggle="tab" href="#contractuels"
                                                    role="tab">
                                                    <span class="d-none d-sm-block">Liste des enseignants non permanants
                                                    </span>
                                                </a>
                                            </li>
                                            <li class="nav-item waves-effect waves-light">
                                                <a class="nav-link" data-toggle="tab" href="#vacataires" role="tab">
                                                    <span class="d-none d-sm-block">Liste des enseignants permanants
                                                    </span>
                                                </a>
                                            </li>
                                        </ul>
                                        <div class="col-12 d-flex justify-content-end my-1 pr-2">
                                            <button type="button" class="btn btn-primary d-none" id="print">
                                                <i class="bx bx-printer"></i> Imprimer Tout
                                            </button>
                                        </div>

                                        <!-- Tab panes -->
                                        <div class="tab-content mt-3">
                                            <!-- Bouton Imprimer Tout -->
                                            <div class="col-12 d-flex justify-content-end my-1 pr-2">
                                                <button type="button" class="btn btn-primary d-none" id="print">
                                                    <i class="bx bx-printer"></i> Imprimer Tout
                                                </button>
                                            </div>

                                            <!-- Liste des enseignants non permanents -->
                                            <div class="tab-pane active" id="contractuels" role="tabpanel">
                                                <div class="table-responsive">
                                                    <table id="table_contractuels"
                                                        class="table table-striped table-bordered" style="width:100%">
                                                        <thead>
                                                            <tr>
                                                                <th>
                                                                    <div class="checkbox">
                                                                        <input type="checkbox" class="checkbox-input"
                                                                            id="allSelectContractuels">
                                                                        <label for="allSelectContractuels"></label>
                                                                    </div>
                                                                </th>
                                                                <th>PRÉNOM & NOM </th>
                                                                <th>TÉLÉPHONE</th>
                                                                <th>DIPLÔME</th>
                                                                <th>CV</th>
                                                                <th width='1%'>OPÉRATION</th>
                                                            </tr>
                                                        </thead>

                                                        <tbody>
                                                            <?php $index = 0 ?>
                                                            <?php foreach ($enseignat_VCT as $enseignant): ?>
                                                            <tr>
                                                                <td>
                                                                    <div class="checkbox">
                                                                        <input type="checkbox"
                                                                            class="checkbox-input rowCheckboxContractuels"
                                                                            id="contractCheckbox<?= $index ?>"
                                                                            data-id="<?= $enseignant->enseignant_id ?>"
                                                                            data-name="<?= htmlspecialchars($enseignant->enseignant_prenom . ' ' . $enseignant->enseignant_nom, ENT_QUOTES, 'UTF-8') ?>">
                                                                        <label
                                                                            for="contractCheckbox<?= $index ?>"></label>
                                                                    </div>
                                                                </td>
                                                                <td><?= htmlspecialchars($enseignant->enseignant_prenom . ' ' . $enseignant->enseignant_nom, ENT_QUOTES, 'UTF-8'); ?>
                                                                </td>
                                                                <td><?= htmlspecialchars($enseignant->enseignant_telephone, ENT_QUOTES, 'UTF-8'); ?>
                                                                </td>
                                                                <td><?= htmlspecialchars($enseignant->enseignant_diplome, ENT_QUOTES, 'UTF-8'); ?>
                                                                </td>
                                                                <td>
                                                                    <?php if (!empty($enseignant->enseignant_cv)): ?>
                                                                    <a href="<?= ROOT ?><?= $enseignant->enseignant_cv ?>"
                                                                        target="_blank">
                                                                        <i class="bx bx-file" title="Voir le CV"></i>
                                                                    </a>
                                                                    <?php else: ?>
                                                                    <i class="bx bx-block"
                                                                        title="Aucun CV disponible"></i>
                                                                    <?php endif; ?>
                                                                </td>
                                                                <td class="text-center">
                                                                    <div class="dropdown">
                                                                        <span
                                                                            class="bx bx-dots-horizontal-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                                                            data-toggle="dropdown" aria-haspopup="true"
                                                                            aria-expanded="false" role="menu"></span>
                                                                        <div class="dropdown-menu dropdown-menu-right">
                                                                            <a class="dropdown-item"
                                                                                href="<?= ROOT ?>/Enseignants/listeEDT_individuel/<?= $enseignant->enseignant_id; ?>"
                                                                                style="font-size: 16px;">
                                                                                <i class="fa-solid fa-calendar-plus mr-2"
                                                                                    style="color: #7367F0; font-size: 18px;"></i>
                                                                                EDT INDIVIDUEL
                                                                            </a>
                                                                            <a class="dropdown-item"
                                                                                href="<?= ROOT ?>/Enseignants/update/<?= $enseignant->enseignant_id; ?>"
                                                                                style="font-size: 16px;">
                                                                                <i class="bx bx-edit mr-2"
                                                                                    style="color: #17a2b8; font-size: 18px;"></i>
                                                                                Modifier
                                                                            </a>
                                                                            <a class="dropdown-item"
                                                                                href="<?= ROOT ?>/Enseignants/delete/<?= $enseignant->enseignant_id; ?>"
                                                                                style="font-size: 16px;">
                                                                                <i class="bx bx-trash mr-2"
                                                                                    style="color: #dc3545; font-size: 18px;"></i>
                                                                                Supprimer
                                                                            </a>
                                                                            <a class="dropdown-item"
                                                                                href="<?= ROOT ?>/Utilisateurs/liste_utilisateur/<?= $enseignant->enseignant_id; ?>"
                                                                                style="font-size: 16px;">
                                                                                <i class="bx bx-plus "></i><i
                                                                                    class="bx bx-user mr-1"></i>Attribuer
                                                                                un compte
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <?php $index++ ?>
                                                            <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                            <!-- Liste des enseignants permanents -->
                                            <div class="tab-pane" id="vacataires" role="tabpanel">
                                                <div class="table-responsive">
                                                    <table id="table_vacataires"
                                                        class="table table-striped table-bordered" style="width:100%">
                                                        <thead>
                                                            <tr>
                                                                <th>
                                                                    <div class="checkbox">
                                                                        <input type="checkbox" class="checkbox-input"
                                                                            id="allSelect">
                                                                        <label for="allSelect"></label>
                                                                    </div>
                                                                </th>
                                                                <th>PRÉNOM & NOM </th>
                                                                <th>MATRICULE</th>
                                                                <th>GRADE</th>
                                                                <th>TÉLÉPHONE</th>
                                                                <th>DIPLÔME</th>
                                                                <th width='1%'>OPÉRATION</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php $index = 0 ?>
                                                            <?php foreach ($enseignat_CDI as $enseignant): ?>
                                                            <tr>
                                                                <td>
                                                                    <div class="checkbox">
                                                                        <input type="checkbox"
                                                                            class="checkbox-input rowCheckbox"
                                                                            id="checkbox<?= $index ?>"
                                                                            data-id="<?= $enseignant->enseignant_id ?>"
                                                                            data-name="<?= htmlspecialchars($enseignant->enseignant_prenom . ' ' . $enseignant->enseignant_nom, ENT_QUOTES, 'UTF-8') ?>">
                                                                        <label for="checkbox<?= $index ?>"></label>
                                                                    </div>
                                                                </td>
                                                                <td><?= htmlspecialchars($enseignant->enseignant_prenom . ' ' . $enseignant->enseignant_nom, ENT_QUOTES, 'UTF-8'); ?>
                                                                </td>
                                                                <td><?= htmlspecialchars($enseignant->enseignant_matricule, ENT_QUOTES, 'UTF-8'); ?>
                                                                </td>
                                                                <td><?= htmlspecialchars($enseignant->nom_grade, ENT_QUOTES, 'UTF-8'); ?>
                                                                </td>
                                                                <td><?= htmlspecialchars($enseignant->enseignant_telephone, ENT_QUOTES, 'UTF-8'); ?>
                                                                </td>
                                                                <td><?= htmlspecialchars($enseignant->enseignant_diplome, ENT_QUOTES, 'UTF-8'); ?>
                                                                </td>
                                                                <td class="text-center">
                                                                    <div class="dropdown">
                                                                        <span
                                                                            class="bx bx-dots-horizontal-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                                                            data-toggle="dropdown" aria-haspopup="true"
                                                                            aria-expanded="false" role="menu"></span>
                                                                        <div class="dropdown-menu dropdown-menu-right">
                                                                            <a class="dropdown-item"
                                                                                href="<?= ROOT ?>/Enseignants/listeEDT_individuel/<?= $enseignant->enseignant_id; ?>"
                                                                                style="font-size: 16px;">
                                                                                <i class="fa-solid fa-calendar-plus mr-2"
                                                                                    style="color: #7367F0; font-size: 18px;"></i>
                                                                                EDT INDIVIDUEL
                                                                            </a>
                                                                            <a class="dropdown-item"
                                                                                href="<?= ROOT ?>/Enseignants/update/<?= $enseignant->enseignant_id; ?>"
                                                                                style="font-size: 16px;">
                                                                                <i class="bx bx-edit mr-2"
                                                                                    style="color: #17a2b8; font-size: 18px;"></i>
                                                                                Modifier
                                                                            </a>
                                                                            <a class="dropdown-item"
                                                                                href="<?= ROOT ?>/Enseignants/delete/<?= $enseignant->enseignant_id; ?>"
                                                                                style="font-size: 16px;">
                                                                                <i class="bx bx-trash mr-2"
                                                                                    style="color: #dc3545; font-size: 18px;"></i>
                                                                                Supprimer
                                                                            </a>
                                                                            <a class="dropdown-item"
                                                                                href="<?= ROOT ?>/Utilisateurs/liste_utilisateur/<?= $enseignant->enseignant_id; ?>"
                                                                                style="font-size: 16px;">
                                                                                <i class="bx bx-plus "></i><i
                                                                                    class="bx bx-user mr-1"></i>Attribuer
                                                                                un compte
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <?php $index++ ?>
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
    <script src="<?= ROOT ?>/assets/mon_js/sweet_alert_suppression.js">

    </script>
    <script>
    var ROOT = "<?= ROOT ?>";
    </script>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        function handleCheckboxSelection(selectId, checkboxClass, printButtonId) {
            const allSelect = document.getElementById(selectId);
            const checkboxes = document.querySelectorAll(checkboxClass);
            const printButton = document.getElementById(printButtonId);

            allSelect.addEventListener("change", function() {
                checkboxes.forEach(checkbox => {
                    checkbox.checked = allSelect.checked;
                });
                togglePrintButton();
            });

            checkboxes.forEach(checkbox => {
                checkbox.addEventListener("change", function() {
                    if (!this.checked) {
                        allSelect.checked = false;
                    } else if (document.querySelectorAll(checkboxClass + ":checked").length ===
                        checkboxes.length) {
                        allSelect.checked = true;
                    }
                    togglePrintButton();
                });
            });

            function togglePrintButton() {
                if (document.querySelectorAll(checkboxClass + ":checked").length > 0) {
                    printButton.classList.remove("d-none");
                } else {
                    printButton.classList.add("d-none");
                }
            }

            printButton.addEventListener("click", function() {
                let selectedTeachers = [];
                checkboxes.forEach(checkbox => {
                    if (checkbox.checked) {
                        const teacherId = checkbox.getAttribute("data-id");
                        const teacherName = checkbox.getAttribute("data-name");
                        selectedTeachers.push({
                            id: teacherId,
                            name: teacherName
                        });
                    }
                });

                // Debugging: afficher les enseignants sélectionnés dans la console
                console.log("Enseignants sélectionnés:", selectedTeachers);

                if (selectedTeachers.length > 0) {
                    let dateDebut = document.getElementById("date_debut")?.value || "";
                    let dateFin = document.getElementById("date_fin")?.value || "";

                    // Debugging: afficher les dates
                    console.log("Date de début:", dateDebut);
                    console.log("Date de fin:", dateFin);

                    selectedTeachers.forEach(teacher => {
                        window.scrollTo({
                            top: 0,
                            behavior: "smooth",
                        });
                        setTimeout(() => {
                            imprimerEdt(teacher.id, "EDT_" + teacher.name.replace(
                                /\s+/g, "_"));
                        }, 500);
                    });
                } else {
                    console.log("Aucun enseignant sélectionné");
                }
            });
        }

        handleCheckboxSelection("allSelect", ".rowCheckbox", "print");
        handleCheckboxSelection("allSelectContractuels", ".rowCheckboxContractuels", "print");
    });
    </script>
    <script src="<?= ROOT ?>/assets/mon_js/pdfedIndividuel.js"></script>
</body>

</html>