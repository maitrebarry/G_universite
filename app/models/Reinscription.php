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

    /**
     * Réinscrit les étudiants sélectionnés dans la promotion cible.
     * Ajoute un lien etudiant_promotion (etat='actif') ; ignore les doublons.
     * L'historique des inscriptions précédentes est conservé.
     */
    /** Parcours (niveau/semestre) d'une promotion. */
    public function getParcours($idPromotion)
    {
        $st = $this->bdd()->prepare("SELECT id_parcours FROM promotion WHERE id_promotion = ?");
        $st->execute([(int) $idPromotion]);
        return $st->fetchColumn();
    }

    /**
     * Réinscrit les étudiants dans la promotion cible.
     * RÈGLE : seuls ceux ayant VALIDÉ leur niveau d'origine sont réinscrits (les autres refusés).
     * Ignore les doublons ; conserve l'historique.
     */
    public function reinscrire(array $idEtudiants, $idNewPromotion, $idOldPromotion = null)
    {
        $idNewPromotion = (int) $idNewPromotion;
        $faits = 0; $deja = 0; $refuses = 0;
        $pdo = $this->bdd();

        // Niveau d'origine pour vérifier la validation
        $idOldParcours = $idOldPromotion ? $this->getParcours($idOldPromotion) : null;
        $note = $idOldParcours ? new Note() : null;

        $check = $pdo->prepare("SELECT COUNT(*) FROM etudiant_promotion WHERE id_etudiants = ? AND id_promotion = ?");
        $insert = $pdo->prepare("INSERT INTO etudiant_promotion (id_etudiants, id_promotion, etat) VALUES (?, ?, 'actif')");

        $pdo->beginTransaction();
        try {
            foreach ($idEtudiants as $idEtu) {
                $idEtu = (int) $idEtu;
                if (!$idEtu) continue;
                // Seuls les étudiants ayant validé leur niveau peuvent monter.
                if ($note) {
                    $v = $note->isValidateSemestre($idEtu, $idOldParcours);
                    if (empty($v['isValidate'])) { $refuses++; continue; }
                }
                $check->execute([$idEtu, $idNewPromotion]);
                if ($check->fetchColumn() > 0) { $deja++; continue; }
                $insert->execute([$idEtu, $idNewPromotion]);
                $faits++;
            }
            $pdo->commit();
        } catch (Exception $e) {
            $pdo->rollBack();
            return ['faits' => 0, 'deja' => 0, 'refuses' => 0, 'erreur' => $e->getMessage()];
        }
        return ['faits' => $faits, 'deja' => $deja, 'refuses' => $refuses];
    }

    /** Toutes les promotions (pour le choix de la classe cible). */
    public function toutesPromotions()
    {
        $q = "SELECT p.id_promotion, p.annee_universitaire, p.id_filiere, p.id_parcours,
                     f.sigle_filiere, f.nom_filiere, s.sigle_semestre
              FROM promotion p
              JOIN filiere f ON p.id_filiere = f.id_filiere
              JOIN parcours pa ON p.id_parcours = pa.id_parcours
              JOIN semestre s ON pa.id_semestre = s.id_semestre
              ORDER BY p.annee_universitaire DESC, f.sigle_filiere ASC, s.sigle_semestre ASC";
        return $this->bdd()->query($q)->fetchAll(PDO::FETCH_OBJ);
    }

    /** IDs des étudiants déjà inscrits dans une promotion (pour marquer la liste). */
    public function dejaInscrits($idPromotion)
    {
        $stmt = $this->bdd()->prepare("SELECT id_etudiants FROM etudiant_promotion WHERE id_promotion = ?");
        $stmt->execute([(int) $idPromotion]);
        return array_map('intval', $stmt->fetchAll(PDO::FETCH_COLUMN));
    }
}