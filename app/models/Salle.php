<?php
    class salle  extends Model{
        public function enregistrementSalle(){
            $errors=[];
                $this->e(extract($_POST));
            if ( $this->existe_deja('nom_salle',$nom_salle,'salle')) {
                $errors[]= 'Ce Nom de salle existe deja';
             }  
            else{
                if (count($errors) ==0) {
                    $insertion = $this->insertion_update_simples('INSERT INTO salle(nom_salle,capacite_salle)
                    VALUES(:nom_salle,:capacite_salle)',[':nom_salle'=>$nom_salle,':capacite_salle'=>$capacite_salle]);
                        if ($insertion ==true) {
                            $this->set_flash('Salle ajouter avec succes', 'primary');
                        }else{
                            $this->set_flash('Salle non ajouter');
                        }
                }
            }
        }
        
    public function modification($data) {
        $sql = 'UPDATE salle SET nom_salle = :nom_salle,capacite_salle = :capacite_salle  WHERE id_salle = :id_salle';
    
        $params = [
            ':nom_salle' => $data['nom_salle'],
            ':capacite_salle' => $data['capacite_salle'],
            ':id_salle' => $data['id_salle']
        ];
        // Exécution de la requête pour mettre à jour la matière
        $modifier = $this->insertion_update_simples($sql, $params);
    
        if ($modifier) {
            $this->set_flash("La salle a été modifiée avec succès", 'primary');
            $this->redirect("Salles/Liste");
          }
    }
   

        
    }