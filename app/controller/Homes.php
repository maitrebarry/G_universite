<?php 
class Homes extends Controller {

    public function index() {
        $periodeModel = new Periode();
        $periodeModel->verifierEtCreerPeriode();

        // Redirection basée sur le rôle
        if (!isset($_SESSION['role'])) {
            $this->redirect('Logins');
            return;
        }

        // Enseignant : tableau de bord personnel. Tous les autres rôles d'administration
        // (SupAdmin, DG, DGA, Chef DR, Scolarité, Secrétaire) : nouveau tableau de bord modernisé.
        if ($_SESSION['role'] === 'Enseignant') {
            $this->dashboard_enseignant();
            return;
        }

        $homeModel = new Home();
        $this->view('dashboard_supadmin', ['data' => $homeModel->getStatsSupAdmin()]);
    }
    
    // ✅ Méthode home() pour résoudre l'erreur 404
    public function home() {
        $this->index();
    }
    
    public function dashboard_supadmin() {
        if ($_SESSION['role'] !== 'SupAdmin') {
            $this->redirect('Logins');
            return;
        }

        $homeModel = new Home();
        $this->view('dashboard_supadmin', ['data' => $homeModel->getStatsSupAdmin()]);
    }

    public function dashboard_enseignant() {
        if ($_SESSION['role'] !== 'Enseignant' || !isset($_SESSION['enseignant_id'])) {
            $this->redirect('Logins');
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
        $this->view('home', $data); // ✅ "home" au lieu de "Home"
    }

    public function dashboard_chef_dr() {    
        if ($_SESSION['role'] !== 'Chef DR') {
            $this->redirect('Logins');
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
       //var_dump($data); exit;
        // Appel au service IA
        // $iaService = new AiService();
        // $iaResponse = $iaService->askAI([
        //     "etudiants" => $data['etudiants'],
        //     "enseignants" => $data['enseignants'],
        //     "indicateurs" => $data['indicateurs']
        // ], "chef_dr");
    
        // $data['ia_response'] = $iaResponse;
        $this->view('home', $data); // ✅ "home" au lieu de "Home"
    }

    public function dashboard_scolarite() {
        if ($_SESSION['role'] !== 'Scolarite') {
            $this->redirect('Logins');
            return;
        }
        
        $homeModel = new Home();

        $data = [
            'role' => 'Scolarite',
            'etudiants' => $homeModel->getStatsEtudiantsParFiliereNiveau_scolarite() ?? [],
            'indicateurs' => $homeModel->getIndicateursGeneraux_scolarite(),
            'inscrits_par_annee' => $homeModel->getInscritsParAnnee(),
        ];
        $this->view('home', $data); // ✅ "home" au lieu de "Home"
    }

    public function dashboard_secretaire() {
        if ($_SESSION['role'] !== 'Sécretaire principale') {
            $this->redirect('Logins');
            return;
        }

        $homeModel = new Home();

        $data = [
            'role' => 'Sécretaire principale',
            'stats' => $homeModel->getStatsSGP(),
            'dernieres_inscriptions' => $homeModel->getDernieresInscriptions(),
            'prochains_evenements' => $homeModel->getProchainsEvenements()
        ];
        $this->view('home', $data); // ✅ "home" au lieu de "Home"
    }

    public function dashboard_dga() {
        if ($_SESSION['role'] !== 'DGA') {
            $this->redirect('Logins');
            return;
        }

        $homeModel = new Home();
        $stats = $homeModel->getStatsDGA();
        $departements = $homeModel->getStatsDepartementsDetail();

        usort($departements, fn($a, $b) => $b->taux_reussite <=> $a->taux_reussite);

        $bestDepartement = $departements[0] ?? null;
        $worstDepartement = end($departements);

        foreach ($departements as &$dep) {
            $dep->critere = '-';
            if ($dep === $bestDepartement) $dep->critere = 'Meilleur';
            if ($dep === $worstDepartement) $dep->critere = 'À suivre';
        }

        // $iaService = new AiService();
        // $iaResponse = $iaService->askAI([
        //     "stats" => ["taux_reussite" => $stats['taux_reussite']],
        //     "departements" => array_map(fn($dep) => [
        //         "departement" => $dep->nom_departement ?? $dep->departement ?? 'N/A',
        //         "taux_reussite" => (float) $dep->taux_reussite
        //     ], $departements)
        // ], "stats");

        $this->view('home', [ // ✅ "home" au lieu de "Home"
            'stats' => $stats,
            'departements' => $departements,
            // 'ia_response' => $iaResponse
        ]);
    }


    public function dashboard_dg() {
        if ($_SESSION['role'] !== 'DG') {
            $this->redirect('Logins');
            return;
        }
    
        $homeModel = new Home();
        $stats = $homeModel->getStatsDG();
        $topFilieres = $homeModel->getTopFilieres();
    
        // $iaService = new AiService();
        // $iaResponse = $iaService->askAI([
        //     "stats" => $stats,
        //     "topFilieres" => $topFilieres
        // ], "dg");
    
        $this->view('home', [ // ✅ "home" au lieu de "Home"
            'role' => 'DG',
            'stats' => $stats,
            'departements' => $stats['departements'],
            'topFilieres' => $topFilieres,
            // 'ia_response' => $iaResponse
        ]);
    }
}
?>