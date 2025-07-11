
<?php $this->view("Partials/header") ?>

<body class="vertical-layout vertical-menu-modern boxicon-layout no-card-shadow 2-columns navbar-sticky footer-static" data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">

    <?php $this->view("Partials/navbar") ?>
    <?php $this->view("Partials/seibar") ?>
    <style>
        .frais-total {
            background-color: #e0f7fa; /* Bleu clair */
            border: 2px solid #00acc1; /* Bordure bleu cyan */
            font-weight: bold;
            color: #006064; /* Texte cyan foncé */
        }

        .frais-paye {
            background-color: #d1f7c4; /* Vert pâle */
            border: 2px solid #4caf50; /* Bordure verte */
            font-weight: bold;
            color: #2e7d32; /* Texte vert foncé */
        }

        .frais-restant {
            background-color: #f8d7da; /* Rouge pâle */

            font-weight: bold;
            color: #c62828; /* Texte rouge foncé */
        }
    </style>
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
        <div class="content-header row">
        <?php $this->view("set_flash") ?>
                        <?php foreach ($errors as $error) {
                                     
                                      } ?>
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
                                        <div class="bs-stepper stepper-form-one">
                                            <div class="bs-stepper-header" role="tablist">
                                                <div class="step" data-target="#defaultStep-one">
                                                <i class="step-icon"></i>

                                                    <button type="button" class="step-trigger" role="tab">
                                                   
                                                <i class="fa-solid fa-restroom fa-2xl"></i>
                                               
                                                </span>
                                                        <span class="bs-stepper-label">Informations de l'Etudiants
                                                        </span>
                                                    </button>
                                                </div>
                                                <div class="line"></div>
                                                <div class="step" data-target="#defaultStep-two">
                                                    <button type="button" class="step-trigger" role="tab">
                                                    <i class="step-icon"></i>
                                                <span class="fonticon-wrap">
                                              
                                                <i class="fa-solid fa-user-group fa-xl"></i>
                                                <i class="fa-solid fa-graduation-cap fa-xl"></i>
                                                
                                                </span>
                                                        <span class="bs-stepper-label">Parents et Diplome
                                                        </span>
                                                    </button>
                                                </div>

                                                <div class="line"></div>
                                                <div class="step" data-target="#defaultStep-three">
                                                    <button type="button" class="step-trigger" role="tab">
                                                    <i class="step-icon"></i>
                                                <span class="fonticon-wrap">
                                                <i class="fa-solid fa-building-columns fa-xl"></i>
                                                <i class="fa-solid fa-user  fa-xl" ></i>
                                              
                                                </span>
                                                        <span class="bs-stepper-label">
                                                            <span class="bs-stepper-title">Profile et universiter
                                                            </span>
                                                        </span>
                                                    </button>
                                                </div>
                                            </div>
                                            <form action="" method="post" enctype="multipart/form-data">
                                                <div class="bs-stepper-content  "   >
                                                    <div id="defaultStep-one" class="content" role="" style="margin:0;">
                                                        <fieldset>
                                                            <div class="row">
                                                                
                                                                <div class="col-sm-4">
                                                                    <div class="form-group">
                                                                        <label>Nom && Prénom<span class="text-danger">*</span></label>
                                                                        <input type="text" class="form-control required"  id="nomPrenom" placeholder="Nom && Prénom" name="nom_prenom_etudiant" >
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-4">
                                                                    <div class="form-group">
                                                                        <label>Genre<span class="text-danger">*</span></label>
                                                                        <select class="form-select form-control" id="genre" name="genre_etudiant">
                                                                            <option value="" disabled>Choisissez le sexe</option>
                                                                            <option value="F">Féminin</option>
                                                                            <option value="M">Masculin</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-4">
                                                                    <div class="form-group">
                                                                        <label>Date de naissance<span class="text-danger">*</span></label>
                                                                        <input type="date" class="form-control" placeholder="Date de naissance" name="date_naissance_etudiant">
                                                                    </div>
                                                                </div>

                                                            </div>
                                                            <div class="row">
                                                                <div class="col-sm-4">
                                                                    <div class="form-group">
                                                                        <label>Lieu de naissance<span class="text-danger">*</span></label>
                                                                        <input type="text" class="form-control" placeholder="Lieu de naissance" name="lieu_naissance_etudiant">
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-4">
                                                                    <div class="form-group">
                                                                        <label>Cercle de Naissance<span class="text-danger">*</span></label>
                                                                        <input type="text" class="form-control" placeholder="Cercle de Naissance" name="cercleNais">
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-4">
                                                                    <div class="form-group">
                                                                        <label>Commune de Naissance<span class="text-danger">*</span></label>
                                                                        <input type="text" class="form-control" placeholder="Commune de Naissance" name="commNais">
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-4">
                                                                    <div class="form-group">
                                                                        <label>Nationalité<span class="text-danger">*</span></label>
                                                                        <input type="text" class="form-control" placeholder="Nationalité" name="nationnalite">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>Numero Télephone<span class="text-danger">*</span> </label>
                                                                        <input type="number" class="form-control" placeholder="Numero Télephone " name="contact_etudiant">
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
                                                        <div class="button-action mt-5" >
                                                            <button type="button"
                                                                class="btn bg-secondary btn-prev me-3"
                                                                style="color:white" disabled>Prev</button>
                                                            <button type="button" class="btn bg-primary btn-nxt"
                                                                style="color:white;float:right;" >Next</button>
                                                        </div>
                                                    </div>
                                                    <div id="defaultStep-two" class="content" role="tabpanel" style="margin:0;">
                                                    <fieldset class="border p-4 rounded shadow-sm">
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label>Prénom du Père <span class="text-danger">*</span></label>
                                                                    <input type="text" class="form-control" placeholder="Prénom du Père" name="prenompere">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label>Nom et Prénom de la Mère <span class="text-danger">*</span></label>
                                                                    <input type="text" class="form-control" placeholder="Nom et Prénom de la Mère" name="prenomnommere">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label>Lieu de Résidence des Parents <span class="text-danger">*</span></label>
                                                                    <input type="text" class="form-control" placeholder="Lieu de Résidence" name="lieuresidenceparents">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <hr class="my-2">

                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label>Diplôme <span class="text-danger">*</span></label>
                                                                    <select class="form-select form-control" name="diplome">
                                                                        <option value="" disabled selected>Choisissez le Diplôme</option>
                                                                        <option value="Bac">Bac</option>
                                                                        <option value="BT">BT</option>
                                                                        <option value="IFM">IFM</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label>Numéro de Place <span class="text-danger">*</span></label>
                                                                    <input type="number" class="form-control" placeholder="Numéro de Place" name="numplace">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label>Série <span class="text-danger">*</span></label>
                                                                    <input type="text" class="form-control" placeholder="Série" name="serie">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label>Année du Diplôme <span class="text-danger">*</span></label>
                                                                    <input type="number" class="form-control"id="anneeDiplome" placeholder="Année du Diplôme" name="anneediplome">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label>Pays <span class="text-danger">*</span></label>
                                                                    <input type="text" class="form-control" placeholder="Pays" name="pays">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label>Académie <span class="text-danger">*</span></label>
                                                                    <input type="text" class="form-control" placeholder="Académie" name="academie">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                        <div class="button-action mt-5">
                                                            <button type="button"
                                                                class="btn bg-secondary btn-prev me-3"
                                                                style="color:white">Prev</button>
                                                            <button type="button" class="btn bg-primary btn-nxt"
                                                                style="color:white;float:right;">Next</button>
                                                        </div>
                                                    </div>
                                                    <div id="defaultStep-three" class="content" role="tabpanel" style="margin:0;">
                                                        <fieldset>
                                                            <!-- <div class="col-12">
                                                                <h6 class="py-50">Profile et universiter</h6>
                                                            </div> -->
                                                        
                                                            <div class="row">
                                                            
                                                                <div class="col-md-3 mb-2">
