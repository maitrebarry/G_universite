<?php
class Enseignants extends Controller
{
    public function index()
    {
        $this->lsite_enseignant();
    }

    public function lsite_enseignant()
    {

        $commandeModel = new Enseignant();
        $enseignat_enseignat_PERMANANT = $commandeModel->getEnseignantCDI();
        $enseignat_NON_PERMANANT = $commandeModel->getEnseignantVCT();
        //  var_dump($enseignat_enseignat_PERMANANT);
        //  var_dump($enseignat_NON_PERMANANT);
        //  exit;
        $this->view(
            'liste_enseignant',
            [
                'enseignat_CDI' => $enseignat_enseignat_PERMANANT,
                'enseignat_VCT' => $enseignat_NON_PERMANANT
            ]
        );
    }

    public function ajouter_enseignant()
    {
        $enseignant = new Enseignant();
        $filiere = $enseignant->SelectAllData("*", "grade");
        if (isset($_POST["envoyer"])) {
            // Nettoyage des données utilisateur
            $_POST = array_map('trim', $_POST);
            $cv_file = $_FILES['cv'] ?? null;
            // Appel de la méthode d'enregistrement
            $enseignant->enregistrement($cv_file, $_POST);
            if (!empty($enseignant->errors)) {
                $_SESSION['input'] = $_POST;
                $_SESSION['errors'] = $enseignant->errors;
            } else {
                // Nettoyer les sessions en cas de succès
                unset($_SESSION['input']);
                unset($_SESSION['errors']);
            }
        }

        // Récupération des données de session
        $input_values = $_SESSION['input'] ?? [];
        $errors = $_SESSION['errors'] ?? [];
        unset($_SESSION['input'], $_SESSION['errors']);

        // Chargement de la vue avec les données nécessaires
        $this->view("ajouter_enseignant", [
            'errors' => $errors,
            'filiere' => $filiere,
            'input_values' => $input_values
        ]);
    }

    public function update($id)
    {
        $enseignant = new Enseignant();
        $errors = [];

        // Récupérer les données de l'enseignant avec le grade via une jointure
        $select = "
            SELECT enseignants.*, grade.nom_grade 
            FROM enseignants
            LEFT JOIN grade ON grade.id_grade = enseignants.id_grade
            WHERE enseignants.enseignant_id = :id
        ";
        $enseignantData = $enseignant->select_data_table_join_where($select, ['id' => $id]);

        // Vérifiez si l'enseignant existe
        if (empty($enseignantData)) {
            $errors[] = "L'enseignant avec l'ID spécifié n'existe pas.";
            $this->view('modifier_enseignant', ['errors' => $errors]);
            return;
        }
        $enseignantData = $enseignantData[0];

        // Récupérer la liste des grades pour le formulaire
        $grades = $enseignant->SelectAllData("*", "grade");

        // Traitement lors de la soumission du formulaire
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $statut = $_POST['enseignant_statut'];

            // Gestion du fichier CV
            if (isset($_FILES['enseignant_cv']) && $_FILES['enseignant_cv']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'C:/xampp/htdocs/G_universite/public/cv_enseignant/';
                $cvFileName = uniqid() . '_' . basename($_FILES['enseignant_cv']['name']);
                $cvFilePath = $uploadDir . $cvFileName;

                // Supprimez l'ancien fichier si nécessaire
                if (!empty($enseignantData->enseignant_cv) && file_exists('C:/xampp/htdocs/G_universite/public/' . $enseignantData->enseignant_cv)) {
                    unlink('C:/xampp/htdocs/G_universite/public/' . $enseignantData->enseignant_cv);
                }

                if (move_uploaded_file($_FILES['enseignant_cv']['tmp_name'], $cvFilePath)) {
                    $cv = 'cv_enseignant/' . $cvFileName;
                } else {
                    $errors[] = "Échec du téléversement du fichier CV.";
                }
            } else {
                $cv = $enseignantData->enseignant_cv;
            }

            // Validation supplémentaire pour les permanents
            if ($statut === 'PERMANANT') {
                if (empty($_POST['id_grade'])) {
                    $errors[] = "Le grade est obligatoire pour un enseignant permanent.";
                }
                if (empty($_POST['enseignant_matricule'])) {
                    $errors[] = "Le matricule est obligatoire pour un enseignant permanent.";
                }
            }

            // Préparer les données pour la mise à jour
            $data = [
                'id' => $id,
                'enseignant_statut' => $statut,
                'id_grade' => $statut === 'PERMANANT' ? (int)$_POST['id_grade'] : null,
                'enseignant_matricule' => $statut === 'PERMANANT' ? $_POST['enseignant_matricule'] : null,
                'enseignant_nom' => $_POST['enseignant_nom'],
                'enseignant_prenom' => $_POST['enseignant_prenom'],
                'enseignant_date_naissance' => $_POST['enseignant_date_naissance'],
                'enseignant_email' => $_POST['enseignant_email'],
                'enseignant_telephone' => $_POST['enseignant_telephone'],
                'enseignant_diplome' => $_POST['enseignant_diplome'],
                'enseignant_cv' => $cv ?? null,
            ];

            // Mise à jour si aucune erreur
            if (empty($errors)) {
                $result = $enseignant->modification($data);

                if ($result) {
                    // Rechargez les données après modification
                    $enseignantData = $enseignant->select_data_table_join_where($select, ['id' => $id])[0];
                } else {
                    $errors[] = "Échec de la mise à jour de l'enseignant.";
                }
            }
        }

