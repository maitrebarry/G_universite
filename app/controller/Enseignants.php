<?php
class Enseignants extends Controller
{
    public function index()
    {
        $this->lsite_enseignant();
    }

    public function lsite_enseignant() {
       
        $commandeModel = new Enseignant(); 
        $enseignat_enseignat_PERMANANT = $commandeModel->getEnseignantCDI();
         $enseignat_NON_PERMANANT = $commandeModel->getEnseignantVCT();
        //  var_dump($enseignat_CDI);
        //  var_dump($enseignat_VCT);
        //  exit;
            $this->view('liste_enseignant',
            [ 
                    'enseignat_CDI' => $enseignat_enseignat_PERMANANT,
                    'enseignat_VCT' => $enseignat_NON_PERMANANT 
                  ]);

    }
   
    public function ajouter_enseignant() {
        $enseignant = new Enseignant();
          $filiere = $enseignant->SelectAllData("*", "grade");
        if (isset($_POST["envoyer"])) {
            // Nettoyage des données utilisateur
            $_POST = array_map('trim', $_POST);
            $cv_file = $_FILES['cv'] ?? null;

            // Appel de la méthode d'enregistrement
            $enseignant->enregistrement($cv_file, $_POST);

            if (!empty($enseignant->errors)) {
                $_SESSION['input'] = $_POST;
                $_SESSION['errors'] = $enseignant->errors;
            } else {
                // Nettoyer les sessions en cas de succès
                unset($_SESSION['input']);
                unset($_SESSION['errors']);

            
            }
        }

        // Récupération des données de session
        $input_values = $_SESSION['input'] ?? [];
        $errors = $_SESSION['errors'] ?? [];
        unset($_SESSION['input'], $_SESSION['errors']); 

        // Chargement de la vue avec les données nécessaires
        $this->view("ajouter_enseignant", [
            'errors' => $errors,
            'filiere' => $filiere,
            'input_values' => $input_values
        ]);
    }

    public function update($id) {
        $enseignant = new Enseignant();
        $errors = [];

        // Récupérer les données de l'enseignant avec le grade via une jointure
        $select = "
            SELECT enseignants.*, grade.nom_grade 
            FROM enseignants
            LEFT JOIN grade ON grade.id_grade = enseignants.id_grade
            WHERE enseignants.enseignant_id = :id
        ";
        $enseignantData = $enseignant->select_data_table_join_where($select, ['id' => $id]);

        // Vérifiez si l'enseignant existe
        if (empty($enseignantData)) {
            $errors[] = "L'enseignant avec l'ID spécifié n'existe pas.";
            $this->view('modifier_enseignant', ['errors' => $errors]);
            return;
        }
        $enseignantData = $enseignantData[0]; 

        // Récupérer la liste des grades pour le formulaire
        $grades = $enseignant->SelectAllData("*","grade");
    
        // Traitement lors de la soumission du formulaire
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $statut = $_POST['enseignant_statut'];

            // Gestion du fichier CV
            if (isset($_FILES['enseignant_cv']) && $_FILES['enseignant_cv']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'C:/xampp/htdocs/G_universite/public/cv_enseignant/';
                $cvFileName = uniqid() . '_' . basename($_FILES['enseignant_cv']['name']);
                $cvFilePath = $uploadDir . $cvFileName;

                // Supprimez l'ancien fichier si nécessaire
                if (!empty($enseignantData->enseignant_cv) && file_exists('C:/xampp/htdocs/G_universite/public/' . $enseignantData->enseignant_cv)) {
                    unlink('C:/xampp/htdocs/G_universite/public/' . $enseignantData->enseignant_cv);
                }

                if (move_uploaded_file($_FILES['enseignant_cv']['tmp_name'], $cvFilePath)) {
                    $cv = 'cv_enseignant/' . $cvFileName; 
                } else {
                    $errors[] = "Échec du téléversement du fichier CV.";
                }
            } else {
                $cv = $enseignantData->enseignant_cv; 
            }

            // Validation supplémentaire pour les permanents
            if ($statut === 'PERMANANT') {
                if (empty($_POST['id_grade'])) {
                    $errors[] = "Le grade est obligatoire pour un enseignant permanent.";
                }
                if (empty($_POST['enseignant_matricule'])) {
                    $errors[] = "Le matricule est obligatoire pour un enseignant permanent.";
                }
            }

            // Préparer les données pour la mise à jour
            $data = [
                'id' => $id,
                'enseignant_statut' => $statut,
                'id_grade' => $statut === 'PERMANANT' ? (int)$_POST['id_grade'] : null,
                'enseignant_matricule' => $statut === 'PERMANANT' ? $_POST['enseignant_matricule'] : null,
                'enseignant_nom' => $_POST['enseignant_nom'],
                'enseignant_prenom' => $_POST['enseignant_prenom'],
                'enseignant_date_naissance' => $_POST['enseignant_date_naissance'],
                'enseignant_email' => $_POST['enseignant_email'],
                'enseignant_telephone' => $_POST['enseignant_telephone'],
                'enseignant_diplome' => $_POST['enseignant_diplome'],
                'enseignant_cv' => $cv ?? null,
            ];

            // Mise à jour si aucune erreur
            if (empty($errors)) {
                $result = $enseignant->modification($data);

                if ($result) {
                    // Rechargez les données après modification
                    $enseignantData = $enseignant->select_data_table_join_where($select, ['id' => $id])[0];
                } else {
                    $errors[] = "Échec de la mise à jour de l'enseignant.";
                }
            }
    }

