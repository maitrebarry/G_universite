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
                    $destination = PUBLIC_PATH . '/cv_enseignant/' . $file_name;
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

    private function upload_contrat($contrat)
    {
        $default_image = '/contrat_enseignant/default_contrat.pdf';
        $taillemax = 2067152;
        $extensions_valides = ['pdf', 'doc', 'docx'];
    
        if (isset($contrat['name']) && $contrat['error'] == 0) {
            $file_name = basename($contrat['name']);
            $file_tmp = $contrat['tmp_name'];
            $file_size = $contrat['size'];
            $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    
            if ($file_size <= $taillemax) {
                if (in_array($file_extension, $extensions_valides)) {
                    $destination = PUBLIC_PATH . '/contrat_enseignant/' . $file_name;
                    if (move_uploaded_file($file_tmp, $destination)) {
                        return '/contrat_enseignant/' . $file_name; // Chemin relatif enregistré dans la base
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
            switch ($contrat['error']) {
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

    // public function enregistrement($files = [], $post, $allGrades = [])
    // {
    //     // Initialisation de $files si null
    //     $files = $files ?? [];
        
    //     $statut = $post['statut'] ?? null; // Note: vérifiez si c'est 'statut' ou 'statut' dans $_POST
    //     $grade = $post['grade'] ?? null;
    //     $matricule = $post['matricule'] ?? null;
    //     $nom = $post['nom'] ?? null;
    //     $prenom = $post['prenom'] ?? null;
    //     $date_naissance = $post['date_naissance'] ?? null;
    //     $email = $post['email'] ?? null;
    //     $telephone = $post['telephone'] ?? null;
    //     $diplome = $post['diplome'] ?? null;
    //     $code = $post['code'] ?? null;
    //     $administration = $post['administration'] ?? 0;
    //     $id_departement = $post['id_departement'] ?? null; 
    //     // Gérer les champs en fonction du statut (avec orthographe originale)
    //     if ($statut === "NON_PERMANANT") {
    //         $grade = null;
    //     } else {
    //         $code = null;
    //         // Initialiser correctement les fichiers
    //         $files['cv'] = $files['cv'] ?? null;
    //         $files['contrat'] = $files['contrat'] ?? null;
    
    //         // Validation supplémentaire pour les permanents
    //         if (!empty($grade)) {
    //             $selectedGrade = null;
    //             foreach ($allGrades as $g) {
    //                 if ($g->id_grade == $grade) {
    //                     $selectedGrade = $g;
    //                     break;
    //                 }
    //             }
    
    //             if ($selectedGrade) {
    //                 $isAdminGrade = strpos($selectedGrade->nom_grade, '_admin') !== false;
    //                 if ($administration == 1 && !$isAdminGrade) {
    //                     $this->errors[] = "Le grade sélectionné n'est pas un grade administratif";
    //                 } elseif ($administration == 0 && $isAdminGrade) {
    //                     $this->errors[] = "Vous avez sélectionné un grade administratif mais le statut administration est 'Non'";
    //                 }
    //             }
    //         }
    //     }
    
    //     // Upload des fichiers seulement si nécessaires
    //     $cv = null;
    //     $contrat = null;
        
    //     if ($statut === "NON_PERMANANT") {
    //         $cv = $this->upload_cv($files['cv'] ?? null);
    //         $contrat = $this->upload_contrat($files['contrat'] ?? null);
    //     }
    
    //     // Validation commune
    //     if (empty($nom)) $this->errors[] = "Le nom est obligatoire";
    //     if (empty($prenom)) $this->errors[] = "Le prénom est obligatoire";
    //     if (empty($date_naissance)) $this->errors[] = "La date de naissance est obligatoire";
    //     if (empty($email)) $this->errors[] = "L'email est obligatoire";
    //     if (empty($telephone)) $this->errors[] = "Le téléphone est obligatoire";
    //     if (empty($diplome)) $this->errors[] = "Le diplôme est obligatoire";
    //     if (empty($matricule)) $this->errors[] = "Le matricule est obligatoire";
    
    //     if ($statut === "NON_PERMANANT") {
    //         if (empty($cv)) $this->errors[] = "Le CV est obligatoire";
    //         if (empty($contrat)) $this->errors[] = "Le contrat est obligatoire";
    //         if (empty($code)) $this->errors[] = "Le code bancaire est obligatoire";
    //     } else {
    //         if (empty($grade)) $this->errors[] = "Le grade est obligatoire pour un permanent";
    //     }
    
    //     if (!empty($this->errors)) return false;
    
    //     $bdd = $this->connect();
        
    //     $sql = "INSERT INTO enseignants 
    //         (enseignant_statut, id_grade, enseignant_matricule, enseignant_nom, enseignant_prenom, 
    //         enseignant_date_naissance, enseignant_email, enseignant_telephone, enseignant_diplome, 
    //         enseignant_cv, contrat, code_bancaire, administration,id_departement) 
    //         VALUES 
    //         (:enseignant_statut, :id_grade, :enseignant_matricule, :enseignant_nom, :enseignant_prenom, 
    //         :enseignant_date_naissance, :enseignant_email, :enseignant_telephone, :enseignant_diplome, 
    //         :enseignant_cv, :contrat, :code_bancaire, :administration, :id_departement)";

    //     $stmt = $bdd->prepare($sql);
    
    //     $success = $stmt->execute([
    //         ":enseignant_statut" => $statut, // On conserve l'orthographe originale
    //         ":id_grade" => $grade,
    //         ":enseignant_matricule" => $matricule,
    //         ":enseignant_nom" => $nom,
    //         ":enseignant_prenom" => $prenom,
    //         ":enseignant_date_naissance" => $date_naissance,
    //         ":enseignant_email" => $email,
    //         ":enseignant_telephone" => $telephone,
    //         ":enseignant_diplome" => $diplome,
    //         ":enseignant_cv" => $cv,
    //         ":contrat" => $contrat,
    //         ":code_bancaire" => $code,
    //         ":administration" => $administration,
    //         ":id_departement" => $id_departement
    //     ]);
    
    //     if ($success) {
    //         $this->set_flash("Enseignant ajouté avec succès", 'primary');
    //         $this->redirect("Enseignants/liste_enseignant");
    //     } else {
    //         $errorInfo = $stmt->errorInfo();
    //         $this->errors[] = "Échec de l'ajout de l'enseignant: " . $errorInfo[2];
    //     }
    // }
    public function enregistrement($files = [], $post, $allGrades = [])
    {
        $files = $files ?? [];

        $statut = $post['statut'] ?? null;
        $grade = $post['grade'] ?? null;
        $matricule = $post['matricule'] ?? null;
        $nom = $post['nom'] ?? null;
        $prenom = $post['prenom'] ?? null;
        $date_naissance = $post['date_naissance'] ?? null;
        $email = $post['email'] ?? null;
        $telephone = $post['telephone'] ?? null;
        $diplome = $post['diplome'] ?? null;
        $code = $post['code'] ?? null;
        $administration = $post['administration'] ?? 0;
        $id_departement = $post['id_departement'] ?? null;

        // Validation commune
        if (empty($nom)) $this->errors[] = "Le nom est obligatoire";
        if (empty($prenom)) $this->errors[] = "Le prénom est obligatoire";
        if (empty($date_naissance)) $this->errors[] = "La date de naissance est obligatoire";
        if (empty($email)) $this->errors[] = "L'email est obligatoire";
        if (empty($telephone)) $this->errors[] = "Le téléphone est obligatoire";
        if (empty($diplome)) $this->errors[] = "Le diplôme est obligatoire";
        if (empty($matricule)) $this->errors[] = "Le matricule est obligatoire";

        // Validation fichiers et code bancaire
        if ($statut === "NON_PERMANANT") {
            $cv = $this->upload_cv($files['cv'] ?? null);
            $contrat = $this->upload_contrat($files['contrat'] ?? null);
            if (empty($cv)) $this->errors[] = "Le CV est obligatoire";
            if (empty($contrat)) $this->errors[] = "Le contrat est obligatoire";
            if (empty($code)) $this->errors[] = "Le code bancaire est obligatoire";
        } else {
            if (empty($grade)) $this->errors[] = "Le grade est obligatoire pour un permanent";
        }

        if (!empty($this->errors)) return false;

        $bdd = $this->connect();

        // Vérification unicité matricule + nom + prenom
        $checkSql = "SELECT COUNT(*) FROM enseignants WHERE enseignant_matricule = :matricule AND enseignant_nom = :nom AND enseignant_prenom = :prenom";
        $checkStmt = $bdd->prepare($checkSql);
        $checkStmt->execute([
            ":matricule" => $matricule,
            ":nom" => $nom,
            ":prenom" => $prenom
        ]);
        $exists = $checkStmt->fetchColumn();

        if ($exists) {
            $this->errors[] = "Un enseignant avec ce matricule, nom et prénom existe déjà.";
            return false;
        }

        // Insertion
        $sql = "INSERT INTO enseignants 
            (enseignant_statut, id_grade, enseignant_matricule, enseignant_nom, enseignant_prenom, 
            enseignant_date_naissance, enseignant_email, enseignant_telephone, enseignant_diplome, 
            enseignant_cv, contrat, code_bancaire, administration, id_departement) 
            VALUES 
            (:enseignant_statut, :id_grade, :enseignant_matricule, :enseignant_nom, :enseignant_prenom, 
            :enseignant_date_naissance, :enseignant_email, :enseignant_telephone, :enseignant_diplome, 
            :enseignant_cv, :contrat, :code_bancaire, :administration, :id_departement)";

        $stmt = $bdd->prepare($sql);

        $success = $stmt->execute([
            ":enseignant_statut" => $statut,
            ":id_grade" => $grade,
            ":enseignant_matricule" => $matricule,
            ":enseignant_nom" => $nom,
            ":enseignant_prenom" => $prenom,
            ":enseignant_date_naissance" => $date_naissance,
            ":enseignant_email" => $email,
            ":enseignant_telephone" => $telephone,
            ":enseignant_diplome" => $diplome,
            ":enseignant_cv" => $cv ?? null,
            ":contrat" => $contrat ?? null,
            ":code_bancaire" => $code,
            ":administration" => $administration,
            ":id_departement" => $id_departement
        ]);

        if ($success) {
            $this->set_flash("Enseignant ajouté avec succès", 'primary');
            $this->redirect("Enseignants/liste_enseignant");
        } else {
            $errorInfo = $stmt->errorInfo();
            $this->errors[] = "Échec de l'ajout de l'enseignant: " . $errorInfo[2];
        }
    }

        // Sélectionner les enseignants CDI
    // public function getEnseignantCDI()
    // {
    //     $select = "
    //         SELECT enseignants.*, grade.nom_grade 
    //         FROM enseignants
    //         JOIN grade ON grade.id_grade = enseignants.id_grade
    //         WHERE enseignants.enseignant_statut = :statut
    //     ";
    //     $execute_data = ['statut' => 'PERMANANT',];
    //     return $this->select_data_table_join_where($select, $execute_data);
    // }
         // Sélectionner les enseignants VCT
    // public function getEnseignantVCT()
    // {
    //     $select = "*";
    //     $fields = "enseignants";
    //     $whereValue = "enseignant_statut = :statut";
    //     $value = ['statut' => 'NON_PERMANANT'];
    //     return $this->FetchSelectWhere2($select, $fields, $whereValue, $value);
    // }
    // Sélectionner les enseignants CDI par département (optionnel)
    public function getEnseignantCDI($id_departement = null)
    {
        $select = "
            SELECT enseignants.*, grade.nom_grade 
            FROM enseignants
            JOIN grade ON grade.id_grade = enseignants.id_grade
            WHERE enseignants.enseignant_statut = :statut
        ";
        $execute_data = ['statut' => 'PERMANANT'];
    
        if ($id_departement !== null) {
            $select .= " AND enseignants.id_departement = :id_departement";
            $execute_data['id_departement'] = $id_departement;
        }
    
        return $this->select_data_table_join_where($select, $execute_data);
    } 
    // Sélectionner les enseignants VCT par département (optionnel)
    public function getEnseignantVCT($id_departement = null)
    {
        $select = "SELECT * FROM enseignants WHERE enseignant_statut = :statut";
        $value = ['statut' => 'NON_PERMANANT'];
    
        if ($id_departement !== null) {
            $select .= " AND id_departement = :id_departement";
            $value['id_departement'] = $id_departement;
        }
    
        return $this->select_data_table_join_where($select, $value);
    }
    public function modification($data)
    {
        // Validation des données
        if (isset($data['enseignant_statut']) && $data['enseignant_statut'] === 'PERMANANT') {
            if (empty($data['id_grade'])) {
                $this->errors[] = "Erreur : Le grade est obligatoire pour un enseignant permanent.";
                return false;
            }
        }

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
                enseignant_cv = :enseignant_cv,
                contrat = :contrat,
                code_bancaire = :code_bancaire
            WHERE enseignant_id = :id";

        $params = [
            ':enseignant_statut' => $data['enseignant_statut'],
            ':id_grade' => $data['id_grade'],
            ':enseignant_matricule' => $data['enseignant_matricule'],
            ':enseignant_nom' => $data['enseignant_nom'],
            ':enseignant_prenom' => $data['enseignant_prenom'],
            ':enseignant_date_naissance' => $data['enseignant_date_naissance'],
            ':enseignant_email' => $data['enseignant_email'],
            ':enseignant_telephone' => $data['enseignant_telephone'],
            ':enseignant_diplome' => $data['enseignant_diplome'],
            ':enseignant_cv' => $data['enseignant_cv'],
            ':contrat' => $data['contrat'],
            ':code_bancaire' => $data['code_bancaire'],
            ':id' => $data['id']
        ];

        $result = $this->insertion_update_simples($sql, $params);

        if ($result) {
            $this->set_flash("Enseignant mis à jour avec succès", 'success');
            $this->redirect("Enseignants/lsite_enseignant");
        } else {
            $this->errors[] = "Échec de la mise à jour";
        }

        return $result;
    }
    public function getPeriodes()
    {
        $query = "SELECT id_periode, date_debut, date_fin, status FROM periode";
        return $this->select_data_table_join_where($query);
    } 
    // public function getTotalHeuresParEnseignant($date_debut, $date_fin)
    // {
    //     $sql = "SELECT 
    //                 e.enseignant_id, 
    //                 SUM(edt.heure_total) AS total_heures
    //             FROM enseignants e
    //             JOIN enseignant_edt ee ON ee.id_enseignant = e.enseignant_id
    //             JOIN edt ON edt.id_edt = ee.id_edt
    //             WHERE edt.date_debut >= :date_debut 
    //             AND edt.date_fin <= :date_fin
    //             GROUP BY e.enseignant_id";

    //     $params = [
    //         'date_debut' => $date_debut,
    //         'date_fin' => $date_fin
    //     ];

    //     return $this->select_data_table_join_where($sql, $params);
    // }
    // public function getEnseignantsParPeriode($date_debut, $date_fin)
    // {
    //     $sql = "SELECT 
    //                 e.enseignant_id, 
    //                 e.enseignant_nom, 
    //                 e.enseignant_prenom,
    //                 SUM(edt.heure_total) AS total_heures
    //             FROM enseignants e
    //             JOIN enseignant_edt ee ON ee.id_enseignant = e.enseignant_id
    //             JOIN edt ON edt.id_edt = ee.id_edt
    //             WHERE edt.date_debut >= :date_debut 
    //             AND edt.date_fin <= :date_fin
    //             GROUP BY e.enseignant_id, e.enseignant_nom, e.enseignant_prenom";

    //     $params = [
    //         'date_debut' => $date_debut,
    //         'date_fin' => $date_fin
    //     ];

    //     return $this->select_data_table_join_where($sql, $params);
    // }

    // public function getEDTIndividuelsParPeriode($date_debut, $date_fin, $periode_id, $enseignant_id = null)
    // {
    //     $sql = "SELECT 
    //         e.enseignant_id, 
    //         e.enseignant_nom, 
    //         e.enseignant_prenom, 
    //         e.enseignant_statut,
    //         filiere.sigle_filiere AS sigle_filiere,
    //         module.nom_module AS modules,
    //         salle.nom_salle AS salle,
    //         CONCAT(filiere.sigle_filiere, '-', semestre.sigle_semestre, '(', promotion.annee_universitaire, ')') AS classe,
    //         edt.heure_total AS heures_total,
    //         CASE 
    //             WHEN e.enseignant_statut = 'PERMANANT' THEN grade.heures_dues
    //             ELSE 0
    //         END AS heures_dues,
    //         edt.date_debut
    //     FROM enseignants e
    //     JOIN enseignant_edt ee ON ee.id_enseignant = e.enseignant_id
    //     JOIN edt ON ee.id_edt = edt.id_edt
    //     LEFT JOIN filiere ON edt.id_filiere = filiere.id_filiere
    //     LEFT JOIN promotion ON edt.id_promotion = promotion.id_promotion
    //     LEFT JOIN parcours ON promotion.id_parcours = parcours.id_parcours
    //     LEFT JOIN semestre ON parcours.id_semestre = semestre.id_semestre
    //     LEFT JOIN ue_module ON edt.id_module = ue_module.id_ue_module
    //     LEFT JOIN module ON ue_module.id_module = module.id_module
    //     LEFT JOIN salle ON edt.id_salle = salle.id_salle
    //     LEFT JOIN grade ON e.id_grade = grade.id_grade
    //     WHERE edt.id_periode = :periode_id 
    //     AND edt.date_debut >= :date_debut 
    //     AND edt.date_fin <= :date_fin";

    //     $params = [
    //         'periode_id' => $periode_id,
    //         'date_debut' => $date_debut,
    //         'date_fin' => $date_fin
    //     ];

    //     if ($enseignant_id) {
    //         $sql .= " AND e.enseignant_id = :enseignant_id";
    //         $params['enseignant_id'] = $enseignant_id;
    //     }

    //     $sql .= " ORDER BY e.enseignant_id, edt.date_debut ASC";
    //     return $this->select_data_table_join_where($sql, $params);
    // }
    // public function getEmploiDuTempsByEnseignant($id, $date_debut, $date_fin, $periode_id)
    // {
    //     $query = "
    //     SELECT 
    //         edt.id_edt, edt.date_creation, edt.date_debut, edt.date_fin, edt.heure_total, edt.statut,
    //         ue_module.id_ue_module, ue_module.id_ue, ue_module.id_module, ue_module.code_module, ue_module.coeficient, ue_module.cm, ue_module.td, ue_module.tp, ue_module.tpe,
    //         module.nom_module, module.sigle_module,
    //         salle.nom_salle, salle.capacite_salle,
    //         filiere.nom_filiere, filiere.sigle_filiere,
    //         parcours.nom_parcours,
    //         promotion.id_promotion, promotion.annee_universitaire, promotion.statut AS promotion_statut, promotion.id_filiere, promotion.id_parcours,
    //         semestre.nom_semestre, semestre.sigle_semestre,
    //         enseignants.enseignant_nom, enseignants.enseignant_prenom, enseignants.enseignant_date_naissance, enseignants.enseignant_telephone, 
    //         enseignants.enseignant_diplome, enseignants.enseignant_email, enseignants.enseignant_statut,
    //         CASE 
    //             WHEN enseignants.id_grade IS NULL AND enseignants.enseignant_statut = 'NON_PERMANANT' AND enseignants.enseignant_diplome LIKE '%master%' THEN 'Assistant'
    //             WHEN enseignants.id_grade IS NULL AND enseignants.enseignant_statut = 'NON_PERMANANT' AND enseignants.enseignant_diplome LIKE '%doctorat%' THEN 'Maître Assistant'
    //             ELSE grade.nom_grade 
    //         END AS nom_grade,
    //         CASE 
    //             WHEN enseignants.id_grade IS NULL AND enseignants.enseignant_statut = 'NON_PERMANANT' AND enseignants.enseignant_diplome LIKE '%master%' THEN 0
    //             WHEN enseignants.id_grade IS NULL AND enseignants.enseignant_statut = 'NON_PERMANANT' AND enseignants.enseignant_diplome LIKE '%doctorat%' THEN 0
    //             ELSE grade.heures_dues 
    //         END AS heures_dues
    //     FROM 
    //         edt
    //     JOIN enseignant_edt ee ON edt.id_edt = ee.id_edt
    //     LEFT JOIN enseignants ON ee.id_enseignant = enseignants.enseignant_id
    //     LEFT JOIN ue_module ON edt.id_module = ue_module.id_ue_module
    //     LEFT JOIN module ON ue_module.id_module = module.id_module
    //     LEFT JOIN salle ON edt.id_salle = salle.id_salle
    //     LEFT JOIN filiere ON edt.id_filiere = filiere.id_filiere
    //     LEFT JOIN promotion ON edt.id_promotion = promotion.id_promotion
    //     LEFT JOIN parcours ON promotion.id_parcours = parcours.id_parcours
    //     LEFT JOIN semestre ON parcours.id_semestre = semestre.id_semestre
    //     LEFT JOIN grade ON enseignants.id_grade = grade.id_grade
    //     LEFT JOIN periode ON edt.id_periode = periode.id_periode
    //     WHERE 
    //         ee.id_enseignant = :id AND 
    //         edt.date_debut >= :date_debut AND 
    //         edt.date_fin <= :date_fin AND 
    //         edt.id_periode = :periode_id"; 

    //     $query .= " ORDER BY edt.date_debut ASC";

    //     $params = [
    //         "id" => $id,
    //         "date_debut" => $date_debut,
    //         "date_fin" => $date_fin,
    //         "periode_id" => $periode_id  
    //     ];

    //     return $this->select_data_table_join_where($query, $params);
    // }

    // public function getEmploiDuTempsByEnseignantRecap($id, $date_debut, $date_fin, $periode_id)
    // {
    //     $query = "
    //         SELECT  
    //             edt.id_edt, edt.date_creation, edt.date_debut, edt.date_fin, edt.heure_total, edt.statut,

    //             -- Infos UE/Module
    //             ue_module.id_ue_module, ue_module.id_ue, ue_module.id_module AS module_id, 
    //             ue_module.code_module, ue_module.coeficient, ue_module.cm, ue_module.td, ue_module.tp, ue_module.tpe,
    //             module.nom_module, module.sigle_module,

    //             -- Infos salle
    //             salle.nom_salle, salle.capacite_salle,

    //             -- Infos filière/parcours/promotion/semestre
    //             filiere.nom_filiere, filiere.sigle_filiere,
    //             parcours.nom_parcours,
    //             promotion.id_promotion, promotion.annee_universitaire, promotion.statut AS promotion_statut,
    //             promotion.id_filiere, promotion.id_parcours,
    //             semestre.nom_semestre, semestre.sigle_semestre,

    //             -- Infos enseignant
    //             enseignants.enseignant_id,
    //             enseignants.enseignant_matricule,
    //             enseignants.enseignant_nom, enseignants.enseignant_prenom, enseignants.enseignant_date_naissance, 
    //             enseignants.enseignant_telephone, enseignants.enseignant_diplome, 
    //             enseignants.enseignant_email, enseignants.enseignant_statut,

    //             -- Infos utilisateur
    //             utilisateur.role, 

    //             -- Nom du grade calculé selon statut/diplôme
    //             CASE  
    //                 WHEN enseignants.id_grade IS NULL AND enseignants.enseignant_statut = 'NON_PERMANANT' AND enseignants.enseignant_diplome LIKE '%master%' 
    //                     THEN 'Assistant'  
    //                 WHEN enseignants.id_grade IS NULL AND enseignants.enseignant_statut = 'NON_PERMANANT' AND enseignants.enseignant_diplome LIKE '%doctorat%' 
    //                     THEN 'Maître Assistant'  
    //                 ELSE grade.nom_grade  
    //             END AS nom_grade,

    //             -- Heures dues corrigées selon règles
    //             CASE  
    //                 WHEN enseignants.id_grade IS NULL AND enseignants.enseignant_statut = 'NON_PERMANANT' AND enseignants.enseignant_diplome LIKE '%master%' 
    //                     THEN 0  
    //                 WHEN enseignants.id_grade IS NULL AND enseignants.enseignant_statut = 'NON_PERMANANT' AND enseignants.enseignant_diplome LIKE '%doctorat%' 
    //                     THEN 0  
    //                 WHEN enseignants.enseignant_statut = 'PERMANANT' 
    //                     AND (
    //                         grade.nom_grade LIKE '%_admin' 
    //                         OR utilisateur.role LIKE 'admin%' 
    //                         OR utilisateur.role IN ('DG', 'DGA', 'Sécretaire principale', 'Chef DR')
    //                     )
    //                     THEN 56
    //                 ELSE grade.heures_dues  
    //             END AS heures_dues,

    //             -- Modules enseignés sous forme de liste
    //             (
    //                 SELECT GROUP_CONCAT(m.nom_module SEPARATOR ', ') 
    //                 FROM edt e
    //                 JOIN enseignant_edt ee2 ON e.id_edt = ee2.id_edt
    //                 JOIN ue_module um ON e.id_module = um.id_ue_module
    //                 JOIN module m ON um.id_module = m.id_module
    //                 WHERE ee2.id_enseignant = enseignants.enseignant_id
    //             ) AS emplois_du_temps

    //         FROM edt  
    //         JOIN enseignant_edt ee ON edt.id_edt = ee.id_edt
    //         LEFT JOIN ue_module ON edt.id_module = ue_module.id_ue_module  
    //         LEFT JOIN module ON ue_module.id_module = module.id_module  
    //         LEFT JOIN salle ON edt.id_salle = salle.id_salle  
    //         LEFT JOIN filiere ON edt.id_filiere = filiere.id_filiere  
    //         LEFT JOIN promotion ON edt.id_promotion = promotion.id_promotion  
    //         LEFT JOIN parcours ON promotion.id_parcours = parcours.id_parcours  
    //         LEFT JOIN semestre ON edt.id_semestre = semestre.id_semestre  
    //         LEFT JOIN enseignants ON ee.id_enseignant = enseignants.enseignant_id  
    //         LEFT JOIN grade ON enseignants.id_grade = grade.id_grade  
    //         LEFT JOIN periode ON edt.id_periode = periode.id_periode  
    //         LEFT JOIN utilisateur ON enseignants.enseignant_id = utilisateur.enseignant_id  

    //         WHERE  
    //             ee.id_enseignant = :id AND  
    //             edt.date_debut >= :date_debut AND  
    //             edt.date_fin <= :date_fin AND  
    //             edt.id_periode = :periode_id

    //         ORDER BY edt.date_debut ASC
    //     ";

    //     $params = [
    //         "id" => $id,
    //         "date_debut" => $date_debut,
    //         "date_fin" => $date_fin,
    //         "periode_id" => $periode_id
    //     ];

    //     return $this->select_data_table_join_where($query, $params);
    // }

    public function getTotalHeuresParEnseignant($date_debut, $date_fin)
    {
        $sql = "SELECT 
                    e.enseignant_id, 
                    SUM(ee.nombre_heure) AS total_heures
                FROM enseignants e
                JOIN enseignant_edt ee ON ee.id_enseignant = e.enseignant_id
                JOIN edt ON edt.id_edt = ee.id_edt
                WHERE edt.date_debut >= :date_debut 
                AND edt.date_fin <= :date_fin
                GROUP BY e.enseignant_id";

        $params = [
            'date_debut' => $date_debut,
            'date_fin' => $date_fin
        ];

        return $this->select_data_table_join_where($sql, $params);
    }

    public function getEnseignantsParPeriode($date_debut, $date_fin)
    {
        $sql = "SELECT 
                    e.enseignant_id, 
                    e.enseignant_nom, 
                    e.enseignant_prenom,
                    SUM(ee.nombre_heure) AS total_heures
                FROM enseignants e
                JOIN enseignant_edt ee ON ee.id_enseignant = e.enseignant_id
                JOIN edt ON edt.id_edt = ee.id_edt
                WHERE edt.date_debut >= :date_debut 
                AND edt.date_fin <= :date_fin
                GROUP BY e.enseignant_id, e.enseignant_nom, e.enseignant_prenom";

        $params = [
            'date_debut' => $date_debut,
            'date_fin' => $date_fin
        ];

        return $this->select_data_table_join_where($sql, $params);
    }

    public function getEDTIndividuelsParPeriode($date_debut, $date_fin, $periode_id, $enseignant_id = null)
    {
        $sql = "SELECT 
            e.enseignant_id, 
            e.enseignant_nom, 
            e.enseignant_prenom, 
            e.enseignant_statut,
            filiere.sigle_filiere AS sigle_filiere,
            module.nom_module AS modules,
            salle.nom_salle AS salle,
            CONCAT(filiere.sigle_filiere, '-', semestre.sigle_semestre, '(', promotion.annee_universitaire, ')') AS classe,
            SUM(ee.nombre_heure) AS heures_total,
            CASE 
                WHEN e.enseignant_statut = 'PERMANANT' THEN grade.heures_dues
                ELSE 0
            END AS heures_dues,
            edt.date_debut
        FROM enseignants e
        JOIN enseignant_edt ee ON ee.id_enseignant = e.enseignant_id
        JOIN edt ON ee.id_edt = edt.id_edt
        LEFT JOIN filiere ON edt.id_filiere = filiere.id_filiere
        LEFT JOIN promotion ON edt.id_promotion = promotion.id_promotion
        LEFT JOIN parcours ON promotion.id_parcours = parcours.id_parcours
        LEFT JOIN semestre ON parcours.id_semestre = semestre.id_semestre
        LEFT JOIN ue_module ON edt.id_module = ue_module.id_ue_module
        LEFT JOIN module ON ue_module.id_module = module.id_module
        LEFT JOIN salle ON edt.id_salle = salle.id_salle
        LEFT JOIN grade ON e.id_grade = grade.id_grade
        WHERE edt.id_periode = :periode_id 
        AND edt.date_debut >= :date_debut 
        AND edt.date_fin <= :date_fin";

        $params = [
            'periode_id' => $periode_id,
            'date_debut' => $date_debut,
            'date_fin' => $date_fin
        ];

        if ($enseignant_id) {
            $sql .= " AND e.enseignant_id = :enseignant_id";
            $params['enseignant_id'] = $enseignant_id;
        }

        $sql .= " GROUP BY e.enseignant_id, e.enseignant_nom, e.enseignant_prenom, e.enseignant_statut, 
                filiere.sigle_filiere, module.nom_module, salle.nom_salle, classe, grade.heures_dues, edt.date_debut";
        $sql .= " ORDER BY e.enseignant_id, edt.date_debut ASC";
        return $this->select_data_table_join_where($sql, $params);
    }

    public function getEmploiDuTempsByEnseignant($id, $date_debut, $date_fin, $periode_id)
    {
        $query = "
        SELECT 
            edt.id_edt, edt.date_creation, edt.date_debut, edt.date_fin, ee.nombre_heure AS heure_total, edt.statut,
            ue_module.id_ue_module, ue_module.id_ue, ue_module.id_module, ue_module.code_module, ue_module.coeficient, ue_module.cm, ue_module.td, ue_module.tp, ue_module.tpe,
            module.nom_module, module.sigle_module,
            salle.nom_salle, salle.capacite_salle,
            filiere.nom_filiere, filiere.sigle_filiere,
            parcours.nom_parcours,
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
        JOIN enseignant_edt ee ON edt.id_edt = ee.id_edt
        LEFT JOIN enseignants ON ee.id_enseignant = enseignants.enseignant_id
        LEFT JOIN ue_module ON edt.id_module = ue_module.id_ue_module
        LEFT JOIN module ON ue_module.id_module = module.id_module
        LEFT JOIN salle ON edt.id_salle = salle.id_salle
        LEFT JOIN filiere ON edt.id_filiere = filiere.id_filiere
        LEFT JOIN promotion ON edt.id_promotion = promotion.id_promotion
        LEFT JOIN parcours ON promotion.id_parcours = parcours.id_parcours
        LEFT JOIN semestre ON parcours.id_semestre = semestre.id_semestre
        LEFT JOIN grade ON enseignants.id_grade = grade.id_grade
        LEFT JOIN periode ON edt.id_periode = periode.id_periode
        WHERE 
            ee.id_enseignant = :id AND 
            edt.date_debut >= :date_debut AND 
            edt.date_fin <= :date_fin AND 
            edt.id_periode = :periode_id"; 

        $query .= " ORDER BY edt.date_debut ASC";

        $params = [
            "id" => $id,
            "date_debut" => $date_debut,
            "date_fin" => $date_fin,
            "periode_id" => $periode_id  
        ];

        return $this->select_data_table_join_where($query, $params);
    }

    public function getEmploiDuTempsByEnseignantRecap($id, $date_debut, $date_fin, $periode_id)
    {
        $query = "
            SELECT  
                edt.id_edt, edt.date_creation, edt.date_debut, edt.date_fin, ee.nombre_heure AS heure_total, edt.statut,

                -- Infos UE/Module
                ue_module.id_ue_module, ue_module.id_ue, ue_module.id_module AS module_id, 
                ue_module.code_module, ue_module.coeficient, ue_module.cm, ue_module.td, ue_module.tp, ue_module.tpe,
                module.nom_module, module.sigle_module,

                -- Infos salle
                salle.nom_salle, salle.capacite_salle,

                -- Infos filière/parcours/promotion/semestre
                filiere.nom_filiere, filiere.sigle_filiere,
                parcours.nom_parcours,
                promotion.id_promotion, promotion.annee_universitaire, promotion.statut AS promotion_statut,
                promotion.id_filiere, promotion.id_parcours,
                semestre.nom_semestre, semestre.sigle_semestre,

                -- Infos enseignant
                enseignants.enseignant_id,
                enseignants.enseignant_matricule,
                enseignants.enseignant_nom, enseignants.enseignant_prenom, enseignants.enseignant_date_naissance, 
                enseignants.enseignant_telephone, enseignants.enseignant_diplome, 
                enseignants.enseignant_email, enseignants.enseignant_statut,

                -- Infos utilisateur
                utilisateur.role, 

                -- Nom du grade calculé selon statut/diplôme
                CASE  
                    WHEN enseignants.id_grade IS NULL AND enseignants.enseignant_statut = 'NON_PERMANANT' AND enseignants.enseignant_diplome LIKE '%master%' 
                        THEN 'Assistant'  
                    WHEN enseignants.id_grade IS NULL AND enseignants.enseignant_statut = 'NON_PERMANANT' AND enseignants.enseignant_diplome LIKE '%doctorat%' 
                        THEN 'Maître Assistant'  
                    ELSE grade.nom_grade  
                END AS nom_grade,

                -- Heures dues corrigées selon règles
                CASE  
                    WHEN enseignants.id_grade IS NULL AND enseignants.enseignant_statut = 'NON_PERMANANT' AND enseignants.enseignant_diplome LIKE '%master%' 
                        THEN 0  
                    WHEN enseignants.id_grade IS NULL AND enseignants.enseignant_statut = 'NON_PERMANANT' AND enseignants.enseignant_diplome LIKE '%doctorat%' 
                        THEN 0  
                    WHEN enseignants.enseignant_statut = 'PERMANANT' 
                        AND (
                            grade.nom_grade LIKE '%_admin' 
                            OR utilisateur.role LIKE 'admin%' 
                            OR utilisateur.role IN ('DG', 'DGA', 'Sécretaire principale', 'Chef DR')
                        )
                        THEN 56
                    ELSE grade.heures_dues  
                END AS heures_dues,

                -- Modules enseignés sous forme de liste
                (
                    SELECT GROUP_CONCAT(m.nom_module SEPARATOR ', ') 
                    FROM edt e
                    JOIN enseignant_edt ee2 ON e.id_edt = ee2.id_edt
                    JOIN ue_module um ON e.id_module = um.id_ue_module
                    JOIN module m ON um.id_module = m.id_module
                    WHERE ee2.id_enseignant = enseignants.enseignant_id
                ) AS emplois_du_temps

            FROM edt  
            JOIN enseignant_edt ee ON edt.id_edt = ee.id_edt
            LEFT JOIN ue_module ON edt.id_module = ue_module.id_ue_module  
            LEFT JOIN module ON ue_module.id_module = module.id_module  
            LEFT JOIN salle ON edt.id_salle = salle.id_salle  
            LEFT JOIN filiere ON edt.id_filiere = filiere.id_filiere  
            LEFT JOIN promotion ON edt.id_promotion = promotion.id_promotion  
            LEFT JOIN parcours ON promotion.id_parcours = parcours.id_parcours  
            LEFT JOIN semestre ON edt.id_semestre = semestre.id_semestre  
            LEFT JOIN enseignants ON ee.id_enseignant = enseignants.enseignant_id  
            LEFT JOIN grade ON enseignants.id_grade = grade.id_grade  
            LEFT JOIN periode ON edt.id_periode = periode.id_periode  
            LEFT JOIN utilisateur ON enseignants.enseignant_id = utilisateur.enseignant_id  

            WHERE  
                ee.id_enseignant = :id AND  
                edt.date_debut >= :date_debut AND  
                edt.date_fin <= :date_fin AND  
                edt.id_periode = :periode_id

            ORDER BY edt.date_debut ASC
        ";

        $params = [
            "id" => $id,
            "date_debut" => $date_debut,
            "date_fin" => $date_fin,
            "periode_id" => $periode_id
        ];

        return $this->select_data_table_join_where($query, $params);
    }




}
