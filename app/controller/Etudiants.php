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
 public function traiter_paiement_groupes() {
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



    
  
  
public function importExcel() {
    $Etudiants = new Etudiant();
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['excelFile'])) {
        // Récupérer le fichier Excel téléchargé
        $file = $_FILES['excelFile']['tmp_name'];

        // Charger le fichier Excel
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
        $sheet = $spreadsheet->getActiveSheet();
        $rowIterator = $sheet->getRowIterator(2); // Commence à la ligne 2 (ignorer les en-têtes)

        // Instancier le modèle Etudian
        $etudiantModel = new Etudiant();

        // Parcourir les lignes du fichier Excel
        foreach ($rowIterator as $row) {
            $data = [];
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);

            foreach ($cellIterator as $cell) {
                $data[] = $cell->getFormattedValue();
            }

            // Vérifier que la ligne contient bien 27 valeurs
            if (count($data) === 26) {
                // Préparer les données sous forme de tableau associatif
                $etudiantData = [
                   
                    'nom_prenom_etudiant' => $data[0],
                    'date_naissance_etudiant' => $data[1],
                    'lieu_naissance_etudiant' => $data[2],
                    'genre_etudiant' => $data[3],
                    'matricule_etudiant' => $data[4],
                    'contact_etudiant' => $data[5],
                    'diplom' => $data[6],
                    'id_statut' => $data[7],
                    'annee' => $data[8],
                    'numetudiant' => $data[9],
                    'prenompere' => $data[10],
                    'prenomnommere' => $data[11],
                    'cercleNais' => $data[12],
                    'commNais' => $data[13],
                    'nationnalite' => $data[14],
                    'anneediplome' => $data[15],
                    'serie' => $data[16],
                    'pays' => $data[17],
                    'academie' => $data[18],
                    'lieuresidenceparents' => $data[19],
                    'adresseactuel' => $data[20],
                    'numplace' => $data[21],
                    'profilname' => $data[22],
                    'id_promotion' => $data[23],
                    'montant' => $data[24],
                    'total_frais' => $data[25]
                ];

                // Appeler la méthode du modèle pour insérer les données
                $etudiantModel->insertEtudiant($etudiantData);
            }
        }

        // Retourner une réponse (ex : confirmation d'import)
        echo "Les données ont été importées avec succès!";
    } else {
        // Si aucun fichier n'a été téléchargé, afficher un message d'erreur
        echo "Veuillez télécharger un fichier Excel.";
    }
}
 public function liste_inscription_groupe(){
   
    $this->view('liste_inscription_groupe');
}
 public function filtrer_etudiants(){
   
    $this->view('liste_inscription_groupe');
}
}