<?php
class Note extends Model
{
    // la methode pour pour retourner la dernière peridode crée
    public function getCurrentPeriode()
    {
        $periode = $this->FetchSelectWhere("*", "periode", "status LIKE 'inachevé'");
        return $periode;
    }

    public function getInfoModule($idModule)
    {
        $query = "SELECT ue.nom_ue, ue.sigle_ue, module.nom_module, module.sigle_module, ue_module.coeficient, ue_module.id_ue
        FROM  ue_module INNER JOIN module ON  module.id_module =ue_module.id_module
        INNER JOIN ue ON  ue_module.id_ue =ue.id_ue 
        WHERE id_ue_module=? Limit 1";

        $data = [$idModule];
        $infoModule = $this->select_data_table_join_where($query, $data);
        if (!empty($infoModule)) {
            return $infoModule[0];
        } else {
            return [];
        }
    }

    public function getInfosUe($idUe)
    {
        $query = "SELECT ue.id_ue, ue.nom_ue, ue.sigle_ue, module.nom_module, module.sigle_module, 
        ue_module.coeficient, ue_module.id_ue_module FROM  ue_module 
        INNER JOIN module ON  module.id_module =ue_module.id_module
        INNER JOIN ue ON  ue_module.id_ue =ue.id_ue 
        WHERE ue_module.id_ue=?";

        $data = [$idUe];
        $infosUe = $this->select_data_table_join_where($query, $data);
        if (!empty($infosUe)) {
            return $infosUe;
        } else {
            return [];
        }
    }

    public function getInfoSemestre($idSemestre)
    {
        try {

            $ues = $this->FetchAllSelectWhere("*", "ue", "id_parcours=?", [$idSemestre]);

            foreach ($ues as $ue) {
                $infosUe = $this->getInfosUe($ue->id_ue);
                $infosSemestre[] = $infosUe;
            }
        } catch (Exception $e) {
        }

        return $infosSemestre;
    }


    public function getInfoPromotion($idPromotion)
    {
        $requette = "SELECT id_promotion, annee_universitaire, promotion.statut, promotion.id_parcours, promotion.id_filiere, nom_filiere, 
            sigle_filiere, nom_semestre, sigle_semestre FROM promotion INNER JOIN filiere ON promotion.id_filiere=filiere.id_filiere
            INNER JOIN parcours ON promotion.id_parcours=parcours.id_parcours INNER JOIN semestre ON parcours.id_semestre=semestre.id_semestre
            WHERE promotion.id_promotion=? LIMIT 1";

        $promotions = $this->select_data_table_join_where($requette, [$idPromotion]);

        if (!empty($promotions)) {
            $currentSemestre = $promotions[0]->sigle_semestre;

            // la recuperation des semestres de la filière
            $requetteSemestre = "SELECT id_parcours, parcours.id_semestre, nom_semestre, sigle_semestre 
                FROM parcours  INNER JOIN semestre ON parcours.id_semestre=semestre.id_semestre WHERE id_filiere=? AND id_parcours <= ?";
            $semestres = $this->select_data_table_join_where($requetteSemestre, [$promotions[0]->id_filiere, $promotions[0]->id_parcours]);
            return [
                'promotion' => $promotions[0],
                'semestres' => $semestres,
            ];
        }
        return [];
    }

    public function getNotes($idPromotion, $idSemestre, $idModule)
    {
        // La verification  de l'existance des notes des étudiants
        try {
            $query = "SELECT id_note, note_devoir, note_evaluation, note_session, moyenne_module, note_etudiant.id_etudiant, 
                    note_etudiant.id_promotion, note_etudiant.id_module, nom_prenom_etudiant, matricule_etudiant,
                    genre_etudiant
                    FROM  note_etudiant INNER JOIN etudiant ON note_etudiant.id_etudiant= etudiant.id_etudiant
                    WHERE note_etudiant.id_promotion = ? AND note_etudiant.id_parcours = ? AND note_etudiant.id_module=?";

            $notes_etudiant = $this->select_data_table_join_where($query, [$idPromotion, $idSemestre, $idModule]);
        } catch (Exception $e) {
            $this->set_flash($e->getMessage() . " : !Veuillez bien verifier vos données");
            //throw $th;
        }
        return $notes_etudiant;
    }

