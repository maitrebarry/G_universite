<?php 
class Module extends Model{
  public function saveModule(){
    $this->e(extract($_POST));
    $insert= $this->insertion_update_simples("INSERT INTO module (nom_module, sigle_module) 
                                              VALUES (:nom_module,:sigle_module)",
                                              [":nom_module"=>$nom_module,"sigle_module"=>$sigle_module]);
    if($insert == true){
      $this->set_flash("insertion faite avec succès", 'primary');
        $this->redirect("Modules/listeModule");
    }
  }

  // function pour la modification
  public function editModule($data){
      $req= "UPDATE module 
           SET nom_module =:nom_module, 
               sigle_module=:sigle_module
                WHERE id_module=:id_module";

         $params= [
              ":nom_module" => $data['nom_module'],
              ":sigle_module" => $data['sigle_module'],
              ':id_module' => $data['id_module'],
             ];
    
      $modification= $this->insertion_update_simples($req, $params);

    if($modification == true){
      $this->set_flash("modification faite avec succès","success");
      $this->redirect("Modules/listeModule");
    }

  }
   
   
}