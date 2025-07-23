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
            'inscrits_par_annee' => $homeModel->getInscritsParAnnee(),
        ];
        // var_dump($data);exit;
        $this->view('Home', $data);
    }
    
    public function dashboard_secretaire() {
        if ($_SESSION['role'] !== 'Sécretaire principale') {
            $this->redirect('Login');
            return;
        }

        $homeModel = new Home();

        $data = [
            'role' => 'Sécretaire principale',
            'stats' => $homeModel->getStatsSGP(),
            'dernieres_inscriptions' => $homeModel->getDernieresInscriptions(),
            'prochains_evenements' => $homeModel->getProchainsEvenements()
        ];
        // var_dump($data); exit;
        $this->view('Home', $data);
    }
    public function dashboard_dga() {
        if ($_SESSION['role'] !== 'DGA') {
            $this->redirect('Login');
            return;
        }

        $homeModel = new Home();

        $stats = $homeModel->getStatsDGA();

        // Récupération des stats départementales détaillées
        $departements = $homeModel->getStatsDepartementsDetail();

        // Identifier meilleur et pire département
        $bestDepartement = $departements[0] ?? null;
        $worstDepartement = end($departements);

        foreach ($departements as &$dep) {
            if ($dep === $bestDepartement) {
                $dep->critere = 'Meilleur';
            } elseif ($dep === $worstDepartement) {
                $dep->critere = 'à Suivre';
            } else {
                $dep->critere = '-';
            }
        }
        reset($departements);
        // var_dump($stats); 
        // var_dump($departements);exit; 
        
        // Passer tout à la vue
        $this->view('Home', [
            'stats' => $stats,
            'departements' => $departements
        ]);
    }



   public function dashboard_dg()
{
    if ($_SESSION['role'] !== 'DG') {
        $this->redirect('Login');
        return;
    }

    $homeModel = new Home();
    $stats = $homeModel->getStatsDG();
    $topFilieres = $homeModel->getTopFilieres();
    // var_dump($stats);
    // var_dump($topFilieres);exit; // Pour débogage, à supprimer en production
    $this->view('Home', [
        'role' => 'DG',
        'stats' => $stats,
        'departements' => $stats['departements'], // ✅ important
        'topFilieres' => $topFilieres
    ]);

}


}