<!-- inclusion du partie header -->
<?php $this->view("Partials/header") ?>

<body class="vertical-layout vertical-menu-modern boxicon-layout no-card-shadow 2-columns  navbar-sticky footer-static  " data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">

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
                            <h5 class="content-header-title float-left pr-1 mb-0">Réinscription des Etudiants</h5>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb p-0 mb-0">
                                    <li class="breadcrumb-item"><a href="index.html"><i class="bx bx-home-alt"></i></a>
                                    </li>
                                    <li class="breadcrumb-item"><a href="#">Gestion Etudiants</a>
                                    </li>
                                    <li class="breadcrumb-item active">Réinscription
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
                        <div class="col-12">
                            <div class="card card-animated-border-top ">
                                <div class="card-content">
                                    <div class="card-body">
                                        <form action="">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label" for="single-select ">Filiere</label>
                                                        <select class="select2 form-control">
                                                            <option value="" disabled>Choisissez une filiere</option>
                                                            <option value="square">Square</option>
                                                            <option value="rectangle">Rectangle</option>
                                                            <option value="rombo">Rombo</option>
                                                            <option value="romboid">Romboid</option>
                                                            <option value="trapeze">Trapeze</option>
                                                            <option value="traible">Triangle</option>
                                                            <option value="polygon">Polygon</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label" for="single-select ">Nouveau Niveau</label>
                                                        <select class="select2 form-control">
                                                            <option value="" disabled>Choisissez un nouveau niveau</option>
                                                            <option value="square">Square</option>
                                                            <option value="rectangle">Rectangle</option>
                                                            <option value="rombo">Rombo</option>
                                                            <option value="romboid">Romboid</option>
                                                            <option value="trapeze">Trapeze</option>
                                                            <option value="traible">Triangle</option>
                                                            <option value="polygon">Polygon</option>
                                                        </select>

                                                    </div>
                                                </div>

                                            </div>
                                       
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="card card-animated-border-top ">
                                <div class="card-content">
                                    <div class="card-body card-dashboard">
                                        <div class="table-responsive">
                                            <table class="table-extended-chechbox table zero-configuration table-bordered" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th></th>
                                                        <th>Nom && Prénom</th>
                                                        <th>statut</th>
                                                        <th>Diplome</th>
                                                        <th>Contact</th>
                                                        <th>MATRICULE</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col-12 d-flex justify-content-end mt-4">
                                            <button name="envoyer" type="submit" class="btn btn-primary">Envoyer</button>
                                        </div>
                                    </div>
                                    </form>
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

</body>
<!-- END: Body-->

</html>