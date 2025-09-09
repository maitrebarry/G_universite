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
                COUNT(DISTINCT CASE 
                    WHEN py.montant_paye > 0 AND DATE_FORMAT(py.date,'%Y') BETWEEN SUBSTRING(p.annee_universitaire,1,4) AND SUBSTRING(p.annee_universitaire,6,4)
                    THEN e.id_etudiant END) AS inscrits,
                COUNT(DISTINCT CASE 
                    WHEN (py.idPayem IS NULL OR py.montant_paye = 0)
                    THEN e.id_etudiant END) AS non_inscrits,
                COUNT(DISTINCT CASE WHEN e.genre_etudiant = 'M' THEN e.id_etudiant END) AS hommes,
                COUNT(DISTINCT CASE WHEN e.genre_etudiant = 'F' THEN e.id_etudiant END) AS femmes
            FROM filiere f
            LEFT JOIN promotion p ON f.id_filiere = p.id_filiere
            LEFT JOIN parcours pa ON p.id_parcours = pa.id_parcours
            LEFT JOIN semestre se ON pa.id_semestre = se.id_semestre
           LEFT JOIN etudiant_promotion ep 
             ON ep.id_promotion = p.id_promotion AND ep.etat = 1
            LEFT JOIN etudiant e 
                ON e.id_etudiant = ep.id_etudiants

            LEFT JOIN payement py ON py.idEtudt = e.id_etudiant
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
        -- Total étudiants du département
        (SELECT COUNT(*) 
         FROM etudiant e
         JOIN etudiant_promotion ep ON ep.id_etudiants = e.id_etudiant AND ep.etat = 1
         JOIN promotion p ON ep.id_promotion = p.id_promotion
         JOIN filiere f ON p.id_filiere = f.id_filiere
         WHERE f.id_departement = :id_departement
        ) AS total_etudiants,

        -- Total inscrits (paiement effectué)
        (SELECT COUNT(DISTINCT e.id_etudiant)
         FROM etudiant e
         JOIN etudiant_promotion ep ON ep.id_etudiants = e.id_etudiant AND ep.etat = 1
         JOIN promotion p ON ep.id_promotion = p.id_promotion
         JOIN filiere f ON p.id_filiere = f.id_filiere
         LEFT JOIN payement py ON py.idEtudt = e.id_etudiant
         WHERE f.id_departement = :id_departement
         AND py.montant_paye > 0 AND py.date IS NOT NULL
        ) AS total_inscrits,

        -- Total enseignants
        (SELECT COUNT(*) 
         FROM enseignants 
         WHERE id_departement = :id_departement
        ) AS total_enseignants,

        -- Total filières
        (SELECT COUNT(*) 
         FROM filiere 
         WHERE id_departement = :id_departement
        ) AS total_filieres
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
                COALESCE(ee_salle.nom_salle, s.nom_salle) as salle,
                'À venir' as statut
            FROM edt e
            JOIN ue_module um ON e.id_module = um.id_ue_module
            JOIN module m ON um.id_module = m.id_module
            JOIN filiere f ON e.id_filiere = f.id_filiere
            JOIN promotion p ON e.id_promotion = p.id_promotion
            JOIN semestre se ON e.id_semestre = se.id_semestre
            LEFT JOIN enseignant_edt ee ON e.id_edt = ee.id_edt
            LEFT JOIN enseignants en ON ee.id_enseignant = en.enseignant_id
            LEFT JOIN salle s ON e.id_salle = s.id_salle
            LEFT JOIN salle ee_salle ON ee.id_salle = ee_salle.id_salle
            WHERE e.date_debut >= DATE(NOW())
            AND e.statut = 0
            AND f.id_departement = :id_departement
            GROUP BY e.id_edt, date_cours, module, sigle, niveau, COALESCE(ee_salle.nom_salle, s.nom_salle), statut
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
                COALESCE(ee_salle.nom_salle, s.nom_salle) AS salle
            FROM edt e
            JOIN ue_module um ON e.id_module = um.id_ue_module
            JOIN module m ON um.id_module = m.id_module
            JOIN filiere f ON e.id_filiere = f.id_filiere
            JOIN promotion p ON e.id_promotion = p.id_promotion
            JOIN semestre se ON e.id_semestre = se.id_semestre
            LEFT JOIN salle s ON e.id_salle = s.id_salle
            LEFT JOIN enseignant_edt ee ON e.id_edt = ee.id_edt
            LEFT JOIN salle ee_salle ON ee.id_salle = ee_salle.id_salle
            WHERE e.date_fin BETWEEN :start AND :end
            AND f.id_departement = :id_departement
            GROUP BY e.id_edt, date_examen, heure, module, niveau, COALESCE(ee_salle.nom_salle, s.nom_salle)
            ORDER BY e.date_fin ASC
        ";

        return $this->select_data_table_join_where($query, [
            'start' => $start,
            'end' => $end,
            'id_departement' => $id_departement
        ]);
    }
        /**
     * tableau de bord pour la scolarité
     */
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

                -- Étudiants inscrits (paiement effectué dans l’année universitaire)
                COUNT(DISTINCT CASE 
                    WHEN py.montant_paye > 0 
                        AND YEAR(py.date) BETWEEN LEFT(p.annee_universitaire,4) AND RIGHT(p.annee_universitaire,4)
                    THEN e.id_etudiant END
                ) AS inscrits,

                -- Étudiants non inscrits (aucun paiement ou paiement = 0)
                COUNT(DISTINCT CASE 
                    WHEN (py.idPayem IS NULL OR py.montant_paye = 0)
                    THEN e.id_etudiant END
                ) AS non_inscrits,

                -- Répartition hommes / femmes
                COUNT(DISTINCT CASE WHEN e.genre_etudiant = 'M' THEN e.id_etudiant END) AS hommes,
                COUNT(DISTINCT CASE WHEN e.genre_etudiant = 'F' THEN e.id_etudiant END) AS femmes,

                -- Résultats académiques
                COUNT(DISTINCT CASE WHEN ne.moyenne_module >= 10 THEN e.id_etudiant END) AS admis,
                COUNT(DISTINCT CASE WHEN ne.moyenne_module < 10 THEN e.id_etudiant END) AS ajournes

            FROM filiere f
            JOIN departement d ON f.id_departement = d.id_departement
            LEFT JOIN promotion p ON f.id_filiere = p.id_filiere
            LEFT JOIN parcours pa ON p.id_parcours = pa.id_parcours
            LEFT JOIN semestre se ON pa.id_semestre = se.id_semestre

            -- 🔹 Nouvelle jointure correcte via inscription
              LEFT JOIN etudiant_promotion i ON i.id_promotion = p.id_promotion
            LEFT JOIN etudiant e ON i.id_etudiants = e.id_etudiant

            -- Paiement relié à l'étudiant
            LEFT JOIN payement py ON py.idEtudt = e.id_etudiant

            -- Notes reliées à étudiant + promotion
            LEFT JOIN (
                SELECT ne1.id_etudiant, ne1.id_promotion, MAX(ne1.moyenne_module) AS moyenne_module
                FROM note_etudiant ne1
                GROUP BY ne1.id_etudiant, ne1.id_promotion
            ) ne ON e.id_etudiant = ne.id_etudiant AND i.id_promotion = ne.id_promotion

            GROUP BY f.id_filiere, se.id_semestre, p.annee_universitaire
            ORDER BY d.nom_departement, f.nom_filiere, se.id_semestre, p.annee_universitaire DESC;

        ";
        
        return $this->select_data_table_join_where($query, []);
    }
    public function getIndicateursGeneraux_scolarite() {
        $query = "
            SELECT
            (SELECT COUNT(DISTINCT e.id_etudiant)
            FROM etudiant e
            JOIN etudiant_promotion ep ON ep.id_etudiants = e.id_etudiant AND ep.etat = 1
            ) AS total_etudiants,

            (SELECT COUNT(DISTINCT idEtudt)
            FROM payement
            WHERE montant_paye > 0 AND date IS NOT NULL
            ) AS total_inscrits,

            (SELECT COUNT(DISTINCT idEtudt)
            FROM payement
            WHERE montant_paye > 0 AND date IS NOT NULL 
            AND YEAR(date) BETWEEN YEAR(CURDATE())-2 AND YEAR(CURDATE())
            ) AS total_inscrits_3_ans,

            (SELECT COUNT(*) FROM enseignants) AS total_enseignants,
            (SELECT COUNT(*) FROM filiere) AS total_filieres,
            (SELECT COUNT(*) FROM departement) AS total_departements,

            (SELECT COUNT(DISTINCT ne.id_etudiant)
            FROM note_etudiant ne
            WHERE ne.moyenne_module >= 10
            ) AS total_admis,

            (SELECT COUNT(DISTINCT ne.id_etudiant)
            FROM note_etudiant ne
            WHERE ne.moyenne_module < 10
            ) AS total_ajournes;

        ";
        
        return $this->select_data_table_join_where($query, [])[0] ?? (object)[
            'total_etudiants' => 0,
            'total_inscrits' => 0,
            'total_inscrits_3_ans' => 0,
            'total_enseignants' => 0,
            'total_filieres' => 0,
            'total_departements' => 0,
            'total_admis' => 0,
            'total_ajournes' => 0
        ];
    }
    public function getInscritsParAnnee() {
        $query = "
            SELECT 
                annee,
                COUNT(DISTINCT idEtudt) AS total
            FROM payement
            WHERE montant_paye > 0 
            AND annee IS NOT NULL
            AND annee != 0
            GROUP BY annee
            ORDER BY annee DESC
            LIMIT 3
        ";
        return $this->select_data_table_join_where($query, []);
    }

     /**
     * tableau de bord pour la secrétaire principale
     */

    public function getStatsSGP() {
        $query = "
            SELECT
                (SELECT COUNT(*) FROM departement) AS total_departements,
                (SELECT COUNT(*) FROM filiere) AS total_filieres,
                (SELECT COUNT(*) FROM etudiant) AS total_etudiants,
                (SELECT COUNT(*) FROM enseignants) AS total_enseignants
        ";
        return $this->select_data_table_join_where($query, [])[0] ?? (object)[
            'total_departements' => 0,
            'total_filieres' => 0,
            'total_etudiants' => 0,
            'total_enseignants' => 0
        ];
    }
    public function getDernieresInscriptions($limit = 5) {
        $limit = intval($limit);

        $query = "
            SELECT 
                e.id_etudiant,
                e.nom_prenom_etudiant,
                f.nom_filiere,
                f.sigle_filiere,
                DATE_FORMAT(MAX(p.date), '%d/%m/%Y') AS date_inscription
            FROM etudiant e
            JOIN promotion pr ON e.id_promotion = pr.id_promotion
            JOIN filiere f ON pr.id_filiere = f.id_filiere
            JOIN payement p ON p.idEtudt = e.id_etudiant
            WHERE p.montant_paye > 0 
            AND p.date IS NOT NULL
            GROUP BY e.id_etudiant, e.nom_prenom_etudiant, f.nom_filiere, f.sigle_filiere
            ORDER BY MAX(p.date) DESC
            LIMIT $limit
        ";

        return $this->select_data_table_join_where($query);
    }


    public function getProchainsEvenements($limit = 5) {
        $limit = intval($limit);
        $query = "
            SELECT 
                'Cours' AS type,
                m.nom_module AS evenement,
                DATE_FORMAT(e.date_debut, '%d/%m/%Y') AS date,
                CONCAT(f.sigle_filiere, '-S', s.id_semestre) AS niveau
            FROM edt e
            JOIN module m ON e.id_module = m.id_module
            JOIN filiere f ON e.id_filiere = f.id_filiere
            JOIN semestre s ON e.id_semestre = s.id_semestre
            WHERE e.date_debut >= CURDATE()
            AND e.statut = 0
            
            UNION ALL
            
            SELECT 
                'Examen' AS type,
                m.nom_module AS evenement,
                DATE_FORMAT(e.date_fin, '%d/%m/%Y') AS date,
                CONCAT(f.sigle_filiere, '-S', s.id_semestre) AS niveau
            FROM edt e
            JOIN module m ON e.id_module = m.id_module
            JOIN filiere f ON e.id_filiere = f.id_filiere
            JOIN semestre s ON e.id_semestre = s.id_semestre
            WHERE e.date_fin >= CURDATE()
            
            ORDER BY date ASC
            LIMIT $limit
        ";
        return $this->select_data_table_join_where($query);
    }
    /**
     * tableau de bord pour le DGA
     */
    
    public function getStatsDGA()
    {
        $stats = [
            'taux_reussite' => 0,
            'evolution' => '+0%',
            'best_dep' => ['nom' => '0', 'taux' => 0],
            'worst_dep' => ['nom' => '0', 'taux' => 0],
            'total_etudiants' => 0,
            'total_inscrits' => 0,
            'taux_inscription' => 0,
            'taux_echec' => 0
        ];

        // ✅ 1. Total étudiants
        $queryTotal = "SELECT COUNT(*) as total FROM etudiant";
        $resTotal = $this->select_data_table_join_where($queryTotal);
        $totalEtudiants = $resTotal[0]->total ?? 0;
        $stats['total_etudiants'] = $totalEtudiants;

        // ✅ 2. Total inscrits (paiement valide)
        $queryInscrits = "
            SELECT COUNT(DISTINCT e.id_etudiant) as total_inscrits
            FROM etudiant e
            JOIN payement p ON p.idEtudt = e.id_etudiant
            WHERE p.montant_paye > 0 AND p.date IS NOT NULL
        ";
        $resInscrits = $this->select_data_table_join_where($queryInscrits);
        $totalInscrits = $resInscrits[0]->total_inscrits ?? 0;
        $stats['total_inscrits'] = $totalInscrits;

        // ✅ 3. Taux d'inscription
        $stats['taux_inscription'] = ($totalEtudiants > 0)
            ? round(($totalInscrits / $totalEtudiants) * 100, 2)
            : 0;

        // ✅ 4. Étudiants admis (moyenne_module >= 10)
        $queryAdmis = "
            SELECT COUNT(DISTINCT n.id_etudiant) as total_admis
            FROM note_etudiant n
            JOIN etudiant e ON e.id_etudiant = n.id_etudiant
            JOIN payement p ON p.idEtudt = e.id_etudiant
            WHERE n.moyenne_module >= 10 AND p.montant_paye > 0 AND p.date IS NOT NULL
        ";
        $resAdmis = $this->select_data_table_join_where($queryAdmis);
        $totalAdmis = $resAdmis[0]->total_admis ?? 0;

        // ✅ 5. Taux de réussite & échec
        if ($totalInscrits > 0) {
            $tauxReussite = ($totalAdmis / $totalInscrits) * 100; // en %
            $stats['taux_reussite'] = round($tauxReussite, 2);
            $stats['taux_echec'] = round(100 - $tauxReussite, 2);
        } else {
            $stats['taux_reussite'] = 0;
            $stats['taux_echec'] = 100;
        }

        // ✅ 6. Evolution (placeholder)
        $stats['evolution'] = '+0.35%'; 

        // ✅ 7. Meilleur et pire département avec tri intelligent
        $queryDep = "
            SELECT 
                d.nom_departement,
                COUNT(DISTINCT e.id_etudiant) AS total_etudiants,
                COUNT(DISTINCT CASE WHEN n.moyenne_module >= 10 THEN e.id_etudiant END) AS admis,
                ROUND(
                    IF(COUNT(DISTINCT e.id_etudiant) > 0,
                        (COUNT(DISTINCT CASE WHEN n.moyenne_module >= 10 THEN e.id_etudiant END) / COUNT(DISTINCT e.id_etudiant)) * 100,
                        0
                    ), 2
                ) AS taux
            FROM departement d
            LEFT JOIN filiere f 
                ON f.id_departement = d.id_departement
            LEFT JOIN promotion pr 
                ON pr.id_filiere = f.id_filiere
            LEFT JOIN etudiant_promotion ep 
                ON ep.id_promotion = pr.id_promotion AND ep.etat = 1
            LEFT JOIN etudiant e 
                ON e.id_etudiant = ep.id_etudiants
            LEFT JOIN note_etudiant n 
                ON n.id_etudiant = e.id_etudiant
            LEFT JOIN payement p 
                ON p.idEtudt = e.id_etudiant 
                AND p.montant_paye > 0 
                AND p.date IS NOT NULL
            GROUP BY d.id_departement
            ORDER BY taux DESC, total_etudiants DESC;

        ";
        $resDep = $this->select_data_table_join_where($queryDep);

        if (!empty($resDep)) {
            $stats['best_dep'] = [
                'nom' => $resDep[0]->nom_departement,
                'taux' => (float)$resDep[0]->taux
            ];

            $lastIndex = count($resDep) - 1;
            $stats['worst_dep'] = [
                'nom' => $resDep[$lastIndex]->nom_departement,
                'taux' => (float)$resDep[$lastIndex]->taux
            ];
        }

        return $stats;
    }

    public function getStatsDepartementsDetail()
    {
        $query = "
                SELECT 
                d.nom_departement AS departement,
                COUNT(DISTINCT e.id_etudiant) AS total_etudiants,
                COUNT(DISTINCT CASE WHEN ne.moyenne_module >= 10 THEN e.id_etudiant END) AS admis,
                IF(COUNT(DISTINCT e.id_etudiant) > 0, 
                    ROUND(
                        (COUNT(DISTINCT CASE WHEN ne.moyenne_module >= 10 THEN e.id_etudiant END) 
                        / COUNT(DISTINCT e.id_etudiant)) * 100, 2
                    ), 0
                ) AS taux_reussite
            FROM departement d
            LEFT JOIN filiere f 
                ON f.id_departement = d.id_departement
            LEFT JOIN parcours pa 
                ON pa.id_filiere = f.id_filiere
            LEFT JOIN promotion p 
                ON p.id_parcours = pa.id_parcours
            LEFT JOIN etudiant_promotion ep 
                ON ep.id_promotion = p.id_promotion AND ep.etat = 1
            LEFT JOIN etudiant e 
                ON e.id_etudiant = ep.id_etudiants
            LEFT JOIN note_etudiant ne 
                ON ne.id_etudiant = e.id_etudiant
            GROUP BY d.id_departement
            ORDER BY taux_reussite DESC, total_etudiants DESC;

        ";

        return $this->select_data_table_join_where($query, []);
    }

    /**
     * tableau de bord pour le directeur général
     */
    public function getStatsAcademiques()
    {
        $stats = [
            'taux_reussite' => 0,
            'evolution' => '+0%',
            'best_dep' => ['nom' => '0', 'taux' => 0],
            'worst_dep' => ['nom' => '0', 'taux' => 0],
            'total_etudiants' => 0,
            'total_inscrits' => 0,
            'taux_inscription' => 0,
            'taux_echec' => 0
        ];

        // ✅ 1. Total étudiants
        $queryTotal = "SELECT COUNT(*) as total FROM etudiant";
        $resTotal = $this->select_data_table_join_where($queryTotal);
        $totalEtudiants = $resTotal[0]->total ?? 0;
        $stats['total_etudiants'] = $totalEtudiants;

        // ✅ 2. Total inscrits (paiement valide)
        $queryInscrits = "
            SELECT COUNT(DISTINCT e.id_etudiant) as total_inscrits
            FROM etudiant e
            JOIN payement p ON p.idEtudt = e.id_etudiant
            WHERE p.montant_paye > 0 AND p.date IS NOT NULL
        ";
        $resInscrits = $this->select_data_table_join_where($queryInscrits);
        $totalInscrits = $resInscrits[0]->total_inscrits ?? 0;
        $stats['total_inscrits'] = $totalInscrits;

        // ✅ 3. Taux d'inscription
        $stats['taux_inscription'] = ($totalEtudiants > 0)
            ? round(($totalInscrits / $totalEtudiants) * 100, 2)
            : 0;

        // ✅ 4. Étudiants admis (moyenne_module >= 10)
        $queryAdmis = "
            SELECT COUNT(DISTINCT n.id_etudiant) as total_admis
            FROM note_etudiant n
            JOIN etudiant e ON e.id_etudiant = n.id_etudiant
            JOIN payement p ON p.idEtudt = e.id_etudiant
            WHERE n.moyenne_module >= 10 AND p.montant_paye > 0 AND p.date IS NOT NULL
        ";
        $resAdmis = $this->select_data_table_join_where($queryAdmis);
        $totalAdmis = $resAdmis[0]->total_admis ?? 0;

        // ✅ 5. Taux de réussite & échec
        if ($totalInscrits > 0) {
            $tauxReussite = ($totalAdmis / $totalInscrits) * 100;
            $stats['taux_reussite'] = round($tauxReussite, 2);
            $stats['taux_echec'] = round(100 - $tauxReussite, 2);
        } else {
            $stats['taux_reussite'] = 0;
            $stats['taux_echec'] = 100;
        }

        // ✅ 6. Meilleur et pire département avec tri intelligent
        $queryDep = "
            SELECT 
                d.nom_departement,
                COUNT(DISTINCT e.id_etudiant) AS total_etudiants,
                COUNT(DISTINCT CASE WHEN n.moyenne_module >= 10 THEN e.id_etudiant END) AS admis,
                ROUND(
                    IF(COUNT(DISTINCT e.id_etudiant) > 0,
                        (COUNT(DISTINCT CASE WHEN n.moyenne_module >= 10 THEN e.id_etudiant END) / COUNT(DISTINCT e.id_etudiant)) * 100,
                        0
                    ), 2
                ) AS taux
            FROM departement d
            LEFT JOIN filiere f ON f.id_departement = d.id_departement
            LEFT JOIN promotion pr ON pr.id_filiere = f.id_filiere
            LEFT JOIN etudiant e ON e.id_promotion = pr.id_promotion
            LEFT JOIN note_etudiant n ON n.id_etudiant = e.id_etudiant
            LEFT JOIN payement p ON p.idEtudt = e.id_etudiant AND p.montant_paye > 0 AND p.date IS NOT NULL
            GROUP BY d.id_departement
            ORDER BY taux DESC, total_etudiants DESC
        ";
        $resDep = $this->select_data_table_join_where($queryDep);

        if (!empty($resDep)) {
            $stats['best_dep'] = [
                'nom' => $resDep[0]->nom_departement,
                'taux' => (float)$resDep[0]->taux
            ];

            $lastIndex = count($resDep) - 1;
            $stats['worst_dep'] = [
                'nom' => $resDep[$lastIndex]->nom_departement,
                'taux' => (float)$resDep[$lastIndex]->taux
            ];
        }

        return $stats;
    }

    public function getStatsDG()
    {
        $stats = $this->getStatsAcademiques();

        // ✅ Recettes totales
        $queryFinance = "SELECT SUM(montant_paye) as total_recettes FROM payement WHERE montant_paye > 0";
        $resFinance = $this->select_data_table_join_where($queryFinance);
        $stats['total_recettes'] = $resFinance[0]->total_recettes ?? 0;

        // ✅ Alertes
        $queryAlerte = "
            SELECT COUNT(DISTINCT e.id_etudiant) as alertes
            FROM etudiant e
            JOIN payement p ON p.idEtudt = e.id_etudiant AND p.montant_paye > 0
            LEFT JOIN note_etudiant n ON n.id_etudiant = e.id_etudiant
            WHERE n.id_etudiant IS NULL
        ";
        $resAlertes = $this->select_data_table_join_where($queryAlerte);
        $stats['alertes'] = $resAlertes[0]->alertes ?? 0;

        // ✅ Tableau par département (ajout recettes + inscrits)
        $queryDep = "
            SELECT 
                d.nom_departement,
                COUNT(DISTINCT e.id_etudiant) AS total_etudiants,
                COUNT(DISTINCT CASE WHEN p.montant_paye > 0 THEN e.id_etudiant END) AS inscrits,
                COUNT(DISTINCT CASE WHEN n.moyenne_module >= 10 THEN e.id_etudiant END) AS admis,
                ROUND(
                    IF(COUNT(DISTINCT CASE WHEN p.montant_paye > 0 THEN e.id_etudiant END) > 0,
                        (COUNT(DISTINCT CASE WHEN n.moyenne_module >= 10 THEN e.id_etudiant END) / COUNT(DISTINCT CASE WHEN p.montant_paye > 0 THEN e.id_etudiant END)) * 100,
                        0
                    ), 2
                ) AS taux_reussite,
                IFNULL(SUM(p.montant_paye), 0) AS recettes
            FROM departement d
            LEFT JOIN filiere f ON f.id_departement = d.id_departement
            LEFT JOIN promotion pr ON pr.id_filiere = f.id_filiere
            LEFT JOIN etudiant e ON e.id_promotion = pr.id_promotion
            LEFT JOIN payement p ON p.idEtudt = e.id_etudiant
            LEFT JOIN note_etudiant n ON n.id_etudiant = e.id_etudiant
            GROUP BY d.id_departement
            ORDER BY taux_reussite DESC, total_etudiants DESC
        ";
        $departements = $this->select_data_table_join_where($queryDep);

        // ✅ Ajouter critère
        if (!empty($departements)) {
            $departements[0]->critere = "Meilleur";
            $departements[count($departements)-1]->critere = " à surveiller";
            for ($i = 1; $i < count($departements)-1; $i++) {
                $departements[$i]->critere = "-";
            }
        }

        $stats['departements'] = $departements;
        return $stats;
    }

    // ✅ Top 3 filières
    public function getTopFilieres()
    {
        $query = "
        SELECT 
            f.nom_filiere AS filiere,
            COUNT(DISTINCT e.id_etudiant) AS total_etudiants,
            COUNT(DISTINCT CASE WHEN ne.moyenne_module >= 10 THEN e.id_etudiant END) AS admis,
            IF(COUNT(DISTINCT e.id_etudiant) > 0, 
                ROUND(
                    (COUNT(DISTINCT CASE WHEN ne.moyenne_module >= 10 THEN e.id_etudiant END) 
                    / COUNT(DISTINCT e.id_etudiant)) * 100, 2
                ), 0) AS taux_reussite
        FROM filiere f
        JOIN parcours pa ON pa.id_filiere = f.id_filiere
        JOIN promotion p ON p.id_parcours = pa.id_parcours
        JOIN etudiant e ON e.id_promotion = p.id_promotion
        LEFT JOIN note_etudiant ne ON ne.id_etudiant = e.id_etudiant
        GROUP BY f.id_filiere
        ORDER BY taux_reussite DESC, total_etudiants DESC
        LIMIT 3
        ";

        return $this->select_data_table_join_where($query, []);
    }
}
?>
