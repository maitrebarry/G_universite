<?php
class Utilisateurs extends Controller
{
    public function index() {}

    public function profil()
    {
        if (!isset($_SESSION['id_utilisateur'])) {
            $this->redirect('Logins');
            return;
        }

        $utilisateur = new Utilisateur();
        $profil = $this->getProfilConnecte($utilisateur);

        $this->view('profil', ['profil' => $profil]);
    }

    public function update_profil()
    {
        if (!isset($_SESSION['id_utilisateur'])) {
            $this->redirect('Logins');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('Utilisateurs/profil');
            return;
        }

        $utilisateur = new Utilisateur();
        $nomPrenom = trim($_POST['nom_prenom'] ?? '');
        $email = filter_var(trim($_POST['email_utilisateurs'] ?? ''), FILTER_SANITIZE_EMAIL);
        $contact = trim($_POST['contact_utilisateur'] ?? '');

        if ($nomPrenom === '' || $email === '' || $contact === '') {
            $utilisateur->set_flash('Veuillez remplir tous les champs du profil.', 'warning');
            $this->redirect('Utilisateurs/profil');
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $utilisateur->set_flash('Adresse email invalide.', 'warning');
            $this->redirect('Utilisateurs/profil');
            return;
        }

        $idUtilisateur = (int) $_SESSION['id_utilisateur'];
        $enseignantId = $_SESSION['enseignant_id'] ?? null;

        if ($enseignantId) {
            $emailExiste = $utilisateur->FetchSelectWhere(
                'enseignant_id',
                'enseignants',
                'enseignant_email = ? AND enseignant_id <> ?',
                [$email, $enseignantId]
            );

            $emailExisteUtilisateur = $utilisateur->FetchSelectWhere(
                'id_utilisateur',
                'utilisateur',
                'email_utilisateurs = ? AND id_utilisateur <> ?',
                [$email, $idUtilisateur]
            );
        } else {
            $emailExiste = $utilisateur->FetchSelectWhere(
                'id_utilisateur',
                'utilisateur',
                'email_utilisateurs = ? AND id_utilisateur <> ?',
                [$email, $idUtilisateur]
            );

            $emailExisteUtilisateur = $utilisateur->FetchSelectWhere(
                'enseignant_id',
                'enseignants',
                'enseignant_email = ?',
                [$email]
            );
        }

        if ($emailExiste || $emailExisteUtilisateur) {
            $utilisateur->set_flash('Cette adresse email est deja utilisee par un autre compte.', 'warning');
            $this->redirect('Utilisateurs/profil');
            return;
        }

        if ($enseignantId) {
            $parts = preg_split('/\s+/', $nomPrenom, 2);
            $prenom = $parts[0] ?? '';
            $nom = $parts[1] ?? $parts[0];

            $utilisateur->insertion_update_simples(
                'UPDATE enseignants SET enseignant_prenom = :prenom, enseignant_nom = :nom, enseignant_email = :email, enseignant_telephone = :contact WHERE enseignant_id = :id',
                [
                    ':prenom' => $prenom,
                    ':nom' => $nom,
                    ':email' => $email,
                    ':contact' => $contact,
                    ':id' => $enseignantId,
                ]
            );
        } else {
            $utilisateur->insertion_update_simples(
                'UPDATE utilisateur SET nom_prenom = :nom_prenom, email_utilisateurs = :email, contact_utilisateur = :contact WHERE id_utilisateur = :id',
                [
                    ':nom_prenom' => $nomPrenom,
                    ':email' => $email,
                    ':contact' => $contact,
                    ':id' => $idUtilisateur,
                ]
            );
        }

        $_SESSION['nom_prenom'] = $nomPrenom;
        $_SESSION['email_utilisateurs'] = $email;
        $_SESSION['contact_utilisateur'] = $contact;

        $utilisateur->set_flash('Vos informations personnelles ont ete mises a jour.', 'success');
        $this->redirect('Utilisateurs/profil');
    }

    public function update_mot_passe()
    {
        if (!isset($_SESSION['id_utilisateur'])) {
            $this->redirect('Logins');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('Utilisateurs/profil');
            return;
        }

        $utilisateur = new Utilisateur();
        $_SESSION['profil_tab'] = 'password';

        $ancien = $_POST['ancien_mot_passe'] ?? '';
        $nouveau = $_POST['nouveau_mot_passe'] ?? '';
        $confirmation = $_POST['confirmation_mot_passe'] ?? '';

        if ($ancien === '' || $nouveau === '' || $confirmation === '') {
            $utilisateur->set_flash('Veuillez remplir tous les champs du mot de passe.', 'warning');
            $this->redirect('Utilisateurs/profil');
            return;
        }

        if (strlen($nouveau) < 8) {
            $utilisateur->set_flash('Le nouveau mot de passe doit contenir au moins 8 caracteres.', 'warning');
            $this->redirect('Utilisateurs/profil');
            return;
        }

        if ($nouveau !== $confirmation) {
            $utilisateur->set_flash('La confirmation ne correspond pas au nouveau mot de passe.', 'warning');
            $this->redirect('Utilisateurs/profil');
            return;
        }

        $compte = $utilisateur->FetchSelectWhere(
            'id_utilisateur, mot_passe',
            'utilisateur',
            'id_utilisateur = ?',
            [(int) $_SESSION['id_utilisateur']]
        );

        if (!$compte || !password_verify($ancien, $compte->mot_passe)) {
            $utilisateur->set_flash('Ancien mot de passe incorrect.', 'danger');
            $this->redirect('Utilisateurs/profil');
            return;
        }

        $utilisateur->insertion_update_simples(
            'UPDATE utilisateur SET mot_passe = :mot_passe WHERE id_utilisateur = :id',
            [
                ':mot_passe' => password_hash($nouveau, PASSWORD_DEFAULT),
                ':id' => (int) $_SESSION['id_utilisateur'],
            ]
        );

        $utilisateur->set_flash('Votre mot de passe a ete modifie avec succes.', 'success');
        $this->redirect('Utilisateurs/profil');
    }

