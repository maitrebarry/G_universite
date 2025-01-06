<?php $this->view("Partials/header") ?>

<body class="vertical-layout vertical-menu-modern boxicon-layout no-card-shadow 2-columns navbar-sticky footer-static" data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">

    <?php $this->view("Partials/navbar") ?>
    <?php $this->view("Partials/seibar") ?>

    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
        <div class="content-header row">
                <div class="content-header-left col-12 mb-2 mt-1">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h5 class="content-header-title float-left pr-1 mb-0">Incription des Etudiants</h5>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb p-0 mb-0">
                                    <li class="breadcrumb-item"><a href="index.html"><i class="bx bx-home-alt"></i></a>
                                    </li>
                                    <li class="breadcrumb-item"><a href="#">Gestion Etudiants</a>
                                    </li>
                                    <li class="breadcrumb-item active">Inscription
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <section id="info-tabs-">
                    <div class="row">
                        <div class="col-12">
                            <div class="card icon-tab card-animated-border-top">
                            
                                <div class="card-content mt-2">
                                    <div class="card-body">
                                        <form action="#" class="wizard-validation">
                                            <h6>
                                                <i class="step-icon"></i>
                                                <span class="fonticon-wrap">
                                                <i class="fa-solid fa-restroom fa-2xl"></i>
                                               
                                                </span>
                                                <span>Informations de l'Etudiants</span>
                                            </h6>
                                            <fieldset>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <h6 class="py-50">Informations de l'Etudiants</h6>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label>Nom && Prénom<span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control required" placeholder="Nom && Prénom"  >
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label>Genre<span class="text-danger">*</span></label>
                                                            <select class="form-select form-control ">
                                                                <option value="" disabled>Choisissez le sexe</option>
                                                                <option value="">Féminin</option>
                                                                <option value="">Masculin</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label>Date de naissance<span class="text-danger">*</span></label>
                                                            <input type="date" class="form-control" placeholder="Date de naissance">
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label>Lieu de naissance<span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control" placeholder="Lieu de naissance">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label>Cercle de Naissance<span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control" placeholder="Cercle de Naissance">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label>Commune de Naissance<span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control" placeholder="Commune de Naissance">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label>Nationalité<span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control" placeholder="Nationalité">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Numero Télephone<span class="text-danger">*</span> </label>
                                                            <input type="number" class="form-control" placeholder="Numero Télephone ">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Email<span class="text-danger">*</span></label>
                                                            <input type="mail" class="form-control" placeholder="Email">
                                                        </div>
                                                    </div>

                                                </div>
                                            </fieldset>
                                            <h6>
                                                <i class="step-icon"></i>
                                                <span class="fonticon-wrap">
                                              
                                                <i class="fa-solid fa-user-group fa-xl"></i>
                                                <i class="fa-solid fa-graduation-cap fa-xl"></i>
                                                
                                                </span>
                                                <span>Parents et Diplome </span>
                                            </h6>
                                            <fieldset>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <h6 class="py-50">information parents</h6>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label>Prénom père<span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control " placeholder="Prénom père">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label>Nom && Prenom mère<span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control " placeholder="Nom && Prenom mère">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label>Lieu résidence des parents<span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control" placeholder="Date de naissance">
                                                        </div>
                                                    </div>

                                                </div>
                                                <hr>
                                                <div class="col-12">
                                                    <h6 class="py-50">Diplome</h6>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label>Diplome<span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control " placeholder="Diplome">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label>Numero de place<span class="text-danger">*</span></label>
                                                            <input type="number" class="form-control" placeholder="Numero de place">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Serie<span class="text-danger">*</span> </label>
                                                            <input type="text" class="form-control" placeholder="Serie ">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label>Anneés diplome<span class="text-danger">*</span></label>
                                                            <input type="number" class="form-control" placeholder="Anneés diplome">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label>Pays<span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control" placeholder="Pays">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Academie<span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control" placeholder="Academie">
                                                        </div>
                                                    </div>

                                                </div>
                                            </fieldset>
                                            <!-- body content of step 2 end-->
                                            <!-- Step 3-->
                                            <h6>
                                                <i class="step-icon"></i>
                                                <span class="fonticon-wrap">
                                                <i class="fa-solid fa-building-columns fa-xl"></i>
                                                <i class="fa-solid fa-user  fa-xl" ></i>
                                              
                                                </span>
                                                <span>Profile et universiter</span>
                                            </h6>
                                            <fieldset>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <h6 class="py-50">Profile et universiter</h6>
                                                    </div>
                                                    <form action="" method="POST" class=" row g-3 form" enctype="multipart/form-data">
                                                        <div class="card-body">
                                                            <div class="box-body">
                                                                <div class="row">
                                                                    <div class="col-md-4">

                                                                        <label >Matricule<span class="text-danger">*</span></label>
                                                                        <input type="text" class="form-control required" placeholder="Matricule" >

                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <label >N étudiants<span class="text-danger">*</span></label>
                                                                        <input type="text" class="form-control" placeholder="N étudiants">
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <label >Années univertisaire<span class="text-danger">*</span></label>
                                                                        <input name="text" id="email" type="text" class="form-control" placeholder="Années univertisaire">
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label >Statut<span class="text-danger">*</span></label>
                                                                            <select class="form-select form-control">
                                                                                <option value="" disabled>Choisissez la Statut</option>

                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label >Filière<span class="text-danger">*</span></label>
                                                                            <select class="form-select form-control">
                                                                                <option value="" disabled>Choisissez la Filière</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <label class="form-label">Profile<span class="text-danger">*</span></label>
                                                                        <input  type="file" class="form-control" >
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                            </fieldset>
                                           
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- Form wizard with icon tabs section end -->





            </div>
        </div>
    </div>

    <?php $this->view("Partials/foot") ?>
    <?php $this->view("Partials/footer") ?>




</body>

</html>