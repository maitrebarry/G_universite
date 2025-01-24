<?php

class Login extends Model
{
    public function connecter()
    {
        // Récupération des champs email et mot de passe du formulaire
        $email = filter_var($_POST['email_utilisateurs'] ?? null, FILTER_SANITIZE_EMAIL);
        $mot_passe = htmlspecialchars($_POST['mot_passe'] ?? null);

        // Vérification des champs vides
        if (empty($email) || empty($mot_passe)) {
            if (empty($email)) {
                $this->set_flash("Veuillez remplir le champ Email", 'danger');
            }
            if (empty($mot_passe)) {
                $this->set_flash("Veuillez remplir le champ Mot de passe", 'danger');
            }
            return; // Sortir si les champs sont vides
        }

        // Vérification dans la table enseignant d'abord
        $enseignant = $this->FetchSelectWhere(
            '*',
            'enseignants',
            'enseignant_email = :enseignant_email',
            ['enseignant_email' => $email]
        );

        if ($enseignant) {
            // Cas d'un enseignant : récupérer son mot de passe dans la table utilisateur
            $utilisateur = $this->FetchSelectWhere(
                '*',
                'utilisateur',
                'enseignant_id = :enseignant_id',
                ['enseignant_id' => $enseignant->enseignant_id]
            );

            if (!$utilisateur) {
                $this->set_flash("Aucun utilisateur trouvé pour cet enseignant", 'danger');
                return;
            }

            // Vérifier le mot de passe
            if (password_verify($mot_passe, $utilisateur->mot_passe)) {
                // Stocker les informations de l'enseignant dans la session
                $_SESSION['id_utilisateur'] = $utilisateur->id_utilisateur;
                $_SESSION['enseignant_id'] = $enseignant->enseignant_id;
                $_SESSION['nom_prenom'] = $enseignant->enseignant_prenom . " " . $enseignant->enseignant_nom;
                $_SESSION['email_utilisateurs'] = $enseignant->enseignant_email;
                $_SESSION['contact_utilisateur'] = $enseignant->enseignant_telephone;
                $_SESSION['role'] = $utilisateur->role;

                // Redirection après connexion
                $this->redirect("Homes/home");
                return;
            } else {
                $this->set_flash("Mot de passe incorrect pour cet enseignant", 'danger');
                return;
            }
        } else {
            // Cas d'un utilisateur classique
            $utilisateur = $this->FetchSelectWhere(
                '*',
                'utilisateur',
                'email_utilisateurs = :email_utilisateurs',
                ['email_utilisateurs' => $email]
            );

            if (!$utilisateur) {
                $this->set_flash("Aucun utilisateur trouvé avec cet email", 'danger');
                return;
            }

            // Vérification du mot de passe
            if (password_verify($mot_passe, $utilisateur->mot_passe)) {
                // Stocker les informations de l'utilisateur classique dans la session
                $_SESSION['id_utilisateur'] = $utilisateur->id_utilisateur;
                $_SESSION['enseignant_id'] = null; // Pas d'enseignant
                $_SESSION['nom_prenom'] = $utilisateur->nom_prenom;
                $_SESSION['email_utilisateurs'] = $utilisateur->email_utilisateurs;
                $_SESSION['contact_utilisateur'] = $utilisateur->contact_utilisateur;
                $_SESSION['role'] = $utilisateur->role;

                // Redirection après connexion
                $this->redirect("Homes/home");
                return;
            } else {
                $this->set_flash("Mot de passe incorrect pour cet utilisateur", 'danger');
                return;
            }
        }
    }
}
