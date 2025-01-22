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
    public function ajouterEdt($edt, $horaires)
    {
        try {
            $connection = $this->bdd();
            // le debut de la transaction
            $connection->beginTransaction();

            // La verification des infos de base de l'edt
            if (!$this->isArrayDataValid($edt)) {
                throw new Exception("Données Edt Invalide");
            }
            $periode = $this->getCurrentPeriode();
            $this->e(extract($edt));
            $requetteEdt = "INSERT INTO edt(date_creation, date_debut, date_Fin, statut, heure_total, id_filiere, id_promotion, id_module, id_enseignant, id_salle, id_periode) 
            VALUES (:dateCreation, :dateDebut, :dateFin, :statut, :heureTotal, :idFiliere, :idPromotion, :idModule, :idEnseignant, :idSalle, :idPeriode)";
            $dateFin = new DateTime($dateDebut);
            $dateFin->add(new DateInterval('P7D'));
            $param = [
                "dateCreation" => date("Y-m-d"),
                "dateDebut" => $dateDebut,
                "dateFin" => $dateFin->format("Y-m-d"),
                "statut" => 0,
                "heureTotal" => $heureTotal,
                "idFiliere" => $idFiliere,
                "idPromotion" => $idPromotion,
                "idModule" => $idModule,
                "idEnseignant" => $idEnseignant,
                "idSalle" => $idSalle,
                "idPeriode" => $periode->id_periode,


            ];
            $reponse = $this->insertion_update_simples_insert_id($requetteEdt, $param);
            $idEdt = $reponse['lastInsertId'];

            // la verification des horaires
            if (empty($horaires)) {
                throw new Exception("Données Horaires Invalide");
            }

            // les requettes pour inserer des horaires ou des taches
            $requetteHoraire = "INSERT INTO horaire(heure_debut, heure_fin, id_edt) VALUES (:heureDebut, :heureFin, :idEdt)";
            $requetteTache = "INSERT INTO tache(type_tache, id_horaire, id_jour) VALUES (:typeTache, :idHoraire, :idJour)";
            foreach ($horaires as $horaire) {
                $this->e(extract($horaire));
                if (empty(trim($heureDebut)) || empty(trim($heureFin))) {
                    throw new Exception("Données Horaires Invalide");
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
                    throw new Exception("Données HorairesT Invalide");
                }

                foreach ($taches as $tache) {
                    if (!$this->isArrayDataValid($tache)) {
                        throw new Exception("Données HoraireT2 Invalide");
                    }
                    $this->e(extract($tache));

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

    // La methode pour recuperer les infos d'un edt
    public function getInfoEdt($idEdt)
    {
        $infoEdt = [];
        $connection = $this->bdd();

        try {
            // le debut de la transaction
            $connection->beginTransaction();

            // la recuperation de l'edt
            $requetteEdt = "SELECT id_edt, date_creation, date_debut, date_fin, statut, id_module, id_promotion,
                edt.id_enseignant, enseignant_prenom, enseignant_nom, enseignant_telephone,   
                edt.id_salle, nom_salle, capacite_salle FROM edt
                INNER JOIN enseignants ON edt.id_enseignant=enseignants.enseignant_id
                INNER JOIN salle ON edt.id_salle=salle.id_salle  WHERE id_edt=? ";
            $resultat = $this->select_data_table_join_where($requetteEdt, [$idEdt]);
            $edt = $resultat[0];
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
            $requettePromotion = "SELECT id_promotion, annee_universitaire, statut, promotion.id_parcours, nom_filiere, sigle_filiere, nom_semestre, sigle_semestre 
            FROM promotion INNER JOIN filiere ON promotion.id_filiere=filiere.id_filiere
            INNER JOIN parcours ON promotion.id_parcours=parcours.id_parcours INNER JOIN semestre ON parcours.id_semestre=semestre.id_semestre
            WHERE id_promotion=?";
            $resultat = $this->select_data_table_join_where($requettePromotion, [$edt->id_promotion]);
            $promotion = $resultat[0];

            // la fin de la transaction
            $connection->commit();

            $infoEdt = [
                "module" => $module,
                "promotion" => $promotion,
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

    // la fonction pour recuperer la liste des emplois
    public function listeEdts()
    {
        $edts = [];
        $listeEdts = $this->SelectAllDataOrder("id_edt", "edt", "id_edt");
        foreach ($listeEdts as $edt) {
            $infoEdt = $this->getInfoEdt($edt->id_edt);
            $edts[] =  $infoEdt;
        }

        return $edts;
    }


    public function trierListeEdt($idFiliere, $idPromotion = null)
    {
        $whereCondition = "id_filiere=? AND id_promotion=?";
        $whereValues = [$idFiliere, $idPromotion];
        if ($idPromotion == null || empty($idPromotion)) {
            $whereCondition = "id_filiere=?";
            $whereValues = [$idFiliere];
        }
        $edts = [];

        $listeEdts = $this->FetchAllSelectWhere("id_edt", "edt", $whereCondition, $whereValues);
        foreach ($listeEdts as $edt) {
            $infoEdt = $this->getInfoEdt($edt->id_edt);
            $edts[] =  $infoEdt;
        }

        return $edts;
    }
}
