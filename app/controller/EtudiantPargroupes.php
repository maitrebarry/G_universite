<?php

require_once __DIR__ . '/../../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\IReadFilter;

class ChunkReadFilter implements IReadFilter
{
    private $startRow = 0;
    private $endRow = 0;

    public function setRows(int $startRow, int $chunkSize): void
    {
        $this->startRow = $startRow;
        $this->endRow = $startRow + $chunkSize - 1;
    }

    public function readCell($column, $row, $worksheetName = ''): bool
    {
        return $row >= $this->startRow && $row <= $this->endRow;
    }
}

class EtudiantPargroupes extends Controller
{
   
   public function redirect($page)
    {
        header("Location:" . ROOT . "/" . trim($page, "/"));
        exit();
    }
    public function set_flash($message, $type = 'danger')
    {
        $_SESSION['notification']['message'] = $message;
        $_SESSION['notification']['type'] = $type;
          $_SESSION['notification']['class'] = $this->get_alert_class($type);
        $_SESSION['notification']['icon'] = $this->get_alert_icon($type);
    }
    
    protected function get_alert_class($type)
    {
        $classes = [
            'success' => 'alert-success',
            'danger' => 'alert-danger',
            'warning' => 'alert-warning',
            'info' => 'alert-info',
        ];
        return $classes[$type] ?? 'alert-secondary';
    }

    protected function get_alert_icon($type)
    {
        $icons = [
            'success' => '✔️',
            'danger' => '❌',
            'warning' => '⚠️',
            'info' => 'ℹ️',
        ];
        return $icons[$type] ?? '🔔';
    }

 public function index()
{
    // Récupérer les données des filières
    $filiereModel = new Filiere();
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

    // Champs de la BDD (table `etudiant`)
    $champsBdd = ['nom_prenom_etudiant','prenom', 'date_naissance_etudiant', 'genre_etudiant','matricule_etudiant','diplome','id_statut','lieu_naissance_etudiant'];

    // Entêtes Excel si présentes
    $entetesExcel = isset($_SESSION['entetes'])  ? $_SESSION['entetes'] : [];

    // Afficher la vue principale
    $this->view('liste_inscription_groupe', [
        'listeFilieres' => $listeFilieres,        // pas supprimé si tu en as besoin ailleurs
        'listeParAnnee' => $listeParAnnee,        // pour la double sélection année > promotion
        'champsBdd' => $champsBdd,
        'entetesExcel' => $entetesExcel
    ]);
}

public function genererMatricule($anneeDiplome, $nom, $prenom, $genre, $index)
{
    $noms = explode(" ", trim($nom));
    $prenoms = explode(" ", trim($prenom));

    $premiereLettreNom = isset($noms[0][0]) ? strtoupper($noms[0][0]) : '';
    $premiereLettrePrenom = isset($prenoms[0][0]) ? strtoupper($prenoms[0][0]) : '';

    $suffixe = str_pad($index + 1, 3, '0', STR_PAD_LEFT); // Exemple : 001, 002, etc.

    return $anneeDiplome . $premiereLettreNom . $premiereLettrePrenom . strtoupper($genre) . $suffixe;
}