<<<<<<< HEAD
                                                                    <label >Matricule<span class="text-danger">*</span></label>
=======
                                                                    <label >N° Etudiant<span class="text-danger">*</span></label>
>>>>>>> 7d93232ab9b020409746aee02548156ca6dffcc1
                                                                    <input type="text" class="form-control required" name="matricule_etudiant" id="matricule"  placeholder="Matricule" readonly>

                                                                </div>
                                                                <div class="col-md-3  mb-2">
                                                                    <label >Matricule CENOU<span class="text-danger">*</span></label>
                                                                    <input type="text" class="form-control" name="numetudiant"  placeholder="Matricule CENOU">
                                                                </div>
                                                                <div class="col-md-3  mb-2">
                                                                    <label >Années univertisaire<span class="text-danger">*</span></label>
                                                                    <select class="form-select form-control" name="id_promotion" id="id_promotion">
                                                                            <option value="" >Choisissez Années univertisaire</option>
                                                                            <?php foreach ($listePromotion as $Promotion): ?>  
                                                                            <option
                                                                                value="<?= htmlspecialchars($Promotion->id_promotion); ?>">
                                                                                <?= htmlspecialchars($Promotion->sigle_filiere."-".$Promotion->sigle_semestre ."(".$Promotion->annee_universitaire.")"); ?>
                                                                            </option>
                                                                            <?php endforeach; ?>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-3  mb-2">
                                                                    <label class="form-label">Profile<span class="text-danger">*</span></label>
                                                                    <input  type="file" name="profilname" class="form-control" >
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                            <!-- Champ Statut -->
                                                                <div class="col-md-3 mb-2">
                                                                    <div class="form-group">
                                                                        <label>Statut<span class="text-danger">*</span></label>
                                                                        <select class="form-select form-control" name="id_statut" id="statut">
                                                                            <option value="" disabled selected>Choisissez le Statut</option>
                                                                            <option value="Regle">Regulier</option>
                                                                            <option value="Cl">CL</option>
                                                                            <option value="Proffesionnel">Proffesionnel</option>
                                                                        </select>
                                                                    </div>
                                                                </div>

                                                                <!-- Type de Professionnel -->
                                                                <div class="col-md-3 mb-2" id="type-prof-container" style="display: none;">
                                                                    <div class="form-group">
                                                                        <label>Type de Professionnel<span class="text-danger">*</span></label>
                                                                        <select class="form-select form-control" name="type_professionnel" id="type-professionnel">
                                                                            <option value="" disabled selected>Choisissez le Type</option>
                                                                            <option value="Etatique">Professionnel Étatique</option>
                                                                            <option value="Simple">Professionnel Collectivite</option>
                                                                            <option value="Prive">Professionnel Privé</option>
                                                                        </select>
                                                                    </div>
                                                                </div>

                                                                <!-- Frais d'inscription -->
                                                                <div class="col-md-2 mb-2" id="prix-container" style="display: none;">
                                                                    <div class="form-group">
                                                                        <label class="form-label">Frais d'Inscription</label>
                                                                        <input type="number" class="form-control" id="frais-inscription" name="frais_inscription" placeholder="Frais d'inscription" readonly>
                                                                    </div>
                                                                </div>

                                                                 <!-- Frais de Formation -->
                                                                <div class="col-md-2 mb-2" id="formation-container" style="display: none;">
                                                                    <div class="form-group">
                                                                        <label class="form-label">Frais de Formation</label>
                                                                        <input type="number" class="form-control" id="montant" name="montant_paye" placeholder="Prix de Formation" readonly>
                                                                    </div>
                                                                </div>
                                                               
                                                              
                                                                   <!-- Total des Frais -->
                                                            <div class="col-md-5" id="total-container" style="display: none;">
                                                               <!-- Total des Frais -->

                                                                <div class="row align-items-end">
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label class="form-label">Total</label>
                                                                            <input type="number" class="form-control frais-total" id="total-frais" name="total_frais" placeholder="Total des frais" readonly>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label class="form-label">Somme payée</label>
                                                                            <input type="number" class="form-control frais-paye" id="montant_paye" name="montant_paye" placeholder="Montant payé" value="" min="0">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label for="montant-restant">Somme restante :</label>
                                                                            <input type="number" class="form-control frais-restant" id="montant-restant" name="montant_restant" readonly>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                        </fieldset>
                                                        <div class="button-action mt-3">
                                                            <button type="button"
                                                                class="btn bg-primary btn-prev me-3"
                                                                style="color:white">Prev</button>
                                                            <button class="btn bg-success me-3" type="submit" name="ddddddd"
                                                                
                                                                style="color:white;float:right;">Enregistre</button>
                                    
                                                        </div>
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
                <!-- Form wizard with icon tabs section end -->
            </div>
        </div>
    </div>

    <?php $this->view("Partials/foot") ?>
    <?php $this->view("Partials/footer") ?>

