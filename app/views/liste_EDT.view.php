<?php $this->view("Partials/header") ?>

<body class="vertical-layout vertical-menu-modern boxicon-layout no-card-shadow 2-columns navbar-sticky footer-static"
    data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">

    <?php $this->view("Partials/navbar") ?>
    <?php $this->view("Partials/seibar") ?>

    <div class="app-content content">
        <div id="loader" class="w-100 position-absolute d-none justify-content-center align-items-center" style="height:100vh;z-index:100">
            <div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div>
        </div>

        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-12 mb-2 mt-1">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h5 class="content-header-title float-left pr-1 mb-0">Emplois du temps</h5>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb p-0 mb-0">
                                    <li class="breadcrumb-item"><a href="<?= ROOT ?>/Homes"><i class="bx bx-home-alt"></i></a></li>
                                    <li class="breadcrumb-item active">Gestion EDT</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-body">
                <?php $this->view('set_flash'); ?>

                <div class="gu-table-card">
                    <div class="gu-toolbar">
                        <span class="gu-title"><i class="bx bx-calendar"></i> Emplois du temps</span>

                        <div class="d-flex align-items-center flex-wrap ms-auto" style="gap:10px;">
                            <select class="form-select" id="anneeUniversitaire" name="anneeUniversitaire" style="width:auto;min-width:140px;" title="Année universitaire">
                                <?php
                                $annee_debut = 2012;
                                $annee_actuelle = (int) date('Y');
                                if ((int) date('n') <= 8) { $annee_actuelle--; }
                                $courante = $annee_actuelle . '-' . ($annee_actuelle + 1);
                                for ($a = $annee_debut; $a <= $annee_actuelle; $a++) {
                                    $val = $a . '-' . ($a + 1);
                                    echo '<option value="' . $val . '"' . ($val === $courante ? ' selected' : '') . '>' . $val . '</option>';
                                }
                                ?>
                            </select>
                            <select class="form-select" id="promotions" style="width:auto;min-width:170px;" title="Classe">
                                <option value="" disabled selected>Choisir une classe</option>
                            </select>
                            <button type="button" class="btn btn-soft-primary" id="print"><i class="bx bx-printer"></i> Imprimer</button>
                            <a href="<?= ROOT ?>/Emploi_du_temps/ajouter_EDT" class="btn btn-primary"><i class="bx bx-plus"></i> Nouveau</a>
                        </div>
                    </div>

                    <div id="listeEdts">
                        <div class="gu-empty"><i class="bx bx-calendar"></i>Choisissez une <b>année</b> puis une <b>classe</b> pour afficher les emplois du temps.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <?php $this->view("Partials/foot") ?>
    <?php $this->view("Partials/footer") ?>
</body>

</html>
<script>
    const ROOT = "<?= ROOT ?>";
    const ROOT_EDT = ROOT + "/Emploi_du_temps";
</script>
<script src="<?= ROOT ?>/assets/mon_js/edt.js"></script>
<script>
    var infoFiliere = [];

    $(document).ready(function () {
        classesAnneeUniversitaire($("#anneeUniversitaire option:selected").val());
    });

    $("#anneeUniversitaire").change(function () {
        classesAnneeUniversitaire($("#anneeUniversitaire option:selected").val());
    });

    $("#promotions").change(function () {
        var opt = $("#promotions option:selected");
        trierListeEdt(opt.data("filiere"), opt.val(), opt.data("semestre"));
    });

    $('#print').click(function () {
        window.scrollTo({ top: 0, behavior: "smooth" });
        setTimeout(function () {
            var n = 0;
            $('#edts tbody tr').each(function () {
                var cb = $(this).find('.isSelected');
                if (cb.prop("checked") && cb.data("id")) {
                    imprimerEdt(cb.data("id"), cb.data("nom"));
                    n++;
                }
            });
            if (!n && window.Swal) Swal.fire({ icon: 'info', title: 'Aucun EDT sélectionné', text: 'Cochez au moins un emploi du temps à imprimer.', confirmButtonColor: '#1f4f9c' });
        }, 600);
    });
</script>