    // Charger la vue avec les données mises à jour
    $this->view('modifier_enseignant', [
        'enseignant' => $enseignantData,
        'grades' => $grades,
        'errors' => $errors
    ]);
    }




    // public function update($id) {
    //     $enseignant = new Enseignant();
    //     $errors = []; 
    //     $enseignantData = $enseignant->FetchSelectWhere("*", "enseignants", "enseignant_id=:id", ["id" => $id]);
    //     // Vérifiez si les données sont récupérées
    //     if (!$enseignantData) {
    //         $errors[] = "L'enseignant avec l'ID spécifié n'existe pas.";
    //         $this->view('modifier_enseignant', ['errors' => $errors]);
    //         return;
    //     }

    //     if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //         $statut = $_POST['enseignant_statut'];
    //         // Traitement du fichier CV
    //         if (isset($_FILES['enseignant_cv']) && $_FILES['enseignant_cv']['error'] === UPLOAD_ERR_OK) {
    //             $uploadDir = 'C:/xampp/htdocs/G_universite/public/cv_enseignant/';
    //             $cvFileName = uniqid() . '_' . basename($_FILES['enseignant_cv']['name']);
    //             $cvFilePath = $uploadDir . $cvFileName;
    //             if (move_uploaded_file($_FILES['enseignant_cv']['tmp_name'], $cvFilePath)) {
    //                 $cv = $cvFileName;
    //             } else {
    //                 $errors[] = "Échec du téléversement du fichier CV.";
    //             }
    //         } else {
    //             $cv = $enseignantData->enseignant_cv; 
    //         }

    //         // Préparer les données
    //         $data = [
    //             'id' => $id,
    //             'enseignant_statut' => $statut,
    //             'enseignant_grade' => $statut === 'CDI' ? $_POST['enseignant_grade'] : null,
    //             'enseignant_matricule' => $statut === 'CDI' ? $_POST['enseignant_matricule'] : null,
    //             'enseignant_nom' => $_POST['enseignant_nom'],
    //             'enseignant_prenom' => $_POST['enseignant_prenom'],
    //             'enseignant_date_naissance' => $_POST['enseignant_date_naissance'],
    //             'enseignant_email' => $_POST['enseignant_email'],
    //             'enseignant_telephone' => $_POST['enseignant_telephone'],
    //             'enseignant_diplome' => $_POST['enseignant_diplome'],
    //             'enseignant_cv' => $cv ?? null,
    //         ];

        
    //         // Si aucune erreur, procéder à la mise à jour
    //         if (empty($errors)) {
    //             $result = $enseignant-> modification($data);      
    //         }
    //     }

    //     // Charger la vue avec les données de l'enseignant et les erreurs
    //     $this->view('modifier_enseignant', ['enseignant' => $enseignantData, 'errors' => $errors]);
    // }

    public function delete($id) {
        $perso = new Enseignant();     
        // Définir la requête de suppression et les paramètres
        $sql = 'DELETE FROM enseignants WHERE enseignant_id = :id';
        $params = [':id' => $id];
        $result = $perso->insertion_update_simples($sql, $params); 
        if ($result->rowCount() > 0) {
            $perso->set_flash("Suppression réussie", 'success');
        }  
        $perso->redirect('Enseignants/liste_enseignant');
    }   

   


    public function liste_emargement() {
        // Instancier les modèles nécessaires
        $filiereModel = new Filiere();
        $semestreModel = new Semestre();
        $enseignantModel = new Enseignant();
        
        // Récupérer toutes les données nécessaires pour les filtres
        $filiere = $filiereModel->SelectAllData("*", "filiere");
        $semestre = $semestreModel->SelectAllData("*", "semestre");
        $enseignants = $enseignantModel->SelectAllData("*", "enseignants");
        $errors = [];
        $resultats = [];
        $filters = []; 

        // Traitement du formulaire d'enregistrement d'émargement
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
            // Récupération des données du formulaire
            $id_enseignant = isset($_POST['enseignant']) ? intval($_POST['enseignant']) : null;
            $id_filiere = isset($_POST['filiere']) ? intval($_POST['filiere']) : null;
            $id_semestre = isset($_POST['semestre']) ? intval($_POST['semestre']) : null;
            $date_debut = $_POST['date_debut'] ?? null;
            $date_fin = $_POST['date_fin'] ?? null;
            $nh_programme = isset($_POST['nh_programme']) ? intval($_POST['nh_programme']) : null;
            $statut = $_POST['statut'] ?? null;
            $grade = $_POST['grade'] ?? null;

            // Vérification des champs obligatoires pour l'enregistrement
            if (empty($id_enseignant)) {
                $errors[] = "Veuillez sélectionner un enseignant.";
            }
            if (empty($id_filiere)) {
                $errors[] = "Veuillez sélectionner une filière.";
            }
            if (empty($id_semestre)) {
                $errors[] = "Veuillez sélectionner un semestre.";
            }
            if (empty($date_debut)) {
                $errors[] = "Veuillez sélectionner une date de début.";
            }
            if (empty($date_fin)) {
                $errors[] = "Veuillez sélectionner une date de fin.";
            }
            if (empty($statut)) {
                $errors[] = "Veuillez sélectionner un statut.";
            }

            // Récupération et traitement spécifique selon le statut
            if (empty($errors)) {
                $heures_supp = 0;
                $heures_dues = null;

                // Initialiser la variable cumule_heures_programmees
                $cumul_heures_programmees = 0;

                // Récupération des heures supplémentaires existantes pour cet enseignant et ce semestre
                $resultats_existants = $enseignantModel->recupererEmargementData(['id_enseignant' => $id_enseignant, 'id_semestre' => $id_semestre]);
                foreach ($resultats_existants as $resultat) {
                    $cumul_heures_programmees += $resultat->nh_programme;
                }

                $cumul_heures_programmees += $nh_programme; // Ajouter les heures programmées de cette nouvelle entrée

                if ($statut == "1") { // CDI
                    $heures_dues = intval($_POST['heures_dues'] ?? 0); // Heures dues obtenues du formulaire
                    $heures_supp = max(0, $cumul_heures_programmees - $heures_dues);
                } else { // VACT
                    $heures_supp = intval($_POST['heures_supp'] ?? 0);
                }

                // Préparer les données pour l'insertion
                $emargementData = [
                    'id_enseignant' => $id_enseignant,
                    'id_filiere' => $id_filiere,
                    'id_semestre' => $id_semestre,
                    'date_debut' => $date_debut,
                    'date_fin' => $date_fin,
                    'nh_programme' => $nh_programme,
                    'heures_supp' => $heures_supp,
                    'statut' => $statut,
                    'grade' => $statut == "1" ? $grade : null,
                    'heures_dues' => $statut == "1" ? $heures_dues : null,
                ];

                // Insertion dans la base de données
                $insertion = $enseignantModel->enregistrer_emargement($emargementData);

                if ($insertion) {
                    $enseignantModel->set_flash("Insertion faite avec succès.", 'success');
                    $enseignantModel->clear_input_data();
                    $enseignantModel->redirect("Enseignants/liste_emargement");
                } else {
                    $errors[] = "Une erreur s'est produite lors de l'insertion des données.";
                }
            }
        }

        // Traitement du formulaire de filtrage
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_filtre'])) {
            // Récupération des données du formulaire de filtrage
            $id_enseignant_filtre = isset($_POST['enseignant']) ? intval($_POST['enseignant']) : null;
            $id_filiere_filtre = isset($_POST['filiere']) ? intval($_POST['filiere']) : null;
            $id_semestre_filtre = isset($_POST['semestre']) ? intval($_POST['semestre']) : null;

            // Préparation des filtres
            $filters = [
                'id_enseignant' => $id_enseignant_filtre,
                'id_filiere' => $id_filiere_filtre,
                'id_semestre' => $id_semestre_filtre
            ];

            // Récupération des résultats filtrés
            $resultats = $enseignantModel->recupererEmargementData($filters);
        } else {
            // Récupération des heures supplémentaires et programmées sans filtres
            $resultats = $enseignantModel->recupererEmargementData();
        }

        // Charger la vue avec les données récupérées
        $this->view("liste_emargement", [
            'filiere' => $filiere,
            'semestre' => $semestre,
            'enseignants' => $enseignants,
            'errors' => $errors,
            'resultats' => $resultats
        ]);
    }
    public function getCumulHeuresProgrammees() {
        $id_enseignant = $_GET['id_enseignant'] ?? null;

        if ($id_enseignant) {
            $enseignantModel = new Enseignant();
            $cumulHeuresProgrammees = $enseignantModel->getCumulHeuresProgrammees($id_enseignant);
            echo json_encode(['cumul_heures_programmees' => $cumulHeuresProgrammees]);
        } else {
            echo json_encode(['error' => 'ID enseignant manquant']);
        }
    }
    
    public function update_emargement($id) {
        $model = new Enseignant();
        $erreurs = [];

        // Vérification de l'existence du bouton "modifier"
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modifier'])) {
            // Récupération et nettoyage des données entrantes
            $id_enseignant = isset($_POST['id_enseignant']) ? intval($_POST['id_enseignant']) : null;
            $id_filiere = isset($_POST['id_filiere']) ? intval($_POST['id_filiere']) : null;
            $id_semestre = isset($_POST['id_semestre']) ? intval($_POST['id_semestre']) : null;
            $date_debut = $_POST['date_debut'] ?? null;
            $date_fin = $_POST['date_fin'] ?? null;
            $nh_programme = isset($_POST['nh_programme']) ? intval($_POST['nh_programme']) : null;
            $statut = $_POST['statut'] ?? null;
            $grade = $_POST['grade'] ?? null;

           

            // Si aucune erreur, traitement spécifique selon le statut
            if (empty($erreurs)) {
                $heures_supp = 0;
                $heures_dues = null;
                $cumul_heures_programmees = 0;

                // Récupération des heures supplémentaires existantes pour cet enseignant et ce semestre
                $resultats_existants = $model->recupererEmargementData(['id_enseignant' => $id_enseignant, 'id_semestre' => $id_semestre]);
                foreach ($resultats_existants as $resultat) {
                    $cumul_heures_programmees += $resultat->nh_programme;
                }

                $cumul_heures_programmees += $nh_programme; // Ajouter les heures programmées de cette nouvelle entrée

                if ($statut == "1") { // CDI
                    $heures_dues = intval($_POST['heures_dues'] ?? 0); // Heures dues obtenues du formulaire
                    $heures_supp = max(0, $cumul_heures_programmees - $heures_dues);
                } else { // VACT
                    $heures_supp = intval($_POST['heures_supp'] ?? 0);
                }

                // Préparer les données pour la mise à jour
                $emargementData = [
                    'id_enseignant' => $id_enseignant,
                    'id_filiere' => $id_filiere,
                    'id_semestre' => $id_semestre,
                    'date_debut' => $date_debut,
                    'date_fin' => $date_fin,
                    'nh_programme' => $nh_programme,
                    'heures_supp' => $heures_supp,
                    'statut' => $statut,
                    'grade' => $statut == "1" ? $grade : null,
                    'heures_dues' => $statut == "1" ? $heures_dues : null,
                ];

                // Appel de la méthode de mise à jour
                $result = $model->mettre_a_jour_emargement($id, $emargementData);

                // Gestion du résultat de la mise à jour et redirection
                if ($result) {
                    $model->set_flash("Emargement modifié avec succès.", 'success');
                } else {
                    $erreurs[] = "Erreurs lors de la mise à jour.";
                }
                
                $model->redirect('Enseignants/liste_emargement');
            } else {
                // Gestion des erreurs de validation
                foreach ($erreurs as $erreur) {
                    echo $erreur . '<br>';
                }
            }
        } else {
            $erreurs[] = "Le bouton modifier n'a pas été détecté.";
            $model->redirect('Enseignants/liste_emargement');
        }

        if (!empty($erreurs)) {
            // Gérez l'affichage des erreurs si nécessaire
            foreach ($erreurs as $erreur) {
                echo $erreur . '<br>';
            }
        }
    }

public function getEnseignantsParStatut() {
    $statut = $_GET['statut'] ?? null;

    if ($statut) {
        $model = new Enseignant();
        if ($statut === 'CDI') {
            $enseignants = $model->getEnseignantCDI();
        } else if ($statut === 'VACT') {
            $enseignants = $model->getEnseignantVCT();
        } else {
            echo json_encode(['success' => false, 'message' => 'Statut invalide']);
            return;
        }

        echo json_encode(['success' => true, 'enseignants' => $enseignants]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Statut non fourni']);
    }
}










    public function delete_emargement($id) {
        $emargement = new Enseignant(); 
        // Définir la requête de suppression et les paramètres
        $sql = 'DELETE FROM emargement WHERE id_emargement = :id';
        $params = [':id' => $id];
        // Exécuter la requête de suppression
        $result_emargement = $emargement->insertion_update_simples($sql, $params);
        if ($result_emargement->rowCount() > 0) {
            $emargement->set_flash("Suppression réussie", 'success');
        } 
        $emargement->redirect('Enseignants/liste_emargement');
    }










}