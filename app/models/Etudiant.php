
<?php
class Etudiant  extends Model{
    public function __construct() {
        $this->pdo = $this->bdd(); // Utilisez bdd() pour obtenir la connexion PDO
    }
    public $errors = [];

    public function upload_cv($file)
    {
        $default_image = '/profile/guem.png'; 
        $taillemax = 2067152; 
        $extensions_valides = ['gif', 'png', 'jpg', 'jpeg'];

        // Vérifiez si le fichier est passé et n'a pas d'erreurs
        if (isset($file['name']) && $file['error'] == 0) {
            $file_name = basename($file['name']);
            $file_tmp = $file['tmp_name'];
            $file_size = $file['size'];
            $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            if ($file_size <= $taillemax) {
                if (in_array($file_extension, $extensions_valides)) {
                    $destination = 'C:/xampp/htdocs/G_universite/public/profile/' . $file_name;
                    if (move_uploaded_file($file_tmp, $destination)) {
                        return '/profile/' . $file_name; // Chemin relatif enregistré dans la base
                    } else {
                        $this->errors[] = "Erreur lors du déplacement du fichier.";
                    }
                } else {
                    $this->errors[] = "Extension de fichier non valide. Extensions autorisées : .gif, .png, .jpg.";
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

    public function enregistrementEtudiantAvecPaiement($post, $file) {
        $errors = [];
        extract($post);
    
        // Validation des champs obligatoires
        if (empty($nom_prenom_etudiant)) {
            $errors[] = "Le nom et prénom de l'étudiant est requis.";
        }
        if (empty($date_naissance_etudiant)) {
            $errors[] = "La date de naissance est requise.";
        }
        if (!filter_var($contact_etudiant, FILTER_VALIDATE_INT)) {
            $errors[] = "Le numéro de contact est invalide.";
        }
    
        // Upload du CV
        $profilname = $this->upload_cv($file);
        if (!$profilname) {
            $errors[] = "Erreur lors de l'upload du CV.";
        }
    
        if (!empty($errors)) {
            $this->set_flash(implode('<br>', $errors), 'danger');
            return false;
        }
    
        // Début de la transaction pour garantir atomicité
        $this->pdo->beginTransaction();
    
        try {
            // Insertion des données de l'étudiant
            $insertionEtudiant = $this->insertion_update_simples(
                'INSERT INTO etudiant(nom_prenom_etudiant, date_naissance_etudiant, lieu_naissance_etudiant, genre_etudiant, matricule_etudiant, contact_etudiant, diplome, id_statut, id_filiere, id_promotion, numetudiant, prenompere, prenomnommere, cercleNais, commNais, nationnalite, anneediplome, serie, pays, academie, numplace, profilname, total_frais) 
                VALUES(:nom_prenom_etudiant, :date_naissance_etudiant, :lieu_naissance_etudiant, :genre_etudiant, :matricule_etudiant, :contact_etudiant, :diplome, :id_statut, :id_filiere, :id_promotion, :numetudiant, :prenompere, :prenomnommere, :cercleNais, :commNais, :nationnalite, :anneediplome, :serie, :pays, :academie, :numplace, :profilname, :total_frais)',
                [
                    ':nom_prenom_etudiant' => $nom_prenom_etudiant,
                    ':date_naissance_etudiant' => $date_naissance_etudiant,
                    ':lieu_naissance_etudiant' => $lieu_naissance_etudiant,
                    ':genre_etudiant' => $genre_etudiant,
                    ':matricule_etudiant' => $matricule_etudiant,
                    ':contact_etudiant' => $contact_etudiant,
                    ':diplome' => $diplome,
                    ':id_statut' => $id_statut,
                    ':id_filiere' => $id_filiere,
                    ':id_promotion' => $id_promotion,
                    ':numetudiant' => $numetudiant,
                    ':prenompere' => $prenompere,
                    ':prenomnommere' => $prenomnommere,
                    ':cercleNais' => $cercleNais,
                    ':commNais' => $commNais,
                    ':nationnalite' => $nationnalite,
                    ':anneediplome' => $anneediplome,
                    ':serie' => $serie,
                    ':pays' => $pays,
                    ':academie' => $academie,
                    ':numplace' => $numplace,
                    ':profilname' => $profilname,
                    ':total_frais' => $total_frais
                ]
            );
        
            if (!$insertionEtudiant) {
                throw new Exception('Erreur lors de l\'ajout de l\'étudiant.');
            }
        
            if ($insertionEtudiant) {
                $idEtudt = $this->pdo->lastInsertId();
               
           
            
            // Si le formulaire de paiement est soumis
            if (isset($_POST['montant_paye'])) {
                $montantPaye = $_POST['montant_paye'];
        
                // Validation du montant
                if (!is_numeric($montantPaye) || $montantPaye <= 0) {
                    throw new Exception('Le montant payé doit être valide et supérieur à zéro.');
                }
        
                // Récupérer les frais totaux de l'étudiant
                $requeteFrais = $this->pdo->prepare('SELECT total_frais FROM etudiant WHERE id_etudiant = :idEtudt');
                $requeteFrais->execute([':idEtudt' => $idEtudt]);
                $etudiant = $requeteFrais->fetch();
        
                if (!$etudiant) {
                    throw new Exception('Étudiant introuvable.');
                }
        
                $totalFrais = $etudiant['total_frais'];
        
                if ($montantPaye > $totalFrais) {
                    throw new Exception('Le montant payé ne peut pas dépasser les frais totaux.');
                }
        
                // Vérification si un paiement existe déjà
                $requetePaiement = $this->pdo->prepare('SELECT montant_paye FROM payement WHERE idEtudt = :idEtudt');
                $requetePaiement->execute([':idEtudt' => $idEtudt]);
                $paiement = $requetePaiement->fetch();
        
                if ($paiement) {
                    // Mise à jour du montant payé
                    $nouveauMontant = $paiement['montant_paye'] + $montantPaye;
                    $this->insertion_update_simples(
                        'UPDATE payement SET montant_paye = :montant_paye, date = :date WHERE idEtudt = :idEtudt',
                        [
                            ':montant_paye' => $nouveauMontant,
                            ':date' => date('Y-m-d'),
                            ':idEtudt' => $idEtudt
                        ]
                    );
                } else {
                    // Insertion d'un nouveau paiement
                    $this->insertion_update_simples(
                        'INSERT INTO payement(montant_paye, idEtudt, annee, date) 
                        VALUES(:montant_paye, :idEtudt, :annee, :date)',
                        [
                            ':montant_paye' => $montantPaye,
                            ':idEtudt' => $idEtudt,
                            ':annee' => date('Y'),
                            ':date' => date('Y-m-d H:i:s')

                        ]
                    );
                }
        
                echo 'Paiement enregistré avec succès.';
            }
        }
            // Validation de la transaction
            $this->pdo->commit();
            $this->set_flash('Étudiant et paiement ajoutés avec succès.', 'primary');
            return true;
        } catch (Exception $e) {
            // Annulation de la transaction en cas d'erreur
            $this->pdo->rollBack();
            $this->set_flash($e->getMessage(), 'danger');
            return false;
        }
        
        
        
    }
     public function enregistrementPaiement($post) {
        $errors = [];
        extract($post);

    
        if (!empty($errors)) {
            $this->set_flash(implode('<br>', $errors), 'danger');
            return false;
        }
    
        // Début de la transaction pour garantir atomicité
        $this->pdo->beginTransaction();
    
        try {
           
           // Si le formulaire de paiement est soumis
            if (isset($_POST['montant_paye'])) {
                $montantPaye = $_POST['montant_paye'];
        
                // Validation du montant
                if (!is_numeric($montantPaye) || $montantPaye <= 0) {
                    throw new Exception('Le montant payé doit être valide et supérieur à zéro.');
                }
        
                // Récupérer les frais totaux de l'étudiant
                $requeteFrais = $this->pdo->prepare('SELECT total_frais FROM etudiant WHERE id_etudiant = :idEtudt');
                $requeteFrais->execute([':idEtudt' => $idEtudt]);
                $etudiant = $requeteFrais->fetch();
        
                if (!$etudiant) {
                    throw new Exception('Étudiant introuvable.');
                }
        
                $totalFrais = $etudiant['total_frais'];
        
                if ($montantPaye > $totalFrais) {
                    throw new Exception('Le montant payé ne peut pas dépasser les frais totaux.');
                }
        
                // Vérification si un paiement existe déjà
                $requetePaiement = $this->pdo->prepare('SELECT montant_paye FROM payement WHERE idEtudt = :idEtudt');
                $requetePaiement->execute([':idEtudt' => $idEtudt]);
                $paiement = $requetePaiement->fetch();
        
                if ($paiement) {
                    // Mise à jour du montant payé
                    $nouveauMontant = $paiement['montant_paye'] + $montantPaye;
                    $this->insertion_update_simples(
                        'UPDATE payement SET montant_paye = :montant_paye, date = :date WHERE idEtudt = :idEtudt',
                        [
                            ':montant_paye' => $nouveauMontant,
                            ':date' => date('Y-m-d'),
                            ':idEtudt' => $idEtudt
                        ]
                    );
                } else {
                    // Insertion d'un nouveau paiement
                    $this->insertion_update_simples(
                        'INSERT INTO payement(montant_paye, idEtudt, annee, date) 
                        VALUES(:montant_paye, :idEtudt, :annee, :date)',
                        [
                            ':montant_paye' => $montantPaye,
                            ':idEtudt' => $idEtudt,
                            ':annee' => date('Y'),
                            ':date' => date('Y-m-d H:i:s')

                        ]
                    );
                }
        
                echo 'Paiement enregistré avec succès.';
            }
        
            // Validation de la transaction
            $this->pdo->commit();
            $this->set_flash('Étudiant et paiement ajoutés avec succès.', 'primary');
            return true;
        } catch (Exception $e) {
            // Annulation de la transaction en cas d'erreur
            $this->pdo->rollBack();
            $this->set_flash($e->getMessage(), 'danger');
            return false;
        }
        
        
        
    }
    public function trie_liste_etudiant($id_promotion){
        $query = "SELECT *
                  FROM etudiant
                  INNER JOIN filiere ON etudiant.id_filiere = filiere.id_filiere
                  
                  WHERE etudiant.id_promotion = :id_promotion";
        $bdd=$this->bdd();
        $stmt = $bdd->prepare($query);
        $stmt->bindParam(':id_promotion', $id_promotion, PDO::PARAM_INT);
        $stmt->execute();

        $info_etudiant = $stmt->fetchAll(PDO::FETCH_OBJ);
        return $info_etudiant;

    }
        // Récupérer les informations d'un étudiant par ID
        public function getById($id)
        {
            $stmt = $this->pdo->prepare("SELECT * FROM etudiant WHERE id_etudiant = :id");
            $stmt->execute([':id' => $id]);
            return $stmt->fetch();
        }
    
        // Récupérer l'historique des paiements pour un étudiant donné
        public function getPaymentsByStudentId($id)
        {
            $stmt = $this->pdo->prepare("SELECT * FROM payement WHERE idEtudt = :id ORDER BY date DESC");
            $stmt->execute([':id' => $id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    // Méthode pour ajouter un paiement
public function ajouterPaiement() {
    // Vérifiez si le formulaire est soumis
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Récupération des valeurs du formulaire
        $id_etudiant = $_POST['id_etudiant'];
        $montant_paye = $_POST['montant_paye'];

        // Récupération du total payé existant
        $totalPayéActuel = $this->getTotalPayé($id_etudiant); // Fonction à définir pour obtenir le total payé actuel

        // Mise à jour du paiement dans la table `payement`
        $this->updatePaiement($id_etudiant, $montant_paye);

        // Rediriger vers une autre page après la mise à jour (par exemple, une page de confirmation ou le formulaire de paiement)
       // header("Location: /etudiants"); // Remplacez par l'URL de destination souhaitée
       $this->set_flash('paiement ajoutés avec succès.', 'primary');
        exit();
    }
}

// Fonction pour récupérer le total payé existant pour un étudiant
private function getTotalPayé($id_etudiant) {
    // Effectuer la requête pour obtenir le total des paiements pour cet étudiant
    $stmt = $this->pdo->prepare("SELECT SUM(montant_paye) as total_payé FROM payement WHERE idEtudt = ?");
    $stmt->execute([$id_etudiant]);
    $result = $stmt->fetch();
    return $result['total_payé'] ? $result['total_payé'] : 0;
}

// Fonction pour mettre à jour la table `payement` avec le paiement ajouté
private function updatePaiement($id_etudiant, $montant_paye) {
    // Insertion du paiement dans la table `payement`
    $stmt = $this->pdo->prepare("INSERT INTO payement (idEtudt, montant_paye, date) VALUES (?, ?, NOW())");
    $stmt->execute([$id_etudiant, $montant_paye]);
}

public function getStudentsByFiliereAndPromotion($promotionId) {
    $pdo = $this->bdd();
    $stmt = $pdo->prepare("SELECT * FROM etudiant WHERE id_promotion = $promotionId ");
    try {
        $stmt->execute([$promotionId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo 'Erreur : ' . $e->getMessage();
    }
}

}
    

