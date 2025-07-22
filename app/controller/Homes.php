<?php 

class Homes extends Controller {
    public function index() {
        $periodeModel = new Periode();
        $periodeModel->verifierEtCreerPeriode();

        // Redirection basée sur le rôle
        if (!isset($_SESSION['role'])) {
            $this->redirect('Login');
            return;
        }

        switch ($_SESSION['role']) {
            case 'Enseignant':
                $this->dashboard_enseignant();
                break;
            case 'Chef DR':
                $this->dashboard_chef_dr();
                break;
            case 'Sécretaire principale':
                $this->dashboard_secretaire();
                break;
            case 'DGA':
                $this->dashboard_dga();
                break;
            case 'DG':
                $this->dashboard_dg();
                break;
            case 'SupAdmin':
                $this->dashboard_supadmin();
                break;
            case 'Scolarite':
                $this->dashboard_scolarite();
                break;
            default:
                $this->redirect('Login');
        }
    }
    public function dashboard_supadmin() {
        if ($_SESSION['role'] !== 'SupAdmin') {
            $this->redirect('Login');
            return;
        }

        $homeModel = new Home();
      
        $this->view('Home');
    }
    public function dashboard_enseignant() {
        if ($_SESSION['role'] !== 'Enseignant' || !isset($_SESSION['enseignant_id'])) {
            $this->redirect('Login');
            return;
        }

        $homeModel = new Home();
        $id_enseignant = $_SESSION['enseignant_id'];

        $data = [
            'role' => 'Enseignant',
            'activiteSemaine' => $homeModel->getActiviteHebdomadaire($id_enseignant),
            'statsMoyenne' => $homeModel->getPourcentageEtudiantsMoyenne($id_enseignant),
            'statsParcours' => $homeModel->getStatsEtudiantsParcours($id_enseignant),
            'emploiDuTemps' => $homeModel->getEmploiDuTemps($id_enseignant),
            'periodeActive' => $homeModel->getPeriodeActive()
        ];
        $this->view('Home', $data);
    }
    public function dashboard_chef_dr() {    
        if ($_SESSION['role'] !== 'Chef DR') {
            $this->redirect('Login');
            return;
        }
        $homeModel = new Home();
        $id_departement = $_SESSION['id_departement'];
        $data = [
            'role' => 'Chef DR',
            'etudiants' => $homeModel->getStatsEtudiantsParFiliereNiveau($id_departement) ?? [],
            'enseignants' => $homeModel->getStatsEnseignants($id_departement),
            'indicateurs' => $homeModel->getIndicateursGeneraux($id_departement),
            'examens' => $homeModel->getExamensAVenirDetails($id_departement) ?? [],
            'cours' => $homeModel->getCoursProgrammes($id_departement) ?? []
        ];
        // Debug: vérifiez les données avant envoi à la vue
        // var_dump($data); exit;
        $this->view('Home', $data);
    }
    public function dashboard_scolarite() {
        if ($_SESSION['role'] !== 'Scolarite') {
            $this->redirect('Login');
            return;
        }
        
        $homeModel = new Home();

        $data = [
            'role' => 'Scolarite',
            'etudiants' => $homeModel->getStatsEtudiantsParFiliereNiveau_scolarite() ?? [],
            'indicateurs' => $homeModel->getIndicateursGeneraux_scolarite(),
        ];

        $this->view('Home', $data);
    }
    public function dashboard_secretaire() {
        if ($_SESSION['role'] !== 'Sécretaire principale') {
            $this->redirect('Login');
            return;
        }

        $homeModel = new Home();

       

        $this->view('Home');
    }
    // public function dashboard_dga() {
    //     if ($_SESSION['role'] !== 'DGA') {
    //         $this->redirect('Login');
    //         return;
    //     }

    //     $homeModel = new Home();

    //     $data = [
    //         'role' => 'DGA',
    //         'statsFormations' => $homeModel->getStatsFormations(),
    //         'budgetGlobal' => $homeModel->getBudgetGlobal(),
    //         'tauxOccupationSalles' => $homeModel->getTauxOccupationSalles(),
    //         'periodeActive' => $homeModel->getPeriodeActive()
    //         // Ajoutez d'autres données spécifiques au DGA
    //     ];

    //     $this->view('Home', $data);
    // }
    // public function dashboard_dg() {
    //     if ($_SESSION['role'] !== 'DG') {
    //         $this->redirect('Login');
    //         return;
    //     }

    //     $homeModel = new Home();

    //     $data = [
    //         'role' => 'DG',
    //         'indicateursPerformance' => $homeModel->getIndicateursPerformance(),
    //         'evolutionEffectifs' => $homeModel->getEvolutionEffectifs(),
    //         'resultatsFinanciers' => $homeModel->getResultatsFinanciers(),
    //         'periodeActive' => $homeModel->getPeriodeActive()
    //         // Ajoutez d'autres données spécifiques au DG
    //     ];

    //     $this->view('Home', $data);
    // }
}