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
            return;
        }

        // Vérification dans la table enseignant (avec jointure sur grade)
        $query = "
            SELECT 
                e.enseignant_id,
                e.enseignant_prenom,
                e.enseignant_nom,
                e.enseignant_email,
                e.enseignant_telephone,
                g.nom_grade,
                u.id_utilisateur,
                u.role,
                u.mot_passe,
                u.signature,
                u.statut,  -- Ajout de la colonne statut
                d.id_departement,
                d.nom_departement,
                d.sigle_departement
            FROM 
                enseignants e
            LEFT JOIN 
                grade g ON e.id_grade = g.id_grade
            LEFT JOIN 
                utilisateur u ON e.enseignant_id = u.enseignant_id
            LEFT JOIN 
                departement d ON d.id_departement = u.id_departement
            WHERE 
                e.enseignant_email = :enseignant_email
        ";

        $enseignant = $this->select_data_table_join_where($query, ['enseignant_email' => $email]);

        if (!empty($enseignant)) {
            $enseignant = $enseignant[0]; // Récupérer la première ligne des résultats
            // Vérification du statut
            if ($enseignant->statut != 1) {
                $this->set_flash("Votre compte est inactif, veuillez contacter l'administrateur.", 'danger');
                return;
            }

            // Vérifier si le mot de passe correspond
            if (password_verify($mot_passe, $enseignant->mot_passe)) {
                // Stocker les informations de l'enseignant dans la session
                $_SESSION['id_utilisateur'] = $enseignant->id_utilisateur;
                $_SESSION['enseignant_id'] = $enseignant->enseignant_id;
                $_SESSION['nom_prenom'] = $enseignant->enseignant_prenom . " " . $enseignant->enseignant_nom;
                $_SESSION['email_utilisateurs'] = $enseignant->enseignant_email;
                $_SESSION['contact_utilisateur'] = $enseignant->enseignant_telephone;
                $_SESSION['role'] = $enseignant->role;
                $_SESSION['signature'] = $enseignant->signature;

                // Ajouter le grade dans la session uniquement si non nul
                if (!empty($enseignant->nom_grade)) {
                    $_SESSION['nom_grade'] = $enseignant->nom_grade;
                }
                if (strtoupper(str_replace(" ", "", $enseignant->role)) == strtoupper('ChefDR')) {
                    $_SESSION['nom_departement'] = $enseignant->nom_departement;
                    $_SESSION['sigle_departement'] = $enseignant->sigle_departement;

                }

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

            // Vérification du statut
            if ($utilisateur->statut != 1) {
                $this->set_flash("Votre compte est inactif, veuillez contacter l'administrateur.", 'danger');
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
                $_SESSION['signature'] = $utilisateur->signature;
                // Pas de grade pour un utilisateur classique
                unset($_SESSION['nom_grade']); // Supprime toute ancienne valeur de grade si existante

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
?>