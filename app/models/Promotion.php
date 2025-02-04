<?php

/*class Note extends Model {
    
    // Récupérer les étudiants selon la promotion et le module
 
/*
        // 🔹 Récupérer les étudiants selon la promotion et le module
        public function getStudentsByPromotionAndModule($promotionId, $moduleId) {
            $pdo = $this->bdd();
            $stmt = $pdo->prepare("SELECT * FROM etudiant WHERE promotion_id = ? AND module_id = ?");
            $stmt->execute([$promotionId, $moduleId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    
        // 🔹 Enregistrer une note pour un étudiant
        public function saveNote($etudiant_id, $devoir, $evaluation, $note_session = null) {
            $pdo = $this->bdd();
            
            // Calcul de la moyenne : si session de rattrapage, seule la note session est prise en compte
            if ($note_session !== null) {
                $moyenne = $note_session;
            } else {
                $moyenne = ($devoir + $evaluation) / 2;
            }
    
            // Insertion de la note
            $sql = "INSERT INTO notes (id_etudiant, note_devoir, note_evaluation, note_session, moyenne) 
                    VALUES (?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([$etudiant_id, $devoir, $evaluation, $note_session, $moyenne]);
        }
    
        // 🔹 Récupérer la liste des filières
        public function getAllFiliere() {
            $pdo = $this->bdd();
            return $pdo->query("SELECT * FROM filiere ORDER BY id")->fetchAll(PDO::FETCH_OBJ);
        }
    
        // 🔹 Récupérer la liste des promotions d'une filière
        public function getPromotionsByFiliere($filiereId) {
            $pdo = $this->bdd();
            $stmt = $pdo->prepare("SELECT * FROM promotion WHERE filiere_id = ?");
            $stmt->execute([$filiereId]);
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        }
    
        // 🔹 Récupérer la liste des modules d'une promotion
        public function getModulesByPromotion($promotionId) {
            $pdo = $this->bdd();
            $stmt = $pdo->prepare("SELECT * FROM module WHERE promotion_id = ?");
            $stmt->execute([$promotionId]);
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        }
    
        // 🔹 Méthode pour enregistrer les notes pour plusieurs étudiants à la fois
        public function saveNotes($notes) {
            $pdo = $this->bdd();
            $stmt = $pdo->prepare("INSERT INTO notes (etudiant_id, devoir, evaluation, note_session, moyenne) VALUES (:etudiant_id, :devoir, :evaluation, :note_session, :moyenne)");
    
            foreach ($notes as $etudiant_id => $note) {
                $devoir = floatval($note['devoir']);
                $evaluation = floatval($note['evaluation']);
                $note_session = isset($note['note_session']) ? floatval($note['note_session']) : null;
                $moyenne = ($note_session !== null) ? $note_session : ($devoir + $evaluation) / 2;
    
                $stmt->execute([
                    'etudiant_id' => $etudiant_id,
                    'devoir' => $devoir,
                    'evaluation' => $evaluation,
                    'note_session' => $note_session,
                    'moyenne' => $moyenne
                ]);
            }
        }
    
        // 🔹 Récupérer tous les étudiants
        public function getStudents() {
            $pdo = $this->bdd();
            return $pdo->query("SELECT * FROM etudiant")->fetchAll(PDO::FETCH_ASSOC);
        }
    
}*/




class Promotion extends Model {

    
    // 🔹 Récupérer les étudiants selon la promotion et le module
    public function getStudentsByFiliereAndPromotion($filiereId, $promotionId) {
        $pdo = $this->bdd();
        $stmt = $pdo->prepare("SELECT * FROM etudiant WHERE id_filiere = ? AND id_promotion = ?");
        try {
            $stmt->execute([$filiereId, $promotionId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo 'Erreur : ' . $e->getMessage();
        }
    }
    
    // 🔹 Enregistrer une note pour un étudiant
    public function saveNote($etudiant_id, $devoir, $evaluation, $note_session = null) {
        $pdo = $this->bdd();
        
        // Calcul de la moyenne
        if ($note_session !== null) {
            $moyenne = $note_session;
        } else {
            $moyenne = ($devoir + $evaluation) / 2;
        }

        $sql = "INSERT INTO note_etudiant (id_etudiant, note_devoir, note_evaluation, note_session, moyenne) 
                VALUES (?, ?, ?, ?, ?)";
        
        try {
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([$etudiant_id, $devoir, $evaluation, $note_session, $moyenne]);
        } catch (PDOException $e) {
            echo 'Erreur : ' . $e->getMessage();
        }
    }

    // 🔹 Récupérer la liste des filières
    public function getAllFiliere() {
        $pdo = $this->bdd();
        return $pdo->query("SELECT * FROM filiere ORDER BY id")->fetchAll(PDO::FETCH_OBJ);
    }

    // 🔹 Récupérer la liste des promotions d'une filière
    public function getPromotionsByFiliere($filiereId) {
        $pdo = $this->bdd();  // Assure-toi que la méthode bdd() établit correctement la connexion à la base de données
    
        // Prépare la requête SQL
        $stmt = $pdo->prepare("SELECT * FROM promotion WHERE id_filiere = ?");
    
        try {
            // Exécute la requête avec le paramètre filiereId
            $stmt->execute([$filiereId]);
    
            // Récupère et retourne les promotions associées à la filière
            $promotions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            // Message de débogage pour confirmer les résultats
            if ($promotions) {
                echo 'Promotions récupérées avec succès.<br>';
            } else {
                echo 'Aucune promotion trouvée pour cette filière.<br>';
            }
    
            return $promotions;
    
        } catch (PDOException $e) {
            // En cas d'erreur, affiche un message d'erreur
            echo 'Erreur : ' . $e->getMessage();
        }
    }
    

    // public function getPromotionsByFiliere($filiereId) {
    //     $query = "SELECT * FROM promotions WHERE filiere_id = ?";
    //     $stmt = $this->pdo->prepare($query);
    //     $stmt->execute([$filiereId]);
    //     return $stmt->fetchAll(PDO::FETCH_ASSOC);
    // }
    

    // 🔹 Récupérer la liste des modules d'une promotion
    public function getModulesByPromotion($promotionId) {
        $pdo = $this->bdd();
        $stmt = $pdo->prepare("SELECT * FROM module WHERE id_promotion = ?");
        try {
            $stmt->execute([$promotionId]);
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            echo 'Erreur : ' . $e->getMessage();
        }
    }

    // 🔹 Méthode pour enregistrer les notes pour plusieurs étudiants à la fois
    public function saveNotes($notes) {
        $pdo = $this->bdd();
        $stmt = $pdo->prepare("INSERT INTO notes (id_etudiant, note_devoir, note_evaluation, note_session, moyenne) 
                               VALUES (:id_etudiant, :note_devoir, :note_evaluation, :note_session, :moyenne)");

        foreach ($notes as $etudiant_id => $note) {
            $devoir = floatval($note['note_devoir']);
            $evaluation = floatval($note['note_evaluation']);
            $note_session = isset($note['note_session']) ? floatval($note['note_session']) : null;
            $moyenne = ($note_session !== null) ? $note_session : ($devoir + $evaluation) / 2;

            try {
                $stmt->execute([
                    'id_etudiant' => $etudiant_id,
                    'note_devoir' => $devoir,
                    'note_evaluation' => $evaluation,
                    'note_session' => $note_session,
                    'moyenne' => $moyenne
                ]);
            } catch (PDOException $e) {
                echo 'Erreur : ' . $e->getMessage();
            }
        }
    }

  
}



?>
