<?php
class Periodes extends Controller
{
    public function index()
    {
        $this->view("liste_periode");
    }
    public function enregistrement()
{
    $periode = new Periode();
    $periode->enregistrementAnne();
    $this->redirect('Periodes/Liste');
}


    public function Liste()
    {
        $Periodes = new Periode();
        if(isset($_POST["submit"])){
            //echo "ddddd";exit;
            $Periodes->enregistrementAnne(["date_debut","date_fin"]);  
            $Periodes->verifierEtCreerPeriode();
        }
        
        // Récupérer toutes les périodes
        $datas = $Periodes->SelectAllData("*", "periode");

        $this->view('liste_periode', ['datas' => $datas]);
    }
    
    public function update()
    {
        $Periodes = new Periode();
        if (isset($_POST["modifier"])) {
            extract($_POST);
            $id_periode = $_POST['id_periode'];
            $date_debut = $_POST['date_debut'];
            $date_fin = $_POST['date_fin'];
            $Periodes->modification(['id_periode' => $id_periode,'date_debut' => $date_debut, 'date_fin' => $date_fin]);
        }
        $this->view('liste_periode');
    }
    public function supprimer($id_periode)
    {
        $Periodes = new Periode();
        $sql = 'DELETE FROM periode  WHERE id_periode = :id_periode';
        $params = [':id_periode' => $id_periode];
        $supprimer = $Periodes->insertion_update_simples($sql, $params);
        if ($supprimer->rowCount() > 0) {
            $Periodes->set_flash("periode a été supprime avec succès", 'primary');
        }
        $Periodes->redirect("Periodes/Liste");
    }
}