    public function initialiseNotes($idPromotion, $idSemestre, $idUe, $idModule)
    {

        $etudiantModel = new Etudiant();
        $etudiants = $etudiantModel->trie_liste_etudiant($idPromotion);
        foreach ($etudiants as $etudiant) {
            //insertion de note des étudiants un à un
            $query = "INSERT INTO note_etudiant(id_etudiant, id_promotion, id_parcours, id_ue, id_module) 
            VALUES (:idEtudiant, :idPromotion, :idParcours, :idUe, :idModule)
            ON DUPLICATE KEY UPDATE id_module = :idModule";

            $data = [
                "idEtudiant" => $etudiant->id_etudiant,
                "idPromotion" => $idPromotion,
                "idUe" => $idUe,
                "idModule" => $idModule,
                "idParcours" => $idSemestre
            ];
            $this->insertion_update_simples($query, $data);
        }
    }

    public function isProgrammer($idPromotion, $idSemestre, $idModule): bool
    {
        $data = [$idPromotion, $idModule];
        $edt = $this->FetchSelectWhere("id_edt", "edt", "edt.id_promotion=?  AND edt.id_module=?", $data);
        if (empty($edt) || $edt == null) {
            return false;
        }
        return true;
    }

    public function getAllNotesEtudiant($idPromotion, $idSemestre, $idModule)
    {
        $notes_etudiant = [];
        $connection = $this->bdd();
        try {

            // le debut de la transaction
            $connection->beginTransaction(); //Cette une fonction prédefie en php

            // if (!$this->isProgrammer($idPromotion, $idSemestre, $idModule)) {
            //     throw new Exception(" Cette Promotion n 'a pas été programmée en ce module");
            // }

            $notes_etudiant = $this->getNotes($idPromotion, $idSemestre, $idModule);
            //Vérification si les notes  sont pas vides
            if (empty($notes_etudiant) || $notes_etudiant == null) {

                $infosModule = $this->getInfoModule($idModule);
                $this->initialiseNotes($idPromotion, $idSemestre, $infosModule->id_ue, $idModule);

                $notes_etudiant = $this->getNotes($idPromotion, $idSemestre, $idModule);
            }


            // la fin de la transaction
            $connection->commit();
        } catch (Exception $e) {
            $connection->rollBack();
            $this->set_flash("Erreur : " . $e->getMessage(), "warning");
        }
        return $notes_etudiant;
    }

    public function save_note_etudiant($idNote, $noteDevoir, $noteEvaluation, $noteSession, $moyenneModule)
    {
        $query = "UPDATE note_etudiant SET note_devoir=?, note_evaluation=?, note_session=?, moyenne_module=? WHERE id_note=?";
        $data = [$noteDevoir, $noteEvaluation, $noteSession, $moyenneModule, $idNote];
        $note_modifier = $this->insertion_update_simples($query, $data);

        if (!$note_modifier) {
            $this->set_flash("modification échouée");
        }
        return $note_modifier;
    }

    public function getAllMoyenneUeEtudiants($idPromotion, $idSemestre, $idUe = null)
    {

        $moyeneUeEtudiants = [];
        $infosUe = $this->getInfosUe($idUe);
        foreach ($infosUe as $module) {
            $moyeneModuleEtudiants = $this->getAllNotesEtudiant($idPromotion, $idSemestre, $module->id_ue_module);
            $moyeneUeEtudiants[] = $moyeneModuleEtudiants;
        }

        return ['infosUe' => $infosUe, "moyenneUeEtudiants" => $moyeneUeEtudiants];
    }


    public function getAllMoyenneSemestreEtudiants($idPromotion, $idSemestre)
    {

        $moyenneSemestre = [];
        $connection = $this->bdd();

        try {
            // le debut de la transaction
            $connection->beginTransaction();

            $infosSemestre = $this->getInfoSemestre($idSemestre);
            foreach ($infosSemestre as $ue) {
                $query = "SELECT AVG(moyenne_module) AS moyenne_ue, note_etudiant.id_etudiant, note_etudiant.id_ue
                    FROM  note_etudiant 
                    WHERE note_etudiant.id_promotion = ? AND note_etudiant.id_parcours = ? AND note_etudiant.id_ue=? 
                    GROUP BY note_etudiant.id_etudiant";

                $moyenneUe = $this->select_data_table_join_where($query, [$idPromotion, $idSemestre, $ue[0]->id_ue]);

                $moyenneSemestre[] = [
                    'infosUe' => $ue,
                    "moyennesUe" => $moyenneUe
                ];
            }

            // la fin de la transaction
            $connection->commit();
        } catch (Exception $e) {
            $connection->rollBack();
            $this->set_flash("Erreur : " . $e->getMessage(), "warning");
        }

        return [
            "infosSemestre" => $infosSemestre,
            "moyennesSemestre" => $moyenneSemestre
        ];
    }

