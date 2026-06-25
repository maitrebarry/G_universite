<?php
class Emploi_du_temp  extends Model
{
    // la methode pour pour retourner la dernière peridode crée
    public function getCurrentPeriode()
    {
        $periode = $this->FetchSelectWhere("*", "periode", "status = 'inachevé' ");
        return $periode;
    }

    // la methode pour ajouter un edt
    public function ajouterEdt($edt, $horaires, $enseignants, $forceSalle = false)
    {
        try {
            $connection = $this->bdd();
            // le debut de la transaction
            $connection->beginTransaction();

            // La verification des infos de base de l'edt
            // Validation des champs réellement requis (idEdt = édition uniquement ;
            // heureTotal est facultatif, normalisé plus bas). On NOMME les champs
            // manquants pour un message clair côté utilisateur.
            $champsRequis = [
                'idFiliere'   => 'filière',
                'idPromotion' => 'classe',
                'idSemestre'  => 'parcours / semestre',
                'idModule'    => 'module',
                'dateDebut'   => 'date de début',
            ];
            $manquants = [];
            foreach ($champsRequis as $cle => $libelle) {
                $val = $edt[$cle] ?? null;
                if ($val === null || trim((string) $val) === '') {
                    $manquants[] = $libelle;
                }
            }
            if (!empty($manquants)) {
                throw new Exception("Informations manquantes : " . implode(', ', $manquants) . ". Choisissez bien la classe puis le module.");
            }
            $periode = $this->getCurrentPeriode();
            if (!is_object($periode) || empty($periode->id_periode)) {
                throw new Exception("Aucune période active (statut « inachevé »). Activez une période avant de créer un emploi du temps.");
            }
            extract($edt);

            // Vérifications intelligentes : budget d'heures du module + conflits salle/enseignant.
            $this->verifierBudgetHeures($idModule, $enseignants);
            $conf = $this->detecterConflits(
                $this->idsDistincts($enseignants, 'salle'),
                $this->idsDistincts($enseignants, 'enseignant'),
                $this->cellsFromHoraires($horaires),
                $periode->id_periode,
                null
            );
            if (!empty($conf['enseignant'])) {
                throw new Exception("Conflit d'enseignant — " . implode(' ; ', $conf['enseignant']));
            }
            if (!empty($conf['salle']) && !$forceSalle) {
                throw new Exception("Conflit de salle — " . implode(' ; ', $conf['salle']));
            }

            $requetteEdt = "INSERT INTO edt(date_creation, date_debut, date_Fin, statut, heure_total, id_filiere, id_promotion, id_semestre, id_module, id_periode)
            VALUES (:dateCreation, :dateDebut, :dateFin, :statut, :heureTotal, :idFiliere, :idPromotion, :idSemestre, :idModule, :idPeriode)";
            $dateFin = new DateTime($dateDebut);
            $dateFin->add(new DateInterval('P7D'));
            $param = [
                "dateCreation" => date("Y-m-d"),
                "dateDebut" => $dateDebut,
                "dateFin" => $dateFin->format("Y-m-d"),
                "statut" => 0,
                "heureTotal" => (int) $heureTotal,
                "idFiliere" => $idFiliere,
                "idPromotion" => $idPromotion,
                "idSemestre" => $idSemestre,
                "idModule" => $idModule,
                "idPeriode" => $periode->id_periode,

            ];
            $reponse = $this->insertion_update_simples_insert_id($requetteEdt, $param);
            $idEdt = $reponse['lastInsertId'];
            if ($enseignants == null || count($enseignants) < 1) {
                throw new Exception("Aucun enseignant sélectionné.");
            }
            $requetteEnseignant = "INSERT INTO enseignant_edt(id_edt, id_enseignant, groupe, nombre_heure, type_cours, id_salle)
                VALUES (:idEdt, :idEnseignant, :groupe, :nombreHeure, :typeCours, :salle)";
            foreach ($enseignants as $ens) {
                $idEnseignant = $ens['enseignant'] ?? null;
                $salle = trim((string) ($ens['salle'] ?? ''));
                if (empty($idEnseignant)) {
                    throw new Exception("Un enseignant de la liste est invalide (identifiant manquant).");
                }
                if ($salle === '') {
                    throw new Exception("Salle non sélectionnée pour un enseignant.");
                }
                $param = [
                    "idEdt" => $idEdt,
                    "idEnseignant" => $idEnseignant,
                    "groupe" => $ens['groupe'] ?? '',
                    "nombreHeure" => $ens['nombreHeure'] ?? 0,
                    "typeCours" => $ens['typeCours'] ?? '',
                    "salle" => $salle,
                ];
                $this->insertion_update_simples($requetteEnseignant, $param);
            }
            // la verification des horaires
            if (empty($horaires)) {
                throw new Exception("Créneau horaire invalide (aucun créneau, ou heure de début/fin manquante).");
            }

            // les requettes pour inserer des horaires ou des taches
            $requetteHoraire = "INSERT INTO horaire(heure_debut, heure_fin, id_edt) VALUES (:heureDebut, :heureFin, :idEdt)";
            $requetteTache = "INSERT INTO tache(type_tache, id_horaire, id_jour) VALUES (:typeTache, :idHoraire, :idJour)";
            foreach ($horaires as $horaire) {
                extract($horaire);
                if (empty(trim($heureDebut)) || empty(trim($heureFin))) {
                    throw new Exception("Créneau horaire invalide (aucun créneau, ou heure de début/fin manquante).");
                }
                // l'insertion d'un horaire
                $param = [
                    "heureDebut" => $heureDebut,
                    "heureFin" => $heureFin,
                    "idEdt" => $idEdt,
                ];
                $reponse = $this->insertion_update_simples_insert_id($requetteHoraire, $param);
                $idHoraire = $reponse['lastInsertId'];

                // la verification des taches pour chaque horaire
                if (empty($taches)) {
                    throw new Exception("Un créneau n'a aucun jour associé.");
                }

                foreach ($taches as $tache) {
                    if (!$this->isArrayDataValid($tache)) {
                        throw new Exception("Tâche invalide (type ou jour manquant).");
                    }
                    extract($tache);

                    // l'insertion d'un horaire
                    $param = [
                        "typeTache" => $typeTache,
                        "idHoraire" => $idHoraire,
                        "idJour" => $idJour,
                    ];
                    $this->insertion_update_simples($requetteTache, $param);
                }
            }

            // la fin de la transaction
            $connection->commit();
            $this->set_flash(
                "EDT ajouté avec succès  
                <button type='button' class='btn btn-link' id='print' data-id='$idEdt'>
                 <span class='text-italic text-bold-600 text-dark' >Imprimer <i class=' bx bx-printer'></i></span>
                 </button>",
                "primary"
            );
        } catch (Exception $e) {
            $connection->rollBack();
            $this->set_flash($e->getMessage() . " : !Veuillez bien verifier vos données");
            return;
        }
    }

