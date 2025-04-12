<?php 
class Homes extends Controller{
   public function index(){
    
    $periodeModel = new Periode();
    $periodeModel->verifierEtCreerPeriode();

    
       $this->view('Home');
   }
  
}