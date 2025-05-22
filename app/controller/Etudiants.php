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
        INNER JOIN semestre ON parcours.id_semestre=semestre.id_semestre");

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

        $this->view('liste_incrit', [
            'listeFilieres' => $listeFilieres,        // pas supprimé si tu en as besoin ailleurs
            'listeParAnnee' => $listeParAnnee,
            // pour la double sélection année > promotion
        ]);
    }


    public function incrit_etudiant()
    {
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

    public function trier_liste_etudiant()
    {
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

        // Récupérer l'étudiant par son ID
        $student = new Etudiant();
        $etudiant = $student->getById($id); // Appel de la méthode non statique

        // Vérifier si l'étudiant existe
        if ($etudiant) {
            // Récupérer l'historique des paiements pour cet étudiant
            $payments = $student->getPaymentsByStudentId($id);
            // Calculer le montant total payé
            $totalPaid = 0;
            foreach ($payments as $payment) {
                $totalPaid += $payment['montant_paye']; // Additionner tous les paiements
            }

            // Calculer le montant restant
            $remainingAmount = $etudiant['total_frais'] - $totalPaid;

            // Passer les informations à la vue
            $this->view('paiement_inscription', [
                'etudiant' => $etudiant,
                'payments' => $payments,
                'remainingAmount' => $remainingAmount
            ]);
            if (isset($_POST["paie"])) {
                // la méthode d'enregistrement
                $student->ajouterPaiement();
            }
        } else {
            $this->view('paiement_inscription', ['error' => 'Étudiant introuvable.']);
        }
    }
    public function traiter_paiement_groupes()
    {
        $etudiant = new Etudiant();

        // Vérifier que des montants ont été soumis
        if (isset($_POST['paiement']) && is_array($_POST['paiement'])) {
            $paiements = $_POST['paiement'];
            $erreur = false;

            foreach ($paiements as $idEtudt => $montant) {
                // Vérifier que le montant est valide (supérieur à zéro)
                if ($montant > 0) {
                    $data = [
                        'idEtudt' => $idEtudt,
                        'montant_paye' => $montant,
                        'date' => date('Y-m-d H:i:s'),
                    ];
                    $etudiant->addPayment($data); // Ajouter le paiement
                } else {
                    $_SESSION['error'] = "Montant invalide pour l'étudiant ID: $idEtudt.";
                    $erreur = true; // Si une erreur se produit, ne pas continuer
                    break; // On arrête la boucle si un paiement est invalide
                }
            }

            if (!$erreur) {
                $_SESSION['message'] = "Paiements effectués avec succès.";
            }
        } else {
            $_SESSION['message'] = "Aucun montant soumis.";
        }

        // Redirection après traitement
        header("Location: " . ROOT . "/Etudiants");
        exit;
    }

    public function paiement_groupe()
    {
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




 public function liste_inscription_groupe(){
   
    $this->view('liste_inscription_groupe');
}
 public function filtrer_etudiants(){
   
    $this->view('liste_inscription_groupe');
}
}
