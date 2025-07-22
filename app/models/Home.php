<?php
class Home extends Model
{

    /**
     * Activité hebdomadaire : cours et heures
     */
    public function getActiviteHebdomadaire($id_enseignant)
    {
        $start = date('Y-m-d', strtotime('monday this week'));
        $end = date('Y-m-d', strtotime('sunday this week'));

        $query = "
            SELECT 
                COUNT(DISTINCT ee.id_edt) AS total_cours,
                SUM(CASE WHEN e.statut = 1 THEN 1 ELSE 0 END) AS cours_confirmes,
                SUM(CASE WHEN e.statut = 0 THEN 1 ELSE 0 END) AS cours_en_attente,
                SUM(CASE WHEN e.statut = 1 THEN e.heure_total ELSE 0 END) AS heures_confirmées,
                SUM(CASE WHEN e.statut = 0 THEN e.heure_total ELSE 0 END) AS heures_en_attente
            FROM enseignant_edt ee
            INNER JOIN edt e ON ee.id_edt = e.id_edt
            WHERE ee.id_enseignant = :id_enseignant
              AND e.date_debut BETWEEN :start AND :end
        ";

        $params = [
            'id_enseignant' => $id_enseignant,
            'start' => $start,
            'end' => $end
        ];

        $result = $this->select_data_table_join_where($query, $params);
        return $result ? $result[0] : (object)[
            'total_cours' => 0,
            'cours_confirmes' => 0,
            'cours_en_attente' => 0,
            'heures_confirmées' => 0,
            'heures_en_attente' => 0
        ];
    }

    /**
     * Performance globale des étudiants
     */
    public function getPourcentageEtudiantsMoyenne($id_enseignant)
    {
        $query = "
            SELECT 
                COUNT(*) AS total_evalues,
                SUM(CASE WHEN moyenne_module >= 10 THEN 1 ELSE 0 END) AS avec_moyenne
            FROM note_etudiant
            WHERE moyenne_module IS NOT NULL
              AND id_module IN (
                  SELECT DISTINCT e.id_module
                  FROM enseignant_edt ee
                  INNER JOIN edt e ON ee.id_edt = e.id_edt
                  WHERE ee.id_enseignant = :id_enseignant
              )
        ";

        $params = ['id_enseignant' => $id_enseignant];
        $result = $this->select_data_table_join_where($query, $params);

        if ($result && $result[0]->total_evalues > 0) {
            return [
                'pourcentage' => round(($result[0]->avec_moyenne / $result[0]->total_evalues) * 100, 1),
                'avec_moyenne' => $result[0]->avec_moyenne,
                'total_evalues' => $result[0]->total_evalues
            ];
        }

        return [
            'pourcentage' => 0,
            'avec_moyenne' => 0,
            'total_evalues' => 0
        ];
    }

    /**
     * Statistiques par parcours
     */
    public function getStatsEtudiantsParcours($id_enseignant)
    {
        $query = "
            SELECT 
                p.nom_parcours,
                COUNT(n.id_etudiant) AS total_etudiants,
                SUM(CASE WHEN n.moyenne_module >= 10 THEN 1 ELSE 0 END) AS avec_moyenne
            FROM note_etudiant n
            INNER JOIN parcours p ON p.id_parcours = n.id_parcours
            WHERE n.moyenne_module IS NOT NULL
              AND n.id_module IN (
                  SELECT DISTINCT e.id_module
                  FROM enseignant_edt ee
                  INNER JOIN edt e ON ee.id_edt = e.id_edt
                  WHERE ee.id_enseignant = :id_enseignant
              )
            GROUP BY p.id_parcours
        ";

        $params = ['id_enseignant' => $id_enseignant];
        return $this->select_data_table_join_where($query, $params);
    }

    /**
     * Emploi du temps de l’enseignant
     */
    public function getEmploiDuTemps($id_enseignant)
    {
        $query = "
            SELECT 
                e.id_edt,
                DATE_FORMAT(e.date_debut, '%d/%m/%Y') AS date_cours,
                m.nom_module AS module,
                f.nom_filiere AS filiere,
                CONCAT(f.sigle_filiere, '-S', se.id_semestre, ' (', p.annee_universitaire, ')') AS promotion,
                s.nom_salle,
                e.statut
            FROM enseignant_edt ee
            INNER JOIN edt e ON ee.id_edt = e.id_edt
            LEFT JOIN module m ON m.id_module = e.id_module
            LEFT JOIN salle s ON s.id_salle = e.id_salle
            LEFT JOIN filiere f ON f.id_filiere = e.id_filiere
            LEFT JOIN promotion p ON p.id_promotion = e.id_promotion
            LEFT JOIN semestre se ON se.id_semestre = e.id_semestre
            WHERE ee.id_enseignant = :id_enseignant
            ORDER BY e.date_debut ASC
            LIMIT 10
        ";

        return $this->select_data_table_join_where($query, ['id_enseignant' => $id_enseignant]);
    }
    public function getPeriodeActive()
    {
        $today = date('Y-m-d');

        $query = "
            SELECT id_periode, date_debut, date_fin, status
            FROM periode
            WHERE :today BETWEEN date_debut AND date_fin
            ORDER BY date_debut DESC
            LIMIT 1
        ";

        $params = ['today' => $today];
        $result = $this->select_data_table_join_where($query, $params);

        return $result ? $result[0] : null;
    }

