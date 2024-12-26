<?php 
class Enseignant extends Model{



         public $errors = [];

    private function upload_cv($file)
    {
        $default_image = '/cv_enseignant/default_cv.pdf'; 
        $taillemax = 2067152; 
        $extensions_valides = ['pdf', 'doc', 'docx'];

        if (isset($file['name']) && $file['error'] == 0) {
            $file_name = basename($file['name']);
            $file_tmp = $file['tmp_name'];
            $file_size = $file['size'];
            $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            if ($file_size <= $taillemax) {
                if (in_array($file_extension, $extensions_valides)) {
                    $destination = 'C:/xampp/htdocs/G_universites/public/cv_enseignant/' . $file_name;
                    if (move_uploaded_file($file_tmp, $destination)) {
                        return '/cv_enseignant/' . $file_name; // Chemin relatif enregistré dans la base
                    } else {
                        $this->errors[] = "Erreur lors du déplacement du fichier.";
                    }
                } else {
                    $this->errors[] = "Extension de fichier non valide. Extensions autorisées : .pdf, .doc, .docx.";
                }
            } else {
                $this->errors[] = "Taille du fichier trop grande. Taille maximale autorisée : 2 Mo.";
            }
        } else {
            switch ($file['error']) {
                case UPLOAD_ERR_NO_FILE:
                    return $default_image; // Retourne le fichier par défaut si aucun fichier téléchargé
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    $this->errors[] = "Le fichier dépasse la taille maximale autorisée.";
                    break;
                case UPLOAD_ERR_PARTIAL:
                    $this->errors[] = "Le fichier n'a été que partiellement téléchargé.";
                    break;
                default:
                    $this->errors[] = "Erreur inconnue lors de l'upload.";
                    break;
            }
        }

        return $default_image; // Retourne le fichier par défaut en cas d'erreur
    }


   
    public function enregistrement($file, $table = []) {
            // Récupérer les données du formulaire
            $statut = $_POST['statut'] ?? null;
            $grade = $_POST['grade'] ?? null;
            // $heures = $_POST['heures'] ?? null;
            $matricule = $_POST['matricule'] ?? null;
            $nom = $_POST['nom'] ?? '';
            $prenom = $_POST['prenom'] ?? '';
            $date_naissance = $_POST['date_naissance'] ?? '';
            $email = $_POST['email'] ?? '';
            $telephone = $_POST['telephone'] ?? '';
            $diplome = $_POST['diplome'] ?? '';

            // Messages d'erreur pour la validation du téléphone
            $messages = [
                'length' => "Le numéro de téléphone doit contenir exactement 8 chiffres.",
                'first_digit_invalid' => "Le premier chiffre doit être supérieur ou égal à 3.",
                'first_digit_range' => "Le premier chiffre doit être compris entre 3 et 9.",
                'duplicate_phone' => "Ce numéro de téléphone est déjà utilisé.",
            ];

            // Validation du CV
            $cv = $this->upload_cv($file);

            // Validation des champs obligatoires
            if (empty($nom)) {
                $this->errors[] = "Le nom est obligatoire.";
            }

            if (empty($prenom)) {
                $this->errors[] = "Le prénom est obligatoire.";
            }

            if (empty($date_naissance)) {
                $this->errors[] = "La date de naissance est obligatoire.";
            }

            if (empty($email)) {
                $this->errors[] = "L'adresse email est obligatoire.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->errors[] = "Le format de l'adresse email est invalide.";
            }

            // Vérification de l'unicité de l'email
            if ($this->user_verify('enseignant_email', 'enseignants', $email) > 0) {
                $this->errors[] = "Cet email est déjà enregistré, veuillez choisir un autre.";
            }

            // Validation du numéro de téléphone
            $validation_result = $this->telephone_numero_verification1($telephone);
            if (is_array($validation_result)) {
                $this->errors = array_merge($this->errors, $validation_result);
            }

            // Vérification de l'unicité du numéro de téléphone
            if ($this->user_verify('enseignant_telephone', 'enseignants', $telephone) > 0) {
                $this->errors[] = $messages['duplicate_phone'];
            }

            // Gestion des champs si statut est "VCT"
            if ($statut === "VCT") {
                $grade = null;
                // $heures = null;
                $matricule = null;
            }

            // Si des erreurs sont présentes, arrêter l'insertion et sauver les données de l'utilisateur
            if (!empty($this->errors)) {
                $this->save_input_data(); 
                return;
            }

            // Insertion dans la base de données
            $bdd = $this->connect();
            $insertion_enseignant = $bdd->prepare("INSERT INTO enseignants 
                (enseignant_statut, enseignant_grade, enseignant_matricule, enseignant_nom, enseignant_prenom, enseignant_date_naissance, enseignant_email, enseignant_telephone, enseignant_diplome, enseignant_cv) 
                VALUES (:enseignant_statut, :enseignant_grade, :enseignant_matricule, :enseignant_nom, :enseignant_prenom, :enseignant_date_naissance, :enseignant_email, :enseignant_telephone, :enseignant_diplome, :enseignant_cv)");

            $insertion = $insertion_enseignant->execute([
                ":enseignant_statut" => $statut,
                ":enseignant_grade" => $grade, 
                // ":enseignant_heures_semesre" => $heures, 
                ":enseignant_matricule" => $matricule, 
                ":enseignant_nom" => $nom,
                ":enseignant_prenom" => $prenom,
                ":enseignant_date_naissance" => $date_naissance,
                ":enseignant_email" => $email,
                ":enseignant_telephone" => $telephone,
                ":enseignant_diplome" => $diplome,
                ":enseignant_cv" => $cv,
            ]);
              if ($insertion) {
                    $this->set_flash("Enseignant ajouté avec succès", 'success');
                    $this->redirect("Enseignants/lsite_enseignant");
                } else {
                    $this->set_flash("Échec de la mise à jour", 'error');
                }

    }

     // Sélectionner les enseignants CDI
    public function getEnseignantCDI() {
        $select = "*";  
        $fields = "enseignants";  
        $whereValue = "enseignant_statut = :statut";  
        $value = ['statut' => 'CDI'];  
        return $this->FetchSelectWhere2($select, $fields, $whereValue, $value);
    }
    // Sélectionner les enseignants VCT
    public function getEnseignantVCT() {    
        $select = "*";  
        $fields = "enseignants";  
        $whereValue = "enseignant_statut = :statut";  
        $value = ['statut' => 'VACT'];  
        return $this->FetchSelectWhere2($select, $fields, $whereValue, $value);
    }

    public function modification($data) {
        $sql = "UPDATE enseignants 
                SET enseignant_statut = :enseignant_statut, 
                    enseignant_grade = :enseignant_grade, 
                    enseignant_matricule = :enseignant_matricule, 
                    enseignant_nom = :enseignant_nom, 
                    enseignant_prenom = :enseignant_prenom, 
                    enseignant_date_naissance = :enseignant_date_naissance, 
                    enseignant_email = :enseignant_email, 
                    enseignant_telephone = :enseignant_telephone, 
                    enseignant_diplome = :enseignant_diplome, 
                    enseignant_cv = :enseignant_cv 
                WHERE enseignant_id = :id";

        $params = [
            ':enseignant_statut' => $data['enseignant_statut'],
            ':enseignant_grade' => $data['enseignant_grade'],
            ':enseignant_matricule' => $data['enseignant_matricule'],
            ':enseignant_nom' => $data['enseignant_nom'],
            ':enseignant_prenom' => $data['enseignant_prenom'],
            ':enseignant_date_naissance' => $data['enseignant_date_naissance'],
            ':enseignant_email' => $data['enseignant_email'],
            ':enseignant_telephone' => $data['enseignant_telephone'],
            ':enseignant_diplome' => $data['enseignant_diplome'],
            ':enseignant_cv' => $data['enseignant_cv'],
            ':id' => $data['id']
        ];

        $result = $this->insertion_update_simples($sql, $params);

        if ($result) {
            $this->set_flash("Enseignant mis à jour avec succès", 'success');
             $this->redirect("Enseignants/lsite_enseignant");
        } else {
            $this->set_flash("Échec de la mise à jour", 'error');
        }
    }


  




   



    








}