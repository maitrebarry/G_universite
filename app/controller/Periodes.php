<?php
class Periodes extends Controller
{
    public function index()
    {
        $this->view("liste_periode");
    }

    public function Liste()
    {
        $Periodes = new Periode();
        
        // Vérifier et créer une période si nécessaire
        $Periodes->verifierEtCreerPeriode();

        // Récupérer toutes les périodes
        $datas = $Periodes->SelectAllData("*", "periode");

        $this->view('liste_periode', ['datas' => $datas]);
    }
    public function verifierPeriode()
    {
        $periodeModel = new Periode();
        $periodeModel->verifierEtCreerPeriode();
        $this->view('liste_periode');
    }
    // public function update()
    // {
    //     $Periodes = new Periode();
    //     if (isset($_POST["modifier"])) {
    //         extract($_POST);
    //         $id_anne = $_POST['id_anne'];
    //         $anne_scolaire = $_POST['anne_scolaire'];
    //         $date_debut = $_POST['date_debut'];
    //         $date_fin = $_POST['date_fin'];
    //         $Periodes->modification(['id_anne' => $id_anne, 'anne_scolaire' => $anne_scolaire, 'date_debut' => $date_debut, 'date_fin' => $date_fin]);
    //     }
    //     $this->view('Liste');
    // }
    // public function supprimer($id_anne)
    // {
    //     $Periodes = new Periode();
    //     $sql = 'DELETE FROM anne_universitaire  WHERE id_anne = :id_anne';
    //     $params = [':id_anne' => $id_anne];
    //     $supprimer = $Periodes->insertion_update_simples($sql, $params);
    //     if ($supprimer->rowCount() > 0) {
    //         $Periodes->set_flash("L'annee a été supprime avec succès", 'primary');
    //     }
    //     $Periodes->redirect("Periodes/Liste");
    // }
}
