<?php
class Reinsciptions extends Controller
{
    public function index()
    {
        $this->view('reinscription');
    }

    public function get_etudiants()
    {
        if (isset($_POST["annee_universitaire"], $_POST["id_filiere"], $_POST["id_semestre"])) {
            $etudiant = new Etudiant();
            $liste_etudiant = $etudiant->trie_liste_etudiant(
                $_POST["annee_universitaire"],
                $_POST["id_filiere"],
                $_POST["id_semestre"]
            );

            $this->view('post_reinscription', [
                'liste_etudiant' => $liste_etudiant
            ]);
            return;
        }
    }
}