    public function getAllMoyenneLicenceEtudiants($idPromotion, $licence)
    {
        $semestres = explode('|', trim($licence));
        $moyennesLicence = [];
        $infosLicence = [];
        foreach ($semestres as $semestre) {
            $moyennesSemestre = $this->getAllMoyenneSemestreEtudiants($idPromotion, $semestre);
            $moyennesLicence[] = $moyennesSemestre;

            $requetteSemestre = "SELECT id_parcours, parcours.id_semestre, nom_semestre, sigle_semestre 
            FROM parcours  INNER JOIN semestre ON parcours.id_semestre=semestre.id_semestre WHERE id_parcours = ? LIMIT 1";
            $infosLicence[] = $this->select_data_table_join_where($requetteSemestre, [$semestre])[0];
        }

        return [
            "moyennesLicence" => $moyennesLicence,
            "infosLicence" => $infosLicence
        ];
    }

    public function getNoteEtudiant($idEtudiant, $idPromotion, $idSemestre)
    {

        $connection = $this->bdd();

        try {
            // le debut de la transaction
            $connection->beginTransaction();
            $ues = $this->getInfoSemestre($idSemestre);

            $requetteEtudiant = "SELECT * FROM etudiant
                    INNER JOIN promotion ON etudiant.id_promotion = promotion.id_promotion
                    INNER JOIN filiere ON promotion.id_filiere = filiere.id_filiere
                    INNER JOIN parcours ON promotion.id_parcours = parcours.id_parcours
                    INNER JOIN semestre ON parcours.id_semestre = semestre.id_semestre
                    WHERE etudiant.id_etudiant = ? LIMIT 1";
            $etudiant = $this->select_data_table_join_where($requetteEtudiant, [$idEtudiant])[0];

            $notesUes = [];

            foreach ($ues as $ue) {
                $moyenneUe = $this->FetchSelectWhere(
                    "AVG(moyenne_module) AS moyenne_ue",
                    "note_etudiant",
                    "id_promotion = ? AND id_parcours = ? AND id_ue=?  AND id_etudiant= ?",
                    [$idPromotion, $idSemestre, $ue[0]->id_ue, $idEtudiant]
                );
                $noteModules = [];

                foreach ($ue as $module) {
                    $idModule = $module->id_module;
                    $query = "SELECT id_note, note_devoir, note_evaluation, note_session, moyenne_module
                    FROM  note_etudiant 
                    WHERE note_etudiant.id_promotion = ? AND note_etudiant.id_parcours = ? 
                    AND note_etudiant.id_module=? AND note_etudiant.id_etudiant=?";
                    $noteModules[] =  $this->FetchSelectWhere(
                        "*",
                        "note_etudiant",
                        "id_promotion = ? AND id_parcours = ? AND id_module=? AND id_etudiant=?",
                        [$idPromotion, $idSemestre, $idModule, $idEtudiant]
                    );
                }

                $notesUes[] = [
                    'moyenneUe' => $moyenneUe,
                    "noteModules" => $noteModules
                ];
            }

            // la fin de la transaction
            $connection->commit();
        } catch (Exception $e) {
            $connection->rollBack();
            $this->set_flash("Erreur : " . $e->getMessage(), "warning");
        }
    }

    // public function getAllMoyennePromotionEtudiants($idPromotion)
    // {

    //     $moyenneSemestre = [];
    //     $connection = $this->bdd();

    //     try {
    //         // le debut de la transaction
    //         $connection->beginTransaction();

    //         $infosPromotion = $this->getInfoPromotion($idPromotion);
    //         $semestres = $infosPromotion['semestres'];
    //         $etudiantModel = new Etudiant();
    //         $etudiants = $etudiantModel->trie_liste_etudiant($idPromotion);
    //         foreach ($semestres as $semestre) {
    //             $moyenneSemestre = $this->getAllMoyenneSemestreEtudiants($idPromotion, $semestre->id_parcours);

    //         }

    //         // la fin de la transaction
    //         $connection->commit();
    //     } catch (Exception $e) {
    //         $connection->rollBack();
    //         $this->set_flash("Erreur : " . $e->getMessage(), "warning");
    //     }

    //     return [
    //         "infosSemestre" => $infosSemestre,
    //         "moyennesSemestre" => $moyenneSemestre
    //     ];
    // }
}
