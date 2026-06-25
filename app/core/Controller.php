<?php

// class Controller
// {
//    function load_model($model){

//       if(file_exists("app/models/".ucfirst($model).".php")){
//           require_once("app/models/".ucfirst($model).".php");
//           return  new $model();
//       }
//       return  false;
//    }
//    function view($view,$data=[]){
//        if(file_exists("app/views/".ucfirst($view).".view.php")){
//            extract($data);
//            require_once("app/views/".ucfirst($view).".view.php");
//        }else{
//            require_once("app/views/404.view.php");
//        }
//    }
//    public  function redirect($page)
//    {
//        header("Location:".ROOT."/".trim($page,"/"));
//        exit();
//    }

// }





class Controller
{
    /**
     * Controleurs accessibles SANS authentification (page de connexion, deconnexion).
     */
    protected static $publicControllers = ['Logins', 'Deconnections'];

    /**
     * Restriction de role appliquee AUTOMATIQUEMENT au niveau CONTROLEUR.
     * Cle = nom du controleur, valeur = liste des roles autorises.
     * Entree absente = il suffit d'etre connecte.
     * Pour une granularite par methode, appeler $this->requireRole([...]) dans la methode.
     *
     * Roles connus: SupAdmin, DG, DGA, Chef DR, Sécretaire principale, Scolarite, Enseignant
     */
    protected static $roleMap = [
        // Exemples a adapter a votre politique d'acces :
        // 'Departements' => ['SupAdmin', 'DG', 'DGA'],
        // 'Notes'        => ['SupAdmin', 'Chef DR', 'Enseignant'],
    ];

    public function __construct()
    {
        // La session est deja demarree dans Database.php (charge par l'autoload).
        $controller = get_class($this);

        // Pages publiques : on n'exige pas de session.
        if (in_array($controller, static::$publicControllers, true)) {
            return;
        }

        // 1) Authentification obligatoire pour tout le reste.
        $this->requireLogin();

        // 2) Restriction de role eventuelle au niveau controleur.
        if (!empty(static::$roleMap[$controller])) {
            $this->requireRole(static::$roleMap[$controller]);
        }
    }

    /** L'utilisateur a-t-il une session valide ? */
    public function isLoggedIn(): bool
    {
        return !empty($_SESSION['id_utilisateur']);
    }

    /** Role courant de l'utilisateur connecte (ou null). */
    public function currentRole(): ?string
    {
        return $_SESSION['role'] ?? null;
    }

    /** L'utilisateur possede-t-il l'un des roles donnes ? */
    public function hasRole($roles): bool
    {
        return in_array($this->currentRole(), (array) $roles, true);
    }

    /** Exige une session valide, sinon redirige vers la page de connexion. */
    public function requireLogin(): void
    {
        if (!$this->isLoggedIn()) {
            $this->flash("Veuillez vous connecter pour acceder a cette page.", 'danger');
            $this->redirect('Logins');
        }
    }

    /** Exige l'un des roles donnes, sinon redirige vers l'accueil. */
    public function requireRole($roles): void
    {
        $this->requireLogin();
        if (!$this->hasRole($roles)) {
            $this->flash("Acces refuse : vous n'avez pas les droits necessaires.", 'danger');
            $this->redirect('Homes');
        }
    }

    /** Message flash (meme structure que Model::set_flash, lisible par les vues). */
    protected function flash(string $message, string $type = 'danger'): void
    {
        $classes = [
            'primary' => 'bg-rgba-primary',
            'success' => 'bg-rgba-success',
            'danger'  => 'bg-rgba-danger',
            'warning' => 'bg-rgba-warning',
        ];
        $icons = [
            'primary' => 'bx bx-info-circle',
            'success' => 'bx bx-check-circle',
            'danger'  => 'bx bx-error',
            'warning' => 'bx bx-warning',
        ];
        $_SESSION['notification'] = [
            'message' => $message,
            'type'    => $type,
            'class'   => $classes[$type] ?? 'bg-rgba-danger',
            'icon'    => $icons[$type] ?? 'bx bx-info-circle',
        ];
    }

    function load_model($model)
    {
        if (file_exists("app/models/" . ucfirst($model) . ".php")) {
            require_once("app/models/" . ucfirst($model) . ".php");
            return new $model();
        }
        return false;
    }

    public function view($view, $data = [])
    {
        // notifications dispo dans toutes les vues
        $data['notifications'] = $this->getNotifications();

        $filename = "app/views/" . $view . ".view.php";
        if (file_exists($filename)) {
            extract($data);
            require $filename;
        } else {
            var_dump("Vue introuvable : " . $filename);
            exit;
        }
    }

    public function redirect($page)
    {
        header("Location:" . ROOT . "/" . trim($page, "/"));
        exit();
    }

    private function getNotifications()
    {
        if (!isset($_SESSION['enseignant_id'])) {
            return [];
        }

        $model = $this->load_model('Emploi_du_temp');
        $sql = "SELECT e.id_edt, e.date_debut, e.date_fin, m.nom_module, s.nom_salle
                FROM edt e
                JOIN module m ON m.id_module = e.id_module
                JOIN salle s ON s.id_salle = e.id_salle
                JOIN enseignant_edt ee ON ee.id_edt = e.id_edt
                WHERE ee.id_enseignant = ? AND e.date_debut >= ?
                ORDER BY e.date_debut ASC
                LIMIT 5";

        $params = [
            $_SESSION['enseignant_id'],
            date('Y-m-d')
        ];

        return $model->select_data_table_join_where($sql, $params);
    }
}

