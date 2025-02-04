<?php



class Note extends Model
{

    // 🔹 Récupérer les étudiants selon la promotion et le module

    public function getModulesByFiliere($filiereId)
    {
        $pdo = $this->bdd();
        $stmt = $pdo->prepare("SELECT * FROM etudiant WHERE id_filiere = ?");
        try {
            $stmt->execute([$filiereId]);
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            echo 'Erreur : ' . $e->getMessage();
        }
    }

    // 🔹 Enregistrer une note pour un étudiant
    public function saveNote($etudiant_id, $devoir, $evaluation, $note_session = null)
    {
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

    //🔹 Récupérer la liste des filières
    public function getAllFiliere()
    {
        $pdo = $this->bdd();
        return $pdo->query("SELECT * FROM filiere ORDER BY id")->fetchAll(PDO::FETCH_OBJ);
    }

    // 🔹 Récupérer la liste des promotions d'une filière
    public function getPromotionsByFiliere($filiereId)
    {
        $pdo = $this->bdd();
        $stmt = $pdo->prepare("SELECT * FROM promotion WHERE id_filiere = ?");
        try {
            $stmt->execute([$filiereId]);
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            echo 'Erreur : ' . $e->getMessage();
        }
    }

    // 🔹 Récupérer la liste des modules d'une promotion
    public function getModulesByPromotion($promotionId)
    {
        $pdo = $this->bdd();
        $stmt = $pdo->prepare("SELECT * FROM module WHERE id_promotion = ?");
        try {
            $stmt->execute([$promotionId]);
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            echo 'Erreur : ' . $e->getMessage();
        }
    }

    //Méthode pour récupérer tous les étudiants selon la filère et la promotion
    public function getEtudiantsByFiliereAndPromotion($filiere_id, $promotion_id)
    {
        $stmt = $this->pdo->prepare("
            SELECT e.id, e.nom_prenom, e.matricule_etudiant, e.genre_etudiant,  e.date_naissance
            FROM etudiant e
            WHERE e.filiere_id = :filiere_id AND e.promotion_id = :promotion_id
        ");
        $stmt->execute([
            ':filiere_id' => $filiere_id,
            ':promotion_id' => $promotion_id
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // 🔹 Méthode pour enregistrer les notes pour plusieurs étudiants à la fois
    public function getNote($etudiant_id, $module_id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM note WHERE etudiant_id = :etudiant_id AND module_id = :module_id");
        $stmt->execute([
            ':etudiant_id' => $etudiant_id,
            ':module_id' => $module_id
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function saveNote1($etudiant_id, $module_id, $note_normale, $note_devoir, $note_rattrapage)
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO note_etudiant (id_etudiant, id_module, note_session_normale,note_devoir, note_session_rattrapage)
            VALUES (:id_etudiant, :id_module, :note_normale, :note_devoir :note_rattrapage)
            ON DUPLICATE KEY UPDATE 
                note_session_normale = :note_normale, 
                note_devoir = :note_devoir,
                note_session_rattrapage = :note_rattrapage
        ");
        $stmt->execute([
            ':etudiant_id' => $etudiant_id,
            ':module_id' => $module_id,
            ':note_normale' => $note_normale,
            ':note_devoir' => $note_devoir,
            ':note_rattrapage' => $note_rattrapage
        ]);
    }

    // 🔹 Récupérer tous les étudiants
    public function getStudents()
    {
        $pdo = $this->bdd();


        return $pdo->query("SELECT * FROM etudiant")->fetchAll(PDO::FETCH_ASSOC);
    }
}
