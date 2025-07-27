<?php 

class Homes extends Controller {
    private string $pythonPath = "C:\\Users\\DELL\\AppData\\Local\\Programs\\Python\\Python313\\python.exe";
    private string $scriptPath = "C:\\xampp\\htdocs\\G_universite\\python\\analyse_ai.py";
    private string $chatScript = "C:\\xampp\\htdocs\\G_universite\\python\\chat_ai.py";
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
    /**
     * Dashboard pour l'Enseignant
     * Vérifie le rôle de l'utilisateur et affiche les statistiques pertinentes.
     */
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
    /**
     * Dashboard pour le Chef de Département (Chef DR)
     * Vérifie le rôle de l'utilisateur et affiche les statistiques pertinentes.
     */
    // public function dashboard_chef_dr() {    
    //     if ($_SESSION['role'] !== 'Chef DR') {
    //         $this->redirect('Login');
    //         return;
    //     }
    //     $homeModel = new Home();
    //     $id_departement = $_SESSION['id_departement'];
    //     $data = [
    //         'role' => 'Chef DR',
    //         'etudiants' => $homeModel->getStatsEtudiantsParFiliereNiveau($id_departement) ?? [],
    //         'enseignants' => $homeModel->getStatsEnseignants($id_departement),
    //         'indicateurs' => $homeModel->getIndicateursGeneraux($id_departement),
    //         'examens' => $homeModel->getExamensAVenirDetails($id_departement) ?? [],
    //         'cours' => $homeModel->getCoursProgrammes($id_departement) ?? []
    //     ];
        
    //     // Appel au script Python via service IA
    //     $iaService = new AiService();
    //     $iaResponse = $iaService->askAI([
    //         "stats" => ["taux_reussite" => $stats['taux_reussite']],
    //         "departements" => array_map(fn($dep) => [
    //             "departement" => $dep->nom_departement ?? $dep->departement ?? 'N/A',
    //             "taux_reussite" => (float) $dep->taux_reussite
    //         ], $departements)
    //     ], "stats");
    //     // Debug: vérifiez les données avant envoi à la vue
    //     // var_dump($data); exit;
    //     $this->view('Home',
        
    //     $data);
    // }

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
    
        // Appel au service IA
        $iaService = new AiService();
        $iaResponse = $iaService->askAI([
            "etudiants" => $data['etudiants'],
            "enseignants" => $data['enseignants'],
            "indicateurs" => $data['indicateurs']
        ], "chef_dr");
    