        /**
     * tableau de bord pour le chef DR
     */
   

    public function getStatsEnseignants($id_departement) {
        $query = "
            SELECT 
                COUNT(*) AS total,
                SUM(CASE WHEN enseignant_statut = 'PERMANANT' THEN 1 ELSE 0 END) AS permanents,
                SUM(CASE WHEN enseignant_statut = 'NON_PERMANANT' THEN 1 ELSE 0 END) AS non_permanents
            FROM enseignants
            WHERE id_departement = :id_departement
        ";
        return $this->select_data_table_join_where($query, ['id_departement' => $id_departement])[0] ?? (object)[
            'total' => 0,
            'permanents' => 0,
            'non_permanents' => 0
        ];
    }
    public function getStatsEtudiantsParFiliereNiveau($id_departement) {
        $query = "
            SELECT 
                f.id_filiere,
                f.nom_filiere,
                f.sigle_filiere,
                CASE 
                    WHEN se.id_semestre IN (1, 2) THEN 'L1'
                    WHEN se.id_semestre IN (3, 4) THEN 'L2'
                    WHEN se.id_semestre IN (5, 6) THEN 'L3'
                    ELSE 'Autre'
                END AS niveau,
                p.annee_universitaire,
                COUNT(DISTINCT CASE WHEN e.total_frais > 0 THEN e.id_etudiant END) AS inscrits,
                COUNT(DISTINCT CASE WHEN e.total_frais = 0 AND se.id_semestre IN (3,4,5,6) THEN e.id_etudiant END) AS non_inscrits,
                COUNT(DISTINCT CASE WHEN e.genre_etudiant = 'M' THEN e.id_etudiant END) AS hommes,
                COUNT(DISTINCT CASE WHEN e.genre_etudiant = 'F' THEN e.id_etudiant END) AS femmes
            FROM filiere f
            LEFT JOIN promotion p ON f.id_filiere = p.id_filiere
            LEFT JOIN parcours pa ON p.id_parcours = pa.id_parcours
            LEFT JOIN semestre se ON pa.id_semestre = se.id_semestre
            LEFT JOIN etudiant e ON p.id_promotion = e.id_promotion
            WHERE f.id_departement = :id_departement
            AND p.annee_universitaire = (
                SELECT MAX(annee_universitaire) 
                FROM promotion 
                WHERE id_filiere = f.id_filiere
            )
            GROUP BY f.id_filiere, se.id_semestre
            HAVING niveau IS NOT NULL
            ORDER BY f.nom_filiere, se.id_semestre
        ";
        
        return $this->select_data_table_join_where($query, ['id_departement' => $id_departement]);
    }
    public function getIndicateursGeneraux($id_departement) {
        $query = "
            SELECT
                (SELECT COUNT(*) FROM etudiant e 
                JOIN promotion p ON e.id_promotion = p.id_promotion
                JOIN filiere f ON p.id_filiere = f.id_filiere
                WHERE f.id_departement = :id_departement) AS total_etudiants,
                
                (SELECT COUNT(*) FROM etudiant e 
                JOIN promotion p ON e.id_promotion = p.id_promotion
                JOIN filiere f ON p.id_filiere = f.id_filiere
                WHERE f.id_departement = :id_departement AND e.total_frais > 0) AS total_inscrits,
                
                (SELECT COUNT(*) FROM enseignants WHERE id_departement = :id_departement) AS total_enseignants,
                
                (SELECT COUNT(*) FROM filiere WHERE id_departement = :id_departement) AS total_filieres
        ";
        return $this->select_data_table_join_where($query, ['id_departement' => $id_departement])[0] ?? (object)[
            'total_etudiants' => 0,
            'total_inscrits' => 0,
            'total_enseignants' => 0,
            'total_filieres' => 0
        ];
    }
    public function getCoursProgrammes($id_departement) {
        $query = "
            SELECT DISTINCT
                DATE_FORMAT(e.date_debut, '%d/%m/%Y') as date_cours,
                m.nom_module as module,
                m.sigle_module as sigle,
                CONCAT(f.sigle_filiere, '-S', se.id_semestre) as niveau,
                GROUP_CONCAT(DISTINCT CONCAT(en.enseignant_nom, ' ', en.enseignant_prenom) SEPARATOR ', ') AS professeurs,
                s.nom_salle as salle,
                'À venir' as statut
            FROM edt e
            JOIN module m ON e.id_module = m.id_module
            JOIN filiere f ON e.id_filiere = f.id_filiere
            JOIN promotion p ON e.id_promotion = p.id_promotion
            JOIN semestre se ON e.id_semestre = se.id_semestre
            LEFT JOIN enseignant_edt ee ON e.id_edt = ee.id_edt
            LEFT JOIN enseignants en ON ee.id_enseignant = en.enseignant_id
            JOIN salle s ON e.id_salle = s.id_salle
            WHERE e.date_debut >= DATE(NOW())
            AND e.statut = 0
            AND f.id_departement = :id_departement
            GROUP BY e.id_edt
            ORDER BY e.date_debut ASC
            LIMIT 5
        ";
        
        return $this->select_data_table_join_where($query, [
            'id_departement' => $id_departement
        ]);
    }
    public function getExamensAVenirDetails($id_departement) {
        $start = date('Y-m-d');
        $end = date('Y-m-d', strtotime('+30 days'));

        $query = "
            SELECT 
                DATE_FORMAT(e.date_fin, '%d/%m/%Y') AS date_examen,
                CONCAT(TIME_FORMAT(e.date_debut, '%H:%i'), '-', TIME_FORMAT(e.date_fin, '%H:%i')) AS heure,
                m.nom_module AS module,
                CONCAT(f.sigle_filiere, '-S', se.id_semestre, ' (', p.annee_universitaire, ')') AS niveau,
                s.nom_salle AS salle
            FROM edt e
            JOIN module m ON e.id_module = m.id_module
            JOIN filiere f ON e.id_filiere = f.id_filiere
            JOIN promotion p ON e.id_promotion = p.id_promotion
            JOIN semestre se ON e.id_semestre = se.id_semestre
            JOIN salle s ON e.id_salle = s.id_salle
            WHERE e.date_fin BETWEEN :start AND :end
            AND f.id_departement = :id_departement
            ORDER BY e.date_fin ASC
        ";

        return $this->select_data_table_join_where($query, [
            'start' => $start,
            'end' => $end,
            'id_departement' => $id_departement
        ]);
    }

