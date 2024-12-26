<!-- inclusion du partie header -->
<?php $this->view("Partials/header")?>

<body class="vertical-layout vertical-menu-modern boxicon-layout no-card-shadow 2-columns  navbar-sticky footer-static  " data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">

    <!-- inclusion du partie header -->
    <?php $this->view("Partials/navbar")?>
    <!-- inclusion du partie header fin-->

    <!-- inclusion du partie seibar-->
    <?php $this->view("Partials/seibar")?>
    <!-- inclusion du partie seibar fin-->

    <!--  Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-12 mb-2 mt-1">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h5 class="content-header-title float-left pr-1 mb-0">Form Validation</h5>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb p-0 mb-0">
                                    <li class="breadcrumb-item"><a href="index.html"><i class="bx bx-home-alt"></i></a>
                                    </li>
                                    <li class="breadcrumb-item"><a href="#">Forms</a>
                                    </li>
                                    <li class="breadcrumb-item active">Form Validation
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <!-- formulaire -->
                <section class="simple-validation">
                    <div class="row">
                        <div class="col-md-12" >
                            <div class="card card-animated-border-top  " >
                                <div class="card-header">
                                    <h4 class="card-title text-center">Création d'emploi du temps</h4>
                                </div>
                                <div class="card-content">
                                    <div class="card-body ">
                                        <form class="form-horizontal" novalidate>
                                            <div class="row">
                                                <div class="col-sm-4">
                                                <label class="form-label" for="single-select ">Filiere</label>
                                                <div class="form-group">
                                                    <select class="select2 form-control" >
                                                        <option value="square">Filiere</option>
                                                        <option value="rectangle">GI</option>
                                                    </select>
                                                </div>
                                                </div>
                                                <div class="col-sm-4">
                                                <label class="form-label" for="single-select ">Année universitaire</label>
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <input type="email" name="email" class="form-control" placeholder="Année universitaire" required data-validation-required-message="This Email field is required">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                <label class="form-label" for="single-select ">Niveau</label>
                                                    <div class="form-group">
                                                        <select class="select2 form-control" >
                                                            <option value="square">Niveau</option>
                                                            <option value="rectangle">GI</option>
                                                        </select>
                                                    </div>
                                               </div>
                                               </div>
                                            <div class="row">
                                                <div class="col-sm-4">
                                                <label class="form-label" for="single-select ">Date début</label>
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <input type="date" name="text" class="form-control" placeholder="Date début" required data-validation-required-message="This First Name field is required">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                <label class="form-label" for="single-select ">Date fin</label>
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <input type="date" name="email" class="form-control" placeholder="Date fin" required data-validation-required-message="This Email field is required">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                <label class="form-label" for="single-select ">Salle</label>
                                                    <div class="form-group">
                                                        <select class="select2 form-control" >
                                                            <option value="square">Salle</option>
                                                            <option value="rectangle">GI</option>
                                                        </select>
                                                    </div>
                                               </div>
                                            </div>
                                            <div class="row">
                                            <div class="col-sm-4">
                                            <label class="form-label" for="single-select ">Enseignant</label>
                                                    <div class="form-group">
                                                        <select class="select2 form-control" >
                                                            <option value="square">Enseignant</option>
                                                            <option value="rectangle">GI</option>
                                                        </select>
                                                    </div>
                                               </div>
                                               <div class="col-sm-4">
                                               <label class="form-label" for="single-select ">Module</label>
                                                    <div class="form-group">
                                                        <select class="select2 form-control" >
                                                            <option value="square">Module</option>
                                                            <option value="rectangle">GI</option>
                                                        </select>
                                                    </div>
                                               </div>
                            
                                                <div class="col-sm-4">
                                                <label class="form-label" for="single-select ">Nombre d'heure</label>
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <input type="text" name="text" class="form-control" placeholder="Nombre d'heure" required data-validation-required-message="This First Name field is required">
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                            <button type="submit" style="float: right;" class="btn btn-primary">Enregistrer</button> <br>
                                        </form>
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
   <?php $this->view("Partials/foot")?>
   <!-- inclusion du partie foot fin-->  
    <!-- inclusion du partie footer-->
    <?php $this->view("Partials/footer")?>
    <!-- inclusion du partie footer fin-->
</body>
<!-- END: Body-->

</html>