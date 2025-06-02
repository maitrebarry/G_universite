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
    public function periodes()
    {
        $model = new Enseignant();
        $periodes = $model->getPeriodes();
        echo json_encode($periodes);
    } 
    public function enseignants_par_periode()
    {
        $date_debut = $_POST['date_debut'] ?? null;
        $date_fin = $_POST['date_fin'] ?? null;
        $model = new Enseignant();
        $profs = $model->getEnseignantsParPeriode($date_debut, $date_fin);
        echo json_encode($profs);
    }
    public function listeEDT_individuels_par_periode()
    {
        $date_debut = $_POST['date_debut'] ?? null;
        $date_fin = $_POST['date_fin'] ?? null;
        $enseignant_id = $_POST['enseignant_id'] ?? null;
        $model = new Enseignant();
        $liste = $model->getEDTIndividuelsParPeriode($date_debut, $date_fin, $enseignant_id);
        $this->view("listeEDT_individuels_par_periode", [
            "liste" => $liste,
            "date_debut" => $date_debut,
            "date_fin" => $date_fin
        ]);
    }
    public function table_EDT_individuels()
    {
        $date_debut = $_POST['date_debut'] ?? null;
        $date_fin = $_POST['date_fin'] ?? null;
        $enseignant_id = $_POST['enseignant_id'] ?? null;
        $model = new Enseignant();
        $liste = $model->getEDTIndividuelsParPeriode($date_debut, $date_fin, $enseignant_id);
    
        // Récupère le total global par enseignant
        $totaux = [];
        foreach ($model->getTotalHeuresParEnseignant($date_debut, $date_fin) as $row) {
            $totaux[$row->enseignant_id] = $row->total_heures;
        }
    
        $this->view("table_EDT_individuels", [
            "liste" => $liste,
            "date_debut" => $date_debut,
            "date_fin" => $date_fin,
            "totaux_heures" => $totaux
        ]);
    }

    public function imprimerEDTIndividuels()
    {
        require_once dirname(__DIR__, 2) . '/vendor/autoload.php';
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    
        $model = new Enseignant();
        $date_debut = $_POST['date_debut'] ?? null;
        $date_fin = $_POST['date_fin'] ?? null;
        $enseignants = $_POST['enseignants'] ?? [];
    
        if (count($enseignants) === 1) {
            // Impression individuelle
            $eid = $enseignants[0];
            $emplois = $model->getEmploiDuTempsByEnseignant($eid, $date_debut, $date_fin, 'inachevé');
            if (!empty($emplois)) {
                $enseignant = $emplois[0];
                $heures_totales = 0;
                $heures_dues = $enseignant->heures_dues ?? 0;
                $heures_supp = 0;
                $semestres_promotions = [];
                foreach ($emplois as $edt) {
                    $heures_totales += $edt->heure_total;
                    $semestre_promotion = $edt->nom_semestre . " (" . $edt->annee_universitaire . ")";
                    if (!in_array($semestre_promotion, $semestres_promotions)) {
                        $semestres_promotions[] = $semestre_promotion;
                    }
                }
                if ($enseignant->enseignant_statut == 'PERMANANT') {
                    $heures_supp = max(0, $heures_totales - $heures_dues);
                } else {
                    $heures_supp = $heures_totales;
                }
                // Génération du HTML pour Dompdf
                ob_start();
                include(__DIR__ . '/../views/pdf_EDT_individuel.php');
                $html = ob_get_clean();
    
                $dompdf = new \Dompdf\Dompdf();
                $dompdf->loadHtml($html);
                $dompdf->setPaper('A4', 'portrait');
                $dompdf->render();
                $pdfContent = $dompdf->output();
    
                if (!empty($pdfContent)) {
                    $filename = preg_replace('/[^A-Za-z0-9_\-]/', '_', $enseignant->enseignant_prenom . '_' . $enseignant->enseignant_nom) . '.pdf';
                    header('Content-Type: application/pdf');
                    header('Content-Disposition: attachment; filename="' . $filename . '"');
                    header('Content-Length: ' . strlen($pdfContent));
                    echo $pdfContent;
                    exit;
                } else {
                    die("Erreur lors de la génération du PDF.");
                }
            } else {
                die("Aucun emploi du temps trouvé.");
            }
        } elseif (count($enseignants) > 1) {
            // Impression groupée (ZIP)
            $zip = new \ZipArchive();
            $zipFilename = sys_get_temp_dir() . '/edt_individuels_' . time() . '.zip';
            if ($zip->open($zipFilename, \ZipArchive::CREATE) !== TRUE) {
                die("Impossible de créer le fichier ZIP ($zipFilename)");
            }
            $nbPdf = 0;
            foreach ($enseignants as $eid) {
                $emplois = $model->getEmploiDuTempsByEnseignant($eid, $date_debut, $date_fin, 'inachevé');
                if (!empty($emplois)) {
                    $enseignant = $emplois[0];
                    $heures_totales = 0;
                    $heures_dues = $enseignant->heures_dues ?? 0;
                    $heures_supp = 0;
                    $semestres_promotions = [];
                    foreach ($emplois as $edt) {
                        $heures_totales += $edt->heure_total;
                        $semestre_promotion = $edt->nom_semestre . " (" . $edt->annee_universitaire . ")";
                        if (!in_array($semestre_promotion, $semestres_promotions)) {
                            $semestres_promotions[] = $semestre_promotion;
                        }
                    }
                    if ($enseignant->enseignant_statut == 'PERMANANT') {
                        $heures_supp = max(0, $heures_totales - $heures_dues);
                    } else {
                        $heures_supp = $heures_totales;
                    }
                                        
                   
                    // var_dump([
                    //     'enseignant' => $enseignant,
                    //     'emplois_du_temps' => $emplois,
                    //     'heures_totales' => $heures_totales,
                    //     'heures_dues' => $heures_dues,
                    //     'heures_supp' => $heures_supp,
                    //     'semestres_promotions' => $semestres_promotions,
                    //     'date_debut' => $date_debut,
                    //     'date_fin' => $date_fin
                    // ]);
                    // exit;
                    $emplois_du_temps = $emplois;
                    ob_start();
                    include(__DIR__ . '/../views/pdf_EDT_individuel.php');
                    $html = ob_get_clean();
    
                    $dompdf = new \Dompdf\Dompdf();
                    $dompdf->loadHtml($html);
                    $dompdf->setPaper('A4', 'portrait');
                    $dompdf->render();
                    $pdfContent = $dompdf->output();
    
                    if (!empty($pdfContent)) {
                        $filename = 'EDT_Individuels/' . preg_replace('/[^A-Za-z0-9_\-]/', '_', $enseignant->enseignant_prenom . '_' . $enseignant->enseignant_nom) . '.pdf';
                        $zip->addFromString($filename, $pdfContent);
                        $nbPdf++;
                    }
                }
            }
            $zip->close();
    
            if ($nbPdf > 0 && file_exists($zipFilename) && filesize($zipFilename) > 0) {
                header('Content-Type: application/zip');
                header('Content-Disposition: attachment; filename="EDT_Individuels.zip"');
                header('Content-Length: ' . filesize($zipFilename));
                readfile($zipFilename);
                unlink($zipFilename);
                exit;
            } else {
                if (file_exists($zipFilename)) {
                    unlink($zipFilename);
                }
                die("Erreur lors de la création du ZIP (aucun PDF généré).");
            }
        } else {
            die("Aucun enseignant sélectionné.");
        }
    }
    // Affichage de l'aperçu HTML
    public function apercuEDTIndividuel($id)
    {
        $model = new Enseignant();
        $date_debut = $_POST['date_debut'] ?? null;
        $date_fin = $_POST['date_fin'] ?? null;
        $status = $_POST['status'] ?? 'inachevé';
    
        $emplois = $model->getEmploiDuTempsByEnseignant($id, $date_debut, $date_fin, $status);
        $enseignant = !empty($emplois) ? $emplois[0] : null;
    
        // Calculs
        $heures_totales = 0;
        $heures_dues = $enseignant->heures_dues ?? 0;
        $heures_supp = 0;
        $semestres_promotions = [];
        foreach ($emplois as $edt) {
            $heures_totales += $edt->heure_total;
            $semestre_promotion = $edt->nom_semestre . " (" . $edt->annee_universitaire . ")";
            if (!in_array($semestre_promotion, $semestres_promotions)) {
                $semestres_promotions[] = $semestre_promotion;
            }
        }
        if ($enseignant && $enseignant->enseignant_statut == 'PERMANANT') {
            $heures_supp = max(0, $heures_totales - $heures_dues);
        } else {
            $heures_supp = $heures_totales;
        }
    
        $this->view('apercu_EDT_individuel', [
            'enseignant' => $enseignant,
            'emplois_du_temps' => $emplois,
            'heures_totales' => $heures_totales,
            'heures_dues' => $heures_dues,
            'heures_supp' => $heures_supp,
            'semestres_promotions' => $semestres_promotions,
            'date_debut' => $date_debut,
            'date_fin' => $date_fin,
            'status' => $status
        ]);
    }
    public function apercuEDTIndividuelsGroupes()
    {
        $model = new Enseignant();
        // echo "<h2>Aperçu des EDT individuels sélectionnés</h2>";exit;
        $ids = isset($_GET['enseignants']) ? explode(',', $_GET['enseignants']) : [];
        $ids = array_unique($ids);
        $date_debut = $_GET['date_debut'] ?? null;
        $date_fin = $_GET['date_fin'] ?? null;
        $status = $_GET['status'] ?? 'inachevé';
    
        $apercus = [];
        foreach ($ids as $eid) {
            $emplois = $model->getEmploiDuTempsByEnseignant($eid, $date_debut, $date_fin, $status);
            if (!empty($emplois)) {
                $enseignant = $emplois[0];
                $heures_totales = 0;
                $heures_dues = $enseignant->heures_dues ?? 0;
                $heures_supp = 0;
                $semestres_promotions = [];
                foreach ($emplois as $edt) {
                    $heures_totales += $edt->heure_total;
                    $semestre_promotion = $edt->nom_semestre . " (" . $edt->annee_universitaire . ")";
                    if (!in_array($semestre_promotion, $semestres_promotions)) {
                        $semestres_promotions[] = $semestre_promotion;
                    }
                }
                if ($enseignant->enseignant_statut == 'PERMANANT') {
                    $heures_supp = max(0, $heures_totales - $heures_dues);
                } else {
                    $heures_supp = $heures_totales;
                }
                $apercus[] = [
                    'enseignant' => $enseignant,
                    'emplois_du_temps' => $emplois,
                    'heures_totales' => $heures_totales,
                    'heures_dues' => $heures_dues,
                    'heures_supp' => $heures_supp,
                    'semestres_promotions' => $semestres_promotions,
                    'date_debut' => $date_debut,
                    'date_fin' => $date_fin,
                    'status' => $status
                ];
            }
        }
        // var_dump($apercus);exit;
        $this->view('apercu_EDT_individuels_groupes', [
            'apercus' => $apercus
        ]);
    }
    // gestion d'edt individuel
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
        // Gestion des dates (priorité à celles saisies par l'utilisateur)
            $date_debut = isset($_POST['date_debut']) ? $_POST['date_debut'] : null;
            $date_fin = isset($_POST['date_fin']) ? $_POST['date_fin'] : null;

            // Si aucune date n'est choisie, afficher seulement le formulaire de filtrage
            if ($date_debut === null || $date_fin === null) {
                $this->view("filtreEDT_individuel", [
                    "periodes" => $periodes,
                    "errors" => $errors,
                    "status" => $status
                ]);
                return;
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
        if(isset($_POST['action'])){
            // Affichage de la vue
                $this->view("plusierlisteEDT_individuel", [
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
                return;
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
}


