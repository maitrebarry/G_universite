<?php
class Semestres extends Controller
{
    public function index(){
    
    } 
    public function Liste()
    { 
         $semestre=new Semestre;
         if (isset($_POST['envoie'])) {
            //echo"ooooo";exit;
            $semestre->EnregistreSemestre("nom_semestre","sigle_semestre");
           }
           $datas = $semestre->SelectAllData("*", "semestre");
        $this->view('liste_semestre',['datas'=>$datas]);
    }
    public function editSemestre() {
        $modules = new Semestre();
        if (isset($_POST['editmodule'])) {
        //    echo 'okk';exit;
        $id_semestre=$_POST['id_semestre'];
        $nomSemestre = $_POST['nom_semestre'];
        $sigleSemestre = $_POST['sigle_semestre'];
        $modules->modification(['id_semestre' => $id_semestre, 'nom_semestre' => $nomSemestre,'sigle_semestre'=>$sigleSemestre]);

        }

        $this->redirect('Semestres/Liste');
    }
    public function delete($id) {
        $S = new Semestre();     
        // Définir la requête de suppression et les paramètres
        $sql = 'DELETE FROM semestre WHERE id_semestre = :id';
        $params = [':id' => $id];
        $result = $S->insertion_update_simples($sql, $params); 
        if ($result->rowCount() > 0) {
            $S->set_flash("Suppression réussie", 'success');
        }  
        $S->redirect("Semestres/Liste");
    } 

}