<?php
class Emploi_du_temps extends Controller
{
    public function index()
    {
        $edtModel = new Emploi_du_temp();
        $edts = $edtModel->listeEdts();
        $filiereModel = new Filiere();
        $filieres = $filiereModel->SelectAllData("*", "filiere");
        $this->view('liste_EDT', ['edts' => $edts, 'filieres' => $filieres]);
    }

    public function trier_liste_edt()
    {
        if (isset($_POST['action']) && $_POST['action'] == "trier_edt") {
            @$idFiliere = $_POST['idFiliere'];
            @$idPromotion = $_POST['idPromotion'];
            $edtModel = new Emploi_du_temp();
            $edts = $edtModel->trierListeEdt($idFiliere, $idPromotion);
            $this->view("post_liste_edt", ["edts" => $edts]);
        }
    }


    public function ajouter_EDT($idFiliere = null, $idPromotion = null)
    {
        if (isset($_POST['action']) && $_POST['action'] === "ajouter_EDT") {
            @$edt = $_POST['edt'];
            @$horaires = $_POST['horaires'];
            $edtModel = new Emploi_du_temp();
            $edtModel->ajouterEdt($edt, $horaires);
            $this->view("set_flash");
            return;
        }
        $filiereModel = new Filiere();
        $filieres = $filiereModel->SelectAllData("*", "filiere");
        $enseignants = $filiereModel->SelectAllData("*", "enseignants");
        $salles = $filiereModel->SelectAllData("*", "salle");
        $jours = $filiereModel->SelectAllData("*", "jour");
        $this->view("ajouter_EDT", [
            'filieres' => $filieres,
            "enseignants" => $enseignants,
            "salles" => $salles,
            "jours" => $jours,
            'idFiliere' => $idFiliere,
            'idPromotion' => $idPromotion
        ]);
    }


    public function apercu_edt($idEdt = null)
    {
        if ($idEdt != null && is_numeric($idEdt)) {
            $edtModel = new Emploi_du_temp();
            $infosEdt = $edtModel->getInfoEdt($idEdt);
            $horairesEdt = $edtModel->getHorairesEdt($idEdt);
            $jours = $edtModel->SelectAllData("*", "jour");
            if (!empty($infosEdt)) {
                $this->view("apercu_edt", ["infosEdt" => $infosEdt, "horairesEdt" => $horairesEdt, "jours" => $jours]);
            }
        }
    }

    public function filiere_info()
    {
        if (isset($_POST['idFiliere'])) {

            $idFiliere = $_POST['idFiliere'];
            $filiereModel = new Filiere();
            $infoFiliere = $filiereModel->apercu_filiere($idFiliere);
            $statut = (@$_POST['source'] == 'all') ? null : 1;
            $promotions = $filiereModel->listePromotions($idFiliere, $statut);
            $infoFiliere['promotions'] = $promotions;
            header("Content-Type:application/json");
            echo json_encode($infoFiliere);
        }
    }

    public function get_ancien_edt()
    {
        if (isset($_POST['idFiliere'], $_POST['idModule'])) {
            $edtModel = new Emploi_du_temp();
            $idFiliere = $_POST['idFiliere'];
            $idModule = $_POST['idModule'];
            $edt = $edtModel->getAncienEdt($idFiliere, $idModule);
            header("Content-Type:application/json");
            echo json_encode($edt);
        }
    }

    public function editer_edt($idEdt = null)
    {
        if ($idEdt != null && is_numeric($idEdt)) {
            $edtModel = new Emploi_du_temp();
            $infosEdt = $edtModel->getInfoEdt($idEdt);
            $horairesEdt = $edtModel->getHorairesEdt($idEdt);
            $jours = $edtModel->SelectAllData("*", "jour");
            $filiereModel = new Filiere();
            $filieres = $filiereModel->SelectAllData("*", "filiere");
            $enseignants = $filiereModel->SelectAllData("*", "enseignants");
            $salles = $filiereModel->SelectAllData("*", "salle");
            if ($infosEdt != null && !empty($infosEdt)) {
                $this->view('editer_edt', [
                    "infosEdt" => $infosEdt,
                    "horairesEdt" => $horairesEdt,
                    "jours" => $jours,
                    "filieres" => $filieres,
                    "enseignants" => $enseignants,
                    "salles" => $salles
                ]);
            }
        } else if ($_POST['action'] == "editer_edt") {
            @$edt = $_POST['edt'];
            @$horaires = $_POST['horaires'];
            $edtModel = new Emploi_du_temp();
            $edtModel->editerEdt($edt, $horaires);
            $this->view("set_flash");
            return;
        }
        exit("Edt Introuvable");
    }
}
