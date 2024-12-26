<?php
    class Annees_universite  extends Model{
        public function enregistrementAnne(){
            $this->e(extract($_POST));
            $insertion = $this->insertion_update_simples('INSERT INTO anne_universitaire(anne_scolaire,date_debut,date_fin)
             VALUES(:anne_scolaire,:date_debut,:date_fin)',[':anne_scolaire'=>$anne_scolaire,':date_debut'=>$date_debut,':date_fin'=>$date_fin]);
       if ($insertion ==true) {
        $this->set_flash('Années ajouter avec succes', 'primary');
       }else{
        $this->set_flash('Années non ajouter');
       }
    }
    public function modification($data) {
        $sql = 'UPDATE anne_universitaire SET anne_scolaire = :anne_scolaire,date_debut = :date_debut,date_fin = :date_fin  WHERE id_anne = :id_anne';
    
        $params = [
            ':anne_scolaire' => $data['anne_scolaire'],
            ':date_debut' => $data['date_debut'],
            ':date_fin' => $data['date_fin'],
            ':id_anne' => $data['id_anne']
        ];
        // Exécution de la requête pour mettre à jour la matière
        $modifier = $this->insertion_update_simples($sql, $params);
    
        if ($modifier) {
            $this->set_flash("L'anne a été modifiée avec succès", 'primary');
            $this->redirect("Annees_universites/Liste");
         }
    }
   

        
    }