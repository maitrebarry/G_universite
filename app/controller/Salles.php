<?php
class Salles extends Controller
{
    public function index(){
        $this->view('liste_salle');
    }
    public function Liste()
    {
        $Salles = new Salle();
        if (isset($_POST['envoie'])) {
            $Salles->enregistrementSalle(["nom_salle","capacite_salle"]);
        }
        $datas = $Salles->SelectAllData("*","salle");
        $this->view('liste_salle', ['datas'=>$datas]);

}
public function update() {
    $Salles = new Salle();
    if (isset($_POST["modifier"])) {
       extract($_POST);
       $id_salle = $_POST['id_salle'];
        $nom_salle = $_POST['nom_salle'];
        $capacite_salle = $_POST['capacite_salle'];
        $Salles->modification(['id_salle' => $id_salle, 'nom_salle' => $nom_salle,'capacite_salle' => $capacite_salle]);
    }
  $this->view('Liste');
}
public function supprimer($id_salle) {
    $Salles = new Salle();
    $sql = 'DELETE FROM salle  WHERE id_salle = :id_salle';
    $params =[':id_salle'=>$id_salle];
    $supprimer = $Salles->insertion_update_simples($sql, $params);
    if ($supprimer->rowCount()>0) {
        $Salles->set_flash("La salle a été supprime avec succès",'primary'); 
     }
     $Salles->redirect("Salles/Liste");  

}
}