<!-- inclusion du partie header -->
<?php $this->view("Partials/header") ?>

<body
    class="vertical-layout vertical-menu-modern boxicon-layout no-card-shadow 2-columns  navbar-sticky footer-static  "
    data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">

    <!-- inclusion du partie header -->
    <?php $this->view("Partials/navbar") ?>
    <!-- inclusion du partie header fin-->

    <!-- inclusion du partie seibar-->
    <?php $this->view("Partials/seibar") ?>
    <!-- inclusion du partie seibar fin-->

    <!--  Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-12 mb-2 mt-1">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h5 class="content-header-title float-left pr-1 mb-0">Enregistrements du filière</h5>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb p-0 mb-0">
                                    <li class="breadcrumb-item"><a href="index.html"><i class="bx bx-home-alt"></i></a>
                                    </li>
                                    <li class="breadcrumb-item"><a href="#">Gestion Filière</a>
                                    </li>
                                    <li class="breadcrumb-item active">Liste
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <!-- formulaire -->
                <section id="table-chechbox">

                    <div class="row">
                        <div>
                            <?php $this->view("set_flash"); ?>
                        </div>
                        <div class="col-12">
                            <div class="card">

                                <div class="card-content">
                                    <a href="<?= ROOT ?>/Filieres/ajouter_Filiere"><button class="btn btn-primary"
                                            style="float:right;"><i class="bx bx-plus"></i>&nbsp; FILIERE </button></a>
                                    <div class="card-body card-dashboard">


                                        <div class="table-responsive">
                                            <table id="table-extended-chechbox"
                                                class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>

                                                        <th>Nom filière</th>
                                                        <th>Code Filière</th>
                                                        <th>Statut</th>
                                                        <th class="text-center dt-no-sorting">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($filieres as $filiere): ?>
                                                    <tr>
                                                        <td>
                                                            <?php echo strtoupper($filiere->nom_filiere) ?>
                                                        </td>

                                                        <td>
                                                            <?php echo strtoupper($filiere->sigle_filiere) ?>
                                                        </td>
                                                        <td> <span class=" badge badge-light-warning">Actif</span>
                                                        </td>


                                                        <td class="text-center dt-no-sorting">
                                                            <div class="dropdown">
                                                                <span
                                                                    class="bx bx-dots-horizontal-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                                                    data-toggle="dropdown" aria-haspopup="true"
                                                                    aria-expanded="false" role="menu">
                                                                </span>
                                                                <div class="dropdown-menu dropdown-menu-right">
                                                                    <a class="dropdown-item"
                                                                        href="<?= ROOT ?>/Filieres/apercu_Filiere/<?php echo $filiere->id_filiere ?>">
                                                                        <i class="bx bx-show mr-1"></i>Aperçu
                                                                    </a>
                                                                    <a class="dropdown-item editerFiliere"
                                                                        data-toggle="modal" data-target="#menuEditer"
                                                                        href="#"
                                                                        data-id="<?php echo $filiere->id_filiere ?>"><i
                                                                            class="bx bx-edit-alt mr-1"></i> Editer</a>
                                                                    <a class="dropdown-item" href="#"><i
                                                                            class="bx bx-trash mr-1"></i> Supprimer</a>
                                                                    <a class="dropdown-item ajouterPromotion"
                                                                        data-toggle="modal" data-target="#menuPromotion"
                                                                        href="#"
                                                                        data-id="<?php echo $filiere->id_filiere ?>">
                                                                        <i class="bx bx-plus"></i>&nbsp; Promotion
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <?php endforeach ?>

                                                </tbody>
                                            </table>
                                            <!-- partie insertion des données -->
                                            <div class="modal-primary mr-1 mb-1 d-inline-block">
                                                <div class="modal fade text-left" id="menuEditer" tabindex="-1"
                                                    role="dialog" aria-labelledby="myModalLabel160" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable"
                                                        role="document">
                                                        <div class="modal-content">

                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <input type="hidden" id="idFiliere">
                                                                    <div class="col-12">
                                                                        <a href=""
                                                                            class="btn btn-light-warning d-block actionEdit"
                                                                            id="editButton">
                                                                            <i class="bx bx-edit-alt mr-1"></i>Modifier
                                                                            Info </a>
                                                                    </div>
                                                                    <div class="col-12 mt-1">
                                                                        <a href=""
                                                                            class="btn btn-light-info d-block actionEdit"
                                                                            id="addButton">
                                                                            <i class="bx bx-plus mr-1"></i>Ajouter
                                                                            Element</a>
                                                                    </div>
                                                                    <div class="col-12 mt-1">
                                                                        <a href=""
                                                                            class="btn btn-light-danger d-block actionEdit"
                                                                            id="delButton">
                                                                            <i class="bx bx-trash mr-1"></i> Supprimer
                                                                            ELement</a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn  btn-link"
                                                                    data-dismiss="modal">
                                                                    <i class="bx bx-x d-block d-sm-none"></i>
                                                                    <span class="d-none d-sm-block">Annuler</span>
                                                                </button>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- partie insertion des données -->
                                            <div class="modal-primary mr-1 mb-1 d-inline-block modal-lg">
                                                <div class="modal fade text-left" id="menuPromotion" tabindex="-1"
                                                    role="dialog" aria-labelledby="myModalLabel160" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable"
                                                        role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-primary">
                                                                <h5 class="modal-title white" id="myModalLabel160">
                                                                    Crétion de Promotion de <span>GI</span></h5>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close">
                                                                    <i class="bx bx-x"></i>
                                                                </button>
                                                            </div>
                                                            <form action="" method="post">
                                                                <div class="modal-body">
                                                                    <div class="row">
                                                                        <input type="hidden" name="idFiliere"
                                                                            id="filiere">
                                                                        <div class="col mb-6">
                                                                            <label for="nameBasic"
                                                                                class="form-label">Annee
                                                                                Universitaire<span
                                                                                    class="text-danger fs-6">*</span></label>
                                                                            <input type="text" id="nameBasic" value=""
                                                                                class="form-control"
                                                                                name="annneeUniverstaire"
                                                                                placeholder="Nom module" required />
                                                                        </div>
                                                                        <div class="col mb-6">
                                                                            <label for="nameBasic" class="form-label">
                                                                                Semestre <span
                                                                                    class="text-danger fs-6">*</span></label>
                                                                            <select name="idsemestre" id="semestres"
                                                                                class="select2 form-control">

                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button"
                                                                        class="btn btn-light-secondary"
                                                                        data-dismiss="modal">
                                                                        <i class="bx bx-x d-block d-sm-none"></i>
                                                                        <span class="d-none d-sm-block">Annuler</span>
                                                                    </button>
                                                                    <button type="submit"
                                                                        class="btn btn-primary ml-1 d-none d-sm-block"
                                                                        name="saveFiliere">
                                                                        <i class="bx bx-check d-block d-sm-none"></i>
                                                                        Enregistrer
                                                                    </button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- fin insertion des données -->
                                            <!-- fin insertion des données -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- formulaire -->

            </div>
        </div>
    </div>
    <!-- fin: Content-->
    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <!-- inclusion du partie foot-->
    <?php $this->view("Partials/foot") ?>
    <!-- inclusion du partie foot fin-->
    <!-- inclusion du partie footer-->
    <?php $this->view("Partials/footer") ?>
    <!-- inclusion du partie footer fin-->
    <script src="<?= ROOT ?>/assets/mon_js/edt.js"></script>
    <script>
    var infoFiliere = [];
    $('.editerFiliere').click(function() {
        $('#idFiliere').val($(this).data('id'))
    })

    $('#editButton').click(function() {
        let idFiliere = $('#idFiliere').val();
        $(this).attr("href", "<?= ROOT ?>/Filieres/editer_Filiere/" + idFiliere)
    })

    $('#addButton').click(function() {
        let idFiliere = $('#idFiliere').val();
        $(this).attr("href", "<?= ROOT ?>/Filieres/ajouter_element_filiere/" + idFiliere)
    })

    $('#delButton').click(function() {
        let idFiliere = $('#idFiliere').val();
        $(this).attr("href", "<?= ROOT ?>/Filieres/supprimer_element_filiere/" + idFiliere)
    })

    $('#ajouterPromotion').click(async function() {
        let idFiliere = $(this).data("id");

        infoFiliere = await infosFiliere(idFiliere);
        semestresFiliere(infoFiliere);


    })
    </script>
</body>
<!-- END: Body-->

</html>