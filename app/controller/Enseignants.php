<?php
class Enseignants extends Controller
{
    public function index()
    {
        $this->lsite_enseignant();
    }
    // public function lsite_enseignant()
    // {

    //     $commandeModel = new Enseignant();
    //     $enseignat_enseignat_PERMANANT = $commandeModel->getEnseignantCDI();
    //     $enseignat_NON_PERMANANT = $commandeModel->getEnseignantVCT();
    //     //  var_dump($enseignat_enseignat_PERMANANT);
    //     //  var_dump($enseignat_NON_PERMANANT);
    //     //  exit;
    //     $this->view(
    //         'liste_enseignant',
    //         [
    //             'enseignat_CDI' => $enseignat_enseignat_PERMANANT,
    //             'enseignat_VCT' => $enseignat_NON_PERMANANT
    //         ]
    //     );
    // } 
    public function lsite_enseignant()
    {
        $commandeModel = new Enseignant();

        // Par défaut, on ne filtre pas par département (SupAdmin, DG, etc.)
        $id_departement = null;

        // Si l'utilisateur est Chef DR ou un rôle lié à un département, on filtre
        if (
            isset($_SESSION['role']) &&
            in_array($_SESSION['role'], ['Chef DR', 'Sécretaire principale', 'Scolarite'])
            && isset($_SESSION['id_departement'])
        ) {
            $id_departement = $_SESSION['id_departement'];
        }

        $enseignat_enseignat_PERMANANT = $commandeModel->getEnseignantCDI($id_departement);
        $enseignat_NON_PERMANANT = $commandeModel->getEnseignantVCT($id_departement);

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
         if (isset($_SESSION['id_departement'])) {
                $_POST['id_departement'] = $_SESSION['id_departement'];
            } else {
                $_SESSION['errors'][] = "Votre identifiant de département est introuvable. Veuillez vous reconnecter.";
               $enseignant->redirect("Enseignants/ajouter_enseignant"); 
                exit;
            }
        // Vérifier que l'utilisateur est connecté et est chef DR
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Chef DR') {
            // Rediriger ou afficher une erreur
            $_SESSION['errors'][] = "Accès refusé. Seuls les chefs de département peuvent ajouter des enseignants.";
            $enseignant->redirect("ajouter_enseignant"); 
            exit;
        }

        $enseignant = new Enseignant();
        $filiere = $enseignant->SelectAllData("*", "grade");

        if (isset($_POST["envoyer"])) {
            $_POST = array_map('trim', $_POST);
            $_POST['administration'] = $_POST['administration'] ?? 0;

            // Injecter le département du chef DR connecté
            $_POST['id_departement'] = $_SESSION['id_departement'];

            $enseignant->enregistrement($_FILES, $_POST, $filiere);

            if (!empty($enseignant->errors)) {
                $_SESSION['input'] = $_POST;
                $_SESSION['errors'] = $enseignant->errors;
            } else {
                unset($_SESSION['input'], $_SESSION['errors']);
            }
        }

        $input_values = $_SESSION['input'] ?? [];
        $errors = $_SESSION['errors'] ?? [];
        unset($_SESSION['input'], $_SESSION['errors']);

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

        $select = "
            SELECT enseignants.*, grade.nom_grade 
            FROM enseignants
            LEFT JOIN grade ON grade.id_grade = enseignants.id_grade
            WHERE enseignants.enseignant_id = :id
        ";
        $enseignantData = $enseignant->select_data_table_join_where($select, ['id' => $id]);

        if (empty($enseignantData)) {
            $errors[] = "L'enseignant avec l'ID spécifié n'existe pas.";
            $this->view('modifier_enseignant', ['errors' => $errors]);
            return;
        }
        $enseignantData = $enseignantData[0];

        $grades = $enseignant->SelectAllData("*", "grade");

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $statut = $_POST['enseignant_statut'];

