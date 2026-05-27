<?php
    class Semestre  extends Model{
        public function EnregistreSemestre(){

        $this->e(extract($_POST));
             //echo "$nom_matiere";exit;
            $insertion = $this->insertion_update_simples('INSERT INTO semestre (sigle_semestre,nom_semestre)
                VALUES(:sigle_semestre,:nom_semestre)', 
                [':sigle_semestre' => $sigle_semestre,
                ':nom_semestre' => $nom_semestre,
            ]);
            if ($insertion == true) {    
                 $this->set_flash("Insertion faite avec succès","primary");
                  $this->redirect("Semestres/Liste");
            } 

        }
        //modification
        public function modification($data) {
            $sql = 'UPDATE semestre SET nom_semestre = :nom_semestre,sigle_semestre=:sigle_semestre WHERE id_semestre = :id_semestre';
        
            $params = [
                ':nom_semestre' => $data['nom_semestre'],
                ':sigle_semestre' => $data['sigle_semestre'],
                ':id_semestre' => $data['id_semestre'] 
            ];
        
            // Exécution de la requête pour mettre à jour la matière
            $modifier = $this->insertion_update_simples($sql, $params);
        
            if ($modifier) {
                $this->set_flash("semestre a été modifiée avec succès", 'success');
                $this->redirect("Semestres/Liste");    
                
             } 
        }
        
    }