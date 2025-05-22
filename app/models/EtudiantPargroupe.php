<?php
class EtudiantPargroupe extends Model
{
    public function insertEtudiant($data)
    {
        try {
            $query = 'INSERT INTO etudiant (
                nom_prenom_etudiant, date_naissance_etudiant, lieu_naissance_etudiant,
                genre_etudiant, matricule_etudiant, contact_etudiant, diplome,
                id_statut, id_promotion
            ) VALUES (
                :nom_prenom_etudiant, :date_naissance_etudiant, :lieu_naissance_etudiant,
                :genre_etudiant, :matricule_etudiant, :contact_etudiant, :diplome,
                :id_statut, :id_promotion
            )';

            $insert = $this->insertion_update_simples($query, [
                ':nom_prenom_etudiant' => $data['nom_prenom_etudiant'] ?? '',
                ':date_naissance_etudiant' => $data['date_naissance_etudiant'] ?? '',
                ':lieu_naissance_etudiant' => $data['lieu_naissance_etudiant'] ?? '',
                ':genre_etudiant' => $data['genre_etudiant'] ?? '',
                ':matricule_etudiant' => $data['matricule_etudiant'] ?? '',
                ':contact_etudiant' => $data['contact_etudiant'] ?? '',
                ':diplome' => $data['diplome'] ?? '',
                ':id_statut' => $data['id_statut'] ?? '',

                ':id_promotion' => $data['id_promotion'] ?? "",

            ]);

            return $insert;
            if (!$insert) {
                echo '<script>
                document.addEventListener("DOMContentLoaded", function () {
                    Swal.fire("La validation a ete fait avec succes", "", "success").then(() => {
                        window.location.href = "liste_entend.php";
                    });
                });
            </script>';
            }
        } catch (PDOException $e) {
            error_log("Erreur lors de l'insertion : " . $e->getMessage());
            return false;
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


        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam(':id_promotion', $id_promotion, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}