            // Upload CV
            if (isset($_FILES['enseignant_cv']) && $_FILES['enseignant_cv']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'C:/xampp/htdocs/G_universite/public/cv_enseignant/';
                $cvFileName = uniqid() . '_' . basename($_FILES['enseignant_cv']['name']);
                $cvFilePath = $uploadDir . $cvFileName;

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

            // Upload Contrat
            if (isset($_FILES['contrat']) && $_FILES['contrat']['error'] === UPLOAD_ERR_OK) {
                $uploadContratDir = 'C:/xampp/htdocs/G_universite/public/contrat_enseignant/';
                $contratFileName = uniqid() . '_' . basename($_FILES['contrat']['name']);
                $contratFilePath = $uploadContratDir . $contratFileName;

                if (!empty($enseignantData->contrat) && file_exists('C:/xampp/htdocs/G_universite/public/' . $enseignantData->contrat)) {
                    unlink('C:/xampp/htdocs/G_universite/public/' . $enseignantData->contrat);
                }

                if (move_uploaded_file($_FILES['contrat']['tmp_name'], $contratFilePath)) {
                    $contrat = 'contrat_enseignant/' . $contratFileName;
                } else {
                    $errors[] = "Échec du téléversement du fichier Contrat.";
                }
            } else {
                $contrat = $enseignantData->contrat;
            }

            if ($statut === 'PERMANANT') {
                if (empty($_POST['id_grade'])) {
                    $errors[] = "Le grade est obligatoire pour un enseignant permanent.";
                }
                if (empty($_POST['enseignant_matricule'])) {
                    $errors[] = "Le matricule est obligatoire pour un enseignant permanent.";
                }
            }

            $data = [
                'id' => $id,
                'enseignant_statut' => $statut,
                'id_grade' => $statut === 'PERMANANT' ? (int)$_POST['id_grade'] : null,
                // 'enseignant_matricule' => $statut === 'PERMANANT' ? $_POST['enseignant_matricule'] : null,
               'enseignant_matricule' => $_POST['enseignant_matricule'] ?? $enseignantData->enseignant_matricule,
                'enseignant_nom' => $_POST['enseignant_nom'],
                'enseignant_prenom' => $_POST['enseignant_prenom'],
                'enseignant_date_naissance' => $_POST['enseignant_date_naissance'],
                'enseignant_email' => $_POST['enseignant_email'],
                'enseignant_telephone' => $_POST['enseignant_telephone'],
                'enseignant_diplome' => $_POST['enseignant_diplome'],
                'enseignant_cv' => $cv ?? null,
                'contrat' => $contrat ?? null,
                'code_bancaire' => $_POST['code_bancaire'] ?? '',
            ];

            if (empty($errors)) {
                $result = $enseignant->modification($data);
                if ($result) {
                    $enseignantData = $enseignant->select_data_table_join_where($select, ['id' => $id])[0];
                } else {
                    $errors[] = "Échec de la mise à jour de l'enseignant.";
                }
            }
        }

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
    public function listeEDT_individuels_par_periode()
    {
        $date_debut = $_POST['date_debut'] ?? null;
        $date_fin = $_POST['date_fin'] ?? null;
        $periode_id = $_POST['periode_id'] ?? null; // Ajout du filtre par ID période
        $enseignant_id = $_POST['enseignant_id'] ?? null;

        $model = new Enseignant();
        $liste = $model->getEDTIndividuelsParPeriode($date_debut, $date_fin, $periode_id, $enseignant_id);

        $this->view("listeEDT_individuels_par_periode", [
            "liste" => !empty($liste) ? $liste : [],
            "date_debut" => $date_debut,
            "date_fin" => $date_fin,
            "periode_id" => $periode_id 
        ]);
    }

