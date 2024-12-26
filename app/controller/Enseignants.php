<?php
class Enseignants extends Controller
{
    public function index()
    {
        $this->lsite_enseignant();
    }

    public function lsite_enseignant() {
       
        $commandeModel = new Enseignant(); 
        $enseignat_CDI = $commandeModel->getEnseignantCDI();
         $enseignat_VCT = $commandeModel->getEnseignantVCT();
        //  var_dump($enseignat_CDI);
        //  var_dump($enseignat_VCT);
        //  exit;
            $this->view('liste_enseignant',
            [ 
                    'enseignat_CDI' => $enseignat_CDI,
                    'enseignat_VCT' => $enseignat_VCT 
                  ]);

    }
   



    public function ajouter_enseignant() {
        $enseignant = new Enseignant();
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
            'input_values' => $input_values
        ]);
    }

    public function update($id) {
        $enseignant = new Enseignant();
        $errors = []; 
        $enseignantData = $enseignant->FetchSelectWhere("*", "enseignants", "enseignant_id=:id", ["id" => $id]);
        // Vérifiez si les données sont récupérées
        if (!$enseignantData) {
            $errors[] = "L'enseignant avec l'ID spécifié n'existe pas.";
            $this->view('modifier_enseignant', ['errors' => $errors]);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $statut = $_POST['enseignant_statut'];
            // Traitement du fichier CV
            if (isset($_FILES['enseignant_cv']) && $_FILES['enseignant_cv']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'C:/xampp/htdocs/G_universites/public/cv_enseignant/';
                $cvFileName = uniqid() . '_' . basename($_FILES['enseignant_cv']['name']);
                $cvFilePath = $uploadDir . $cvFileName;
                if (move_uploaded_file($_FILES['enseignant_cv']['tmp_name'], $cvFilePath)) {
                    $cv = $cvFileName;
                } else {
                    $errors[] = "Échec du téléversement du fichier CV.";
                }
            } else {
                $cv = $enseignantData->enseignant_cv; 
            }

            // Préparer les données
            $data = [
                'id' => $id,
                'enseignant_statut' => $statut,
                'enseignant_grade' => $statut === 'CDI' ? $_POST['enseignant_grade'] : null,
                'enseignant_matricule' => $statut === 'CDI' ? $_POST['enseignant_matricule'] : null,
                'enseignant_nom' => $_POST['enseignant_nom'],
                'enseignant_prenom' => $_POST['enseignant_prenom'],
                'enseignant_date_naissance' => $_POST['enseignant_date_naissance'],
                'enseignant_email' => $_POST['enseignant_email'],
                'enseignant_telephone' => $_POST['enseignant_telephone'],
                'enseignant_diplome' => $_POST['enseignant_diplome'],
                'enseignant_cv' => $cv ?? null,
            ];

        
            // Si aucune erreur, procéder à la mise à jour
            if (empty($errors)) {
                $result = $enseignant-> modification($data);      
            }
        }

        // Charger la vue avec les données de l'enseignant et les erreurs
        $this->view('modifier_enseignant', ['enseignant' => $enseignantData, 'errors' => $errors]);
    }

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

    public function lsite_emargement(){

        $this->view('lsite_emargement');
    }


}