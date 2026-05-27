<?php
class Etudiants extends Controller
{
    public function index()
    {
       $promotionModel = new Promotion();
       $promotion = $promotionModel->SelectAllData("*","promotion");
       $filiereModel = new Filiere();
       $filiere = $filiereModel->SelectAllDataOrder("*","filiere","id_filiere",1);

       if(isset($_GET["filieres"]) && !empty($_GET["filieres"])){
        $idPromotion = $_GET["promotions"];
        
       // var_dump($promotion); exit;
       }
       
        // Transmettre les données à la vue
        $this->view('ajouter_note',["promotion"=> $promotion]);
    }
    
    
  
        

    
}