    public function trier_liste_etudiants(){  
        if(isset($_POST["id_promotion"])){
          $EtudiantPargroupe = new EtudiantPargroupe();
         $liste_etudiant=$EtudiantPargroupe->trie_liste_etudiant($_POST["id_promotion"]);
         $this->view('liste_etudiant', [
          'liste_etudiant' => $liste_etudiant
           ]);
        }
      }
public function uploadExcel()
{
    if (isset($_FILES['excel_file']) && $_FILES['excel_file']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['excel_file']['tmp_name'];
        $fileName = uniqid() . "_" . $_FILES['excel_file']['name'];
        $destination = "uploads/" . $fileName;
        move_uploaded_file($fileTmpPath, $destination);

        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($destination);
        $worksheet = $spreadsheet->getActiveSheet();
        $entetes = $worksheet->rangeToArray('A1:' . $worksheet->getHighestColumn() . '1')[0];

        $_SESSION['excel_file_path'] = $destination;
        $_SESSION['entetes'] = $entetes;

$champsBdd = [ 'nom_prenom_etudiant','prenom', 'date_naissance_etudiant', 'genre_etudiant','matricule_etudiant','diplome','id_statut','lieu_naissance_etudiant'];

        $this->view('liste_inscription_groupe', [
            'entetesExcel' => $entetes,
            'champsBdd' => $champsBdd
        ]);
    }
}



public function importerEnChunks()
{
    if (isset($_FILES['excel_file']) && $_FILES['excel_file']['error'] === UPLOAD_ERR_OK) {
        set_time_limit(300); // Allonge le temps d'exécution à 5 minutes

        $fileTmpPath = $_FILES['excel_file']['tmp_name'];
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();

        $reader->setReadDataOnly(true); // Ne lit que les données, pas les styles
        $chunkFilter = new ChunkReadFilter();
        $reader->setReadFilter($chunkFilter);

        $chunkSize = 100;
        $startRow = 2;
        $success = 0;
        $indexMatricule = 0;

        $correspondances = $_POST['correspondances'] ?? [];
        $etudiantModel = new EtudiantPargroupe();
        $idPromotion = $_POST['id_promotion'] ?? null;

        while (true) {
            $chunkFilter->setRows($startRow, $chunkSize);
            $spreadsheet = $reader->load($fileTmpPath);
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            if (count($rows) === 0 || (count($rows) === 1 && empty(array_filter($rows[0])))) {
                break;
            }
            foreach ($rows as $i => $ligne) {
                if ($startRow === 2 && $i === 0) continue; // Ignore l'entête au premier passage

                if (array_filter($ligne)) {
                    $donnees = [];

                    foreach ($correspondances as $colIndex => $champBdd) {
                        if ($champBdd !== '') {
                            $donnees[$champBdd] = $ligne[$colIndex] ?? null;
                        }
                    }

                    // Définir l'ID de la promotion
                    $donnees['id_promotion'] = $idPromotion;

                    // Génération du matricule
                    $anneeDiplome = $donnees['annee_diplome'] ?? date("Y");
                    $genre = $donnees['genre_etudiant'] ?? '';
                    $nom = $donnees['nom_prenom_etudiant'] ?? '';
                    $prenom = $donnees['prenom'] ?? '';
                    $donnees['matricule_etudiant'] = $this->genererMatricule($anneeDiplome, $nom, $prenom, $genre, $indexMatricule);
// 👉 Ajouter la logique du montant selon le statut
$statutBrut = $donnees['id_statut'] ?? '';
// Normaliser le statut : minuscule, retirer accents, trim
$statut = strtolower(trim($statutBrut));
$statut = str_replace(['é', 'è', 'ê', 'ë'], 'e', $statut); // pour tous les accents possibles
// Affectation du montant selon le statut
switch ($statut) {
    case 'reg':
    case 'regulier':
        $donnees['total_frais'] = 6000;
        break;
    case 'cl':
        $donnees['total_frais'] = 81000;
        break;
    case 'privee':
        case 'prive':
             case 'Prof. Prive':
        $donnees['total_frais'] = 200000;
        break;

    case 'public':
        case 'publique':
            case 'PROFPUBLIQ':
                case 'PRO. Collect':
    case 'collectivite':
        $donnees['total_frais'] = 150000;
        break;

    default:
        // Statut inconnu, ignorer cet enregistrement
        $donnees['total_frais'] = 150000;
}

    // Insertion sans restriction
if ($etudiantModel->insertEtudiant($donnees)) {
    $success++;
}
                }
            }


            $startRow += $chunkSize;
            unset($spreadsheet); // Libère la mémoire à chaque boucle
        }

        $this->set_flash("$success étudiants importés en lots avec succès.", "success");
        $this->redirect("EtudiantPargroupes");
    } else {
        $this->set_flash("Erreur d'importation du fichier", "danger");
        $this->redirect("EtudiantPargroupes");
    }
}



}
