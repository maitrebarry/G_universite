<?php
class Utilisateurs extends Controller
{
    public function index(){
       
    }
    public function liste_utilisateur(){
        $utilisateur= new Utilisateur();
        $utilisateurenseignant = new Enseignant(); 
        if(isset($_POST["save_user"])){

            $utilisateur->save_utilisateur(["nom_prenom","contact_utilisateur","email_utilisateurs","mot_passe","role"]);   
        }
        //appel du method de recuperation 
        $liste= $utilisateur->SelectAllData('*',"utilisateur");
        $enseignants = $utilisateurenseignant->SelectAllData("*", "enseignants");
       $this->view('liste_utilisateur',['liste'=>$liste,'enseignants' => $enseignants]); 
    } 

    /// methode pour la modification
    public function edit_utilisateurs(){
        $utilisateur= new Utilisateur();
        if (isset($_POST['edit_user'])) {
            //  echo 'okkddddd';exit;
            extract($_POST);
            $id_utilisateur=$_POST["id_utilisateur"];
            $nom_prenom=$_POST["nom_prenom"];
            $contact_utilisateur=$_POST["contact_utilisateur"];
            $email_utilisateurs=$_POST["email_utilisateurs"];
            $mot_passe=$_POST["mot_passe"];
            $role=$_POST["role"];
           $utilisateur->edit_utilisateur(['id_utilisateur'=>$id_utilisateur, 'nom_prenom'=>$nom_prenom, 'contact_utilisateur'=>$contact_utilisateur, 'email_utilisateurs'=>$email_utilisateurs,'mot_passe'=>$mot_passe,'role'=>$role]); 
          }
          $this->view('liste_utilisateur');  
    }
    public function delete($id) {
        $S = new Semestre();     
        // Définir la requête de suppression et les paramètres
        $sql = 'DELETE FROM utilisateur WHERE id_utilisateur = :id';
        $params = [':id' => $id];
        $result = $S->insertion_update_simples($sql, $params); 
        if ($result->rowCount() > 0) {
            $S->set_flash("Suppression réussie", 'success');
        }  
        $S->redirect("Utilisateurs/liste_utilisateur");
    } 
  
}