<?php 
class Utilisateur extends Model{
    // function d'enregistrement
    public function save_utilisateur(){
        $this->e(extract($_POST));
        
        $insert= $this->insertion_update_simples("INSERT INTO utilisateur (nom_prenom, contact_utilisateur,email_utilisateurs,mot_passe,role) 
                                                  VALUES (:nom_prenom,:contact_utilisateur,:email_utilisateurs,:mot_passe,:role)",
                                                  [":nom_prenom"=>$nom_prenom,":contact_utilisateur"=>$contact_utilisateur,":email_utilisateurs"=>$email_utilisateurs,":mot_passe"=>$mot_passe,":role"=>$role]);
        if($insert == true){
          $this->set_flash("insertion faite avec succès", 'primary');
            $this->redirect("Utilisateurs/liste_utilisateur");
        }
      }
      public function edit_utilisateur($data){
        $req= "UPDATE utilisateur 
             SET nom_prenom =:nom_prenom, 
                  contact_utilisateur=:contact_utilisateur,
                  email_utilisateurs=:email_utilisateurs,
                  mot_passe=:mot_passe,
                  role=:role
                  WHERE id_utilisateur=:id_utilisateur";
  
           $params= [
                ":nom_prenom" => $data['nom_prenom'],
                ":contact_utilisateur" => $data['contact_utilisateur'],
                ":email_utilisateurs" => $data['email_utilisateurs'],
                ":mot_passe" => $data['mot_passe'],
                ":role" => $data['role'],
                ':id_utilisateur' => $data['id_utilisateur'],
               ];
      
        $modification= $this->insertion_update_simples($req, $params);
  
        if($modification == true){
          $this->set_flash("modification faite avec succes","sucess");
          $this->redirect("Utilisateurs/liste_utilisateur");
        }
  
    }

}