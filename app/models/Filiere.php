<?php

class Filiere  extends Model
{

    //! Debut de la gestion d'une filière
    // la methode pour ajouter une filière
    public function ajouter_filiere($filiere, $semestres, $ues, $modules)
    {
        $connection = $this->bdd();

        try {

            // le debut de la transaction
            $connection->beginTransaction();
            // La verification de la filière
            if (!$this->isArrayDataValid($filiere)) {
                throw new Exception("Données Filière Invalide");
            }
            // L'insertation des infos de base de la filière
            $this->e(extract($filiere));
            $requtte = "INSERT INTO filiere(nom_filiere, sigle_filiere, id_departement) VALUES (:nomFiliere, :sigleFiliere, :idDepartement)";
            $param = [
                'nomFiliere' => $nomFiliere,
                "sigleFiliere" => $sigleFiliere,
                "idDepartement" => $idDepartement
            ];
            $reponse = $this->insertion_update_simples_insert_id($requtte, $param);
            $idFiliere = $reponse['lastInsertId'];


            // L'insertion des diférents parcours  de la filière ( la relation entre filière et semestre )
            $requtteParcours = 'INSERT INTO parcours(id_filiere, id_semestre) VALUES (:idFiliere, :idSemestre)';
            if (empty($semestres)) {
                throw new Exception("Données Semestre Invalide");
            }
            foreach ($semestres as $semestre) {
                if (!$this->isArrayDataValid($semestre)) {
                    throw new Exception("Données Semestre Invalide");
                }
                $this->e(extract($semestre));
                $param = [
                    'idFiliere' => $idFiliere,
                    'idSemestre' => $idSemestre
                ];
                $reponse = $this->insertion_update_simples_insert_id($requtteParcours, $param);
                $lastInsertIdParcours = $reponse['lastInsertId'];

                if (empty($ues)) {
                    throw new Exception("Données UE Invalide");
                }
                //l'insertion des ues de ce parcours
                $requetteUe = 'INSERT INTO ue( nom_ue, id_parcours ) VALUES ( :nomUe, :idParcours )';
                foreach ($ues as $ue) {
                    if (!$this->isArrayDataValid($ue)) {
                        throw new Exception("Données UE Invalide");
                    }
                    if ($ue['idSemestre'] == $idSemestre) {
                        $this->e(extract($ue));
                        $param = [
                            'nomUe' => $nomUe,
                            'idParcours' => $lastInsertIdParcours
                        ];
                        $reponse = $this->insertion_update_simples_insert_id($requetteUe, $param);
                        $lastInsertIdUe = $reponse['lastInsertId'];

                        if (empty($modules)) {
                            throw new Exception("Données Modules Invalide");
                        }
                        // l'insertion des modules liés à chaque ue (la relation en ue et module)
                        $requetteModule = "INSERT INTO ue_module(id_ue, id_module, code_module, coeficient, cm, td, tp, tpe) 
                        VALUES (:idUe, :idModule, :code, :coeficient, :cm, :td, :tp, :tpe)";
                        foreach ($modules as $module) {
                            if (!$this->isArrayDataValid($module) || count($module) < 5) {
                                throw new Exception("Données Modules Invalide");
                            }
                            if ($module['idSemestre'] == $idSemestre && $module['nomUe'] == $nomUe) {

                                $this->e(extract($module));
                                $param = [
                                    'idUe' => $lastInsertIdUe,
                                    'idModule' => $idModule,
                                    'code' => $moduleCode,
                                    'coeficient' => $moduleCoeficient,
                                    'cm' => $moduleCm,
                                    'td' => $moduleTd,
                                    'tp' => $moduleTp,
                                    'tpe' => $moduleTpe,
                                ];
                                $this->insertion_update_simples($requetteModule, $param);
                            }
                        }
                    }
                }
            }

            // la fin de la transaction
            $connection->commit();
            $this->set_flash("Filière ajouté avec succès", "success");
        } catch (Exception $e) {
            $connection->rollBack();
            $this->set_flash($e->getMessage() . " : !Veuillez bien verifier vos données");
            return false;
        }
    }

