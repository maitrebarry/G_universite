<?php
class Etudiants extends Controller
{
    public function index()
    {
        // Récupérer les données des filières
        $filiereModel = new Filiere(); // Initialiser le modèle des filières
        $listeFilieres = $filiereModel->SelectAllData("*", "promotion 
        INNER JOIN filiere ON promotion.id_filiere=filiere.id_filiere 
        INNER JOIN parcours ON promotion.id_parcours=parcours.id_parcours 
        INNER JOIN semestre ON parcours.id_semestre=semestre.id_semestre");
        // Transmettre les données à la vue
        $this->view('liste_incrit', [
            'listeFilieres' => $listeFilieres  // Nom du tableau de données envoyé à la vue
        ]);
    }
    
    public function incrit_etudiant() {
        $Etudiants = new Etudiant();
        $filiereModel = new Filiere(); // Initialiser le modèle des filières
        $errors = []; // Initialiser un tableau pour les erreurs
    
        if (isset($_POST["ddddddd"])) {
           // extract($_POST);
            // $Etudiants->enregistrementEtudiant($_POST, $_FILES["profilname"]);
            // $Etudiants->enregistrementPaiement($_POST);
                         $Etudiants->enregistrementEtudiantAvecPaiement($_POST, $_FILES["profilname"]);

            $errors = $Etudiants->errors; // Récupérer les erreurs de l'objet Etudiant
        }
    
        // Récupérer les données des filières
        $listeFilieres = $filiereModel->SelectAllData("*", "filiere");
     
        // Récupérer les données des promotions
                 $listePromotion = $filiereModel->SelectAllData("*", "promotion");
                // var_dump($listePromotion);exit;
        
                // Transmettre les données à la vue
                $this->view('incription', [
                    'errors' => $errors,
                    'listePromotion' => $listePromotion,
                    'filieres' => $listeFilieres
                  
                ]);

    }

    public function trier_liste_etudiant(){  
      if(isset($_POST["id_promotion"])){
        $etudiants = new Etudiant();
       $liste_etudiant=$etudiants->trie_liste_etudiant($_POST["id_promotion"]);
       $this->view('liste_etudiant', [
        'liste_etudiant' => $liste_etudiant 
         ]);
      }
    }
    
    public function paiement_etudiant($id){
        
        $this->view('paiement_inscription'); 
    }  

}