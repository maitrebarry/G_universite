<?php
class EtudiantPargroupe extends Model
{
public function insertEtudiant($data)
{
    try {
        // 1. Insertion dans etudiant et récupération du last ID
        $query = 'INSERT INTO etudiant (
            nom_prenom_etudiant, prenom, date_naissance_etudiant, lieu_naissance_etudiant,
            genre_etudiant, matricule_etudiant, contact_etudiant, diplome, nationnalite, numetudiant,
            id_statut, total_frais
        ) VALUES (
            :nom_prenom_etudiant, :prenom, :date_naissance_etudiant, :lieu_naissance_etudiant,
            :genre_etudiant, :matricule_etudiant, :contact_etudiant, :diplome,:nationnalite, :numetudiant,
            :id_statut, :total_frais
        )';


        $result = $this->insertion_update_simples_insert_id($query, [
    ':nom_prenom_etudiant' => $data['nom_prenom_etudiant'] ?? '',
    ':prenom'               => $data['prenom'] ?? '',
    ':date_naissance_etudiant' => $data['date_naissance_etudiant'] ?? '',
    ':lieu_naissance_etudiant' => $data['lieu_naissance_etudiant'] ?? '',
    ':genre_etudiant'      => $data['genre_etudiant'] ?? '',
    ':matricule_etudiant'  => $data['matricule_etudiant'] ?? '',
    ':contact_etudiant'    => $data['contact_etudiant'] ?? '',
    ':diplome'             => $data['diplome'] ?? '',
    ':nationnalite'             => $data['nationnalite'] ?? '',
    ':numetudiant'             => $data['numetudiant'] ?? '',
    ':id_statut'           => $data['id_statut'] ?? '',
    ':total_frais'         => $data['total_frais'] ?? 0
]);

$idEtudiant = $result['lastInsertId'] ?? 0;


        if (!$idEtudiant) {
            return [
                'success' => false,
                'message' => 'Échec de l\'insertion de l\'étudiant.'
            ];
        }


        // 2. Vérifier que la promotion est fournie
        if (empty($data['id_promotion'])) {
            return [
                'success' => false,
                'message' => 'ID de promotion manquant, impossible de lier l\'étudiant.'
            ];
        }

        // 3. Insertion dans etudiant_promotion
       

        $queryPromo = 'INSERT INTO etudiant_promotion (id_etudiants, id_promotion, etat)
               VALUES (:id_etudiants, :id_promotion, :etat)';

$insertPromo = $this->insertion_update_simples($queryPromo, [
    ':id_etudiants' => $idEtudiant,
    ':id_promotion' => $data['id_promotion'],
    ':etat'         => $data['etat'] ?? 'actif'
]);


        return [
            'success' => $insertPromo ? true : false,
            'message' => $insertPromo ? 'Insertion réussie avec promotion.' : 'Échec de l\'insertion dans etudiant_promotion.'
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