    // la methode pour la liste des filières par departement
    public function listeFilieresParDepartement($idDepartement = null)
    {
        if ($idDepartement != null) {
            $filieres = $this->FetchAllSelectWhere("*", "filiere", "id_departement=?", [$idDepartement]);
        } else {
            $filieres = $this->SelectAllData("*", "filiere");
        }
        return $filieres;
    }

    // la methode pour recuperer toutes les informations d'une filière
    public function apercu_filiere($idFiliere)
    {
        $connection = $this->bdd();
        $infoFiliere = [];

        try {
            // le debut de la transaction
            $connection->beginTransaction();

            // la recuperation des infos de base de la filère
            $filiere = $this->FetchSelectWhere(" *", " filiere ", " id_filiere=?", [$idFiliere]);

            // la recuperation des semestres de la filière
            $requetteSemestre = "SELECT DISTINCT id_parcours, parcours.id_semestre, nom_semestre, sigle_semestre 
            FROM parcours  INNER JOIN semestre ON 
            parcours.id_semestre=semestre.id_semestre WHERE id_filiere=?";
            $semestres = $this->select_data_table_join_where($requetteSemestre, [$idFiliere]);

            // la recupetion des des différents ue de chaque semestre
            $ues = [];
            $requetteUe = "SELECT ue.id_ue, nom_ue, sigle_ue, id_parcours, SUM(cm) AS ue_cm, SUM(td) AS ue_td, SUM(tp) AS ue_tp, SUM(tpe) 
            AS ue_tpe, SUM(coeficient) AS ue_credit  FROM ue LEFT JOIN ue_module ON ue_module.id_ue=ue.id_ue WHERE ue.id_parcours=? 
            GROUP BY ue.id_ue, nom_ue, sigle_ue, id_parcours";
            foreach ($semestres as $semestre) {
                $idSemestre = $semestre->id_parcours;
                $resultats = $this->select_data_table_join_where($requetteUe, [$idSemestre]);
                foreach ($resultats as $resultat) {
                    $ues[] = $resultat;
                }
            }

            // la recuperation des modules de chaque ue
            $modules = [];
            $requetteModule = "SELECT id_ue_module, id_ue, ue_module.id_module, code_module, coeficient, cm, td, tp, tpe, module.nom_module,
             module.sigle_module FROM ue_module INNER JOIN module ON ue_module.id_module=module.id_module WHERE id_ue=?";
            foreach ($ues as $ue) {

                $idUe = $ue->id_ue;
                $resultats = $this->select_data_table_join_where($requetteModule, [$idUe]);
                foreach ($resultats as $resultat) {
                    $modules[] = $resultat;
                }
            }

            // la fin de la transaction
            $connection->commit();

            $infoFiliere = [
                "filiere" => $filiere,
                "semestres" => $semestres,
                "ues" => $ues,
                "modules" => $modules
            ];
            return $infoFiliere;
        } catch (Exception $e) {
            $connection->rollBack();
            $this->set_flash("Impossible de recuperer les informations de cette filière");
            $this->redirect("Filieres/listeFiliere");
            return;
        }
    }


