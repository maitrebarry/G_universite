<?php
class EtudiantPargroupe extends Model
{
    public function insertEtudiant($data)
    {
        try {
            // Étape 2 : Insertion si pas de doublon
            $query = 'INSERT INTO etudiant (
            nom_prenom_etudiant, prenom, date_naissance_etudiant, lieu_naissance_etudiant,
            genre_etudiant, matricule_etudiant, contact_etudiant, diplome,
            id_statut, id_promotion, total_frais
        ) VALUES (
            :nom_prenom_etudiant, :prenom, :date_naissance_etudiant, :lieu_naissance_etudiant,
            :genre_etudiant, :matricule_etudiant, :contact_etudiant, :diplome,
            :id_statut, :id_promotion, :total_frais
        )';

            $insert = $this->insertion_update_simples($query, [
                ':nom_prenom_etudiant' => $data['nom_prenom_etudiant'] ?? '',
                ':prenom' => $data['prenom'] ?? '',
                ':date_naissance_etudiant' => $data['date_naissance_etudiant'] ?? '',
                ':lieu_naissance_etudiant' => $data['lieu_naissance_etudiant'] ?? '',
                ':genre_etudiant' => $data['genre_etudiant'] ?? '',
                ':matricule_etudiant' => $data['matricule_etudiant'] ?? '',
                ':contact_etudiant' => $data['contact_etudiant'] ?? '',
                ':diplome' => $data['diplome'] ?? '',
                ':id_statut' => $data['id_statut'] ?? '',
                ':id_promotion' => $data['id_promotion'] ?? '',
                ':total_frais' => $data['total_frais'] ?? 0 // Ajout ici
            ]);

            return [
                'success' => $insert ? true : false,
                'message' => $insert ? 'Insertion réussie.' : 'Échec de l\'insertion.'
            ];
        } catch (PDOException $e) {
            error_log("Erreur lors de l'insertion : " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Erreur PDO : ' . $e->getMessage()
            ];
        }
    }




    public function trie_liste_etudiant($id_promotion)
    {
        $query = "SELECT * 
                  FROM etudiant
                  INNER JOIN promotion ON etudiant.id_promotion = promotion.id_promotion
                  INNER JOIN filiere ON promotion.id_filiere = filiere.id_filiere
                  INNER JOIN parcours ON promotion.id_parcours = parcours.id_parcours
                  INNER JOIN semestre ON parcours.id_semestre = semestre.id_semestre
                  WHERE etudiant.id_promotion = :id_promotion";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id_promotion', $id_promotion, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getTableFields($table)
    {
        $sql = "DESCRIBE $table";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'Field');
    }
}
