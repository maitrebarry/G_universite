<?php



class Logins extends Controller {
    public function index() {
        $perso = new Login();
        $perso->ensureDefaultSupAdmin();

        if (isset($_POST["submit"])) {
            $perso->connecter();
        }

        // Toujours charger la vue, que ce soit après une erreur ou un simple affichage
        $this->view("login");
    } 
}