    // La methode pour recuperer les infos d'un edt
    public function getInfoEdt($idEdt)
    {
        $infoEdt = [];
        $connection = $this->bdd();

        try {
            // le debut de la transaction
            $connection->beginTransaction();

            // la recuperation de l'edt
            $requetteEdt = "SELECT id_edt, date_creation, date_debut, date_fin, statut, id_module, id_promotion FROM edt  WHERE id_edt=? ";
            $resultat = $this->select_data_table_join_where($requetteEdt, [$idEdt]);
            $edt = $resultat[0];

            // Validation d'un edt lorsque la date de fin est atteinte
            if ($edt->statut == 0 && strtotime(date('Y-m-d')) > strtotime($edt->date_fin)) {
                $this->setStatusEdt($edt->id_edt);
            }

            $idModule = $edt->id_module;
            $edt->date_debut = date_format(new DateTime($edt->date_debut), "d-m-Y");
            $edt->date_fin = date_format(new DateTime($edt->date_fin), "d-m-Y");

            // la recuperation du module de l'emploi
            $requetteModule = "SELECT id_ue_module, ue_module.id_ue, ue_module.id_module, code_module, coeficient, cm, td, tp, tpe, module.nom_module,
                            module.sigle_module, ue.nom_ue, ue.id_parcours FROM ue_module INNER JOIN module ON ue_module.id_module=module.id_module 
                            INNER JOIN ue ON ue_module.id_ue=ue.id_ue WHERE id_ue_module=? LIMIT 1";
            $resultat = $this->select_data_table_join_where($requetteModule, [$idModule]);
            $module = $resultat[0];


            // la recuperation de la promotion
            $requettePromotion = "SELECT id_promotion, annee_universitaire, statut, promotion.id_parcours, promotion.id_filiere, nom_filiere, 
            sigle_filiere, nom_semestre, sigle_semestre FROM promotion INNER JOIN filiere ON promotion.id_filiere=filiere.id_filiere
            INNER JOIN parcours ON promotion.id_parcours=parcours.id_parcours INNER JOIN semestre ON parcours.id_semestre=semestre.id_semestre
            WHERE id_promotion=?";
            $resultat = $this->select_data_table_join_where($requettePromotion, [$edt->id_promotion]);
            $promotion = $resultat[0];


            // la recuperation des enseignant
            $requetteEnseignant = "SELECT id_enseignant, groupe, type_cours, nombre_heure, salle.nom_salle, 
            enseignant_prenom, enseignant_nom, enseignant_telephone FROM enseignant_edt
            INNER JOIN enseignants ON enseignant_edt.id_enseignant=enseignants.enseignant_id 
            INNER JOIN salle ON enseignant_edt.id_salle= salle.id_salle WHERE id_edt=? ";

            $enseignants = $this->select_data_table_join_where($requetteEnseignant, [$idEdt]);

            // la fin de la transaction
            $connection->commit();

            $infoEdt = [
                "module" => $module,
                "promotion" => $promotion,
                "enseignants" => $enseignants,
                "edt" => $edt
            ];
            return (object) $infoEdt;
        } catch (Exception $e) {
            $connection->rollBack();
            $this->set_flash("Impossible de recuperer les informations de ce EDT");
            die($e->getMessage());
            // $this->redirect("Emploi_du_temps");
            return;
        }

        return $infoEdt;
    }

