<?php
class Deconnections extends Controller
{
    public function deconnexion()
    {
        // Détruire toutes les données de session
        session_unset(); // Libérer toutes les variables de session
        session_destroy(); // Détruire la session

        // Rediriger vers la page de connexion ou une autre page appropriée
        $this->redirect("Logins");
    }
}