        $data['ia_response'] = $iaResponse;
        $this->view('Home', $data);
    }
    /**
     * Dashboard pour le Scolarité
     * Vérifie le rôle de l'utilisateur et affiche les statistiques pertinentes.
     */
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
    /**
     * Dashboard pour le Secrétaire Principal (SGP)
     * Vérifie le rôle de l'utilisateur et affiche les statistiques pertinentes.
     */
    
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
    /**
     * Dashboard pour le Directeur Général Adjoint (DGA)
     * Vérifie le rôle de l'utilisateur et affiche les statistiques pertinentes.
     */
   

   
    public function dashboard_dga() {
        if ($_SESSION['role'] !== 'DGA') {
            $this->redirect('Login');
            return;
        }

        $homeModel = new Home();
        $stats = $homeModel->getStatsDGA();
        $departements = $homeModel->getStatsDepartementsDetail();

        // Trier par taux de réussite décroissant
        usort($departements, fn($a, $b) => $b->taux_reussite <=> $a->taux_reussite);

        $bestDepartement = $departements[0] ?? null;
        $worstDepartement = end($departements);

        // Ajout critère pour affichage dans la vue
        foreach ($departements as &$dep) {
            $dep->critere = '-';
            if ($dep === $bestDepartement) $dep->critere = 'Meilleur';
            if ($dep === $worstDepartement) $dep->critere = 'À suivre';
        }

        // Appel au script Python via service IA
        $iaService = new AiService();
        $iaResponse = $iaService->askAI([
            "stats" => ["taux_reussite" => $stats['taux_reussite']],
            "departements" => array_map(fn($dep) => [
                "departement" => $dep->nom_departement ?? $dep->departement ?? 'N/A',
                "taux_reussite" => (float) $dep->taux_reussite
            ], $departements)
        ], "stats");

        $this->view('Home', [
            'stats' => $stats,
            'departements' => $departements,
            'ia_response' => $iaResponse
        ]);
    }
  

    public function refreshAI() {
        header('Content-Type: application/json');
        try {
            // ✅ Prépare la commande pour exécuter le script Python
            $python = "C:\\Users\\DELL\\AppData\\Local\\Programs\\Python\\Python313\\python.exe";
            $script = "C:\\xampp\\htdocs\\G_universite\\python\\analyse_ai.py";

            // Pas besoin d'envoyer une question ici, juste mode stats
            $command = "\"$python\" \"$script\"";

            // ✅ Exécution du script Python
            $output = shell_exec($command);

            if (!$output) {
                throw new Exception("Pas de sortie du script Python");
            }

            // ✅ Décoder le JSON renvoyé par Python
            $data = json_decode($output, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception("Erreur décodage JSON Python");
            }

        echo json_encode($data);


        } catch (Exception $e) {
            error_log("Erreur refreshAI: " . $e->getMessage());
            echo json_encode(['response' => "⚠️ Service IA indisponible."]);
        }
        exit;
    }



    public function chatAI() {
        header('Content-Type: application/json');
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $question = $data['question'] ?? '';

            if (empty($question)) {
                echo json_encode(['response' => '⚠️ Question vide.']);
                return;
            }

            $aiService = new AiService();
            $responseRaw = $aiService->askChat($question);

            // ✅ Décoder la réponse JSON brute du script Python
            $responseDecoded = json_decode($responseRaw, true);

            if ($responseDecoded === null) {
                echo json_encode(['response' => '⚠️ Réponse IA invalide reçue.']);
                return;
            }

            // ✅ Retourner la réponse finale
            echo json_encode($responseDecoded, JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            error_log("Erreur chatAI: " . $e->getMessage());
            echo json_encode(['response' => '⚠️ Service IA indisponible']);
        }
    }






// ✅ Helper
private function isJson($string) {
    json_decode($string);
    return (json_last_error() == JSON_ERROR_NONE);
}




/**
 * Dashboard pour le Directeur Général (DG)
     * Vérifie le rôle de l'utilisateur et affiche les statistiques pertinentes.
     */
    // public function dashboard_dg()
    // {
    //     if ($_SESSION['role'] !== 'DG') {
    //         $this->redirect('Login');
    //         return;
    //     }

    //     $homeModel = new Home();
    //     $stats = $homeModel->getStatsDG();
    //     $topFilieres = $homeModel->getTopFilieres();
    //     // var_dump($stats);
    //     // var_dump($topFilieres);exit; // Pour débogage, à supprimer en production
    //     $this->view('Home', [
    //         'role' => 'DG',
    //         'stats' => $stats,
    //         'departements' => $stats['departements'], // ✅ important
    //         'topFilieres' => $topFilieres
    //     ]);

    // }



 
    
    
    
   
    
    public function dashboard_dg()
    {
        if ($_SESSION['role'] !== 'DG') {
            $this->redirect('Login');
            return;
        }
    
        $homeModel = new Home();
        $stats = $homeModel->getStatsDG();
        $topFilieres = $homeModel->getTopFilieres();
    
        // Appel au service IA
        $iaService = new AiService();
        $iaResponse = $iaService->askAI([
            "stats" => $stats,
            "topFilieres" => $topFilieres
        ], "dg");
    
        $this->view('Home', [
            'role' => 'DG',
            'stats' => $stats,
            'departements' => $stats['departements'],
            'topFilieres' => $topFilieres,
            'ia_response' => $iaResponse
        ]);
    }
    
    // ...existing code...

}