    // la metode pour recuperer les horaires d'un edt
    public function getHorairesEdt($idEdt)
    {
        $horairesEdt = [];
        $requetteTache = "SELECT id_tache, type_tache, tache.id_jour, nom_jour FROM tache
        INNER JOIN jour ON tache.id_jour=jour.id_jour
        WHERE id_horaire=?";
        $horaires = $this->FetchAllSelectWhere('*', 'horaire', 'id_edt=:idEdt', ['idEdt' => $idEdt]);
        foreach ($horaires as $horaire) {
            $tachesHoraire = $this->select_data_table_join_where($requetteTache, [$horaire->id_horaire]);
            $horaireEdt = [
                "id_horaire" => $horaire->id_horaire,
                "heure_debut" => $horaire->heure_debut,
                "heure_fin" => $horaire->heure_fin,
                "taches" => $tachesHoraire
            ];
            $horairesEdt[] = (object)$horaireEdt;
        }

        return $horairesEdt;
    }

    // la methode pour recuperer un ancien edt d'une promotion
    public function getAncienEdt($idFiliere, $idModule)
    {

        $edts = $this->FetchAllSelectWhere(
            '*',
            'edt',
            'id_filiere=:idFiliere AND id_module=:idModule',
            ['idFiliere' => $idFiliere, 'idModule' => $idModule]
        );
        return end($edts);
    }

    // la methode pour recuperer la liste des emplois
    public function listeEdts()
    {
        $edts = [];
        $listeEdts = $this->SelectAllData("id_edt, statut, date_fin", "edt ORDER BY id_edt DESC LIMIT 5");
        foreach ($listeEdts as $edt) {
            $infoEdt = $this->getInfoEdt($edt->id_edt);
            $edts[] =  $infoEdt;
        }

        return array_filter($edts);
    }

