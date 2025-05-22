<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

class EtudiantPargroupes extends Controller
{
    public function index()
    {
        // Récupérer les données des filières
        $filiereModel = new Filiere(); // Initialiser le modèle des filières
        $listeFilieres = $filiereModel->SelectAllData("*", "promotion 
         INNER JOIN filiere ON promotion.id_filiere=filiere.id_filiere 
         INNER JOIN parcours ON promotion.id_parcours=parcours.id_parcours 
         INNER JOIN semestre ON parcours.id_semestre=semestre.id_semestre");
        $this->view('liste_inscription_groupe', [
            'listeFilieres' => $listeFilieres
        ]);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = isset($_POST['data']) ? json_decode($_POST['data'], true) : [];

            if (empty($data)) {
                echo json_encode(['success' => false, 'message' => 'Aucune donnée reçue']);
                exit;
            }

            $EtudiantPargroupe = new EtudiantPargroupe();
            $successCount = 0;

            foreach ($data as $etudiant) {
                $insertion = $EtudiantPargroupe->insertEtudiant($etudiant);
                if ($insertion) {
                    $successCount++;
                }
            }

            if ($successCount > 0) {
                echo '<script>
                document.addEventListener("DOMContentLoaded", function () {
                    Swal.fire("La validation a ete fait avec succes", "", "success").then(() => {
                        window.location.href = "liste_entend.php";
                    });
                });
            </script>';
            } else {
                $this->set_flash("Aucune insertion effectuée", 'danger');
            }

            echo json_encode([
                'success' => $successCount > 0,
                'message' => "$successCount enregistrements ajoutés avec succès"
            ]);
        }


        $this->view('liste_inscription_groupe');

        $filiereModel = new Filiere();
        $listeFilieres = $filiereModel->SelectAllData("*", "promotion 
                    INNER JOIN filiere ON promotion.id_filiere=filiere.id_filiere 
                    INNER JOIN parcours ON promotion.id_parcours=parcours.id_parcours 
                    INNER JOIN semestre ON parcours.id_semestre=semestre.id_semestre");

        $this->view('liste_inscription_groupe', [
            'listeFilieres' => $listeFilieres
        ]);
    }

// <<<<<<< inscri-reinscri
//             if (count($rows) === 0 || (count($rows) === 1 && empty(array_filter($rows[0])))) {
//                 break;
//             }
//             foreach ($rows as $i => $ligne) {
//                 if ($startRow === 2 && $i === 0) continue; // Ignore l'entête au premier passage

//                 if (array_filter($ligne)) {
//                     $donnees = [];

//                     foreach ($correspondances as $colIndex => $champBdd) {
//                         if ($champBdd !== '') {
//                             $donnees[$champBdd] = $ligne[$colIndex] ?? null;
//                         }
//                     }

//                     // Définir l'ID de la promotion
//                     $donnees['id_promotion'] = $idPromotion;

//                     // Génération du matricule
//                     $anneeDiplome = $donnees['annee_diplome'] ?? date("Y");
//                     $genre = $donnees['genre_etudiant'] ?? '';
//                     $nom = $donnees['nom_prenom_etudiant'] ?? '';
//                     $prenom = $donnees['prenom'] ?? '';
//                     $donnees['matricule_etudiant'] = $this->genererMatricule($anneeDiplome, $nom, $prenom, $genre, $indexMatricule);
// // 👉 Ajouter la logique du montant selon le statut
// $statutBrut = $donnees['id_statut'] ?? '';
// // Normaliser le statut : minuscule, retirer accents, trim
// $statut = strtolower(trim($statutBrut));
// $statut = str_replace(['é', 'è', 'ê', 'ë'], 'e', $statut); // pour tous les accents possibles
// // Affectation du montant selon le statut
// switch ($statut) {
//     case 'reg':
//     case 'regulier':
//         $donnees['total_frais'] = 6000;
//         break;
//     case 'cl':
//         $donnees['total_frais'] = 81000;
//         break;
//     case 'privee':
//         case 'prive':
//              case 'Prof. Prive':
//         $donnees['total_frais'] = 200000;
//         break;

//     case 'public':
//         case 'publique':
//             case 'PROFPUBLIQ':
//                 case 'PRO. Collect':
//     case 'collectivite':
//         $donnees['total_frais'] = 150000;
//         break;

//     default:
//         // Statut inconnu, ignorer cet enregistrement
//         $donnees['total_frais'] = 150000;
// }

//     // Insertion sans restriction
// if ($etudiantModel->insertEtudiant($donnees)) {
//     $success++;
// }
//                 }
//             }


//             $startRow += $chunkSize;
//             unset($spreadsheet); // Libère la mémoire à chaque boucle
// =======
//     // trie les etudiants par groupe
//     public function trier_liste_etudiants()
//     {
//         if (isset($_POST["id_promotion"])) {
//             $EtudiantPargroupe = new EtudiantPargroupe();
//             $liste_etudiant = $EtudiantPargroupe->trie_liste_etudiant($_POST["id_promotion"]);
//             $this->view('liste_etudiant', [
//                 'liste_etudiant' => $liste_etudiant
//             ]);
// >>>>>>> main
        }
    }
}
