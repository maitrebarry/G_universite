<?php

class Filiere  extends Model {

    public function ajouter_filiere( $filiere, $semestres, $ues, $modules ) {
        $connection = $this->bdd();

        try {

            // le debut de la transaction
            $connection->beginTransaction();
            // L'insertation des infos de base de la filière
            $this->e( extract( $filiere ) );
            $requtte="INSERT INTO filiere(nom_filiere, sigle_filiere) VALUES (:nomFiliere, :sigleFiliere)";
            $param=[
                'nomFiliere'=>$nomFiliere,
                "sigleFiliere"=>$sigleFiliere
            ];
            $reponse = $this->insertion_update_simples_insert_id($requtte, $param);
            $idFiliere = $reponse['lastInsertId'];


            // L'insertion des diférents parcours  de la filière ( la relation entre filière et semestre )
            $requtteParcours = 'INSERT INTO parcours(id_filiere, id_semestre) VALUES (:idFiliere, :idSemestre)';
            foreach ( $semestres as $semestre ) {
                $this->e( extract( $semestre ) );
                $param = [
                    'idFiliere'=>$idFiliere,
                    'idSemestre'=>$idSemestre
                ];
                $reponse = $this->insertion_update_simples_insert_id( $requtteParcours, $param );
                $lastInsertIdParcours = $reponse[ 'lastInsertId' ];

                //l'insertion des ues de ce parcours
                $requetteUe = 'INSERT INTO ue( nom_ue, id_parcours ) VALUES ( :nomUe, :idParcours )';
                foreach ( $ues as $ue ) {
                    if ($ue['idSemestre']==$idSemestre) {
                        $this->e( extract( $ue ) );
                        $param = [
                            'nomUe'=>$nomUe,
                            'idParcours'=>$lastInsertIdParcours
                        ];
                        $reponse = $this->insertion_update_simples_insert_id( $requetteUe, $param );
                        $lastInsertIdUe = $reponse[ 'lastInsertId' ];

                        // l'insertion des modules liés à chaque ue (la relation en ue et module)
                        $requetteModule="INSERT INTO ue_module(id_ue, id_module, code_module, coeficient, cm, td, tp, tpe) 
                        VALUES (:idUe, :idModule, :code, :coeficient, :cm, :td, :tp, :tpe)";
                        foreach ($modules as $module) {
                            if ($module['idSemestre']==$idSemestre && $module['nomUe']==$nomUe ) {
                                $this->e( extract( $module ) );
                                $param = [
                                    'idUe'=>$lastInsertIdUe,
                                    'idModule'=>$idModule,
                                    'code'=>$moduleCode,
                                    'coeficient'=>$moduleCoeficient,
                                    'cm'=>$moduleCm,
                                    'td'=>$moduleTd,
                                    'tp'=>$moduleTd,
                                    'tpe'=>$moduleTpe,     
                                ];
                                $this->insertion_update_simples($requetteModule, $param);
                            }
                        }

                    }
                }

            }

            // la fin de la transaction
            $connection->commit();
            $this->set_flash("Filière ajouté avec succès","success");
        } catch ( Exception $e ) {
            $connection->rollBack();
            $this->set_flash("Echec lors de l'ajout dans la base donnée");
            return false;
        }

    }

    // la methode pour recuperer toutes les informations d'une filière
    public function apercu_filiere($idFiliere){
        $connection = $this->bdd();
        $infoFiliere=[];

        try {
            // le debut de la transaction
            $connection->beginTransaction();

            // la recuperation des infos de base de la filère
            $filiere=$this->FetchSelectWhere(" *"," filiere "," id_filiere=?",[$idFiliere]);

            // la recuperation des semestres de la filière
            $requetteSemestre="SELECT id_parcours, parcours.id_semestre, nom_semestre, sigle_semestre 
            FROM parcours  INNER JOIN semestre ON 
            parcours.id_semestre=semestre.id_semestre WHERE id_filiere=?";
            $semestres=$this->select_data_table_join_where($requetteSemestre,[$idFiliere]);

            // la recupetion des des différents ue de chaque semestre
            $ues=[];
            $requetteUe="SELECT ue.id_ue, nom_ue, sigle_ue, id_parcours, SUM(cm) AS ue_cm, SUM(td) AS ue_td, SUM(tp) AS ue_tp, SUM(tpe) 
            AS ue_tpe, SUM(coeficient) AS ue_credit  FROM ue JOIN ue_module ON ue_module.id_ue=ue.id_ue WHERE ue.id_parcours=? 
            GROUP BY ue.id_ue, nom_ue, sigle_ue, id_parcours";
            foreach ($semestres as $semestre) {
                $idSemestre=$semestre->id_parcours;
                $resultats=$this->select_data_table_join_where($requetteUe,[$idSemestre]);
                foreach($resultats as $resultat){
                    $ues[]=$resultat;
                }
            }

            // la recuperation des modules de chaque ue
            $modules=[];
            $requetteModule="SELECT id_ue_module, id_ue, ue_module.id_module, code_module, coeficient, cm, td, tp, tpe, module.nom_module,
             module.sigle_module FROM ue_module INNER JOIN module ON ue_module.id_module=module.id_module WHERE id_ue=?";
            foreach ($ues as $ue) {
                
                $idUe=$ue->id_ue;
                $resultats=$this->select_data_table_join_where($requetteModule,[$idUe]);
                foreach($resultats as $resultat){
                    $modules[]=$resultat;
                }
            }
          
            // la fin de la transaction
            $connection->commit();

            $infoFiliere=[
                "filiere"=>$filiere,
                "semestres"=>$semestres,
                "ues"=>$ues,
                "modules"=>$modules
            ];
            return $infoFiliere;
        } catch ( Exception $e ) {
            $connection->rollBack();
            $this->set_flash("Impossible de recuperer les informations de cette filière");
            $this->redirect("Filieres/listeFiliere");
            return;
        }

    }

}