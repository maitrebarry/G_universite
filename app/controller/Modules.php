<?php
class Modules extends Controller{ 
     public function index(){
    
   }  
    public function listeModule(){
        $module= new Module();
        if(isset($_POST["saveFiliere"])){
            //echo "ddddd";exit;
            $module->saveModule(["nom_module","sigle_module"]);   
        }
        //appel du method de recuperation 
        $liste= $module->SelectAllData('*',"module");
        $this->view('liste_Module',['liste'=>$liste]); 
    } 
    
    // fonction pour la modification des filiere
    public function editFiliere() {
        $modules = new Module();

        if (isset($_POST['editmodule'])) {
          // echo 'okk';exit;
          extract($_POST);
          $id_module=$_POST["id_module"];
          $nom_module=$_POST["nom_module"];
          $sigle_module=$_POST["sigle_module"];
         $modules->editModule(['id_module'=>$id_module, 'nom_module'=>$nom_module, 'sigle_module'=>$sigle_module]); 
        }

        $this->view('liste_Module'); 
       
    }

    public function delete($id) {
        $S = new Module();     
        // Définir la requête de suppression et les paramètres
        $sql = 'DELETE FROM module WHERE id_module = :id';
        $params = [':id' => $id];
        $result = $S->insertion_update_simples($sql, $params); 
        if ($result->rowCount() > 0) {
            $S->set_flash("Suppression réussie", 'success');
        }  
        $S->redirect("Modules/listeModule");
    } 
    // public function deleteModule($id){
    //     $modules= new Module();
    //     // req delete
    //     $sql= 'DELETE FROM module WHERE id_module= :id';
    //     $params =[':id'=>$id];
    //     $result = $modules->insertion_update_simples($sql, $params);
    //     if($result->rowCount() > 0){
    //         $modules->set_flash("Suppression faite avec ssssss");
    //     }
    //     $modules->redirect('Modules/listeModule');
    // }
}

    
    