    // la methode pour trier la liste des edts
    public function trierListeEdt($idFiliere, $idPromotion, $idSemestre)
    {
        $whereCondition = "id_filiere=? AND id_promotion=? AND id_semestre=?";
        $whereValues = [$idFiliere, $idPromotion, $idSemestre];
        // if ($idPromotion == null || empty($idPromotion)) {
        //     $whereCondition = "id_filiere=?";
        //     $whereValues = [$idFiliere];
        // }
        $edts = [];

        $listeEdts = $this->FetchAllSelectWhere("id_edt", "edt", $whereCondition, $whereValues);
        usort($listeEdts, function ($a, $b) {
            return $b->id_edt - $a->id_edt;
        });
        foreach ($listeEdts as $edt) {
            $infoEdt = $this->getInfoEdt($edt->id_edt);
            $edts[] =  $infoEdt;
        }

        return $edts;
    }

    // la methode pour valider ou annuler un edt un edt 
    public function setStatusEdt($idEdt, $statut = 1)
    {
        try {
            $this->insertion_update_simples(
                'UPDATE edt SET statut=:statut WHERE id_edt=:idEdt LIMIT 1',
                ['statut' => $statut, 'idEdt' => $idEdt]
            );
        } catch (Exception $e) {

            $this->set_flash('Imposible de modifier cet edt');
        }
    }

    // la methode pour editer un edt
    public function editerEdt($edt, $horaires, $forceSalle = false)
    {
        try {
            $connection = $this->bdd();
            // le debut de la transaction
            $connection->beginTransaction();

            // La verification des infos de base de l'edt
            if (!$this->isArrayDataValid($edt)) {
                throw new Exception("Informations de base de l'EDT incomplètes (année, classe, module ou date).");
            }
            $periode = $this->getCurrentPeriode();
            extract($edt);

            // Vérifications intelligentes (en édition, les affectations enseignant/salle sont inchangées).
            $affectations = $this->enseignantsOfEdt($idEdt);
            $this->verifierBudgetHeures($idModule, $affectations);
            $conf = $this->detecterConflits(
                $this->idsDistincts($affectations, 'salle'),
                $this->idsDistincts($affectations, 'enseignant'),
                $this->cellsFromHoraires($horaires),
                $periode->id_periode,
                $idEdt
            );
            if (!empty($conf['enseignant'])) {
                throw new Exception("Conflit d'enseignant — " . implode(' ; ', $conf['enseignant']));
            }
            if (!empty($conf['salle']) && !$forceSalle) {
                throw new Exception("Conflit de salle — " . implode(' ; ', $conf['salle']));
            }

            $requetteEdt = "UPDATE edt SET date_debut=:dateDebut, date_fin=:dateFin, statut=:statut, heure_total=:heureTotal,
                id_filiere=:idFiliere, id_promotion=:idPromotion, id_semestre=:idSemestre, id_module=:idModule, id_periode=:idPeriode
                WHERE id_edt=:idEdt LIMIT 1";
            $dateFin = new DateTime($dateDebut);
            $dateFin->add(new DateInterval('P7D'));
            $param = [
                "dateDebut" => $dateDebut,
                "dateFin" => $dateFin->format("Y-m-d"),
                "statut" => 0,
                "heureTotal" => (int) $heureTotal,
                "idFiliere" => $idFiliere,
                "idPromotion" => $idPromotion,
                "idSemestre" => $idSemestre,
                "idModule" => $idModule,
                "idPeriode" => $periode->id_periode,
                "idEdt" => $idEdt,
            ];
            $reponse = $this->insertion_update_simples($requetteEdt, $param);

            // la verification des horaires
            if (empty($horaires)) {
                throw new Exception("Créneau horaire invalide (aucun créneau, ou heure de début/fin manquante).");
            }
            // La suppression des anciens horaires
            $this->insertion_update_simples("DELETE FROM horaire WHERE id_edt=?", [$idEdt]);

            // les requettes pour inserer les nouveaux horaires ou des taches
            $requetteHoraire = "INSERT INTO horaire(heure_debut, heure_fin, id_edt) VALUES (:heureDebut, :heureFin, :idEdt)";
            $requetteTache = "INSERT INTO tache(type_tache, id_horaire, id_jour) VALUES (:typeTache, :idHoraire, :idJour)";
            foreach ($horaires as $horaire) {
                extract($horaire);
                if (empty(trim($heureDebut)) || empty(trim($heureFin))) {
                    throw new Exception("Créneau horaire invalide (aucun créneau, ou heure de début/fin manquante).");
                }
                // l'insertion d'un horaire
                $param = [
                    "heureDebut" => $heureDebut,
                    "heureFin" => $heureFin,
                    "idEdt" => $idEdt,
                ];
                $reponse = $this->insertion_update_simples_insert_id($requetteHoraire, $param);
                $idHoraire = $reponse['lastInsertId'];

                // la verification des taches pour chaque horaire
                if (empty($taches)) {
                    throw new Exception("Un créneau n'a aucun jour associé.");
                }

                foreach ($taches as $tache) {
                    if (!$this->isArrayDataValid($tache)) {
                        throw new Exception("Tâche invalide (type ou jour manquant).");
                    }
                    extract($tache);

                    // l'insertion d'un horaire
                    $param = [
                        "typeTache" => $typeTache,
                        "idHoraire" => $idHoraire,
                        "idJour" => $idJour,
                    ];
                    $this->insertion_update_simples($requetteTache, $param);
                }
            }

            // la fin de la transaction
            $connection->commit();
            $this->set_flash("EDT ajouté avec succès", "primary");
        } catch (Exception $e) {
            $connection->rollBack();
            $this->set_flash($e->getMessage() . " : !Veuillez bien verifier vos données");
            return;
        }
    }