    // Methode Modification FIlière : Editer Infos
    public function editerInfoFiliere($filiere, $semestres, $ues, $modules)
    {
        $connection = $this->bdd();

        try {

            // le debut de la transaction
            $connection->beginTransaction();
            // La verification de la filière
            if (!$this->isArrayDataValid($filiere)) {
                throw new Exception("Données Filière Invalide");
            }
            // L'insertation des infos de base de la filière
            $this->e(extract($filiere));
            $requtte = "UPDATE filiere SET nom_filiere=:nomFiliere, sigle_filiere=:sigleFiliere WHERE id_filiere=:idFiliere";
            $param = [
                'nomFiliere' => $nomFiliere,
                "sigleFiliere" => $sigleFiliere,
                "idFiliere" => $idFiliere
            ];
            $this->insertion_update_simples($requtte, $param);

            if (empty($semestres)) {
                throw new Exception("Données Semestre Invalide");
            }

            foreach ($semestres as $semestre) {
                if (!$this->isArrayDataValid($semestre)) {
                    throw new Exception("Données Semestre Invalide");
                }
                $this->e(extract($semestre));

                if (empty($ues)) {
                    throw new Exception("Données UE Invalide");
                }
                //l'insertion des ues de ce parcours
                $requetteUe = 'UPDATE ue SET nom_ue=:nomUe WHERE id_ue=:idUe';
                foreach ($ues as $ue) {
                    if (!$this->isArrayDataValid($ue)) {
                        throw new Exception("Données UE Invalide");
                    }
                    if ($ue['idSemestre'] == $idSemestre) {
                        $this->e(extract($ue));
                        $param = [
                            'nomUe' => $nomUe,
                            "idUe" => $idUe
                        ];
                        $this->insertion_update_simples($requetteUe, $param);


                        if (empty($modules)) {
                            throw new Exception("Données Modules Invalide");
                        }
                        // l'insertion des modules liés à chaque ue (la relation en ue et module)
                        $requetteModule = "UPDATE ue_module SET id_module=:idModule, code_module=:code, coeficient=:coeficient, 
                        cm=:cm, td=:td, tp=:tp, tpe=:tpe WHERE id_ue_module=:idUeModule";
                        foreach ($modules as $module) {

                            if (!$this->isArrayDataValid($module) || count($module) < 5) {
                                throw new Exception("Données Modules Invalide");
                            }
                            if ($module['idSemestre'] == $idSemestre && $module['nomUe'] == $nomUe) {

                                $this->e(extract($module));
                                $param = [
                                    'idModule' => $idModule,
                                    'code' => $moduleCode,
                                    'coeficient' => $moduleCoeficient,
                                    'cm' => $moduleCm,
                                    'td' => $moduleTd,
                                    'tp' => $moduleTp,
                                    'tpe' => $moduleTpe,
                                    'idUeModule' => $idUeModule
                                ];

                                $this->insertion_update_simples($requetteModule, $param);
                            }
                        }
                    }
                }
            }

            // la fin de la transaction
            $connection->commit();
            $this->set_flash("Filière Modifier avec succès", "success");
        } catch (Exception $e) {
            $connection->rollBack();
            echo $e->getMessage();
            $this->set_flash($e->getMessage() . " : !Veuillez bien verifier vos données");
            return false;
        }
    }


