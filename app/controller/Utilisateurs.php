<?php
class Utilisateurs extends Controller
{
    public function index() {}
    public function liste_utilisateur($id_enseignant=null)
    {
        $utilisateur = new Utilisateur();
        $utilisateurenseignant = new Enseignant();
        if (isset($_POST["save_user"])) {

            $utilisateur->save_utilisateur(["nom_prenom", "contact_utilisateur", "email_utilisateurs", "mot_passe", "role"]);
        }
        //appel du method de recuperation 
        // Exemple d'utilisation de la méthode
        $select = "
        SELECT 
            utilisateur.id_utilisateur,
            utilisateur.nom_prenom AS utilisateur_nom_prenom,
            utilisateur.contact_utilisateur AS utilisateur_contact,
            utilisateur.email_utilisateurs AS utilisateur_email,
             utilisateur.signature AS signature,
            utilisateur.enseignant_id,
            enseignants.enseignant_nom,
            enseignants.enseignant_prenom,
            enseignants.enseignant_telephone,
            enseignants.enseignant_email
        FROM utilisateur
        LEFT JOIN enseignants
        ON utilisateur.enseignant_id = enseignants.enseignant_id
    ";
    


        // Appel de la méthode en passant les paramètres nécessaires
        $liste = $utilisateur->select_data_table_join_where($select);

        $enseignants = $utilisateurenseignant->SelectAllData("*", "enseignants");
        $departements = $utilisateur->SelectAllData("*", "departement");
        $this->view('liste_utilisateur', ['liste' => $liste, 'enseignants' => $enseignants,'id_enseignant'=>$id_enseignant,'departements'=>$departements]);
    }

    /// methode pour la modification
    public function edit_utilisateurs()
    {
        $utilisateur = new Utilisateur();
        if (isset($_POST['edit_user'])) {
            //  echo 'okkddddd';exit;
            extract($_POST);
            $id_utilisateur = $_POST["id_utilisateur"];
            $nom_prenom = $_POST["nom_prenom"];
            $contact_utilisateur = $_POST["contact_utilisateur"];
            $email_utilisateurs = $_POST["email_utilisateurs"];
            $mot_passe = $_POST["mot_passe"];
            $role = $_POST["role"];
            $utilisateur->edit_utilisateur(['id_utilisateur' => $id_utilisateur, 'nom_prenom' => $nom_prenom, 'contact_utilisateur' => $contact_utilisateur, 'email_utilisateurs' => $email_utilisateurs, 'mot_passe' => $mot_passe, 'role' => $role]);
        }
        $this->view('liste_utilisateur');
    }
    public function delete($id)
    {
        $S = new Semestre();
        // Définir la requête de suppression et les paramètres
        $sql = 'DELETE FROM utilisateur WHERE id_utilisateur = :id';
        $params = [':id' => $id];
        $result = $S->insertion_update_simples($sql, $params);
        if ($result->rowCount() > 0) {
            $S->set_flash("Suppression réussie", 'success');
        }
        $S->redirect("Utilisateurs/liste_utilisateur");
    }
}