        // Charger la vue avec les données mises à jour
        $this->view('modifier_enseignant', [
            'enseignant' => $enseignantData,
            'grades' => $grades,
            'errors' => $errors
        ]);
    }

   
   

    public function delete($id)
    {
        $perso = new Enseignant();
        // Définir la requête de suppression et les paramètres
        $sql = 'DELETE FROM enseignants WHERE enseignant_id = :id';
        $params = [':id' => $id];
        $result = $perso->insertion_update_simples($sql, $params);
        if ($result->rowCount() > 0) {
            $perso->set_flash("Suppression réussie", 'success');
        }
        $perso->redirect('Enseignants/liste_enseignant');
    }

    //gestion d'edt individuel
    public function listeEDT_individuel($id, $date_debut = null, $date_fin = null)
    {
        $model = new Enseignant();
        $errors = [];
        $periodes = $model->getPeriodes();
        $status = isset($_POST['status']) ? $_POST['status'] : 'inachevé';
        $periode_selectionnee = null;
        foreach ($periodes as $periode) {
            if (trim($periode->status) === trim($status)) {
                $periode_selectionnee = $periode;
                break;
            }
        }

        if (!$periode_selectionnee) {
            $errors[] = "Aucune période correspondant au statut '$status' n'a été trouvée.";
        }

        // Gestion des dates (priorité à celles saisies par l'utilisateur)
        $date_debut = isset($_POST['date_debut']) ? $_POST['date_debut'] : ($periode_selectionnee->date_debut ?? null);
        $date_fin = isset($_POST['date_fin']) ? $_POST['date_fin'] : ($periode_selectionnee->date_fin ?? null);
        if ($date_debut === null || $date_fin === null) {
            $errors[] = "Les dates de début et de fin doivent être spécifiées ou disponibles dans la période sélectionnée.";
        }

        // Vérifier que les dates spécifiées sont cohérentes avec la période sélectionnée
        if ($periode_selectionnee && (new DateTime($date_debut) < new DateTime($periode_selectionnee->date_debut) || new DateTime($date_fin) > new DateTime($periode_selectionnee->date_fin))) {
            $errors[] = "Les dates fournies (du $date_debut au $date_fin) ne correspondent pas à la période '$status' sélectionnée (du {$periode_selectionnee->date_debut} au {$periode_selectionnee->date_fin}).";
        }

        // Récupération des emplois du temps
        $emplois_du_temps = [];
        if (empty($errors)) {
            $emplois_du_temps = $model->getEmploiDuTempsByEnseignant($id, $date_debut, $date_fin, $status);

            if (empty($emplois_du_temps)) {
                $errors[] = "Aucun emploi du temps trouvé pour cet enseignant durant la période sélectionnée.";
            }
        }
        $enseignant = null;
        $heures_totales = 0;
        $heures_dues = 0;
        $heures_supp = 0;
        $semestres_promotions = [];

        if (empty($errors)) {
            $enseignant = $emplois_du_temps[0];
            $enseignant->enseignant_statut = ($enseignant->enseignant_statut == 'PERMANANT') ? 'PERMANANT' : 'NON_PERMANANT';

            foreach ($emplois_du_temps as $edt) {
                $heures_totales += $edt->heure_total;
                $semestre_promotion = $edt->nom_semestre . " (" . $edt->annee_universitaire . ")";
                if (!in_array($semestre_promotion, $semestres_promotions)) {
                    $semestres_promotions[] = $semestre_promotion;
                }
            }

            $heures_dues = $enseignant->heures_dues ?? 0;
            if ($enseignant->enseignant_statut == 'PERMANANT') {
                $heures_supp = max(0, $heures_totales - $heures_dues);
            } else {
                $heures_supp = $heures_totales;
            }
        }

        // Affichage de la vue
        $this->view("listeEDT_individuel", [
            "enseignant" => $enseignant,
            "emplois_du_temps" => $emplois_du_temps,
            "heures_totales" => $heures_totales,
            "heures_dues" => $heures_dues,
            "heures_supp" => $heures_supp,
            "semestres_promotions" => $semestres_promotions,
            "date_debut" => $date_debut,
            "date_fin" => $date_fin,
            "errors" => $errors,
            "status" => $status
        ]);
    }

//     public function listeEDT_individuel($id, $date_debut = null, $date_fin = null)
// {
//     $model = new Enseignant();
//     $errors = [];
//     $periodes = $model->getPeriodes();

//     // Vérification des périodes disponibles
//     var_dump($periodes);

//     $status = isset($_POST['status']) ? $_POST['status'] : 'inachevé';
//     echo "Status sélectionné: $status<br>";

