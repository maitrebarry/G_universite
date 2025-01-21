<?php

class Login extends Model
{
    public function connecter()
    {
        // Récupération des champs email et mot de passe du formulaire
        $email_utilisateurs = filter_var($_POST['email_utilisateurs'] ?? null, FILTER_SANITIZE_EMAIL);
        $mot_passe = htmlspecialchars($_POST['mot_passe'] ?? null);

        // Vérification des champs vides
        if (empty($email_utilisateurs) || empty($mot_passe)) {
            if (empty($email_utilisateurs)) {
                $this->set_flash("Veuillez remplir le champ Email", 'danger');
            }
            if (empty($mot_passe)) {
                $this->set_flash("Veuillez remplir le champ Mot de passe", 'danger');
            }
            return; // Sortir si les champs sont vides
        }

        // Vérification dans la table utilisateurs
        $utilisateur = $this->FetchSelectWhere(
            '*',
            'utilisateur',
            'email_utilisateurs = :email_utilisateurs',
            ['email_utilisateurs' => $email_utilisateurs]
        );

        // Vérifiez si l'utilisateur existe
        if (!$utilisateur) {
            $this->set_flash("Aucun utilisateur trouvé avec cet email", 'danger');
            return;
        }

        // Comparer le mot de passe de l'utilisateur
        if (password_verify($mot_passe, $utilisateur->mot_passe)) {
            // Stocker les informations de l'utilisateur dans la session
            $_SESSION['id_utilisateur'] = $utilisateur->id_utilisateur;
            $_SESSION['nom_prenom'] = $utilisateur->nom_prenom;
            $_SESSION['email_utilisateurs'] = $utilisateur->email_utilisateurs;
            $_SESSION['role'] = $utilisateur->role;
            $_SESSION['contact_utilisateur'] = $utilisateur->contact_utilisateur;

            // Redirection après connexion
            $this->redirect("Homes/home");
            return; // Sortir de la fonction après redirection
        } else {
            $this->set_flash("Mot de passe incorrect pour l'utilisateur", 'danger');
            return; // Sortir de la fonction après erreur
        }
    }
}
