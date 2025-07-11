<?php
class Departements extends Controller{ 
     public function index(){
    
   }  
    public function listeDepartements(){
        $departement= new Departement();
        if(isset($_POST["envoyer"])){
            //echo "ddddd";exit;
            $departement->saveDepartement(["nom_departement","sigle_departement"]);   
        }
        //appel du method de recuperation 
        $liste= $departement->SelectAllData('*',"departement");
        $this->view('liste_departements',['liste'=>$liste]); 
    } 
    
    // fonction pour la modification des filiere
    public function editDepartements() {
        $departements = new Departement();

        if (isset($_POST['editdepartement'])) {
          // echo 'okk';exit;
          extract($_POST);
          $id_departement=$_POST["id_departement"];
          $nom_departement=$_POST["nom_departement"];
          $sigle_departement=$_POST["sigle_departement"];
         $departements->editdepartement(['id_departement'=>$id_departement, 'nom_departement'=>$nom_departement, 'sigle_departement'=>$sigle_departement]); 
        }

        $this->view('liste_departement'); 
       
    }

    public function delete($id) {
        $S = new Departement();     
        // Définir la requête de suppression et les paramètres
        $sql = 'DELETE FROM departement WHERE id_departement = :id';
        $params = [':id' => $id];
        $result = $S->insertion_update_simples($sql, $params); 
        if ($result->rowCount() > 0) {
            $S->set_flash("Suppression réussie", 'success');
        }  
        $S->redirect("Departements/listeDepartements");
    } 
    // public function deletedepartement($id){
    //     $departements= new departement();
    //     // req delete
    //     $sql= 'DELETE FROM departement WHERE id_departement= :id';
    //     $params =[':id'=>$id];
    //     $result = $departements->insertion_update_simples($sql, $params);
    //     if($result->rowCount() > 0){
    //         $departements->set_flash("Suppression faite avec ssssss");
    //     }
    //     $departements->redirect('departements/listedepartement');
    // }
}

    
    
