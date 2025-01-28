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

                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb p-0 mb-0">
                                    <li class="breadcrumb-item"><a href="index.html"><i class="bx bx-home-alt"></i></a>
                                    </li>
                                    <li class="breadcrumb-item"><a href="<?= ROOT ?>/Filieres/">Gestion Filière</a>
                                    </li>
                                    <li class="breadcrumb-item active">Liste Promotions
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <!-- formulaire -->
                <?php $this->view("set_flash"); ?>
                <section id="table-chechbox">

                    <div class="row">

                        <div class="col-12">
                            <div class="card">
                                <div class="card-content">

                                    <div class="d-flex justify-content-between align-items-center p-1 m-0">
                                        <div>
                                            <h6>
                                                <?php if (!empty($promotions)): ?>
                                                    <?php echo strtoupper($promotions[0]->nom_filiere) ?>
                                                <?php endif ?>
                                            </h6>
                                        </div>
                                        <button class="btn btn-primary ajouterPromotion" data-toggle="modal"
                                            data-target="#menuPromotion" style="float:right;"
                                            data-id="<?php echo $idFiliere ?>"
                                            data-nom="<?php echo $promotions[0]->sigle_filiere ?>"><i
                                                class="bx bx-plus"></i>&nbsp; Promotion
                                        </button>
                                    </div>

                                    <div class="card-body card-dashboard mt-0">


                                        <div class="table-responsive">
                                            <table class="table zero-configuration table-bordered" style="width:100%">
                                                <thead>
                                                    <tr>

                                                        <th>Promotion</th>
                                                        <th>Semestre</th>
                                                        <th>Statut</th>
                                                        <th class="text-center dt-no-sorting">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($promotions as $promotion): ?>
                                                        <tr>
                                                            <td>
                                                                <?php echo strtoupper($promotion->sigle_filiere . '-' . $promotion->sigle_semestre . '( ' . $promotion->annee_universitaire . ' )') ?>
                                                            </td>
                                                            <td>
                                                                <?php echo strtoupper($promotion->nom_semestre) ?>
                                                            </td>
                                                            <td>
                                                                <?php if ($promotion->statut == 0): ?>
                                                                    <span class="badge badge-warning">En Attente</span>
                                                                <?php endif ?>
                                                                <?php if ($promotion->statut == 1): ?>
                                                                    <span class=" badge badge-primary">En Cours</span>
                                                                <?php endif ?>
                                                                <?php if ($promotion->statut == 2): ?>
                                                                    <span class=" badge badge-success">Achévée</span>
                                                                <?php endif ?>
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
                                                                            href="<?= ROOT ?>/Filieres/apercu_Filiere/">
                                                                            <i class="bx bx-show mr-1"></i>Aperçu
                                                                        </a>
                                                                        <?php if ($promotion->statut == 1 || $promotion->statut == 2): ?>
                                                                            <a class="dropdown-item text-warning statutButton"
                                                                                href="<?= ROOT . '/Filieres/set_status_promotion/' . $promotion->id_filiere . '/' . $promotion->id_promotion . '/0' ?>"
                                                                                data-promotion="<?php echo strtoupper($promotion->sigle_filiere . '-' . $promotion->sigle_semestre . '( ' . $promotion->annee_universitaire . ' )') ?>"
                                                                                data-etat="en attente">
                                                                                <i class="bx bx-stopwatch mr-1 "></i>Mettre
                                                                                en Attente
                                                                            </a>
                                                                        <?php endif ?>
                                                                        <?php if ($promotion->statut == 0 || $promotion->statut == 2): ?>
                                                                            <a class="dropdown-item text-primary statutButton"
                                                                                href="<?= ROOT . '/Filieres/set_status_promotion/' . $promotion->id_filiere . '/' . $promotion->id_promotion . '/1' ?>"
                                                                                data-promotion="<?php echo strtoupper($promotion->sigle_filiere . '-' . $promotion->sigle_semestre . '( ' . $promotion->annee_universitaire . ' )') ?>"
                                                                                data-etat="en marche">
                                                                                <i class="bx bx-log-in-circle mr-1 "></i>Mettre
                                                                                en Marche
                                                                            </a>
                                                                        <?php endif ?>
                                                                        <?php if ($promotion->statut == 0 || $promotion->statut == 1): ?>
                                                                            <a class="dropdown-item text-danger statutButton"
                                                                                href="<?= ROOT . '/Filieres/set_status_promotion/' . $promotion->id_filiere . '/' . $promotion->id_promotion . '/2' ?>"
                                                                                data-promotion="<?php echo strtoupper($promotion->sigle_filiere . '-' . $promotion->sigle_semestre . '( ' . $promotion->annee_universitaire . ' )') ?>"
                                                                                data-etat="en arrêt">
                                                                                <i class="bx bx-log-out mr-1"></i>Mettre en
                                                                                Arrêt
                                                                            </a>
                                                                        <?php endif ?>
                                                                        <?php if ($promotion->statut == 1): ?>
                                                                            <a class="dropdown-item "
                                                                                href="<?= ROOT ?>/Emploi_du_temps/ajouter_EDT/<?php echo $promotion->id_filiere . '/' . $promotion->id_promotion ?>">
                                                                                <i class="bx bx-plus mr-1"></i>Edt
                                                                            </a>
                                                                        <?php endif ?>

                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach ?>

                                                </tbody>
                                            </table>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- formulaire -->
                <!-- partie insertion des données -->
                <div class="modal-primary mr-1 mb-1 d-inline-block modal-lg">
                    <div class="modal fade text-left" id="menuPromotion" tabindex="-1" role="dialog"
                        aria-labelledby="myModalLabel160" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg"
                            role="document">
                            <div class="modal-content">
                                <div class="modal-header bg-primary">
                                    <h5 class="modal-title white" id="myModalLabel160">
                                        Crétion de Promotion de <span class="nomFiliere text-bold-700">
                                    </h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <i class="bx bx-x"></i>
                                    </button>
                                </div>
                                <form action="<?= ROOT ?>/Filieres/ajouter_promotion" method="post">
                                    <div class="modal-body">
                                        <div class="row m-auto d-flex justify-content-around">
                                            <input type="hidden" name="idFiliere" id="filiere">
                                            <div class="col-4 mb-2">
                                                <label for="nameBasic" class="form-label">Annee
                                                    Universitaire<span class="text-danger fs-6">*</span></label>
                                                <input type="text" id="anneeUniversitaire" name="anneeUniversitaire"
                                                    placeholder="YYYY-YYYY" maxlength="9"
                                                    class="form-control text-center" />
                                            </div>
                                            <div class="col-6 mb-2">
                                                <label for="nameBasic" class="form-label">
                                                    Semestre <span class="text-danger fs-6">*</span></label>
                                                <select name="idParcours" id="semestres" class="select2 form-control">

                                                </select>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                                            <i class="bx bx-x d-block d-sm-none"></i>
                                            <span class="d-none d-sm-block">Annuler</span>
                                        </button>
                                        <button type="submit" class="btn btn-primary ml-1 d-none d-sm-block"
                                            name="savePromotion">
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
        $(' .ajouterPromotion').click(async function() {
            let idFiliere = $(this).data("id");
            $("#filiere").val(idFiliere);
            $(".nomFiliere").html($(this).data("nom"));
            console.log(idFiliere);
            infoFiliere = await
            infosFiliere(idFiliere);
            semestresFiliere(infoFiliere);
        })
        $('.statutButton').click(function(event) {
            event.preventDefault();
            url = $(this).attr('href');
            Swal.fire({
                title: "Êtes vous sûr?",
                text: "La promotion " + $(this).data('promotion') + " sera mit " + $(this).data('etat'),
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonClass: "btn btn-primary",
                cancelButtonClass: "btn btn-danger ml-1",
                buttonsStyling: false,
            }).then(function(result) {
                if (result.value) {
                    window.location.href = url;
                }
            });

        })

        $(document).ready(function() {
            setDefaultAcademicYear();
            $('#anneeUniversitaire').keyup(function(event) {
                formatAcademicYear(event);
            })
        })
    </script>

</body>
<!-- END: Body-->

</html>