//     $periode_selectionnee = null;
//     foreach ($periodes as $periode) {
//         if (trim($periode->status) === trim($status)) {
//             $periode_selectionnee = $periode;
//             break;
//         }
//     }

//     // Vérifier si la période sélectionnée est trouvée
//     var_dump($periode_selectionnee);

//     if (!$periode_selectionnee) {
//         $errors[] = "Aucune période correspondant au statut '$status' n'a été trouvée.";
//     }

//     // Gestion des dates (priorité à celles saisies par l'utilisateur)
//     $date_debut = isset($_POST['date_debut']) ? $_POST['date_debut'] : ($periode_selectionnee->date_debut ?? null);
//     $date_fin = isset($_POST['date_fin']) ? $_POST['date_fin'] : ($periode_selectionnee->date_fin ?? null);

//     // Vérification des dates
//     echo "Date début: $date_debut, Date fin: $date_fin<br>";

//     if ($date_debut === null || $date_fin === null) {
//         $errors[] = "Les dates de début et de fin doivent être spécifiées ou disponibles dans la période sélectionnée.";
//     }

//     // Vérifier que les dates spécifiées sont cohérentes avec la période sélectionnée
//     if ($periode_selectionnee && (new DateTime($date_debut) < new DateTime($periode_selectionnee->date_debut) || new DateTime($date_fin) > new DateTime($periode_selectionnee->date_fin))) {
//         $errors[] = "Les dates fournies (du $date_debut au $date_fin) ne correspondent pas à la période '$status' sélectionnée (du {$periode_selectionnee->date_debut} au {$periode_selectionnee->date_fin}).";
//     }

//     // Récupération des emplois du temps
//     $emplois_du_temps = [];
//     if (empty($errors)) {
//         $emplois_du_temps = $model->getEmploiDuTempsByEnseignant($id, $date_debut, $date_fin, $status);

//         // Vérification des emplois du temps récupérés
//         var_dump($emplois_du_temps);

//         if (empty($emplois_du_temps)) {
//             $errors[] = "Aucun emploi du temps trouvé pour cet enseignant durant la période sélectionnée.";
//         }
//     }

//     // Affichage des erreurs si elles existent
//     if (!empty($errors)) {
//         var_dump($errors);
//     }

//     $enseignant = null;
//     $heures_totales = 0;
//     $heures_dues = 0;
//     $heures_supp = 0;
//     $semestres_promotions = [];

//     if (empty($errors)) {
//         $enseignant = $emplois_du_temps[0];
//         $enseignant->enseignant_statut = ($enseignant->enseignant_statut == 'PERMANANT') ? 'PERMANANT' : 'NON_PERMANANT';

//         foreach ($emplois_du_temps as $edt) {
//             $heures_totales += $edt->heure_total;
//             $semestre_promotion = $edt->nom_semestre . " (" . $edt->annee_universitaire . ")";
//             if (!in_array($semestre_promotion, $semestres_promotions)) {
//                 $semestres_promotions[] = $semestre_promotion;
//             }
//         }

//         $heures_dues = $enseignant->heures_dues ?? 0;
//         if ($enseignant->enseignant_statut == 'PERMANANT') {
//             $heures_supp = max(0, $heures_totales - $heures_dues);
//         } else {
//             $heures_supp = $heures_totales;
//         }
//     }

//     // Affichage de la vue
//     $this->view("listeEDT_individuel", [
//         "enseignant" => $enseignant,
//         "emplois_du_temps" => $emplois_du_temps,
//         "heures_totales" => $heures_totales,
//         "heures_dues" => $heures_dues,
//         "heures_supp" => $heures_supp,
//         "semestres_promotions" => $semestres_promotions,
//         "date_debut" => $date_debut,
//         "date_fin" => $date_fin,
//         "errors" => $errors,
//         "status" => $status
//     ]);
// }

 public function imprimerplusieursEDT_individuel()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!isset($_POST['ids']) || !isset($_POST['search'])) {
            header('Content-Type: application/json');
            echo json_encode(["error" => "Paramètres manquants"]);
            exit;
        }

        // Décoder le tableau JSON d'enseignants
        $ids = json_decode($_POST['ids'], true);
        $search = $_POST['search'];

        // Vérifier si la liste n'est pas vide
        if (empty($ids)) {
            header('Content-Type: application/json');
            echo json_encode(["error" => "Aucun enseignant sélectionné."]);
            exit;
        }

        $resultats = [];

        foreach ($ids as $id) {
            ob_start();
            $this->listeEDT_individuel($id, null, null, $search);
            $resultat = ob_get_clean();

            if (!empty($resultat)) {
                $resultats[$id] = $resultat;
            }
        }

        if (!empty($resultats)) {
            header('Content-Type: application/json');
            echo json_encode(["html" => implode('', $resultats)]);
            exit;
        }
    }

    header('Content-Type: application/json');
    echo json_encode(["error" => "Aucun emploi du temps trouvé pour ces enseignants."]);
    exit;
}







}