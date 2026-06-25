<?php

class Login extends Model {
    public function ensureDefaultSupAdmin()
    {
        $count = $this->SelectData('utilisateur', 'COUNT(*) AS total');
        if ($count && (int) $count->total > 0) {
            return;
        }

        $this->insertion_update_simples(
            "INSERT INTO utilisateur (nom_prenom, contact_utilisateur, email_utilisateurs, mot_passe, role, signature, enseignant_id, id_departement, statut)
             VALUES (:nom_prenom, :contact, :email, :mot_passe, :role, NULL, NULL, NULL, 1)",
            [
                ':nom_prenom' => 'Super Admin',
                ':contact' => '',
                ':email' => 'barrymoustapha485@gmail.com',
                ':mot_passe' => password_hash('admin123', PASSWORD_DEFAULT),
                ':role' => 'SupAdmin',
            ]
        );
    }

    public function connecter() {
        $this->ensureDefaultSupAdmin();

        $email = filter_var($_POST['email_utilisateurs'] ?? null, FILTER_SANITIZE_EMAIL);
        // Pas de htmlspecialchars ici : le mot de passe n'est jamais affiché, et le
        // transformer cassait la connexion des mots de passe à caractères spéciaux
        // (la création hache le mot de passe BRUT).
        $mot_passe = $_POST['mot_passe'] ?? '';

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
            $enseignant = $enseignant[0];
            
            if ($enseignant->statut != 1) {
                $this->set_flash("Votre compte est inactif, veuillez contacter l'administrateur.", 'danger');
                return;
            }

            if (password_verify($mot_passe, $enseignant->mot_passe)) {
                $_SESSION['id_utilisateur'] = $enseignant->id_utilisateur;
                $_SESSION['enseignant_id'] = $enseignant->enseignant_id;
                $_SESSION['nom_prenom'] = $enseignant->enseignant_prenom . " " . $enseignant->enseignant_nom;
                $_SESSION['email_utilisateurs'] = $enseignant->enseignant_email;
                $_SESSION['contact_utilisateur'] = $enseignant->enseignant_telephone;
                $_SESSION['role'] = $enseignant->role;
                $_SESSION['signature'] = $enseignant->signature;

                if (!empty($enseignant->nom_grade)) {
                    $_SESSION['nom_grade'] = $enseignant->nom_grade;
                }
                
                if (strtoupper(str_replace(" ", "", $enseignant->role)) == strtoupper('ChefDR')) {
                    $_SESSION['id_departement'] = $enseignant->id_departement;
                    $_SESSION['nom_departement'] = $enseignant->nom_departement;
                    $_SESSION['sigle_departement'] = $enseignant->sigle_departement;
                }

                // ✅ CORRECTION : Redirection vers Homes/home qui existe maintenant
                $this->redirect( 'Homes');
                exit;
            } else {
                $this->set_flash("Mot de passe incorrect pour cet enseignant", 'danger');
                return;
            }
        } else {
            // Utilisateur classique
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

            if ($utilisateur->statut != 1) {
                $this->set_flash("Votre compte est inactif, veuillez contacter l'administrateur.", 'danger');
                return;
            }

            if (password_verify($mot_passe, $utilisateur->mot_passe)) {
                $_SESSION['id_utilisateur'] = $utilisateur->id_utilisateur;
                $_SESSION['enseignant_id'] = null;
                $_SESSION['nom_prenom'] = $utilisateur->nom_prenom;
                $_SESSION['email_utilisateurs'] = $utilisateur->email_utilisateurs;
                $_SESSION['contact_utilisateur'] = $utilisateur->contact_utilisateur;
                $_SESSION['role'] = $utilisateur->role;
                $_SESSION['signature'] = $utilisateur->signature;
                unset($_SESSION['nom_grade']);

                // ✅ CORRECTION : Redirection vers Homes/home qui existe maintenant
                 $this->redirect( 'Homes');
                exit;
            } else {
                $this->set_flash("Mot de passe incorrect pour cet utilisateur", 'danger');
                return;
            }
        }
    }
}
