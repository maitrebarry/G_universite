<?php

class Filieres extends Controller
 {
    public function index() {
        $filiereModel=new Filiere();
        $listeFilieres = $filiereModel->SelectAllData("*", "filiere");
        $this->view( 'liste_filiere', ['filieres'=>$listeFilieres] );
    }

    public function ajouter_filiere() {
        if (isset($_POST['action']) && $_POST['action']=="ajouter_filiere") {
            $filiere=$_POST['filiere'];
            $semestres=$_POST['semestres'];
            $ues=$_POST['ues'];
            $modules=$_POST['modules'];

            if (!empty($filiere)) {
                # code...
            }
            $filiereModel=new Filiere();
            $filiereModel->ajouter_filiere($filiere, $semestres, $ues, $modules);
            $this->view("set_flash");
            exit();
        }

        $semestreModel=new Semestre();
        $listeSemestres=$semestreModel->SelectAllData("*", "semestre");
        $this->view( 'ajouter_filiere' ,['semestres'=>$listeSemestres]);
    }

    // la fonction pour jerer l'ajout des semestres des semestres, des ue et des modules dans une filière

    public function post_ajouter_filiere() {
        if (isset($_POST['action'])) {
            
            $action=$_POST['action'];
            @$semestre=[
                'semestreId'=>$_POST['semestreId'],
                'semestreName'=>$_POST['semestreName'],
            ];
            $moduleModel=new Module();
            $listeModules=$moduleModel->SelectAllData("*", "module");
            $this->view('post_ajouter_filiere',['action'=>$action,'semestre'=>$semestre,"modules"=>$listeModules]);
            
        }
    }

    public function apercu_filiere($idFiliere=null){
        if ($idFiliere!=null && is_numeric($idFiliere)) {
            $filiereModel=new Filiere();
            $infosFiliere=$filiereModel->apercu_filiere($idFiliere);
            if(!empty($infosFiliere)){
                $this->view("apercu_filiere",["infoFiliere"=>$infosFiliere]);
            }
          
        }
       
    }

}