    // ============================================================
    //  Vérifications intelligentes : budget d'heures + conflits
    // ============================================================

    // Cellules réellement occupées par la grille : un tuple (jour, début, fin) par tâche utile.
    private function cellsFromHoraires($horaires)
    {
        $cells = [];
        foreach ((array) $horaires as $h) {
            $debut = trim((string) ($h['heureDebut'] ?? ''));
            $fin = trim((string) ($h['heureFin'] ?? ''));
            if ($debut === '' || $fin === '') continue;
            foreach (($h['taches'] ?? []) as $t) {
                $type = strtolower(trim((string) ($t['typeTache'] ?? '')));
                $jour = $t['idJour'] ?? null;
                if ($jour === null || $jour === '' || $type === '' || $type === 'x') continue;
                $cells[] = ['jour' => (int) $jour, 'debut' => $debut, 'fin' => $fin];
            }
        }
        return $cells;
    }

    // Identifiants distincts (salle / enseignant) d'une liste d'affectations.
    private function idsDistincts($enseignants, $key)
    {
        $ids = [];
        foreach ((array) $enseignants as $e) {
            $v = is_array($e) ? ($e[$key] ?? null) : null;
            if (!empty($v)) $ids[(int) $v] = (int) $v;
        }
        return array_values($ids);
    }

    // Affectations (enseignant / salle / heures) déjà enregistrées pour un EDT (utile en édition).
    private function enseignantsOfEdt($idEdt)
    {
        $out = [];
        $stmt = $this->bdd()->prepare("SELECT id_enseignant, id_salle, nombre_heure, type_cours FROM enseignant_edt WHERE id_edt = ?");
        $stmt->execute([$idEdt]);
        foreach ($stmt->fetchAll(PDO::FETCH_OBJ) as $r) {
            $out[] = ['enseignant' => $r->id_enseignant, 'salle' => $r->id_salle, 'nombreHeure' => $r->nombre_heure, 'typeCours' => $r->type_cours];
        }
        return $out;
    }

    // Refuse une affectation d'heures supérieure au volume du module (cm + td + tp).
    private function verifierBudgetHeures($idUeModule, $enseignants)
    {
        $stmt = $this->bdd()->prepare("SELECT cm, td, tp FROM ue_module WHERE id_ue_module = ?");
        $stmt->execute([$idUeModule]);
        $mod = $stmt->fetch(PDO::FETCH_OBJ);
        if (!$mod) return;
        $budget = (int) $mod->cm + (int) $mod->td + (int) $mod->tp;
        $somme = 0;
        foreach ((array) $enseignants as $e) {
            $somme += (int) (is_array($e) ? ($e['nombreHeure'] ?? 0) : 0);
        }
        if ($budget > 0 && $somme > $budget) {
            throw new Exception("Heures affectées ($somme h) supérieures au volume du module ($budget h). Ajustez la répartition CM/TD/TP.");
        }
    }

