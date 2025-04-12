<?php 
class Departement extends Model{
  public function saveDepartement(){
    $this->e(extract($_POST));
    $insert= $this->insertion_update_simples("INSERT INTO departement (nom_departement, sigle_departement) 
                                              VALUES (:nom_departement,:sigle_departement)",
                                              [":nom_departement"=>$nom_departement,"sigle_departement"=>$sigle_departement]);
    if($insert == true){
      $this->set_flash("Departements enregistré avec succèes", 'primary');
        $this->redirect("Departements/listeDepartements");
    }
  }

  // function pour la modification
  public function editdepartement($data){
      $req= "UPDATE module 
           SET nom_departement =:nom_departement, 
               sigle_departement=:sigle_departement
                WHERE id_departement=:id_departement";

         $params= [
              ":nom_departement" => $data['nom_departement'],
              ":sigle_departement" => $data['sigle_departement'],
              ':id_departement' => $data['id_departement'],
             ];
    
      $modification= $this->insertion_update_simples($req, $params);

    if($modification == true){
      $this->set_flash("modification faite avec succès","success");
      $this->redirect("Departements/listeDepartement");
    }

  }
   
   
}