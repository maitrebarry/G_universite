<?php
class Reinscription  extends Model
{
    public function __construct()
    {
        $this->pdo = $this->bdd(); // Utilisez bdd() pour obtenir la connexion PDO
    }
    public function trie_liste_etudiant($annee_universitaire, $id_promotion)
    {
        $query = "SELECT * 
              FROM etudiant
              INNER JOIN etudiant_promotion ON etudiant.id_etudiant = etudiant_promotion.id_etudiants
              INNER JOIN promotion ON etudiant_promotion.id_promotion = promotion.id_promotion
              INNER JOIN filiere ON promotion.id_filiere = filiere.id_filiere
              INNER JOIN parcours ON promotion.id_parcours = parcours.id_parcours
              INNER JOIN semestre ON parcours.id_semestre = semestre.id_semestre
              WHERE promotion.annee_universitaire = :annee_universitaire
                AND promotion.id_promotion=:idPromotion";

        $stmt = $this->bdd()->prepare($query);
        $stmt->bindParam(':annee_universitaire', $annee_universitaire);
        $stmt->bindParam(':idPromotion', $id_promotion, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}