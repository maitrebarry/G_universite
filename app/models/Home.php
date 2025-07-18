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
     * Statistiques des étudiants par niveau (L1, L2, L3, Non-inscrits)
     */
    // public function getStatsEtudiantsParNiveau()
    // {
    //     $query = "
    //         SELECT 
    //             COUNT(CASE WHEN e.id_promotion = 1 THEN 1 END) as l1,
    //             COUNT(CASE WHEN e.id_promotion = 2 THEN 1 END) as l2,
    //             COUNT(CASE WHEN e.id_promotion = 3 THEN 1 END) as l3,
    //             COUNT(CASE WHEN e.id_statut = 0 THEN 1 END) as unregistered
    //         FROM etudiant e
    //     ";
        
    //     return $this->select_data_table_join_where($query)[0] ?? (object)[
    //         'l1' => 0,
    //         'l2' => 0,
    //         'l3' => 0,
    //         'unregistered' => 0
    //     ];
    // }


    /**
     * Statistiques étudiants par niveau (L1, L2, L3)
     */
    public function getStatsEtudiantsParNiveau($id_departement)
    {
        $query = "
            SELECT 
                CASE 
                    WHEN se.id_semestre IN (1, 2) THEN 'L1'
                    WHEN se.id_semestre IN (3, 4) THEN 'L2'
                    WHEN se.id_semestre IN (5, 6) THEN 'L3'
                END AS niveau,
                COUNT(CASE WHEN e.total_frais != 0 THEN 1 END) AS total_inscrits
            FROM etudiant e
            INNER JOIN promotion p ON e.id_promotion = p.id_promotion
            INNER JOIN parcours pa ON p.id_parcours = pa.id_parcours
            INNER JOIN semestre se ON pa.id_semestre = se.id_semestre
            INNER JOIN filiere f ON p.id_filiere = f.id_filiere
            WHERE f.id_departement = :id_departement
            GROUP BY niveau
        ";

        $rows = $this->select_data_table_join_where($query, ['id_departement' => $id_departement]);
        $stats = (object)['l1' => 0, 'l2' => 0, 'l3' => 0, 'unregistered' => $this->getNonInscrits($id_departement)];

        foreach ($rows as $row) {
            if ($row->niveau === 'L1') $stats->l1 = $row->total_inscrits;
            if ($row->niveau === 'L2') $stats->l2 = $row->total_inscrits;
            if ($row->niveau === 'L3') $stats->l3 = $row->total_inscrits;
        }

        return $stats;
    }
    /**
     * Étudiants non inscrits
     */
    public function getNonInscrits($id_departement)
    {
        $query = "
            SELECT COUNT(*) AS non_inscrits
            FROM etudiant e
            INNER JOIN promotion p ON e.id_promotion = p.id_promotion
            INNER JOIN filiere f ON p.id_filiere = f.id_filiere
            WHERE f.id_departement = :id_departement AND e.total_frais = 0
        ";
        $result = $this->select_data_table_join_where($query, ['id_departement' => $id_departement]);
        return $result ? $result[0]->non_inscrits : 0;
    }
    /**
     * Statistiques des étudiants par genre
     */
    // public function getStatsEtudiantsParGenre()
    // {
    //     $query = "
    //         SELECT 
    //             COUNT(CASE WHEN genre_etudiant = 'M' THEN 1 END) as male,
    //             COUNT(CASE WHEN genre_etudiant = 'F' THEN 1 END) as female
    //         FROM etudiant
    //     ";
        
    //     return $this->select_data_table_join_where($query)[0] ?? (object)[
    //         'male' => 0,
    //         'female' => 0
    //     ];
    // }

     /**
     * Répartition par genre
     */
    public function getStatsEtudiantsParGenre($id_departement)
    {
        $query = "
            SELECT 
                SUM(CASE WHEN genre_etudiant = 'M' AND total_frais != 0 THEN 1 ELSE 0 END) AS male,
                SUM(CASE WHEN genre_etudiant = 'F' AND total_frais != 0 THEN 1 ELSE 0 END) AS female
            FROM etudiant e
            INNER JOIN promotion p ON e.id_promotion = p.id_promotion
            INNER JOIN filiere f ON p.id_filiere = f.id_filiere
            WHERE f.id_departement = :id_departement
        ";
        $result = $this->select_data_table_join_where($query, ['id_departement' => $id_departement]);
        return $result ? $result[0] : (object)['male' => 0, 'female' => 0];
    }

    /**
     * Statistiques des enseignants
     */
    // public function getStatsEnseignants() 
    // {
    //     $query = "
    //         SELECT 
    //             COUNT(*) as total,
    //             SUM(CASE WHEN enseignant_statut = 1 THEN 1 ELSE 0 END) as actifs
    //         FROM enseignants
    //     ";
        
    //     return $this->select_data_table_join_where($query)[0] ?? (object)[
    //         'total' => 0,
    //         'actifs' => 0
    //     ];
    // }

    /**
     * Enseignants par département
     */
    public function getStatsEnseignants($id_departement)
    {
        $query = "
            SELECT 
                COUNT(*) AS total,
                SUM(CASE WHEN enseignant_statut = 'Actif' THEN 1 ELSE 0 END) AS actifs
            FROM enseignants
            WHERE id_departement = :id_departement
        ";
        $result = $this->select_data_table_join_where($query, ['id_departement' => $id_departement]);
        return $result ? $result[0] : (object)['total' => 0, 'actifs' => 0];
    }

    /**
     * Cours programmés (cette semaine)
     */
   
    public function getCoursProgrammes()
    {
        $start = date('Y-m-d', strtotime('monday this week'));
        $end = date('Y-m-d', strtotime('sunday this week'));

        $query = "
            SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN e.statut = 1 THEN 1 ELSE 0 END) as confirmes,
                SUM(e.heure_total) as heures_total
            FROM edt e
            WHERE e.date_debut BETWEEN :start AND :end
        ";

        $params = ['start' => $start, 'end' => $end];
        return $this->select_data_table_join_where($query, $params)[0] ?? (object)[
            'total' => 0,
            'confirmes' => 0,
            'heures_total' => 0
        ];
    }

    /**
     * Taux de réussite global
     */
    public function getTauxReussiteGlobal() {
        $query = "
            SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN moyenne_module >= 10 THEN 1 ELSE 0 END) as reussis,
                ROUND((SUM(CASE WHEN moyenne_module >= 10 THEN 1 ELSE 0 END) * 100.0 / COUNT(*)), 1) as taux
            FROM note_etudiant
            WHERE moyenne_module IS NOT NULL
        ";
        
        return $this->select_data_table_join_where($query)[0] ?? (object)[
            'total' => 0,
            'reussis' => 0,
            'taux' => 0
        ];
    }
    /**
     * Examens à venir (30 prochains jours)
     */
    // public function getExamensAVenir() {
    //     $start = date('Y-m-d');
    //     $end = date('Y-m-d', strtotime('+30 days'));

    //     $query = "
    //         SELECT COUNT(*) as total
    //         FROM edt e
    //         WHERE e.date_debut BETWEEN :start AND :end
    //         AND e.id_edt IN (
    //             SELECT MAX(e2.id_edt)
    //             FROM edt e2
    //             GROUP BY e2.id_module, e2.id_promotion
    //         )
    //     ";

    //     $params = ['start' => $start, 'end' => $end];
    //     $result = $this->select_data_table_join_where($query, $params);
    //     return $result[0]->total ?? 0;
    // }

