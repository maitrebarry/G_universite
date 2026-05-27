<?php
class Reinsciptions extends Controller
{
    public function index()
    {
        $this->view('reinscription');
    }

    public function get_etudiants()
    {
        if (isset($_POST["annee_universitaire"], $_POST["id_promotion"])) {
            $reinscription = new Reinscription();
            $liste_etudiant = $reinscription->trie_liste_etudiant(
                $_POST["annee_universitaire"],
                $_POST["id_promotion"],

            );
            $this->view('post_reinscription', [
                'liste_etudiant' => $liste_etudiant
            ]);
            return;
        }
    }
}