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
                            <h5 class="content-header-title float-left pr-1 mb-0">Enregistrement de l'Enseignant</h5>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb p-0 mb-0">
                                    <li class="breadcrumb-item"><a href="index.html"><i class="bx bx-home-alt"></i></a></li>
                                    <li class="breadcrumb-item"><a href="#">Gestion Enseignant</a></li>
                                    <li class="breadcrumb-item active">Enregistrement</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                
            <div class="content-body">
                <section class="simple-validation">
                <?php $this->view("set_flash"); ?> 
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card ">
                                <div class="card card-animated-border-top">  
                                                 
                                    <div class="card-content">
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
                                        <div class="card-body">
                                           <form method="POST" class="form" enctype="multipart/form-data">
                                                <div class="box-body">
                                                    <div class="row">
                                                        
                                                        <div class="col-md-6">
                                                            <label class="form-label">Statut<span class="text-danger">*</span></label>
                                                            <select name="statut" id="statut" class="form-select form-control">
                                                                <option value="" disabled>Choisissez le statut</option>
                                                                <option value="VACT">VACT</option>
                                                                <option value="CDI">CDI</option>
                                                            </select>
                                                        </div>
                                                       
                                                        <div class="col-md-6" id="cv-container">
                                                            <label class="form-label">CV<span class="text-danger">*</span></label>
                                                            <input type="file" name="cv" class="form-control" 
                                                            value="<?= htmlspecialchars($input_values['cv'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                       
                                                        <div class="col-md-6" id="matricule-container">
                                                            <label class="form-label">Matricule<span class="text-danger">*</span></label>
                                                            <input type="text" name="matricule" id="matricule" class="form-control" placeholder="Matricule"
                                                            value="<?= htmlspecialchars($input_values['matricule'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                                        </div>
                                                        
                                                        <div class="col-md-6" id="grade-container">
                                                            <label class="form-label">Grade<span class="text-danger">*</span></label>
                                                            <select name="grade" id="grade" class="form-select form-control">
                                                                <option value="" disabled>Choisissez le grade</option>
                                                                <option value="Assistant">Assistant</option>
                                                                <option value="Maitre Assistant">Maitre Assistant</option>
                                                                <option value="Maitre de Conférence">Maitre de Conférence</option>
                                                                <option value="Professeur">Professeur</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        
                                                        <div class="col-md-6">
                                                            <label class="form-label">Nom<span class="text-danger">*</span></label>
                                                            <input name="nom" id="nom" type="text" class="form-control" placeholder="Nom"
                                                            value="<?= htmlspecialchars($input_values['nom'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                                        </div>
                                                        
                                                        <div class="col-md-6">
                                                            <label class="form-label">Prénom<span class="text-danger">*</span></label>
                                                            <input name="prenom" id="prenom" type="text" class="form-control" placeholder="Prénom"
                                                            value="<?= htmlspecialchars($input_values['prenom'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                       
                                                        <div class="col-md-6">
                                                            <label class="form-label">Date de naissance<span class="text-danger">*</span></label>
                                                            <input name="date_naissance" id="date_naissance" type="date" class="form-control" placeholder="Date de naissance"
                                                            value="<?= htmlspecialchars($input_values['date_naissance'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                                        </div>
                                                        
                                                        <div class="col-md-6">
                                                            <label class="form-label">Email<span class="text-danger">*</span></label>
                                                            <input name="email" id="email" type="email" class="form-control" placeholder="email"
                                                            value="<?= htmlspecialchars($input_values['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        
                                                        <div class="col-md-6">
                                                            <label class="form-label">Téléphone<span class="text-danger">*</span></label>
                                                            <input name="telephone" id="telephone" type="text" class="form-control" placeholder="Téléphone" 
                                                            value="<?= htmlspecialchars($input_values['telephone'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                                        </div>
                                                        
                                                        <div class="col-md-6">
                                                            <label class="form-label">Diplôme<span class="text-danger">*</span></label>
                                                            <select name="diplome" id="diplome" class="form-select form-control">
                                                                <option value="" disabled>Choisissez le diplôme</option>
                                                                <option value="Master">Master</option>
                                                                <option value="Doctorat">Doctorat</option>
                                                            </select>
                                                        </div>
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
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>

    <?php $this->view("Partials/foot") ?>
    <?php $this->view("Partials/footer") ?>

    
    <script src="<?= ROOT ?>/assets/mon_js/ajax_gVCT_CDI.js"></script>
  

</body>
</html>
