<?php
class Etudiants extends Controller
{
    public function index()
    {
        // Récupérer les données des filières
        $filiereModel = new Filiere(); // Initialiser le modèle des filières
        $listeFilieres = $filiereModel->SelectAllData("*", "promotion
        INNER JOIN filiere ON promotion.id_filiere=filiere.id_filiere
        INNER JOIN parcours ON promotion.id_parcours=parcours.id_parcours
        INNER JOIN semestre ON parcours.id_semestre=semestre.id_semestre
        ORDER BY promotion.annee_universitaire DESC, filiere.sigle_filiere ASC");
        
    // Groupement par année universitaire
    $listeParAnnee = [];
    foreach ($listeFilieres as $filiere) {
        $annee = $filiere->annee_universitaire;
        if (!isset($listeParAnnee[$annee])) {
            $listeParAnnee[$annee] = [];
        }
        $listeParAnnee[$annee][] = $filiere;
    }
        // Transmettre les données à la vue

        // Liste complète : on fournit les filières pour le filtre optionnel.
        // Les données du tableau sont chargées en AJAX via liste_data().
        $filieres = $filiereModel->SelectAllData("*", "filiere");
        $this->view('liste_etudiant_page', ['filieres' => $filieres, 'promotions' => $listeFilieres]);
    }

    // Endpoint JSON : recherche + tri + pagination cote serveur
    public function liste_data()
    {
        header('Content-Type: application/json; charset=utf-8');
        $etu = new Etudiant();

        $q         = trim($_POST['q'] ?? '');
        $promotion = trim($_POST['promotion'] ?? '');
        $sort    = $_POST['sort'] ?? 'name';
        $dir     = $_POST['dir'] ?? 'asc';
        $page    = max(1, (int) ($_POST['page'] ?? 1));
        $size    = (int) ($_POST['size'] ?? 10);
        $size    = ($size > 0 && $size <= 100) ? $size : 10;
        $offset  = ($page - 1) * $size;

        try {
            $total = $etu->compterEtudiants($q, $promotion);
            $rows  = $etu->listerEtudiants($q, $promotion, $sort, $dir, $offset, $size);
        } catch (\Throwable $e) {
            error_log('liste_data: ' . $e->getMessage());
            $total = 0;
            $rows  = [];
        }

        echo json_encode([
            'rows'  => $rows,
            'total' => $total,
            'page'  => $page,
            'size'  => $size,
            'pages' => max(1, (int) ceil($total / $size)),
        ]);
    }

    
    public function incrit_etudiant() {
        $Etudiants = new Etudiant();
        $filiereModel = new Filiere(); // Initialiser le modèle des filières
        $errors = []; // Initialiser un tableau pour les erreurs
    
        if (isset($_POST["ddddddd"])) {
           // extract($_POST);
            // $Etudiants->enregistrementEtudiant($_POST, $_FILES["profilname"]);
            // $Etudiants->enregistrementPaiement($_POST);
                         $Etudiants->enregistrementEtudiantAvecPaiement($_POST, $_FILES["profilname"]);

            $errors = $Etudiants->errors; // Récupérer les erreurs de l'objet Etudiant
        }
    
        // Récupérer les données des filières
        $listeFilieres = $filiereModel->SelectAllData("*", "filiere");
     
        // Récupérer les données des promotions
                 $listePromotion = $filiereModel->SelectAllData("*", "promotion 
                 INNER JOIN filiere ON promotion.id_filiere=filiere.id_filiere 
                 INNER JOIN parcours ON promotion.id_parcours=parcours.id_parcours 
                 INNER JOIN semestre ON parcours.id_semestre=semestre.id_semestre");
                // var_dump($listePromotion);exit;
        
                // Transmettre les données à la vue
                $this->view('incription', [
                    'errors' => $errors,
                    'listePromotion' => $listePromotion,
                    'filieres' => $listeFilieres
                  
                ]);

    }

public function trier_liste_etudiant() {
    if (isset($_POST["annee_universitaire"], $_POST["id_filiere"], $_POST["id_semestre"])) {
        $etudiants = new Etudiant();
        $liste_etudiant = $etudiants->trie_liste_etudiant(
            $_POST["annee_universitaire"],
            $_POST["id_filiere"],
            $_POST["id_semestre"]
        );
        
        $this->view('liste_etudiant', [
            'liste_etudiant' => $liste_etudiant
        ]);
    }
}
        // Afficher la page de paiement pour un étudiant spécifique
        public function paiement_etudiant($id)
        {
            $student = new Etudiant();

            // Enregistrer le paiement AVANT le rendu, puis rediriger (PRG : évite la double soumission).
            if (isset($_POST['paie']) && !empty($_POST['montant_paye'])) {
                if ($student->enregistrerPaiement($id, $_POST['montant_paye'])) {
                    $student->set_flash('Paiement enregistré avec succès.', 'success');
                } else {
                    $student->set_flash('Montant invalide.', 'danger');
                }
                $this->redirect("Etudiants/paiement_etudiant/$id");
                return;
            }

            $etudiant = $student->getById($id);
            if (!$etudiant) {
                $this->view('paiement_inscription', ['error' => 'Étudiant introuvable.']);
                return;
            }

            $payments = $student->getPaymentsByStudentId($id);
            $totalPaid = 0;
            foreach ($payments as $payment) {
                $totalPaid += $payment['montant_paye'];
            }
            $remainingAmount = (int) $etudiant['total_frais'] - $totalPaid;

            $this->view('paiement_inscription', [
                'etudiant' => $etudiant,
                'payments' => $payments,
                'totalPaid' => $totalPaid,
                'remainingAmount' => $remainingAmount
            ]);
        }
 public function traiter_paiement_groupes() {
    $etudiant = new Etudiant();
    $faits = 0;

    if (isset($_POST['paiement']) && is_array($_POST['paiement'])) {
        // Un montant vide ou 0 = étudiant non payé cette fois (on l'ignore, pas d'erreur).
        foreach ($_POST['paiement'] as $idEtudt => $montant) {
            $montant = (int) $montant;
            if ($montant > 0) {
                if ($etudiant->enregistrerPaiement($idEtudt, $montant)) {
                    $faits++;
                }
            }
        }
        $_SESSION['message'] = $faits > 0
            ? "$faits paiement(s) enregistré(s) avec succès."
            : "Aucun montant saisi : aucun paiement enregistré.";
    } else {
        $_SESSION['message'] = "Aucun montant soumis.";
    }

    header("Location: " . ROOT . "/Etudiants");
    exit;
}

public function paiement_groupe() {
    $etudiant = new Etudiant();

    // Récupérer les IDs des étudiants sélectionnés depuis la requête POST
    $etudiant_ids = isset($_POST['paie']) ? $_POST['paie'] : [];

    if (empty($etudiant_ids)) {
        $_SESSION['message'] = "Aucun étudiant sélectionné.";
        header("Location: " . ROOT . "/Etudiants");
        exit;
    }

    // Charger les informations des étudiants sélectionnés
    $etudiantModel = $etudiant->getEtudiantsByIds($etudiant_ids);

    // Récupérer les paiements des étudiants sélectionnés
    $paiements = $etudiant->getTotauxPayesParEtudiants($etudiant_ids);

    // Charger la vue paiement_groupe.php avec les étudiants et leurs paiements
    $this->view('paiement_groupe', [
        'etudiants' => $etudiantModel,
        'paiements' => $paiements
    ]);
}
 public function export_liste(){
        $etudiant = new Etudiant();
        // Utilise trie_liste_etudiant si tu veux filtrer, sinon récupère tous
        // Ici, on suppose que tu veux tout exporter
      $query = "SELECT e.*, f.sigle_filiere 
          FROM etudiant e
          INNER JOIN etudiant_promotion ep ON e.id_etudiant = ep.id_etudiants
          INNER JOIN promotion p ON ep.id_promotion = p.id_promotion
          INNER JOIN filiere f ON p.id_filiere = f.id_filiere";

        $stmt = $etudiant->bdd()->prepare($query);
        $stmt->execute();
        $liste_etudiants = $stmt->fetchAll(PDO::FETCH_OBJ);

        // En-têtes pour Excel
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=liste_etudiants.xls");
        header("Pragma: no-cache");
        header("Expires: 0");

        // Entête professionnel
        echo "<table border='0' style='width:100%; margin-bottom:20px;'>";
        echo "<tr>";
        echo "<td style='text-align:center;'>
                 <h2 style='margin:0;'>Universite DE SEGOU</h2>
                <div style='font-size:14px;'>INSTITUT UNIVERSITAIRE DE FORMATION PROFESSIONNEL(IUFP)</div>
                <div style='font-size:12px;'>Liste des etudiants</div>
              </td>";
        echo "<td style='width:80px;'></td>";
        echo "</tr>";
        echo "</table>";

        // Colonnes du tableau
        $colonnes = [
           'Nom',
            'Prenom',
            'Matricule',
            'Filiere',
            "Emargement"
        ];

        echo "<table border='1' cellpadding='5' cellspacing='0' style='border-collapse:collapse; width:100%; font-family:Arial, sans-serif; font-size:13px;'>";
        echo "<thead style='background:#f2f2f2;'>";
        echo "<tr>";
        foreach ($colonnes as $colonne) {
            echo "<th style='padding:8px;'>" . htmlspecialchars($colonne) . "</th>";
        }
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";

        // Affichage des étudiants
        if (!empty($liste_etudiants)) {
            foreach ($liste_etudiants as $etudiant) {
                echo "<tr>";
                echo "<td style='padding:6px;'>" . htmlspecialchars($etudiant->nom_prenom_etudiant) . "</td>";
                echo "<td style='padding:6px;'>" . htmlspecialchars($etudiant->prenom) . "</td>";
                echo "<td style='padding:6px;'>" . htmlspecialchars($etudiant->matricule_etudiant) . "</td>";
                echo "<td style='padding:6px;'>" . htmlspecialchars($etudiant->sigle_filiere) . "</td>";
                echo "<td style='padding:6px;'></td>"; // Emargement vide pour signature
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='" . count($colonnes) . "' style='text-align:center;'>Aucun étudiant trouvé.</td></tr>";
        }
        echo "</tbody>";
        echo "</table>";
        exit;
}
 public function liste_inscription_groupe(){
   
    $this->view('liste_inscription_groupe');
}
public function modifier($id_etudiant) {
    $etudiants = new Etudiant();
    $filiere   = new Filiere();

    // 🔹 Récupérer les infos de l'étudiant
    $modif = $etudiants->FetchSelectWhere(
        "*", 
        "etudiant", 
        "id_etudiant=:id", 
        ["id" => $id_etudiant]
    );

    // 🔹 Récupérer les filières + promotions liées à l’étudiant
    $filieres = $filiere->select_data_table_join_where(
        "SELECT p.id_promotion, p.annee_universitaire,
                f.id_filiere, f.sigle_filiere, f.nom_filiere,
                pa.id_parcours, pa.nom_parcours,
                s.id_semestre, s.nom_semestre
         FROM etudiant_promotion ep
         INNER JOIN promotion p ON ep.id_promotion = p.id_promotion
         INNER JOIN filiere f ON p.id_filiere = f.id_filiere
         INNER JOIN parcours pa ON p.id_parcours = pa.id_parcours
         INNER JOIN semestre s ON pa.id_semestre = s.id_semestre
         WHERE ep.id_etudiants = ?",
        [$id_etudiant]
    );

    // 🔹 Si formulaire soumis → modification
    if (isset($_POST['modifier'])) {
        $etudiants->modification([ 
            "id"                   => $id_etudiant,
            "nom_prenom_etudiant"  => $_POST['nom_prenom_etudiant'],
            "prenom"  => $_POST['prenom'],
            "date_naissance_etudiant" => $_POST['date_naissance_etudiant'],
            "lieu_naissance_etudiant" => $_POST['lieu_naissance_etudiant'],
            "genre_etudiant"       => $_POST['genre_etudiant'],
            "matricule_etudiant"   => $_POST['matricule_etudiant'],
            "contact_etudiant"     => $_POST['contact_etudiant'],
            "diplome"              => $_POST['diplome'],
            "id_statut"            => $_POST['id_statut'],
            "id_filiere"           => $_POST['id_filiere'],
            "numetudiant"          => $_POST['numetudiant'],
            "prenompere"           => $_POST['prenompere'],
            "prenomnommere"        => $_POST['prenomnommere'],
            "cercleNais"           => $_POST['cercleNais'],
            "commNais"             => $_POST['commNais'],
            "nationnalite"         => $_POST['nationnalite'],
            "anneediplome"         => $_POST['anneediplome'],
            "serie"                => $_POST['serie'],
            "pays"                 => $_POST['pays'],
            "academie"             => $_POST['academie'],
            "lieuresidenceparents" => $_POST['lieuresidenceparents'],
            "adresseactuel"        => $_POST['adresseactuel'],
            "numplace"             => $_POST['numplace'],
            "profilname"           => $_POST['profilname']
        ]);

        // 🔹 Mise à jour de la promotion (table de liaison etudiant_promotion)
        if (!empty($_POST['id_promotion'])) {
            $promotionId = $_POST['id_promotion'];

            // supprimer l'ancien lien
            $etudiants->insertion_update_simples(
                "DELETE FROM etudiant_promotion WHERE id_etudiants = :id",
                [":id" => $id_etudiant]
            );

            // insérer le nouveau lien
            $etudiants->insertion_update_simples(
                "INSERT INTO etudiant_promotion (id_etudiants, id_promotion) VALUES (:id_etudiant, :id_promotion)",
                [
                    ":id_etudiant" => $id_etudiant,
                    ":id_promotion" => $promotionId
                ]
            );
        }
    }

    // 🔹 Passage des données à la vue
    $this->view("modifier_Etudiant", [
        'modif'      => $modif,
        'filieres'   => $filieres,
      
    ]);
}

public function apercu_etudiant($idEtudiant) {
    $etudiants = new Etudiant();
    $filiere   = new Filiere();

    // Récupérer les filières
   // Récupérer les infos de l'étudiant
$etudiant = $etudiants->getEtudiantById($idEtudiant);

// Récupérer les infos des filières, promotions, parcours, semestres liés à l'étudiant
$filieres = $filiere->select_data_table_join_where(
    "SELECT *
     FROM etudiant_promotion
     INNER JOIN promotion ON etudiant_promotion.id_promotion = promotion.id_promotion
     INNER JOIN filiere ON promotion.id_filiere = filiere.id_filiere
     INNER JOIN parcours ON promotion.id_parcours = parcours.id_parcours
     INNER JOIN semestre ON parcours.id_semestre = semestre.id_semestre
     INNER JOIN etudiant ON etudiant.id_etudiant = etudiant_promotion.id_etudiants
     WHERE etudiant.id_etudiant = ?",
    [$idEtudiant]
);

    if ($etudiant) {
        // On passe aussi la liste des filières à la vue
        $this->view("apercu_etudiant", [
            'etudiant' => $etudiant,
            'filieres' => $filieres
        ]);
    } else {
        $this->set_flash('Étudiant non trouvé', 'danger');
        $this->view("apercu_etudiant", [
            'filieres' => $filieres
        ]);
    }
}

 public function filtrer_etudiants(){
   
    $this->view('liste_inscription_groupe');
}
}