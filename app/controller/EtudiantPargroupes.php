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

    // trie les etudiants par groupe
    public function trier_liste_etudiants()
    {
        if (isset($_POST["id_promotion"])) {
            $EtudiantPargroupe = new EtudiantPargroupe();
            $liste_etudiant = $EtudiantPargroupe->trie_liste_etudiant($_POST["id_promotion"]);
            $this->view('liste_etudiant', [
                'liste_etudiant' => $liste_etudiant
            ]);
        }
    }
}