/**
 * Liste détaillée des cours programmés
 */
    public function getListeCoursProgrammes() {
        $start = date('Y-m-d', strtotime('monday this week'));
        $end = date('Y-m-d', strtotime('sunday this week'));
        $query = "
            SELECT 
                DATE_FORMAT(e.date_debut, '%d/%m/%Y') as date_cours,
                m.nom_module as module,
                m.sigle_module as sigle,
                CONCAT(f.sigle_filiere, '-S', se.id_semestre, ' (', p.annee_universitaire, ')') as niveau,
              GROUP_CONCAT(DISTINCT CONCAT(en.enseignant_nom, ' ', en.enseignant_prenom) SEPARATOR ', ') AS professeurs,
                s.nom_salle as salle,
                CASE WHEN e.statut = 1 THEN 'Confirmé' ELSE 'En attente' END as statut
            FROM edt e
            LEFT JOIN module m ON e.id_module = m.id_module
            LEFT JOIN filiere f ON e.id_filiere = f.id_filiere
            LEFT JOIN promotion p ON e.id_promotion = p.id_promotion
            LEFT JOIN semestre se ON e.id_semestre = se.id_semestre
            LEFT JOIN enseignant_edt ee ON e.id_edt = ee.id_edt
            LEFT JOIN enseignants en ON ee.id_enseignant = en.enseignant_id
            LEFT JOIN salle s ON e.id_salle = s.id_salle
            WHERE e.date_debut BETWEEN :start AND :end
            GROUP BY e.id_edt
            ORDER BY e.date_debut ASC
        ";
        $params = ['start' => $start, 'end' => $end];
        return $this->select_data_table_join_where($query, $params);
    }

    // public function getExamensAVenirDetails() {
    //     $start = date('Y-m-d');
    //     $end = date('Y-m-d', strtotime('+30 days'));
    //     $query = "
    //     SELECT 
    //     DATE_FORMAT(e.date_fin, '%d/%m/%Y') AS date_examen,
    //     CONCAT( TIME_FORMAT(h.heure_debut, '%H:%i'), '-', TIME_FORMAT(h.heure_fin, '%H:%i')) AS heure,
    //     m.nom_module AS module,
    //     CONCAT(f.sigle_filiere, '-S', se.id_semestre, ' (', p.annee_universitaire, ')') AS niveau,
    //     s.nom_salle AS salle
    //         FROM edt e
    //         LEFT JOIN module m ON e.id_module = m.id_module
    //         LEFT JOIN filiere f ON e.id_filiere = f.id_filiere
    //         LEFT JOIN promotion p ON e.id_promotion = p.id_promotion
    //         LEFT JOIN semestre se ON e.id_semestre = se.id_semestre
    //         LEFT JOIN salle s ON e.id_salle = s.id_salle
    //         LEFT JOIN (
    //             SELECT id_edt, MIN(heure_debut) AS heure_debut, MIN(heure_fin) AS heure_fin
    //             FROM horaire
    //             GROUP BY id_edt) h ON e.id_edt = h.id_edt
    //         WHERE e.date_fin BETWEEN :start AND :end
    //         AND e.id_edt IN (
    //             SELECT MAX(e2.id_edt)
    //             FROM edt e2
    //             GROUP BY e2.id_module, e2.id_promotion
    //         )
    //         ORDER BY e.date_fin ASC";

    //     $params = ['start' => $start, 'end' => $end];
    //     return $this->select_data_table_join_where($query, $params);
    // }

     /**
     * Examens à venir (dernière séance)
     */
    public function getExamensAVenirDetails($id_departement)
    {
        $start = date('Y-m-d');
        $end = date('Y-m-d', strtotime('+30 days'));

        $query = "
            SELECT 
                DATE_FORMAT(MAX(e.date_fin), '%d/%m/%Y') AS date_examen,
                '08:00-10:00' AS heure,
                m.nom_module AS module,
                CONCAT(f.sigle_filiere, '-S', se.id_semestre, ' (', p.annee_universitaire, ')') AS niveau,
                s.nom_salle AS salle
            FROM edt e
            INNER JOIN module m ON e.id_module = m.id_module
            INNER JOIN filiere f ON e.id_filiere = f.id_filiere
            INNER JOIN promotion p ON e.id_promotion = p.id_promotion
            INNER JOIN semestre se ON e.id_semestre = se.id_semestre
            INNER JOIN salle s ON e.id_salle = s.id_salle
            WHERE e.date_debut BETWEEN :start AND :end
            AND f.id_departement = :id_departement
            GROUP BY e.id_module, e.id_promotion
            ORDER BY date_examen ASC
        ";

        $params = ['start' => $start, 'end' => $end, 'id_departement' => $id_departement];
        return $this->select_data_table_join_where($query, $params);
    }

    public function getExamensAVenir($id_departement)
    {
        $details = $this->getExamensAVenirDetails($id_departement);
        return count($details);
    }

}
?>