    public function table_EDT_individuels()
    {
        // var_dump($_POST);
        $date_debut = $_POST['date_debut'] ?? null;
        $date_fin = $_POST['date_fin'] ?? null;
        $periode_id = $_POST['periode_id'] ?? null; // Ajout du filtre par ID période
        $enseignant_id = $_POST['enseignant_id'] ?? null;
        
        $model = new Enseignant();
        $liste = $model->getEDTIndividuelsParPeriode($date_debut, $date_fin, $periode_id, $enseignant_id);

        // Vérification des données avant affichage
        // var_dump($liste); exit;

        // Récupère le total global par enseignant
        $totaux = [];
        foreach ($model->getTotalHeuresParEnseignant($date_debut, $date_fin) as $row) {
            $totaux[$row->enseignant_id] = $row->total_heures;
        }
        
        $this->view("table_EDT_individuels", [
            "liste" => !empty($liste) ? $liste : [],
            "date_debut" => $date_debut,
            "date_fin" => $date_fin,
            "totaux_heures" => $totaux,
            "periode_id" => $periode_id 
        ]);
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
        $periode_id = $_POST['periode_id'] ?? null; // Ajout du filtre par ID période
        
        $model = new Enseignant();
        $profs = $model->getEnseignantsParPeriode($date_debut, $date_fin, $periode_id);
        
        echo json_encode($profs);
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
        $periode_id = $_POST['periode_id'] ?? null;

        if (count($enseignants) === 1) {
            // Impression individuelle
            $eid = $enseignants[0];
            $emplois = $model->getEmploiDuTempsByEnseignant($eid, $date_debut, $date_fin, $periode_id);

            if (!empty($emplois)) {
                $enseignant = $emplois[0];
                $emplois_du_temps = $emplois;
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

                if ($enseignant->enseignant_statut === 'PERMANANT') {
                    $heures_supp = max(0, $heures_totales - $heures_dues);
                } else {
                    $heures_supp = $heures_totales;
                }

                // Génération PDF
                ob_start();
                include(__DIR__ . '/../views/pdf_EDT_individuel.view.php');
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
                die("Aucun emploi du temps trouvé pour l'enseignant.");
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
                $emplois = $model->getEmploiDuTempsByEnseignant($eid, $date_debut, $date_fin, $periode_id);

                if (!empty($emplois)) {
                    $enseignant = $emplois[0];
                    $emplois_du_temps = $emplois;
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

                    if ($enseignant->enseignant_statut === 'PERMANANT') {
                        $heures_supp = max(0, $heures_totales - $heures_dues);
                    } else {
                        $heures_supp = $heures_totales;
                    }

                    ob_start();
                    include(__DIR__ . '/../views/pdf_EDT_individuel.view.php');
                    $html = ob_get_clean();

                    $dompdf = new \Dompdf\Dompdf();
                    $dompdf->loadHtml($html);
                    $dompdf->setPaper('A4', 'portrait');
                    $dompdf->render();
                    $pdfContent = $dompdf->output();

                    if (!empty($pdfContent)) {
                        $filename = preg_replace('/[^A-Za-z0-9_\-]/', '_', $enseignant->enseignant_prenom . '_' . $enseignant->enseignant_nom) . '.pdf';
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
                ob_clean();
                flush();
                readfile($zipFilename);
                unlink($zipFilename);
                exit;
            } else {
                if (file_exists($zipFilename)) {
                    unlink($zipFilename);
                }
                die("Erreur : Aucun fichier PDF généré.");
            }
            

        } else {
            die("Aucun enseignant sélectionné.");
        }
    }

    public function exporterEDTPDF()
    {
        $model = new Enseignant();
        
        $date_debut = $_POST['date_debut'] ?? null;
        $date_fin = $_POST['date_fin'] ?? null;
        $periode_id = $_POST['periode_id'] ?? null;
        $enseignants = $_POST['enseignants'] ?? [];
    
        // 🔹 Si jamais on reçoit une chaîne JSON, on la décode
        if (is_string($enseignants)) {
            $enseignants = json_decode($enseignants, true);
        }
    
        if (!$periode_id) {
            die("Erreur : periode_id requis !");
        }
    
        $enseignantsIndex = [];
    
        foreach ($enseignants as $eid) {
            $emplois = $model->getEmploiDuTempsByEnseignantRecap($eid, $date_debut, $date_fin, $periode_id);
    
            if (!empty($emplois)) {
                foreach ($emplois as $emploi) {
                    if (!isset($enseignantsIndex[$eid])) {
                        $enseignantsIndex[$eid] = $emploi;
                        $enseignantsIndex[$eid]->emplois_du_temps = [];
    
                     
                        $enseignantsIndex[$eid]->heures_dues = $emploi->heures_dues ?? 0;
    
                       
                       // 🔹 Correction : on affecte uniquement le matricule s’il existe
                       if (!empty($emploi->enseignant_matricule)) {
                        $enseignantsIndex[$eid]->enseignant_matricule = $emploi->enseignant_matricule;
                        } else {
                            // Vérification et formatage de la date de naissance si matricule vide
                            if (!empty($emploi->enseignant_date_naissance)) {
                                $timestamp = strtotime($emploi->enseignant_date_naissance);
                                if ($timestamp !== false) {
                                    $enseignantsIndex[$eid]->enseignant_matricule = date('d-m-Y', $timestamp); // ✅ Formaté en j-m-a
                                } else {
                                    $enseignantsIndex[$eid]->enseignant_matricule = "Date inconnue";
                                }
                            } else {
                                $enseignantsIndex[$eid]->enseignant_matricule = "Non renseigné";
                            }
                        }
                        $enseignantsIndex[$eid]->heures_effectuees = 0;
                        $enseignantsIndex[$eid]->heures_supp = 0;
                    }
    
                  
                    $enseignantsIndex[$eid]->emplois_du_temps[] = $emploi->nom_module;
    
                    // 🔹 Calcul des heures effectuées
                    $enseignantsIndex[$eid]->heures_effectuees += $emploi->heure_total;
                }
    
                // 🔹 Calcul des heures supplémentaires
                if (strtoupper($emploi->enseignant_statut) === 'PERMANANT') {
                    $enseignantsIndex[$eid]->heures_supp = max(0, $enseignantsIndex[$eid]->heures_effectuees - $enseignantsIndex[$eid]->heures_dues);
                } else {
                    $enseignantsIndex[$eid]->heures_supp = $enseignantsIndex[$eid]->heures_effectuees;
                }
            }
        }
    
        // 🔹 Séparation permanents / non-permanents
        $permanents = array_filter($enseignantsIndex, fn($e) => strtoupper($e->enseignant_statut) === 'PERMANANT');
        $non_permanents = array_filter($enseignantsIndex, fn($e) => strtoupper($e->enseignant_statut) !== 'PERMANANT');
    
        // 🔹 Capture de la vue HTML
        ob_start();
        require dirname(__DIR__) . '/views/recap_edt.view.php';
        $html = ob_get_clean();
    
        // 🔹 Configuration PDF
        require_once dirname(__DIR__, 2) . '/vendor/autoload.php';
        $options = new \Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('defaultFont', 'Arial');
    
        $dompdf = new \Dompdf\Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
    
        // 🔹 Téléchargement du PDF sans `exit`
        $pdfFilename = "EDT_Récapitulatif.pdf";
        header("Content-Type: application/pdf");
        header("Content-Disposition: attachment; filename={$pdfFilename}");
        header("Content-Length: " . strlen($dompdf->output()));
        echo $dompdf->output();
    }
    
    public function genererPDF()
    {
        require_once dirname(__DIR__, 2) . '/vendor/autoload.php';
        $model = new Enseignant();
    
        $date_debut = $_POST['date_debut'] ?? null;
        $date_fin = $_POST['date_fin'] ?? null;
        $periode_id = $_POST['periode_id'] ?? null;
        $enseignants = $_POST['enseignants'] ?? [];
    
        // 🔹 Si jamais on reçoit une chaîne JSON, on la décode
        if (is_string($enseignants)) {
            $enseignants = json_decode($enseignants, true);
        }
    
        if (!$periode_id || empty($enseignants)) {
            die("Erreur : période ou enseignants manquants !");
        }
    
        $enseignantsIndex = [];
    
        foreach ($enseignants as $eid) {
            $emplois = $model->getEmploiDuTempsByEnseignantRecap($eid, $date_debut, $date_fin, $periode_id);
    
            if (!empty($emplois)) {
                $heures_totales = 0;
                $semestres_promotions = [];
    
                foreach ($emplois as $emploi) {
                    if (!isset($enseignantsIndex[$eid])) {
                        $enseignantsIndex[$eid] = $emploi;
                        $enseignantsIndex[$eid]->emplois_du_temps = [];
    
                        // 🔹 Correction : récupérer `heures_dues` depuis `grade`
                        $enseignantsIndex[$eid]->heures_dues = $emploi->heures_dues ?? 0;
    
                        if (!empty($emploi->enseignant_matricule)) {
                            $enseignantsIndex[$eid]->enseignant_matricule = $emploi->enseignant_matricule;
                        } else {
                            // Vérification et formatage de la date de naissance si matricule vide
                            if (!empty($emploi->enseignant_date_naissance)) {
                                $timestamp = strtotime($emploi->enseignant_date_naissance);
                                if ($timestamp !== false) {
                                    $enseignantsIndex[$eid]->enseignant_matricule = date('d-m-Y', $timestamp); 
                                } else {
                                    $enseignantsIndex[$eid]->enseignant_matricule = "Date inconnue";
                                }
                            } else {
                                $enseignantsIndex[$eid]->enseignant_matricule = "Non renseigné";
                            }
                        }
                        

                        // sinon on ne touche pas au champ `enseignant_matricule`.
                        // La logique d'affichage du fallback (date de naissance) se gère dans la vue

                    
    
                        $enseignantsIndex[$eid]->heures_effectuees = 0;
                        $enseignantsIndex[$eid]->heures_supp = 0;
                    }
    
                    // 🔹 Ajout des matières enseignées
                    $enseignantsIndex[$eid]->emplois_du_temps[] = $emploi->nom_module;
    
                    // 🔹 Accumulation des heures totales
                    $heures_totales += $emploi->heure_total;
                }
    
                // 🔹 Calcul des heures supplémentaires
                if (strtoupper($emploi->enseignant_statut) === 'PERMANANT') {
                    $enseignantsIndex[$eid]->heures_supp = max(0, $heures_totales - $enseignantsIndex[$eid]->heures_dues);
                } else {
                    $enseignantsIndex[$eid]->heures_supp = $heures_totales;
                }
    
                // 🔹 Stockage des heures effectuées
                $enseignantsIndex[$eid]->heures_effectuees = $heures_totales;
            }
        }
    
        // 🔹 Séparation permanents / non-permanents
        $permanents = array_filter($enseignantsIndex, fn($e) => strtoupper($e->enseignant_statut) === 'PERMANANT');
        $non_permanents = array_filter($enseignantsIndex, fn($e) => strtoupper($e->enseignant_statut) !== 'PERMANANT');
    
        // 🔹 Capture de la vue HTML
        ob_start();
        require dirname(__DIR__) . '/views/recap_edt.view.php';
        $html = ob_get_clean();
        echo "<pre>";
        // // print_r($permanents);
        // print_r($non_permanents);
        // echo "</pre>";
        // exit;
        // // 🔹 Configuration PDF
        $options = new \Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('defaultFont', 'Arial');
    
        // 🔹 Génération du PDF
        $dompdf = new \Dompdf\Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
    
        // 🔹 Téléchargement du PDF 
        $pdfFilename = "EDT_Récapitulatif.pdf";
        header("Content-Type: application/pdf");
        header("Content-Disposition: attachment; filename={$pdfFilename}");
        header("Content-Length: " . strlen($dompdf->output()));
        echo $dompdf->output();
    }
    public function apercuEDTIndividuel($id)
    {
        $model = new Enseignant();
        $date_debut = $_POST['date_debut'] ?? null;
        $date_fin = $_POST['date_fin'] ?? null;
        $periode_id = $_POST['periode_id'] ?? null; // 🔹 Ajout du filtre période

        if (!$periode_id) {
            die("Erreur : periode_id est requis !");
        }

        $emplois = $model->getEmploiDuTempsByEnseignant($id, $date_debut, $date_fin, $periode_id);
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
            'periode_id' => $periode_id // 🔹 Ajout de la période sélectionnée
        ]);
    }     public function apercuEDTIndividuelsGroupes()
    {
        $model = new Enseignant();
        
        $ids = isset($_GET['enseignants']) ? explode(',', $_GET['enseignants']) : [];
        $ids = array_unique($ids);
        $date_debut = $_GET['date_debut'] ?? null;
        $date_fin = $_GET['date_fin'] ?? null;
        $periode_id = $_GET['periode_id'] ?? null; // 🔹 Ajout du filtre période

        if (!$periode_id) {
            die("Erreur : periode_id est requis !");
        }

        $apercus = [];
        foreach ($ids as $eid) {
            $emplois = $model->getEmploiDuTempsByEnseignant($eid, $date_debut, $date_fin, $periode_id);
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
                    'periode_id' => $periode_id // 🔹 Ajout de la période sélectionnée
                ];
            }
        }

        $this->view('apercu_EDT_individuels_groupes', [
            'apercus' => $apercus
        ]);
    }
    public function listeEDT_individuel($id, $date_debut = null, $date_fin = null)
    {
        $model = new Enseignant();
        $errors = [];
        $periodes = $model->getPeriodes();
        $periode_id = isset($_POST['periode_id']) ? $_POST['periode_id'] : null; // 🔹 Ajout du filtre période
        $periode_selectionnee = null;

        foreach ($periodes as $periode) {
            if ($periode->id_periode == $periode_id) { // 🔹 Filtrer par ID de période et non par statut
                $periode_selectionnee = $periode;
                break;
            }
        }

        // Gestion des dates (priorité à celles saisies par l'utilisateur)
        $date_debut = isset($_POST['date_debut']) ? $_POST['date_debut'] : ($periode_selectionnee->date_debut ?? null);
        $date_fin = isset($_POST['date_fin']) ? $_POST['date_fin'] : ($periode_selectionnee->date_fin ?? null);

        // Si aucune période sélectionnée ou dates invalides, afficher le formulaire de filtrage
        if (!$periode_selectionnee || $date_debut === null || $date_fin === null) {
            $errors[] = "Les dates de début et de fin doivent être spécifiées ou disponibles dans la période sélectionnée.";
            $this->view("filtreEDT_individuel", [
                "periodes" => $periodes,
                "errors" => $errors
            ]);
            return;
        }

        // Vérifier que les dates spécifiées sont cohérentes avec la période sélectionnée
        if (new DateTime($date_debut) < new DateTime($periode_selectionnee->date_debut) || new DateTime($date_fin) > new DateTime($periode_selectionnee->date_fin)) {
            $errors[] = "Les dates fournies (du $date_debut au $date_fin) ne correspondent pas à la période sélectionnée (du {$periode_selectionnee->date_debut} au {$periode_selectionnee->date_fin}).";
        }

        // Récupération des emplois du temps
        $emplois_du_temps = [];
        if (empty($errors)) {
            $emplois_du_temps = $model->getEmploiDuTempsByEnseignant($id, $date_debut, $date_fin, $periode_id);
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
        $this->view(isset($_POST['action']) ? "plusierlisteEDT_individuel" : "listeEDT_individuel", [
            "enseignant" => $enseignant,
            "emplois_du_temps" => $emplois_du_temps,
            "heures_totales" => $heures_totales,
            "heures_dues" => $heures_dues,
            "heures_supp" => $heures_supp,
            "semestres_promotions" => $semestres_promotions,
            "date_debut" => $date_debut,
            "date_fin" => $date_fin,
            "errors" => $errors
        ]);
    }
}