</body>
<script>
    document.addEventListener('DOMContentLoaded', function () {
    const fraisParStatut = {
        "Regle": 6000,
        "Cl": 6000,
        "Proffesionnel": 6000
    };

    const fraisParTypeProfessionnel = {
        "Etatique": 6000,
        "Simple": 6000,
        "Prive": 6000
    };

    const fraisFormationParStatut = {
        "Regle": 0,
        "Cl": 75000,
        "Proffesionnel": 100000
    };

    const fraisFormationParTypeProfessionnel = {
        "Etatique": 150000,
        "Simple": 150000,
        "Prive": 200000
    };

    const statutSelect = document.getElementById('statut');
    const typeProfessionnelSelect = document.getElementById('type-professionnel');
    const fraisInscriptionInput = document.getElementById('frais-inscription');
    const montantInput = document.getElementById('montant');
    const montantPayeInput = document.getElementById('montant_paye');
    const totalFraisInput = document.getElementById('total-frais');
    const montantRestantInput = document.getElementById('montant-restant');
    const typeProfContainer = document.getElementById('type-prof-container');
    const prixContainer = document.getElementById('prix-container');
    const formationContainer = document.getElementById('formation-container');
    const totalContainer = document.getElementById('total-container');

    statutSelect.addEventListener('change', function () {
        const statutSelectionne = this.value;

        if (statutSelectionne === "Proffesionnel") {
            typeProfContainer.style.display = 'block';
            prixContainer.style.display = 'none';
            formationContainer.style.display = 'none';
            fraisInscriptionInput.value = '';
            montantInput.value = '';
        } else {
            typeProfContainer.style.display = 'none';
            prixContainer.style.display = 'block';
            formationContainer.style.display = 'block';
            fraisInscriptionInput.value = fraisParStatut[statutSelectionne] || 0;
            montantInput.value = fraisFormationParStatut[statutSelectionne] || 0;
              // Calculer la somme payée automatiquement
              const fraisInscription = parseFloat(fraisInscriptionInput.value) || 0;
            const fraisFormation = parseFloat(montantInput.value) || 0;
            const sommePayee = (2 / 3) * fraisFormation + fraisInscription;
            montantPayeInput.value = sommePayee.toFixed(2); // Affichage avec deux décimales
        }
        mettreAJourTotal();
    });

    typeProfessionnelSelect.addEventListener('change', function () {
        const typeSelectionne = this.value;

        fraisInscriptionInput.value = fraisParTypeProfessionnel[typeSelectionne] || 0;
        montantInput.value = fraisFormationParTypeProfessionnel[typeSelectionne] || 0;

        prixContainer.style.display = 'block';
        formationContainer.style.display = 'block';
        // Calculer la somme payée automatiquement
        const fraisInscription = parseFloat(fraisInscriptionInput.value) || 0;
        const fraisFormation = parseFloat(montantInput.value) || 0;
        const sommePayee = (2 / 3) * fraisFormation + fraisInscription;
        montantPayeInput.value = sommePayee.toFixed(2);

        mettreAJourTotal();
    });

    montantPayeInput.addEventListener('input', mettreAJourTotal);

    function mettreAJourTotal() {
        const fraisInscription = parseFloat(fraisInscriptionInput.value) || 0;
        const fraisFormation = parseFloat(montantInput.value) || 0;
        const montantPaye = parseFloat(montantPayeInput.value) || 0;
        const totalFrais = fraisInscription + fraisFormation;

        totalFraisInput.value = totalFrais;
        montantRestantInput.value = Math.max(totalFrais - montantPaye, 0);

        totalContainer.style.display = totalFrais > 0 ? 'block' : 'none';
    }
});
document.getElementById('montant_paye').addEventListener('input', function () {
    const totalFrais = parseFloat(document.getElementById('total-frais').value) || 0;
    const montantPaye = parseFloat(this.value) || 0;
    const montantRestant = totalFrais - montantPaye;

    const restantInput = document.getElementById('montant-restant');
    restantInput.value = montantRestant;

    if (montantRestant > 0) {
        restantInput.style.backgroundColor = '#f8d7da'; // Rouge pâle
        restantInput.style.color = '#c62828'; // Texte rouge foncé
    } else {
        restantInput.style.backgroundColor = '#d1f7c4'; // Vert pâle
        restantInput.style.color = '#2e7d32'; // Texte vert foncé
    }
});
//Geberation du matrixule
document.addEventListener("DOMContentLoaded", function () {
    function genererMatricule() {
        let anneeDiplome = document.getElementById("anneeDiplome").value;
        let nomPrenom = document.getElementById("nomPrenom").value.trim().split(" ");
        let genre = document.getElementById("genre").value;

        if (anneeDiplome && nomPrenom.length > 0 && genre) {
            let premiereLettreNom = nomPrenom[0] ? nomPrenom[0][0].toUpperCase() : "";
            let premiereLettrePrenom = nomPrenom[1] ? nomPrenom[1][0].toUpperCase() : "";

            // Numéro d'enregistrement simulé (4 chiffres aléatoires)
            let numEnregistrement = Math.floor(1000 + Math.random() * 9000);

            // Générer le matricule
            let matricule = `${anneeDiplome}${premiereLettreNom}${premiereLettrePrenom}${genre}${numEnregistrement}`;

            // Afficher le matricule généré
            document.getElementById("matricule").value = matricule;
        } else {
            document.getElementById("matricule").value = "";
        }
    }

    // Ajouter les événements pour détecter les changements
    document.getElementById("anneeDiplome").addEventListener("input", genererMatricule);
    document.getElementById("nomPrenom").addEventListener("input", genererMatricule);
    document.getElementById("genre").addEventListener("change", genererMatricule);
});

</script>
        
</html>