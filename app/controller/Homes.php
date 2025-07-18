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
            default:
                $this->redirect('Login');
        }
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
            if (!isset($_SESSION['role'])) {
                $this->redirect('Login');
                return;
            }

        $homeModel = new Home();

        // Récupération des données de base
        $statsNiveaux = $homeModel->getStatsEtudiantsParNiveau() ?? (object)[
            'l1' => 0, 'l2' => 0, 'l3' => 0, 'unregistered' => 0
        ];

        // Calcul du total des inscrits
        $totalInscrits = $statsNiveaux->l1 + $statsNiveaux->l2 + $statsNiveaux->l3;
        
        $data = [
            'role' => 'Chef DR',
            'statsNiveaux' => $statsNiveaux,
            'statsGenre' => $homeModel->getStatsEtudiantsParGenre() ?? (object)['male' => 0, 'female' => 0],
            'statsEnseignants' => $homeModel->getStatsEnseignants() ?? (object)['total' => 0, 'actifs' => 0],
            'coursProgrammes' => $homeModel->getCoursProgrammes() ?? (object)['total' => 0, 'confirmes' => 0, 'heures_total' => 0],
            'tauxReussite' => $homeModel->getTauxReussiteGlobal() ?? (object)['taux' => 0, 'reussis' => 0, 'total' => 0],
            'examensAVenir' => $homeModel->getExamensAVenir() ?? 0,
            'coursProgrammesListe' => $homeModel->getListeCoursProgrammes() ?? [],
            'examensDetails' => $homeModel->getExamensAVenirDetails() ?? [],
            'periodeActive' => $homeModel->getPeriodeActive(),
            'totalInscrits' => $totalInscrits ,
            
        ];

        $this->view('Home', $data);
    }

    // public function dashboard_secretaire() {
    //     if ($_SESSION['role'] !== 'Sécretaire principale') {
    //         $this->redirect('Login');
    //         return;
    //     }

    //     $homeModel = new Home();

    //     $data = [
    //         'role' => 'Sécretaire principale',
    //         'totalEtudiants' => $homeModel->getTotalEtudiants(),
    //         'nouveauxInscrits' => $homeModel->getNouveauxInscrits(),
    //         'dossiersEnAttente' => $homeModel->getDossiersEnAttente(),
    //         'periodeActive' => $homeModel->getPeriodeActive()
    //         // Ajoutez d'autres données spécifiques au secrétariat
    //     ];

    //     $this->view('Home', $data);
    // }

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