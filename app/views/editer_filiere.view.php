<!-- inclusion du partie header -->
<?php $this->view("Partials/header") ?>
<?php
$filiere = $infoFiliere['filiere'];
$ancienSemestres = $infoFiliere['semestres'];
$ues = $infoFiliere['ues'];
$ancienModules = $infoFiliere['modules'];
?>
<style>
body {
    background-color: #f8f9fa;
}

.container {
    margin-top: 30px;
}

.section {
    background-color: #ffffff;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    margin-top: 20px;
}

.btn-custom {
    background-color: #007bff;
    color: white;
    border-radius: 6px;
}

.module-list {
    list-style-type: none;
    padding-left: 0;
}

.module-list li {
    padding: 10px;
    border-radius: 4px;
    margin: 5px 0;
}

.module-list li label {
    display: block;
    text-align: center;
}

.delete-btn {
    color: red;
    cursor: pointer;
    font-size: 14px;
}

.delete-btn:hover {
    color: darkred;
}

.alert {
    margin-top: 10px;
}

.char-count {
    font-size: 12px;
    color: gray;
    text-align: right;
}

.error {
    font-size: 12px;
    color: red;
}
</style>

<body class="vertical-layout vertical-menu-modern boxicon-layout no-card-shadow 2-columns navbar-sticky footer-static"
    data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">

    <!-- inclusion du partie navbar -->
    <?php $this->view("Partials/navbar") ?>

    <!-- inclusion du partie seibar -->
    <?php $this->view("Partials/seibar") ?>

    <!-- Content -->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-12 mb-2 mt-1">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h5 class="content-header-title float-left pr-1 mb-0">Modifier une Filière</h5>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb p-0 mb-0">
                                    <li class="breadcrumb-item"><a href="<?php echo ROOT ?>"><i
                                                class="bx bx-home-alt"></i></a></li>
                                    <li class="breadcrumb-item"><a
                                            href="<?php echo ROOT ?>/Filieres/liste_filiere">Liste des Filières</a></li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <section id="basic-datatable">
                    <div class="row">
                        <div id="message" class="col-12"></div>
                        <div class="col-12">
                            <div class="card card-animated-border-top">
                                <div class="card-content">
                                    <div class="card-header row">
                                        <?php if ($action != 'edit'): ?>
                                        <div class="col-md-4 mx-1 mb-1">
                                            <a href="<?php echo ROOT . '/Filieres/editer_filiere/' . $filiere->id_filiere ?>"
                                                class="btn btn-light-warning d-block actionEdit" id="editButton">
                                                <i class="bx bx-edit-alt mr-1"></i>Modifier
                                                Info </a>
                                        </div>
                                        <?php endif ?>

                                        <?php if ($action != 'add'): ?>
                                        <div class="col-md-4 mx-1 mb-1">
                                            <a href="<?php echo ROOT . '/Filieres/ajouter_element_filiere/' . $filiere->id_filiere ?>"
                                                class="btn btn-light-info d-block actionEdit" id="addButton">
                                                <i class="bx bx-plus mr-1"></i>Ajouter
                                                Element</a>
                                        </div>
                                        <?php endif ?>

                                        <?php if ($action != 'del'): ?>
                                        <div class="col-md-3 mb-1">
                                            <a href="<?php echo ROOT . '/Filieres/supprimer_element_filiere/' . $filiere->id_filiere ?>"
                                                class="btn btn-light-danger d-block actionEdit" id="delButton">
                                                <i class="bx bx-trash mr-1"></i> Supprimer
                                                ELement</a>
                                        </div>
                                        <?php endif ?>
                                        <div class="col-md-4 mb-1">
                                            <a href="<?= ROOT ?>/Filieres/apercu_Filiere/<?php echo $filiere->id_filiere ?>"
                                                class="btn btn-light-secondary d-block actionEdit" id="delButton">
                                                <i class="bx bx-show mr-1"></i>Aperçu
                                            </a>
                                        </div>
                                    </div>

                                    <div class="card-body">
                                        <div class="row d-flex justify-content-around">
                                            <div class="col-md-4">
                                                <label for="nomFiliere" class="form-label">Nom de la Filière</label>
                                                <input type="text" id="nomFiliere" class="form-control"
                                                    placeholder="ex : Génie Informatique" maxlength="101"
                                                    value="<?php echo strtoupper($filiere->nom_filiere) ?>"
                                                    data-id="<?php echo $filiere->id_filiere ?>">
                                                <div class="char-count" id="nomFiliereCount">0/100</div>
                                                <div class="error" id="nomFiliereError"></div>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="sigleFiliere" class="form-label">Sigle de la Filière</label>
                                                <input type="text" id="sigleFiliere" class="form-control"
                                                    placeholder="ex : GI" maxlength="50"
                                                    value="<?php echo strtoupper($filiere->sigle_filiere) ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="card card-animated-border-top">
                                <div class="card-content">
                                    <div class="card-body card-dashboard">
                                        <div class="container">
                                            <?php if ($action == "add"): ?>
                                            <div class="section">
                                                <div class="semestre d-flex justify-content-center align-items-center">
                                                    <label for="idSemestre" class="form-label col-md-4">Selectionner un
                                                        Semestre</label>
                                                    <select id="idSemestre" class="select2 form-control col-md-4"
                                                        onchange="addSemestre()">
                                                        <option value="">Choisir un Semestre</option>
                                                        <?php foreach ($semestres as $semestre): ?>
                                                        <option value="<?php echo $semestre->id_semestre ?>">
                                                            <?php echo strtoupper($semestre->nom_semestre) ?>(
                                                            <?php echo strtoupper($semestre->sigle_semestre) ?> )
                                                        </option>
                                                        <?php endforeach ?>
                                                    </select>
                                                </div>
                                                <div class="error w-100 d-flex justify-content-end"
                                                    id="idSemestreError"></div>
                                            </div>
                                            <?php endif ?>

                                            <!-- la section pour afficher les semestres -->
                                            <div id="semestresTable">
                                                <?php foreach ($ancienSemestres as $ancienSemestre): ?>
                                                <div class='section'
                                                    id="semestre_<?php echo $ancienSemestre->id_semestre ?>"
                                                    data-id="<?php echo $ancienSemestre->id_parcours ?>">
                                                    <h4 class='text-center'>
                                                        <?php echo strtoupper($ancienSemestre->nom_semestre) ?>
                                                    </h4>
                                                    <table class='table table-bordered'
                                                        id="tableSemestre_<?php echo $ancienSemestre->id_semestre ?>">
                                                        <thead>
                                                            <tr>
                                                                <th>Unité d'Enseignement (UE)</th>
                                                                <th>Modules</th>
                                                                <?php if ($action == "del" || $action == "add"): ?>
                                                                <th>Actions</th>
                                                                <?php endif ?>
                                                            </tr>
                                                        </thead>
                                                        <tbody
                                                            id="ueContainer_<?php echo $ancienSemestre->id_semestre ?>"
                                                            data-id="<?php echo $ancienSemestre->id_parcours ?>">
                                                            <!-- UE et modules seront ajoutés ici -->
                                                            <?php foreach ($ues as $ue): ?>
                                                            <?php if ($ue->id_parcours == $ancienSemestre->id_parcours): ?>
                                                            <tr class='ue-item'>
                                                                <td class=" p-1">
                                                                    <div
                                                                        class="w-100 h-100 d-flex justify-content-around align-items-center">
                                                                        <input type='text' class='form-control nomUe'
                                                                            placeholder="Nom de l'UE ( ex : UEMPH1 )"
                                                                            value="<?php echo $ue->nom_ue ?>"
                                                                            data-id="<?php echo $ue->id_ue ?>">
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <?php if ($action == 'add'): ?>
                                                                    <div
                                                                        class="w-100 h-100 d-flex justify-content-around ">
                                                                        <button type='button' class='btn btn-primary'
                                                                            onclick="addModule( <?php echo $ancienSemestre->id_semestre ?>, this )"><i
                                                                                class=' bx bxs-plus-square '></i>&nbsp;
                                                                            Module</button>
                                                                    </div>
                                                                    <?php endif ?>
                                                                    <!-- la liste des modules de l'ue -->
                                                                    <ul class='module-list' id="module-list">
                                                                        <?php foreach ($ancienModules as $ancienModule): ?>
                                                                        <?php if ($ancienModule->id_ue == $ue->id_ue): ?>
                                                                        <!-- le formulaire pour ajouter un module -->
                                                                        <li class='module-item row border'>
                                                                            <!-- Module  -->
                                                                            <div class="col-12  ">
                                                                                <label class='text-center'
                                                                                    for='modules'>Module</label>
                                                                                <select
                                                                                    id='module_<?php echo $ancienModule->id_ue_module ?>'
                                                                                    class='select2 form-control module'
                                                                                    data-id="<?php echo $ancienModule->id_ue_module ?>">
                                                                                    <?php foreach ($modules as $module): ?>
                                                                                    <option
                                                                                        value="<?php echo $module->id_module ?>"
                                                                                        <?php echo ($module->id_module == $ancienModule->id_module) ? 'selected' : ''  ?>>
                                                                                        <?php echo strtoupper($module->nom_module) ?>(
                                                                                        <?php echo strtoupper($module->sigle_module) ?>
                                                                                        )
                                                                                    </option>
                                                                                    <?php endforeach ?>
                                                                                </select>
                                                                            </div>

                                                                            <!-- CODE -->
                                                                            <div class='col-4 m-auto'>
                                                                                <label>ECUE</label>
                                                                                <input type='text'
                                                                                    class='form-control code'
                                                                                    value="<?php echo $ancienModule->code_module ?>">
                                                                            </div>
                                                                            <!-- COEFICIENT -->
                                                                            <div class='col-4 m-auto'>
                                                                                <label>Coéficient</label>
                                                                                <input type='number'
                                                                                    class='form-control coeficient'
                                                                                    value="<?php echo $ancienModule->coeficient ?>">
                                                                            </div>
                                                                            <!-- TOTAL -->
                                                                            <div class='col-12 row'>
                                                                                <div class="col-4 m-auto ">
                                                                                    <label>TOTAL</label>
                                                                                    <input type='number'
                                                                                        class='form-control'
                                                                                        value="<?php echo $ancienModule->cm + $ancienModule->td + $ancienModule->tp + $ancienModule->tpe ?>">
                                                                                </div>
                                                                            </div>
                                                                            <!-- CM -->
                                                                            <div class='col-3'>
                                                                                <label>CM</label>
                                                                                <input type='number'
                                                                                    class='form-control cm'
                                                                                    value="<?php echo $ancienModule->cm ?>">
                                                                            </div class='col-3'>
                                                                            <!-- TD -->
                                                                            <div class='col-3'>
                                                                                <label>TD</label>
                                                                                <input type='number'
                                                                                    class='form-control td'
                                                                                    value="<?php echo $ancienModule->td ?>">
                                                                            </div>
                                                                            <!-- TP -->
                                                                            <div class='col-3'>
                                                                                <label>TP</label>
                                                                                <input type='number'
                                                                                    class='form-control tp'
                                                                                    value="<?php echo $ancienModule->tp ?>">
                                                                            </div>
                                                                            <!-- TPE -->
                                                                            <div class='col-3'>
                                                                                <label>TPE</label>
                                                                                <input type='number'
                                                                                    class='form-control tpe'
                                                                                    value="<?php echo $ancienModule->tpe ?>">
                                                                            </div>

                                                                            <?php if ($action == "del"): ?>
                                                                            <!-- SUPPRIMER -->
                                                                            <div
                                                                                class="col-12 mt-1 d-flex justify-content-around">
                                                                                <button type='button'
                                                                                    class='btn btn-danger'
                                                                                    onclick='delElementFiliere(this,<?php echo $ancienModule->id_ue_module ?>)'
                                                                                    id="removeModule"><i
                                                                                        class="bx bxs-minus-square"></i>&nbsp;
                                                                                    Module
                                                                                </button>
                                                                            </div>
                                                                            <?php endif ?>
                                                                        </li>
                                                                        <?php endif ?>
                                                                        <?php endforeach ?>
                                                                    </ul>
                                                                </td>
                                                                <?php if ($action == "del"): ?>
                                                                <td class="p-0 ">
                                                                    <div
                                                                        class="w-100 h-100 d-flex justify-content-around align-items-center">
                                                                        <button ype='button' class='btn btn-danger'
                                                                            onclick="delElementFiliere(this,<?php echo $ue->id_ue ?>,'ue')"
                                                                            data-id="<?php echo $ue->id_ue ?>">
                                                                            <i class="bx bxs-minus-square"></i>&nbsp; UE
                                                                        </button>
                                                                    </div>
                                                                </td>
                                                                <?php endif ?>
                                                            </tr>
                                                            <?php endif ?>
                                                            <?php endforeach ?>
                                                        </tbody>
                                                    </table>
                                                    <?php if ($action == "add"): ?>
                                                    <button type="button" class="btn btn-primary"
                                                        onclick="addUE(<?php echo $ancienSemestre->id_semestre ?>)">
                                                        <i class="bx bxs-plus-square"></i>&nbsp; UE
                                                    </button>
                                                    <?php endif ?>
                                                </div>
                                                <?php endforeach ?>
                                            </div>
                                            <div><input type="hidden" id="action" value="<?php echo $action ?>"></div>
                                            <div class="mt-4" style="float: right;">
                                                <button type="button" class="btn btn-primary" id="btnEnregistrer"
                                                    onclick="ajouterFiliere('<?php echo ROOT . '/Filieres/' . $url ?>',action='<?php echo $url ?>')">Modifier</button>
                                            </div>
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

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <!-- inclusion du partie foot -->
    <?php $this->view("Partials/foot") ?>

    <!-- inclusion du partie footer -->
    <?php $this->view("Partials/footer") ?>
    <script src="<?= ROOT ?>/assets/mon_js/filiere.js"></script>
    <script>
    $(document).ready(function() {


        // // Mise à jour des compteurs de caractères
        // $(".form-control").on("input", function() {
        //     const maxLength = $(this).attr("maxlength");
        //     const currentLength = $(this).val().length;
        //     $(this).siblings(".char-count").text(`${currentLength}/${maxLength}`);
        //     validateForm();
        // });

        // // Validation lors du changement de sélection
        // $("#idSemestre").on("change", function() {
        //     validateForm();
        // });
        const action = $('#action').val();
        if (action !== "edit") {
            $(".form-control").attr("disabled", true);
            $("#idSemestre").attr("disabled", false);
        }

    });
    </script>

</body>