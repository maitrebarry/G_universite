<?php
class Enseignant extends Model
{

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
                    $destination = 'C:/xampp/htdocs/G_universite/public/cv_enseignant/' . $file_name;
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



    public function enregistrement($file, $table = [])
    {
        // Récupérer les données du formulaire
        $statut = $_POST['statut'] ?? null;
        $grade = $_POST['grade'] ?? null;
        // $heures = $_POST['heures'] ?? null;
        $matricule = $_POST['matricule'] ?? null;
        $nom = $_POST['nom'] ?? '';
        $prenom = $_POST['prenom'] ?? '';
        $date_naissance = $_POST['date_naissance'] ?? '';
        $email = $_POST['email'] ?? '';
        $diplome = $_POST['diplome'] ?? '';
        $telephone = $_POST['telephone'] ?? '';

        // Supprimer les espaces ou autres caractères non numériques
        $telephone = preg_replace('/\D/', '', $telephone);
        // Reformater le numéro
        if (strlen($telephone) == 8) {
            $telephone = substr($telephone, 0, 2) . ' ' .
                substr($telephone, 2, 2) . ' ' .
                substr($telephone, 4, 2) . ' ' .
                substr($telephone, 6, 2);
        }

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
        if ($statut === "NON_PERMANANT") {
            $grade = null;
            // $heures = null;
            $matricule = null;
        } else {
            $cv = null;
        }
        // Si des erreurs sont présentes, arrêter l'insertion et sauver les données de l'utilisateur
        if (!empty($this->errors)) {
            $this->save_input_data();
            return;
        }
        // Insertion dans la base de données
        $bdd = $this->connect();
        $insertion_enseignant = $bdd->prepare("INSERT INTO enseignants 
        (enseignant_statut, id_grade, enseignant_matricule, enseignant_nom, enseignant_prenom, enseignant_date_naissance, enseignant_email, enseignant_telephone, enseignant_diplome, enseignant_cv) 
        VALUES (:enseignant_statut, :id_grade, :enseignant_matricule, :enseignant_nom, :enseignant_prenom, :enseignant_date_naissance, :enseignant_email, :enseignant_telephone, :enseignant_diplome, :enseignant_cv)");
        $insertion = $insertion_enseignant->execute([
            ":enseignant_statut" => $statut,
            ":id_grade" => $grade,
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
            $this->set_flash("Enseignant ajouté avec succès", 'primary');
            $this->redirect("Enseignants/lsite_enseignant");
        } else {
            $this->errors[] = "Échec de la mise à jour";
        }
    }


    // Sélectionner les enseignants CDI
    public function getEnseignantCDI()
    {
        $select = "
            SELECT enseignants.*, grade.nom_grade 
            FROM enseignants
            JOIN grade ON grade.id_grade = enseignants.id_grade
            WHERE enseignants.enseignant_statut = :statut
        ";
        $execute_data = ['statut' => 'PERMANANT',];
        return $this->select_data_table_join_where($select, $execute_data);
    }

    // Sélectionner les enseignants VCT
    public function getEnseignantVCT()
    {
        $select = "*";
        $fields = "enseignants";
        $whereValue = "enseignant_statut = :statut";
        $value = ['statut' => 'NON_PERMANANT'];
        return $this->FetchSelectWhere2($select, $fields, $whereValue, $value);
    }

    public function modification($data)
    {
        // Validation des données
        if (isset($data['enseignant_statut']) && $data['enseignant_statut'] === 'PERMANENT') {
            if (empty($data['id_grade'])) {
                $this->errors[] = "Erreur : Le grade est obligatoire pour un enseignant permanent.";
                return;
            }
        }

        // Requête SQL
        $sql = "UPDATE enseignants 
            SET enseignant_statut = :enseignant_statut, 
                id_grade = :id_grade, 
                enseignant_matricule = :enseignant_matricule, 
                enseignant_nom = :enseignant_nom, 
                enseignant_prenom = :enseignant_prenom, 
                enseignant_date_naissance = :enseignant_date_naissance, 
                enseignant_email = :enseignant_email, 
                enseignant_telephone = :enseignant_telephone, 
                enseignant_diplome = :enseignant_diplome, 
                enseignant_cv = :enseignant_cv 
            WHERE enseignant_id = :id";

        // Paramètres
        $params = [
            ':enseignant_statut' => $data['enseignant_statut'],
            ':id_grade' => $data['id_grade'], // Référence à la table des grades
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

        // Exécution de la requête
        $result = $this->insertion_update_simples($sql, $params);

        // Gestion des résultats
        if ($result) {
            $this->set_flash("Enseignant mis à jour avec succès", 'success');
            $this->redirect("Enseignants/lsite_enseignant");
        } else {
            $this->errors[] = "Échec de la mise à jour";
        }
    }


    public function getPeriodes()
    {
        $query = "SELECT id_periode, date_debut, date_fin, status FROM periode";
        return $this->select_data_table_join_where($query);
    }


    public function getEmploiDuTempsByEnseignant($id, $date_debut, $date_fin, $search = 'inachevé')
    {
        $query = "
        SELECT 
            edt.id_edt, edt.date_creation, edt.date_debut, edt.date_fin, edt.heure_total, edt.statut,
            ue_module.id_ue_module, ue_module.id_ue, ue_module.id_module, ue_module.code_module, ue_module.coeficient, ue_module.cm, ue_module.td, ue_module.tp, ue_module.tpe,
            module.nom_module, module.sigle_module,
            salle.nom_salle, salle.capacite_salle,
            filiere.nom_filiere, filiere.sigle_filiere,
            promotion.id_promotion, promotion.annee_universitaire, promotion.statut AS promotion_statut, promotion.id_filiere, promotion.id_parcours,
            semestre.nom_semestre, semestre.sigle_semestre,
            enseignants.enseignant_nom, enseignants.enseignant_prenom, enseignants.enseignant_date_naissance, enseignants.enseignant_telephone, 
            enseignants.enseignant_diplome, enseignants.enseignant_email, enseignants.enseignant_statut,
            CASE 
                WHEN enseignants.id_grade IS NULL AND enseignants.enseignant_statut = 'NON_PERMANANT' AND enseignants.enseignant_diplome LIKE '%master%' THEN 'Assistant'
                WHEN enseignants.id_grade IS NULL AND enseignants.enseignant_statut = 'NON_PERMANANT' AND enseignants.enseignant_diplome LIKE '%doctorat%' THEN 'Maître Assistant'
                ELSE grade.nom_grade 
            END AS nom_grade,
            CASE 
                WHEN enseignants.id_grade IS NULL AND enseignants.enseignant_statut = 'NON_PERMANANT' AND enseignants.enseignant_diplome LIKE '%master%' THEN 0
                WHEN enseignants.id_grade IS NULL AND enseignants.enseignant_statut = 'NON_PERMANANT' AND enseignants.enseignant_diplome LIKE '%doctorat%' THEN 0
                ELSE grade.heures_dues 
            END AS heures_dues
        FROM 
            edt
        LEFT JOIN 
            ue_module ON edt.id_module = ue_module.id_ue_module
        LEFT JOIN 
            module ON ue_module.id_module = module.id_module
        LEFT JOIN 
            salle ON edt.id_salle = salle.id_salle
        LEFT JOIN 
            filiere ON edt.id_filiere = filiere.id_filiere
        LEFT JOIN 
            promotion ON edt.id_promotion = promotion.id_promotion
        LEFT JOIN 
            parcours ON promotion.id_parcours = parcours.id_parcours
        LEFT JOIN 
            semestre ON parcours.id_semestre = semestre.id_semestre
        LEFT JOIN 
            enseignants ON edt.id_enseignant = enseignants.enseignant_id
        LEFT JOIN 
            grade ON enseignants.id_grade = grade.id_grade
        LEFT JOIN 
            periode ON edt.id_periode = periode.id_periode
        WHERE 
            edt.id_enseignant = :id AND 
            edt.date_debut >= :date_debut AND 
            edt.date_fin <= :date_fin
    ";
        if ($search == 'achevé') {
            $query .= " AND periode.status = 'achevé'";
        } else {
            $query .= " AND (edt.statut = 1 OR periode.status = 'inachevé')";
        }
        $query .= " ORDER BY edt.date_debut ASC";
        $params = [
            "id" => $id,
            "date_debut" => $date_debut,
            "date_fin" => $date_fin
        ];
        return $this->select_data_table_join_where($query, $params);
    }
}