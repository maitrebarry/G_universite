<?php
class EtudiantPargroupe extends Model
{
    public function __construct() { $this->pdo = $this->bdd(); }

    private static $etudiantCols = null;

    // Colonnes réelles de la table etudiant (mises en cache pour la requête)
    private function etudiantColumns()
    {
        if (self::$etudiantCols === null) {
            try {
                self::$etudiantCols = $this->bdd()->query("DESCRIBE etudiant")->fetchAll(PDO::FETCH_COLUMN);
            } catch (\Throwable $e) {
                self::$etudiantCols = [];
            }
        }
        return self::$etudiantCols;
    }

    public function insertEtudiant($data)
    {
        $db = $this->bdd();
        try {
            $db->beginTransaction();

            // Colonnes de base toujours présentes
            $cols = [
                'nom_prenom_etudiant', 'prenom', 'date_naissance_etudiant', 'lieu_naissance_etudiant',
                'genre_etudiant', 'matricule_etudiant', 'contact_etudiant', 'diplome', 'nationnalite',
                'numetudiant', 'id_statut', 'total_frais',
            ];

            // Renseigner id_filiere / id_promotion DIRECTEMENT sur etudiant si ces colonnes
            // existent : indispensable car de nombreuses requêtes (tableaux de bord, listes
            // filtrées) lisent etudiant.id_promotion / etudiant.id_filiere et non le lien.
            $tableCols = $this->etudiantColumns();
            foreach (['id_filiere', 'id_promotion'] as $opt) {
                if (in_array($opt, $tableCols, true) && !empty($data[$opt])) {
                    $cols[] = $opt;
                }
            }

            $place = array_map(function ($c) { return ':' . $c; }, $cols);
            $params = [];
            foreach ($cols as $c) {
                $params[':' . $c] = $data[$c] ?? ($c === 'total_frais' ? 0 : '');
            }

            $stmt = $db->prepare('INSERT INTO etudiant (' . implode(', ', $cols) . ') VALUES (' . implode(', ', $place) . ')');
            $stmt->execute($params);
            $idEtudiant = (int) $db->lastInsertId();

            if (!$idEtudiant) {
                throw new \RuntimeException("Échec de l'insertion de l'étudiant.");
            }
            if (empty($data['id_promotion'])) {
                throw new \RuntimeException("ID de promotion manquant, impossible de lier l'étudiant.");
            }

            $promo = $db->prepare('INSERT INTO etudiant_promotion (id_etudiants, id_promotion, etat) VALUES (:e, :p, :etat)');
            $promo->execute([
                ':e' => $idEtudiant,
                ':p' => $data['id_promotion'],
                ':etat' => $data['etat'] ?? 'actif',
            ]);

            $db->commit();
            return ['success' => true, 'id' => $idEtudiant, 'message' => 'Insertion réussie avec promotion.'];
        } catch (\Throwable $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            error_log('insertEtudiant: ' . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
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

        $stmt = $this->bdd()->prepare($query);
        $stmt->bindParam(':id_promotion', $id_promotion, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getTableFields($table)
    {
        $stmt = $this->bdd()->prepare("DESCRIBE " . $table);
        $stmt->execute();
        return array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'Field');
    }

    // Clés existantes (pour la détection de doublons côté client)
    public function existingStudentKeys()
    {
        $stmt = $this->bdd()->query("SELECT numetudiant, nom_prenom_etudiant, prenom FROM etudiant");
        $nums = [];
        $pers = [];
        foreach ($stmt->fetchAll(PDO::FETCH_OBJ) as $r) {
            $n = trim((string) ($r->numetudiant ?? ''));
            if ($n !== '') $nums[] = $n;
            $k = gu_person_key($r->nom_prenom_etudiant ?? '', $r->prenom ?? '');
            if ($k !== '') $pers[] = $k;
        }
        return [
            'numetudiants' => array_values(array_unique($nums)),
            'personnes'    => array_values(array_unique($pers)),
        ];
    }
}
