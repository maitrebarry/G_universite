<?php
class Annees_universites extends Controller
{
    public function index()
    {
           $this->view("liste_annee_universiter");  
    }
    public function Liste()
    {
        $Annees_universites = new Annees_universite();
        if (isset($_POST['submit'])) {
            $Annees_universites->enregistrementAnne(["anne_scolaire","date_debut","date_fin"]);
        }
        $datas = $Annees_universites->SelectAllData("*","anne_universitaire");
        $this->view('liste_annee_universiter', ['datas'=>$datas]);

}
public function update() {
    $Annees_universites = new Annees_universite();
    if (isset($_POST["modifier"])) {
       extract($_POST);
       $id_anne = $_POST['id_anne'];
        $anne_scolaire = $_POST['anne_scolaire'];
        $date_debut = $_POST['date_debut'];
        $date_fin = $_POST['date_fin'];
        $Annees_universites->modification(['id_anne' => $id_anne, 'anne_scolaire' => $anne_scolaire,'date_debut' => $date_debut,'date_fin' => $date_fin]);
    }
  $this->view('Liste');
}
public function supprimer($id_anne) {
    $Annees_universites = new Annees_universite();
    $sql = 'DELETE FROM anne_universitaire  WHERE id_anne = :id_anne';
    $params =[':id_anne'=>$id_anne];
    $supprimer = $Annees_universites->insertion_update_simples($sql, $params);
    if ($supprimer->rowCount()>0) {
        $Annees_universites->set_flash("L'annee a été supprime avec succès",'primary'); 
     }
     $Annees_universites->redirect("Annees_universites/Liste");  

}

}