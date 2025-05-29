<?php
class Note extends Model
{

    public function findEtudiantByClasse($id_promotion)
    {

        $query = "SELECT * 
                    FROM etudiant
                    INNER JOIN promotion ON etudiant.id_promotion = promotion.id_promotion
                    INNER JOIN filiere ON promotion.id_filiere = filiere.id_filiere
                    INNER JOIN parcours ON promotion.id_parcours = parcours.id_parcours
                    INNER JOIN semestre ON parcours.id_semestre = semestre.id_semestre
                    WHERE etudiant.id_promotion = :id_promotion";
        $bdd = $this->bdd();
        $stmt = $bdd->prepare($query);
        $stmt->bindParam(':id_promotion', $id_promotion, PDO::PARAM_INT);
        $stmt->execute();

        $info_etudiant = $stmt->fetchAll(PDO::FETCH_OBJ);
        return $info_etudiant;
    }
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
                FROM parcours  INNER JOIN semestre ON parcours.id_semestre=semestre.id_semestre WHERE id_filiere=? AND id_parcours >=? LIMIT 2";
            $semestres = $this->select_data_table_join_where($requetteSemestre, [$promotions[0]->id_filiere, $promotions[0]->id_parcours]);
            return [
                'promotion' => $promotions[0],
                'semestres' => $semestres,
            ];
        }
        return [
            'promotion' => [],
            'semestres' => [],
        ];
    }

    public function getNotes($idPromotion, $idSemestre, $idModule)
    {
        // La verification  de l'existance des notes des étudiants
        try {
            $query = "SELECT id_note, note_devoir, note_evaluation, note_session, moyenne_module, note_etudiant.id_etudiant, 
                    note_etudiant.id_promotion, note_etudiant.id_module, nom_prenom_etudiant, prenom, matricule_etudiant,
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

        $etudiants = $this->findEtudiantByClasse($idPromotion);
        foreach ($etudiants as $etudiant) {
            //insertion de note des étudiants un à un
            try {
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
            } catch (Exception $e) {
                echo "Erreur d'accès à la base de donnée";
            }
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
        $moyennesUe = [];
        $connection = $this->bdd();

        try {
            // le debut de la transaction
            $connection->beginTransaction();

            $infosSemestre = $this->getInfoSemestre($idSemestre);
            $etudiants = $this->findEtudiantByClasse($idPromotion);



            foreach ($etudiants as $etudiant) {
                $moyenne = $this->isValidateSemestre($etudiant->id_etudiant, $idSemestre);
                $moyenneSemestre[] = ['etudiant' => $etudiant, 'moyenne' => $moyenne['moyenne']];
                $ues = [];
                foreach ($infosSemestre as $ue) {

                    $moyenneUe = $this->isValidateUe($etudiant->id_etudiant, $ue[0]->id_ue);
                    $ues[] = ['nom_ue' => $ue[0]->nom_ue, 'moyenne' => $moyenneUe['moyenne']];
                }

                $moyennesUe[] = ['etudiant' => $etudiant, 'ues' => $ues];
            }

            // la fin de la transaction
            $connection->commit();
        } catch (Exception $e) {
            $connection->rollBack();
            $this->set_flash("Erreur : " . $e->getMessage(), "warning");
        }

        return [
            "infosSemestre" => $infosSemestre,
            "moyennesSemestre" => $moyenneSemestre,
            "moyennesUe" => $moyennesUe
        ];
    }

    public function getAllMoyenneLicenceEtudiants($idPromotion)
    {
        $moyennesLicence = [];
        $moyennesSemestre = [];
        $etudiants = $this->findEtudiantByClasse($idPromotion);
        $infosLicence = $this->getInfoPromotion($idPromotion);
        foreach ($etudiants as $etudiant) {
            $moyenne = $this->isValidateClasse($etudiant->id_etudiant, $idPromotion)['moyenne'];
            $moyennesLicence[] = ['etudiant' => $etudiant, 'moyenne' => $moyenne];

            $semestres = [];
            foreach ($infosLicence['semestres'] as $semestre) {
                $note = $this->isValidateSemestre($etudiant->id_etudiant, $semestre->id_parcours)['moyenne'];
                $semestres[] = ['sigle_semestre' => $semestre->sigle_semestre, 'moyenne' => $note];
            }
            $moyennesSemestre[] = ['etudiant' => $etudiant, 'semestres' => $semestres];
        }

        return [
            'infosLicence' => $infosLicence,
            'moyennesLicence' => $moyennesLicence,
            'moyennesSemestre' => $moyennesSemestre
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


    // le methode pour savoir si un etudiant a valider un module
    public function isValidateModule($idEtudiant, $idModule)
    {
        // Requête SQL pour récupérer la moyenne de l'étudiant dans un module
        $sql = "SELECT moyenne_module 
                FROM note_etudiant 
                WHERE id_etudiant = ? AND id_module = ? 
                ORDER BY id_note DESC LIMIT 1";

        // Utilisation de la méthode générique
        $resultat = $this->FetchAllSelectWhere("moyenne_module", "note_etudiant", "id_etudiant = ? AND id_module = ?", [$idEtudiant, $idModule]);

        // Vérifie si une note a été trouvée 
        if (!empty($resultat)) {
            $moyenne = $resultat[0]->moyenne_module;
            $isValidate = $moyenne >= 10;
            return [
                'isValidate' => $isValidate,
                'moyenne' => $moyenne
            ];
        } else {
            // Aucun résultat trouvé
            return [
                'isValidate' => false,
                'moyenne' => null
            ];
        }
    }

    // la methode pour savoir si un etudiant a valider un ue*
    public function isValidateUe($idEtudiant, $idUe)
    {
        $moyenne = 0;
        $infosUe = $this->getInfosUe($idUe);
        foreach ($infosUe as $module) {
            $resultatModule = $this->isValidateModule($idEtudiant, $module->id_ue_module);
            $moyenne += ($resultatModule['moyenne'] == null) ? 0 : $resultatModule['moyenne'];
        }
        $moyenne /= count($infosUe);
        $isValidate = $moyenne >= 10;
        return [
            'isValidate' => $isValidate,
            'moyenne' => number_format($moyenne, 2)
        ];
    }

    // la methode pour savoir si un etudiant a valider un semestre 
    public function isValidateSemestre($idEtudiant, $idSemestre)
    {
        $infosSemestre = $this->getInfoSemestre($idSemestre);
        $moyenne = 0;
        $coeficient = 0;
        $projetTutoreNames = ['GESTIONDEPROJET', 'PROJET', 'PROJETTUTORE'];

        foreach ($infosSemestre as $ue) {
            $coeficientUe = 0;
            foreach ($ue as $module) {
                $coeficientUe += $module->coeficient;
            }

            $resultatUe = $this->isValidateUe($idEtudiant, $ue[0]->id_ue);
            if ((in_array(strtoupper(str_replace(" ", "", $ue[0]->nom_ue)), $projetTutoreNames)) && $resultatUe['isValidate'] == false) {
                return [
                    'isValidate' => false,
                    'moyenne' => 0
                ];
            }
            $moyenneUe = $resultatUe['moyenne'] * $coeficientUe;

            $coeficient += $coeficientUe;
            $moyenne += ($moyenneUe == null) ? 0 : $moyenneUe;
        }
        $moyenne /= $coeficient;
        $isValidate = $moyenne >= 10;
        return [
            'isValidate' => $isValidate,
            'moyenne' => number_format($moyenne, 2)
        ];
    }

    public function isValidateClasse($idEtudiant, $idPromotion)
    {
        $moyenneLicence = 0;
        $infosLicence = $this->getInfoPromotion($idPromotion);
        $semestres = $infosLicence['semestres'];
        foreach ($semestres as $semestre) {
            $moyenneSemestre = $this->isValidateSemestre($idEtudiant, $semestre->id_parcours);
            // if ($moyenneSemestre['isValidate' == false]) {
            //     return ['isValidate' => false, 'moyenne' => 0];
            // }
            $moyenneLicence += $moyenneSemestre['moyenne'];
        }
        $isValidate = $moyenneLicence >= 10;
        return [
            'isValidate' => $isValidate,
            'moyenne' => $moyenneLicence / 2
        ];
    }
}