    // Détecte les conflits : même salle ou même enseignant sur des créneaux qui se chevauchent,
    // dans la même période. Retourne une liste de messages lisibles (vide = aucun conflit).
    public function detecterConflits($salleIds, $enseignantIds, $cells, $idPeriode, $excludeEdtId = null)
    {
        $resultat = ['salle' => [], 'enseignant' => []];
        if (empty($cells) || (empty($salleIds) && empty($enseignantIds))) return $resultat;

        $params = [':periode' => $idPeriode];
        $orParts = [];
        foreach ($cells as $i => $c) {
            $orParts[] = "(t.id_jour = :j$i AND h.heure_debut < :f$i AND h.heure_fin > :d$i)";
            $params[":j$i"] = $c['jour'];
            $params[":d$i"] = $c['debut'];
            $params[":f$i"] = $c['fin'];
        }
        $overlap = '(' . implode(' OR ', $orParts) . ')';
        $exclude = '';
        if (!empty($excludeEdtId)) { $exclude = " AND e.id_edt <> :exclude"; $params[':exclude'] = $excludeEdtId; }

        $from = "FROM enseignant_edt ee
            JOIN edt e ON e.id_edt = ee.id_edt
            JOIN horaire h ON h.id_edt = e.id_edt
            JOIN tache t ON t.id_horaire = h.id_horaire
            JOIN jour j ON j.id_jour = t.id_jour
            JOIN salle s ON s.id_salle = ee.id_salle
            JOIN enseignants ens ON ens.enseignant_id = ee.id_enseignant
            LEFT JOIN filiere f ON f.id_filiere = e.id_filiere
            WHERE t.type_tache NOT IN ('x', '') AND e.id_periode = :periode $exclude AND $overlap";

        if (!empty($salleIds)) {
            $p = $params; $in = [];
            foreach (array_values($salleIds) as $k => $id) { $in[] = ":s$k"; $p[":s$k"] = $id; }
            $sql = "SELECT DISTINCT s.nom_salle AS lib, j.nom_jour AS jour,
                        TIME_FORMAT(h.heure_debut, '%H:%i') AS d, TIME_FORMAT(h.heure_fin, '%H:%i') AS f, f.sigle_filiere AS fil
                    $from AND ee.id_salle IN (" . implode(', ', $in) . ")";
            $stmt = $this->bdd()->prepare($sql);
            $stmt->execute($p);
            foreach ($stmt->fetchAll(PDO::FETCH_OBJ) as $r) {
                $resultat['salle'][] = "Salle « {$r->lib} » déjà occupée " . mb_strtolower($r->jour) . " {$r->d}-{$r->f}" . ($r->fil ? " ({$r->fil})" : "");
            }
        }
        if (!empty($enseignantIds)) {
            $p = $params; $in = [];
            foreach (array_values($enseignantIds) as $k => $id) { $in[] = ":en$k"; $p[":en$k"] = $id; }
            $sql = "SELECT DISTINCT TRIM(CONCAT(ens.enseignant_nom, ' ', ens.enseignant_prenom)) AS lib, j.nom_jour AS jour,
                        TIME_FORMAT(h.heure_debut, '%H:%i') AS d, TIME_FORMAT(h.heure_fin, '%H:%i') AS f, f.sigle_filiere AS fil
                    $from AND ee.id_enseignant IN (" . implode(', ', $in) . ")";
            $stmt = $this->bdd()->prepare($sql);
            $stmt->execute($p);
            foreach ($stmt->fetchAll(PDO::FETCH_OBJ) as $r) {
                $resultat['enseignant'][] = "Enseignant « {$r->lib} » déjà occupé " . mb_strtolower($r->jour) . " {$r->d}-{$r->f}" . ($r->fil ? " ({$r->fil})" : "");
            }
        }
        return $resultat;
    }
}