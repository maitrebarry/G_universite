<?php

class Filieres extends Controller
{
    //! Debut de la gestion d'une filière
    public function index()
    {
        $idDepartement = null;
        if (isset($_SESSION['id_departement'])) {
            $idDepartement = $_SESSION['id_departement'];
        }
        $filiereModel = new Filiere();
        $listeFilieres = $filiereModel->listeFilieresAvecStats($idDepartement);
        $this->view('liste_filiere', ['filieres' => $listeFilieres]);
    }

    // Suppression d'une filière (AJAX)
    public function supprimer_filiere()
    {
        header('Content-Type: application/json');
        if (empty($_POST['idFiliere']) || !is_numeric($_POST['idFiliere'])) {
            echo json_encode(['ok' => false, 'msg' => 'Filière invalide.']);
            return;
        }
        echo json_encode((new Filiere())->supprimerFiliere($_POST['idFiliere']));
    }

    // Ajouter une filière
    public function ajouter_filiere()
    {
        if (isset($_POST['action']) && $_POST['action'] == "ajouter_filiere") {
            @$filiere = $_POST['filiere'];
            @$semestres = $_POST['semestres'];
            @$ues = $_POST['ues'];
            @$modules = $_POST['modules'];

            $filiereModel = new Filiere();
            $filiereModel->ajouter_filiere($filiere, $semestres, $ues, $modules);
            $this->view("set_flash");
            exit();
        }

        $semestreModel = new Semestre();
        $listeSemestres = $semestreModel->SelectAllData("*", "semestre");
        $departements = $semestreModel->SelectAllData("*", "departement");
        $this->view('ajouter_filiere', ['semestres' => $listeSemestres, 'departements' => $departements]);
    }

    // la fonction pour jerer l'ajout des semestres des semestres, des ue et des modules dans une filière
    public function post_ajouter_filiere()
    {
        if (isset($_POST['action'])) {

            $action = $_POST['action'];
            @$semestre = [
                'semestreId' => $_POST['semestreId'],
                'semestreName' => $_POST['semestreName'],
            ];
            $moduleModel = new Module();
            $listeModules = $moduleModel->SelectAllData("*", "module");
            $this->view('post_ajouter_filiere', ['action' => $action, 'semestre' => $semestre, "modules" => $listeModules]);
        }
    }

    // Voir l'apperçu d'une filière
    public function apercu_filiere($idFiliere = null)
    {
        if ($idFiliere != null && is_numeric($idFiliere)) {
            $filiereModel = new Filiere();
            $infosFiliere = $filiereModel->apercu_filiere($idFiliere);
            if (!empty($infosFiliere)) {
                if (isset($_POST['action']) && $_POST['action'] == 'print') {
                    $this->view("post_apercu_filiere", ["infoFiliere" => $infosFiliere]);
                    return;
                }
                $this->view("apercu_filiere", ["infoFiliere" => $infosFiliere]);
            }
        }
    }

    // Editer une filière
    public function editer_filiere($idFiliere = null)
    {
        if ($idFiliere != null && is_numeric($idFiliere)) {
            $filiereModel = new Filiere();
            $infosFiliere = $filiereModel->apercu_filiere($idFiliere);
            if (!empty($infosFiliere)) {
                $semestreModel = new Semestre();
                $listeSemestres = $semestreModel->SelectAllData("*", "semestre");
                $moduleModel = new Module();
                $listeModules = $moduleModel->SelectAllData("*", "module");
                $action = "edit";
                $this->view(
                    "editer_filiere",
                    [
                        "infoFiliere" => $infosFiliere,
                        'semestres' => $listeSemestres,
                        "modules" => $listeModules,
                        "action" => $action,
                        "url" => "editer_filiere"
                    ]
                );
            }
        } else  if (isset($_POST['action']) && $_POST['action'] == "editer_filiere") {
            @$filiere = $_POST['filiere'];
            @$semestres = $_POST['semestres'];
            @$ues = $_POST['ues'];
            @$modules = $_POST['modules'];

            $filiereModel = new Filiere();
            $filiereModel->editerInfoFiliere($filiere, $semestres, $ues, $modules);
            $this->view("set_flash");
            exit();
        }
    }

    // ajouter des élements dans une filière
    public function ajouter_element_filiere($idFiliere = null)
    {
        if ($idFiliere != null && is_numeric($idFiliere)) {
            $filiereModel = new Filiere();
            $infosFiliere = $filiereModel->apercu_filiere($idFiliere);
            if (!empty($infosFiliere)) {
                $semestreModel = new Semestre();
                $listeSemestres = $semestreModel->SelectAllData("*", "semestre");
                $moduleModel = new Module();
                $listeModules = $moduleModel->SelectAllData("*", "module");
                $action = "add";
                $this->view(
                    "editer_filiere",
                    [
                        "infoFiliere" => $infosFiliere,
                        'semestres' => $listeSemestres,
                        "modules" => $listeModules,
                        "action" => $action,
                        "url" => "ajouter_element_filiere"
                    ]
                );
            }
        } else  if (isset($_POST['action']) && $_POST['action'] == "ajouter_element_filiere") {
            @$filiere = $_POST['filiere'];
            @$semestres = $_POST['semestres'];
            @$ues = $_POST['ues'];
            @$modules = $_POST['modules'];

            $filiereModel = new Filiere();
            $filiereModel->ajouterElementFiliere($filiere, $semestres, $ues, $modules);
            $this->view("set_flash");
            exit();
        }
    }

    // Supprimer des élements dans une filière
    public function supprimer_element_filiere($idFiliere = null)
    {
        $filiereModel = new Filiere();
        if ($idFiliere != null && is_numeric($idFiliere)) {
            $infosFiliere = $filiereModel->apercu_filiere($idFiliere);
            if (!empty($infosFiliere)) {
                $semestreModel = new Semestre();
                $listeSemestres = $semestreModel->SelectAllData("*", "semestre");
                $moduleModel = new Module();
                $listeModules = $moduleModel->SelectAllData("*", "module");
                $action = "del";
                $this->view(
                    "editer_filiere",
                    [
                        "infoFiliere" => $infosFiliere,
                        'semestres' => $listeSemestres,
                        "modules" => $listeModules,
                        "action" => $action,
                        "url" => "supprimer_element_filiere"
                    ]
                );
            }
        } else if (isset($_POST['action']) && is_numeric($_POST['id'])) {
            $action = htmlspecialchars(trim($_POST['action']));
            $id = htmlspecialchars(trim($_POST['id']));;
            $filiereModel->supprimerElementFiliere($action, $id);
        }
    }
    //! Fin de la gestion d'une filière

    //! Debut de la gestion d'une promotion
    // Ajouter une promotion
    public function ajouter_promotion()
    {
        if (strtoupper($_SERVER['REQUEST_METHOD']) === "POST") {

            $filiereModel = new Filiere();
            $filiereModel->ajouterPromotion();
        }
    }

    // Liste des promotions
    public function liste_promotion($idFiliere = null)
    {
        if ($idFiliere == null || !is_numeric($idFiliere)) {
            exit();
        }

        $filiereModel = new Filiere();
        $promotions = $filiereModel->listePromotions($idFiliere);
        $this->view("liste_promotion", ["promotions" => $promotions, "idFiliere" => $idFiliere]);
    }

    public function set_status_promotion($idFiliere, $idPromotion, $statut)
    {
        $filiereModel = new Filiere();
        $isUpdate = $filiereModel->setStatusPromotion($idPromotion, $statut);
        $this->redirect('/Filieres/liste_promotion/' . $idFiliere);
        return;
    }
    //! Fin de la gestion d'une promotion

}