    // Methode Modification FIlière : Ajouter Element
    public function ajouterElementFiliere($filiere, $semestres, $ues, $modules)
    {
        $connection = $this->bdd();

        try {

            // le debut de la transaction
            $connection->beginTransaction();
            // La verification de la filière
            if (!$this->isArrayDataValid($filiere)) {
                throw new Exception("Données Filière Invalide");
            }
            $idFiliere = $filiere['idFiliere'];

            // L'insertion des diférents parcours  de la filière ( la relation entre filière et semestre )
            $requtteParcours = 'INSERT INTO parcours(id_filiere, id_semestre) VALUES (:idFiliere, :idSemestre)';
            if (empty($semestres)) {
                throw new Exception("Données Semestre Invalide");
            }


            foreach ($semestres as $semestre) {
                if (!$this->isArrayDataValid($semestre)) {
                    throw new Exception("Données Semestre Invalide");
                }
                $this->e(extract($semestre));
                if (!isset($semestre['idParcours']) || $semestre['idParcours'] == null) {
                    $param = [
                        'idFiliere' => $idFiliere,
                        'idSemestre' => $idSemestre
                    ];
                    $reponse = $this->insertion_update_simples_insert_id($requtteParcours, $param);
                    $lastInsertIdParcours = $reponse['lastInsertId'];
                } else {
                    $lastInsertIdParcours = $semestre['idParcours'];
                }


                if (empty($ues)) {
                    throw new Exception("Données UE Invalide");
                }
                //l'insertion des ues de ce parcours
                $requetteUe = 'INSERT INTO ue( nom_ue, id_parcours ) VALUES ( :nomUe, :idParcours )';
                foreach ($ues as $ue) {
                    if (!$this->isArrayDataValid($ue)) {
                        throw new Exception("Données UE Invalide");
                    }

                    if ($ue['idSemestre'] == $idSemestre) {
                        $this->e(extract($ue));
                        if (!isset($ue['idUe']) || $ue['idUe'] == null) {
                            $param = [
                                'nomUe' => $nomUe,
                                'idParcours' => $lastInsertIdParcours
                            ];
                            $reponse = $this->insertion_update_simples_insert_id($requetteUe, $param);
                            $lastInsertIdUe = $reponse['lastInsertId'];
                        } else {
                            $lastInsertIdUe = $ue['idUe'];
                        }


                        if (empty($modules)) {
                            throw new Exception("Données Modules Invalide");
                        }
                        // l'insertion des modules liés à chaque ue (la relation en ue et module)
                        $requetteModule = "INSERT INTO ue_module(id_ue, id_module, code_module, coeficient, cm, td, tp, tpe) 
                        VALUES (:idUe, :idModule, :code, :coeficient, :cm, :td, :tp, :tpe)";
                        foreach ($modules as $module) {
                            if (!$this->isArrayDataValid($module) || count($module) < 5) {
                                throw new Exception("Données Modules Invalide");
                            }

                            if ($module['idSemestre'] == $idSemestre && $module['nomUe'] == $nomUe) {

                                if (!isset($module['idUeModule']) || $module['idUeModule'] == null) {
                                    $this->e(extract($module));
                                    $param = [
                                        'idUe' => $lastInsertIdUe,
                                        'idModule' => $idModule,
                                        'code' => $moduleCode,
                                        'coeficient' => $moduleCoeficient,
                                        'cm' => $moduleCm,
                                        'td' => $moduleTd,
                                        'tp' => $moduleTd,
                                        'tpe' => $moduleTpe,
                                    ];
                                    $this->insertion_update_simples($requetteModule, $param);
                                }
                            }
                        }
                    }
                }
            }

            // la fin de la transaction
            $connection->commit();
            $this->set_flash("Filière Modifiée avec succès", "success");
        } catch (Exception $e) {
            $connection->rollBack();
            $this->set_flash($e->getMessage() . " : !Veuillez bien verifier vos données");
            return false;
        }
    }

    public function supprimerElementFiliere($action, $id)
    {
        $column = 'id_' . $action;
        try {
            $isExist = $this->existe_deja($column, $id, $action);
            if ($isExist <= 0) {
                echo "notExist";
                return;
            }

            // Définir la requête de suppression et les paramètres
            $sql = "DELETE FROM $action WHERE $column = :id";
            $params = [':id' => $id];
            $this->insertion_update_simples($sql, $params);
            echo 'success';
            return;
        } catch (\Throwable $th) {
            //throw $th;
            echo 'error';
            return;
        }
    }
    //! Fin de la gestion d'une filière

