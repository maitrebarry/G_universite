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
        
        $query = "SELECT ue.nom_ue, ue.sigle_ue, module.nom_module, module.sigle_module, ue_module.coeficient
        FROM  ue_module INNER JOIN module ON  module.id_module =ue_module.id_module
        INNER JOIN ue ON  ue_module.id_ue =ue.id_ue 
        WHERE id_ue_module=? Limit 1";

        $data=[$idModule];
        $infoModule = $this->select_data_table_join_where ($query, $data);
        if(!empty($infoModule)){
            return $infoModule[0];
        }else{
            return [];
        }
       
    }

    /*********************************************************************************************************** */
    //Function to get All notes of sutendents with joint
    public function getNotes($idPromotion, $idModule)
    {
        // La verification  de l'existance des notes des étudiants
        try {
            $query = "SELECT id_note, note_devoir, note_evaluation, note_session, note_etudiant.id_etudiant, 
                    note_etudiant.id_promotion, note_etudiant.id_module,nom_prenom_etudiant, matricule_etudiant,
                    genre_etudiant

                    FROM  note_etudiant INNER JOIN etudiant ON note_etudiant.id_etudiant= etudiant.id_etudiant
                    WHERE note_etudiant.id_promotion = ? AND note_etudiant.id_module=?";

            $notes_etudiant = $this->select_data_table_join_where($query, [$idPromotion, $idModule]);
        } catch (Exception $e) {
            $this->set_flash($e->getMessage() . " : !Veuillez bien verifier vos données");
            //throw $th;
        }
        return $notes_etudiant;
    }
    /********************************************************************************************************** */
    public function initialiseNotes($idPromotion, $idModule)
    {

        $etudiantModel = new Etudiant();
        $etudiants = $etudiantModel->trie_liste_etudiant($idPromotion);
        foreach ($etudiants as $etudiant) {
            //insertion de note des étudiants un à un
            $query = "INSERT INTO note_etudiant(id_etudiant, id_promotion, id_module) 
            VALUES (:idEtudiant, :idPromotion, :idModule)
            ON DUPLICATE KEY UPDATE id_module = :idModule";

            $query1 = "INSERT INTO note_etudiant(id_etudiant, id_promotion, id_module) VALUES (:idEtudiant, :idPromotion, :idModule)";

            $data = ["idEtudiant" => $etudiant->id_etudiant, "idPromotion" => $idPromotion, "idModule" => $idModule];
            $this->insertion_update_simples($query, $data);
        }
    }
    /********************************************************************************************************** */
    //Function to get all notes of studients
    public function getAllNotesEtudiant($idPromotion, $idModule)
    {
        $notes_etudiant = [];
        $connection = $this->bdd();
        try {

            // le debut de la transaction
            $connection->beginTransaction(); //Cette une fonction prédefie en php

            $notes_etudiant = $this->getNotes($idPromotion, $idModule);
            //Vérification si les notes  sont pas vides
            if (empty($notes_etudiant) || $notes_etudiant == null) {
                //si oui on initialise les notes en inserant une note par defaut pour les étudiants de la promotion sélectionnées
                $this->initialiseNotes($idPromotion, $idModule);
                //On recupère les notes des étudiants depuis la base de données avece la foncion qui suit
                $notes_etudiant = $this->getNotes($idPromotion, $idModule);
            }


            // la fin de la transaction
            $connection->commit();
        } catch (Exception $e) {
            $connection->rollBack();
            $this->set_flash($e->getMessage() . " : !Veuillez bien verifier vos données");
            die($e->getMessage());
        }
        return $notes_etudiant;
    }

    /************************************************************************************************************** */
    public function save_note_etudiant($idNote, $noteDevoir, $noteEvaluation, $noteSession)
    {
        $query = "UPDATE note_etudiant SET note_devoir=?, note_evaluation=?, note_session=? WHERE id_note=?";
        $data = [$noteDevoir, $noteEvaluation, $noteSession, $idNote];
        $note_modifier = $this->insertion_update_simples($query, $data);

        if (!$note_modifier) {
            $this->set_flash("modification échouée");
        }
        return $note_modifier;
    }

    /***************************************************************************************************************** */
}
