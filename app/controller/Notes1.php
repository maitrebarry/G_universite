<?php

class Notes extends Controller {
    
        public function index() {
        $noteModel = new Note();
        $filiereModel = new Filiere();
        $etudiantModel = new Etudiant();
        $promotionModel = new Promotion();
        $moduleModel = new Module();
        
        // Récupérer toutes les filières
        $filieres = $filiereModel->SelectAllData("*", "filiere");
        $data = ['filieres' => $filieres];
        //var_dump($filieres); exit;
    
        // Vérifier si 'filiere' est présent et valide
        if ($data && $data > 0) {
            $idFiliere = intval($_GET['id_filiere']);
            var_dump($idFiliere); exit;
            
            if ($idFiliere > 0) {
                $promotions = $noteModel->getPromotionsByFiliere($idFiliere);
                // Vérifier si des promotions ont été trouvées
                if ($promotions) {
                    echo json_encode(['status' => 'success', 'data' => $promotions]);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Aucune promotion trouvée pour cette filière']);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'ID de filière invalide']);
            }
        }
    
        // // Vérifier si 'promotions' est présent et valide
        // if (isset($_GET['promotions'])) {
        //     $idPromotion = intval($_GET['promotions']);
            
        //     if ($idPromotion > 0) {
        //         $modules = $noteModel->FetchSelectWhere("*","module",$idPromotion);
        //         // Vérifier si des modules ont été trouvés
        //         if ($modules) {
        //             echo json_encode(['status' => 'success', 'data' => $modules]);
        //         } else {
        //             echo json_encode(['status' => 'error', 'message' => 'Aucun module trouvé pour cette promotion']);
        //         }
        //     } else {
        //         echo json_encode(['status' => 'error', 'message' => 'ID de promotion invalide']);
        //     }
        // }

        // //Vérifier si  la promotion et la filière sont présente
        // if( isset($_GET['promotions'])){
        //     $idPromotion = intval($_GET['promotions']);
        //     $idFiliere =  intval($_GET['filiere']);
        //     var_dump($filieres); exit;
        //     //Récupérer de tous les étudiants selon la filière et la promotion
        //     $etudiants = $noteModel->getEtudiantsByFiliereAndPromotion($idFiliere,$idPromotion);
        //     //Vérifier l'existence des étudiants
        //     if($etudiants){
        //         //envoi les données de l"étudiant en format json
        //         echo json_encode(['status' => 'success', 'data' => $etudiants]);
        //     }else{
        //         echo json_encode(['status' => 'error', 'message' => 'Aucun étudiant trouvé pour cette filiere et promotion']);
        //     }

        // }

    
        // Charger la vue avec les données des filières
        $this->view("ajouter_notes", $data);
    }
    
    
   
    }
?>