    //! Debut de la gestion d'une promotion
    // Ajouter une promotion
    public function ajouterPromotion()
    {
        try {
            // if (!$this->isArrayDataValid($_POST)) {
            //     throw new Exception("Données Non Valid : !Veuillez bien verifier vos données");
            // }

            $this->e(extract($_POST));

            $isFiliereExiste = $this->user_verify("id_filiere", "filiere", $idFiliere);
            if ($isFiliereExiste < 0) {
                throw new Exception("Filiere Introuvable : !Veuillez bien verifier vos données");
            }

            $isPromotionExiste = $this->FetchSelectWhere(
                "*",
                "promotion",
                "annee_universitaire=? AND id_filiere=? AND id_parcours=?",
                [$anneeUniversitaire, $idFiliere, $idParcours]
            );
            if ($isPromotionExiste != null || !empty($isPromotionExiste)) {
                throw new Exception("Repetition de Promotion : !Cette filière a dejà la promotion $anneeUniversitaire");
            }

            $requette = "INSERT INTO promotion( annee_universitaire, id_filiere, id_parcours)
             VALUES (:anneeUniversitaire, :idFiliere, :idParcours)";
            $isPromotionAdd = $this->insertion_update_simples($requette, [
                "anneeUniversitaire" => $anneeUniversitaire,
                "idFiliere" => $idFiliere,
                "idParcours" => $idParcours
            ]);
            if (!$isPromotionAdd) {
                $this->set_flash("Erreur de Connection à la basse de donnés : !Veuillez ressayer plus tard");
                $this->redirect("Filieres/liste_promotion/$idFiliere");
                return;
            }

            $this->set_flash("La promotion a été ajouter avec succès", "success");
            $this->redirect("Filieres/liste_promotion/$idFiliere");
            return;
        } catch (Exception $e) {
            $this->set_flash($e->getMessage(), "warning");
            $this->redirect("Filieres/liste_promotion/$idFiliere");
            return;
        }
    }

    // Liste des promotions d'une filière
    public function listePromotions($idFiliere, $statut = null)
    {
        try {
            $this->e(extract($_POST));

            $isFiliereExiste = $this->user_verify("id_filiere", "filiere", $idFiliere);
            if ($isFiliereExiste < 0) {
                throw new Exception("Filiere Introuvable : !Veuillez bien verifier vos données");
            }
            $statutCondtion = '';
            if ($statut != null) {
                $statutCondtion = "AND promotion.statut=$statut";
            }
            $requette = "SELECT id_promotion, annee_universitaire, promotion.statut, promotion.id_parcours, promotion.id_filiere, nom_filiere, 
            sigle_filiere, nom_semestre, sigle_semestre FROM promotion INNER JOIN filiere ON promotion.id_filiere=filiere.id_filiere
            INNER JOIN parcours ON promotion.id_parcours=parcours.id_parcours INNER JOIN semestre ON parcours.id_semestre=semestre.id_semestre
            WHERE promotion.id_filiere=? $statutCondtion";

            $promotions = $this->select_data_table_join_where($requette, [$idFiliere]);
            return $promotions;
        } catch (Exception $e) {
            $this->set_flash($e->getMessage(), "warning");
            $this->redirect("Filieres/");
            return;
        }
    }

    // la methode pour changer le status d'une promotion (en attente, en marche, en arret)
    public function setStatusPromotion($idPromotion, $statut = 1)
    {
        try {
            $isUpdate = $this->insertion_update_simples(
                "UPDATE promotion SET statut=:statut WHERE id_promotion=:idPromotion LIMIT 1",
                ['statut' => $statut, 'idPromotion' => $idPromotion]
            );
            if ($isUpdate) {
                switch ($statut) {
                    case 0:
                        $message = "Cette promotion est desormais en attente";
                        break;
                    case 1:
                        $message = "Cette promotion est desormais en cours";
                        break;
                    case 2:
                        $message = "Cette promotion est desormais en arrêt";
                        break;

                    default:
                        $message = "Cette promotion a été modifier";
                        break;
                }
                $this->set_flash($message, 'primary');
            }
            return $isUpdate;
        } catch (Exception $e) {
            $this->set_flash($e->getMessage() . ' : Imposible de modifier cette promotion');
        }
    }

    //! Fin de la gestion d'une promotion
}