    public function getStatsEtudiantsParFiliereNiveau_scolarite() {
        $query = "
            SELECT 
                f.id_filiere,
                f.nom_filiere,
                f.sigle_filiere,
                d.nom_departement,
                CASE 
                    WHEN se.id_semestre IN (1, 2) THEN 'L1'
                    WHEN se.id_semestre IN (3, 4) THEN 'L2'
                    WHEN se.id_semestre IN (5, 6) THEN 'L3'
                    ELSE 'Autre'
                END AS niveau,
                p.annee_universitaire,
                COUNT(DISTINCT CASE WHEN e.total_frais > 0 THEN e.id_etudiant END) AS inscrits,
                COUNT(DISTINCT CASE WHEN e.total_frais = 0 AND se.id_semestre IN (3,4,5,6) THEN e.id_etudiant END) AS non_inscrits,
                COUNT(DISTINCT CASE WHEN e.genre_etudiant = 'M' THEN e.id_etudiant END) AS hommes,
                COUNT(DISTINCT CASE WHEN e.genre_etudiant = 'F' THEN e.id_etudiant END) AS femmes,
                COUNT(DISTINCT CASE WHEN ne.moyenne_module >= 10 THEN e.id_etudiant END) AS admis,
                COUNT(DISTINCT CASE WHEN ne.moyenne_module < 10 THEN e.id_etudiant END) AS ajournes
            FROM filiere f
            JOIN departement d ON f.id_departement = d.id_departement
            LEFT JOIN promotion p ON f.id_filiere = p.id_filiere
            LEFT JOIN parcours pa ON p.id_parcours = pa.id_parcours
            LEFT JOIN semestre se ON pa.id_semestre = se.id_semestre
            LEFT JOIN etudiant e ON p.id_promotion = e.id_promotion
            LEFT JOIN note_etudiant ne ON e.id_etudiant = ne.id_etudiant AND p.id_promotion = ne.id_promotion
            GROUP BY f.id_filiere, se.id_semestre, p.annee_universitaire
            HAVING niveau IS NOT NULL
            ORDER BY d.nom_departement, f.nom_filiere, se.id_semestre, p.annee_universitaire DESC
        ";
        
        return $this->select_data_table_join_where($query, []);
    }

    public function getIndicateursGeneraux_scolarite() {
        $query = "
            SELECT
                (SELECT COUNT(*) FROM etudiant) AS total_etudiants,
                (SELECT COUNT(*) FROM etudiant WHERE total_frais > 0) AS total_inscrits,
                (SELECT COUNT(*) FROM enseignants) AS total_enseignants,
                (SELECT COUNT(*) FROM filiere) AS total_filieres,
                (SELECT COUNT(*) FROM departement) AS total_departements,
                (SELECT COUNT(DISTINCT id_etudiant) FROM note_etudiant WHERE moyenne_module >= 10) AS total_admis,
                (SELECT COUNT(DISTINCT id_etudiant) FROM note_etudiant WHERE moyenne_module < 10) AS total_ajournes
        ";
        return $this->select_data_table_join_where($query, [])[0] ?? (object)[
            'total_etudiants' => 0,
            'total_inscrits' => 0,
            'total_enseignants' => 0,
            'total_filieres' => 0,
            'total_departements' => 0,
            'total_admis' => 0,
            'total_ajournes' => 0
        ];
    }
}
?>