    private function getProfilConnecte(Utilisateur $utilisateur)
    {
        if (!empty($_SESSION['enseignant_id'])) {
            $enseignant = $utilisateur->FetchSelectWhere(
                'enseignant_prenom, enseignant_nom, enseignant_email, enseignant_telephone',
                'enseignants',
                'enseignant_id = ?',
                [$_SESSION['enseignant_id']]
            );

            if ($enseignant) {
                return [
                    'nom_prenom' => trim($enseignant->enseignant_prenom . ' ' . $enseignant->enseignant_nom),
                    'email_utilisateurs' => $enseignant->enseignant_email,
                    'contact_utilisateur' => $enseignant->enseignant_telephone,
                    'role' => $_SESSION['role'] ?? '',
                    'type' => 'enseignant',
                ];
            }
        }

        $compte = $utilisateur->FetchSelectWhere(
            'nom_prenom, email_utilisateurs, contact_utilisateur, role',
            'utilisateur',
            'id_utilisateur = ?',
            [(int) $_SESSION['id_utilisateur']]
        );

        return [
            'nom_prenom' => $compte->nom_prenom ?? ($_SESSION['nom_prenom'] ?? ''),
            'email_utilisateurs' => $compte->email_utilisateurs ?? ($_SESSION['email_utilisateurs'] ?? ''),
            'contact_utilisateur' => $compte->contact_utilisateur ?? ($_SESSION['contact_utilisateur'] ?? ''),
            'role' => $compte->role ?? ($_SESSION['role'] ?? ''),
            'type' => 'utilisateur',
        ];
    }

    // Hierarchie des roles : un utilisateur ne doit voir dans la liste que les
    // roles strictement en dessous du sien. Le SupAdmin n'apparait jamais.
    private static $roleHierarchy = [
        'SupAdmin'              => 1,
        'DG'                    => 2,
        'DGA'                   => 3,
        'Chef DR'               => 4,
        'Sécretaire principale' => 5,
        'Scolarite'             => 6,
        'Enseignant'            => 7,
    ];

    public function liste_utilisateur($id_enseignant=null)
    {
        $this->requireRole(['SupAdmin', 'DG', 'DGA', 'Chef DR']); // gestion des comptes : reserve a l'administrateur
        $utilisateur = new Utilisateur();
        $utilisateurenseignant = new Enseignant();
        if (isset($_POST["save_user"])) {

            $utilisateur->save_utilisateur(["nom_prenom", "contact_utilisateur", "email_utilisateurs", "mot_passe", "role"]);
        }

        $viewerRank = self::$roleHierarchy[$_SESSION['role'] ?? ''] ?? PHP_INT_MAX;
        $rolesVisibles = array_keys(array_filter(
            self::$roleHierarchy,
            fn($rang) => $rang > $viewerRank
        ));

        //appel du method de recuperation
        // Exemple d'utilisation de la méthode
        $select = "
        SELECT
            utilisateur.id_utilisateur,
            utilisateur.nom_prenom AS utilisateur_nom_prenom,
            utilisateur.contact_utilisateur AS utilisateur_contact,
            utilisateur.email_utilisateurs AS utilisateur_email,
              utilisateur.statut AS statut,
              utilisateur.role AS role,
             utilisateur.signature AS signature,
            utilisateur.enseignant_id,
            enseignants.enseignant_nom,
            enseignants.enseignant_prenom,
            enseignants.enseignant_telephone,
            enseignants.enseignant_email
        FROM utilisateur
        LEFT JOIN enseignants
        ON utilisateur.enseignant_id = enseignants.enseignant_id
        WHERE utilisateur.role IN (" . implode(',', array_fill(0, count($rolesVisibles), '?')) . ")
    ";



        // Appel de la méthode en passant les paramètres nécessaires
        $liste = $rolesVisibles ? $utilisateur->select_data_table_join_where($select, $rolesVisibles) : [];

        $enseignants = $utilisateurenseignant->SelectAllData("*", "enseignants");
        $departements = $utilisateur->SelectAllData("*", "departement");
        $this->view('liste_utilisateur', ['liste' => $liste, 'enseignants' => $enseignants,'id_enseignant'=>$id_enseignant,'departements'=>$departements]);
    }

    /// methode pour la modification
    public function edit_utilisateurs()
    {
        $this->requireRole(['SupAdmin', 'DG', 'DGA', 'Chef DR']); // gestion des comptes : reserve a l'administrateur
        $utilisateur = new Utilisateur();
        if (isset($_POST['edit_user'])) {
            $utilisateur->edit_utilisateur([
                'id_utilisateur'      => $_POST['id_utilisateur'] ?? null,
                'nom_prenom'          => trim($_POST['nom_prenom'] ?? ''),
                'contact_utilisateur' => trim($_POST['contact_utilisateur'] ?? ''),
                'email_utilisateurs'  => trim($_POST['email_utilisateurs'] ?? ''),
                'mot_passe'           => $_POST['mot_passe'] ?? '', // vide = inchangé
                'role'                => $_POST['role'] ?? '',
            ]); // edit_utilisateur effectue la redirection (PRG)
            return;
        }
        // Aucun POST valide : on retourne à la liste (jamais de vue sans données)
        $this->redirect('Utilisateurs/liste_utilisateur');
    }
    public function delete($id)
    {
        $this->requireRole(['SupAdmin', 'DG', 'DGA', 'Chef DR']); // suppression de compte : reserve a l'administrateur
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
