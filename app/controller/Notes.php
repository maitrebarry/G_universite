<?php
class Notes extends Controller
{
    public function index()
    {
        $idDepartement = null;
        if (isset($_SESSION['id_departement'])) {
            $idDepartement = $_SESSION['id_departement'];
        }
        $filiereModel = new Filiere();
        $filieres = $filiereModel->listeFilieresParDepartement($idDepartement);
        $semestreModel = new Semestre();
        $listeSemestres = $semestreModel->SelectAllData("*", "semestre");
        $this->view('ajouter_notes', ['filieres' => $filieres, 'semestres' => $listeSemestres]);
    }

    //Function pour obtenir toutes les notes
    public function get_note_etudiant()
    {
        if (
            isset(
                $_POST['idPromotion'],
                $_POST['idModule'],
                $_POST['idSemestre']
            ) && !empty($_POST['idPromotion'])
            && !empty($_POST['idSemestre'])
            && !empty($_POST['idModule'])
        ) {
            //Etape 2: Récupération des idPromotion et idModule
            $idPromotion = $_POST['idPromotion'];
            $idSemestre = $_POST['idSemestre'];
            $idModule = $_POST['idModule'];

            //Etape 3: instantiation de la classe Note
            $noteModel = new Note();

            //Etape 4: La récupération de toutes les notes selon idPromotion et l'idModule
            $note_des_etudiants = $noteModel->getAllNotesEtudiant($idPromotion, $idSemestre, $idModule);
            //Les informations du module sélectionner
            $infosModule = $noteModel->getInfoModule($idModule);

            if (!empty($note_des_etudiants)) {
                usort($note_des_etudiants, function ($a, $b) {
                    return strcasecmp($a->prenom, $b->prenom); // comparaison insensible à la casse
                });

                $this->view('post_ajouter_notes', ['note_des_etudiants' => $note_des_etudiants, "infosModule" => $infosModule]);
                return;
            } else {
                echo '<div class="gu-empty"><i class="bx bx-user-x"></i>Aucun étudiant trouvé pour cette classe.</div>';
                return;
            }

            $this->view("set_flash");
            return;
        } else {
            echo "notfound";
            exit;
        }
    }

    //Fonction pour enregistrer les notes 
    public function save_note_etudiant()
    {
        header('Content-Type: application/json');
        if (isset($_POST['action']) && $_POST['action'] === 'noterecuee') {
            // Champ vide -> NULL (note non saisie), sinon la valeur.
            $norm = function ($v) { return ($v === null || $v === '') ? null : $v; };
            $idNote     = (int) ($_POST['idNote'] ?? 0);
            $devoir     = $norm($_POST['devoir'] ?? '');
            $evaluation = $norm($_POST['evaluation'] ?? '');
            $session    = $norm($_POST['session'] ?? '');
            $moyenne    = $norm($_POST['moyenne'] ?? '');

            $noteModel = new Note();
            $ok = $noteModel->save_note_etudiant($idNote, $devoir, $evaluation, $session, $moyenne);
            echo json_encode(['ok' => $ok !== false]);
            return;
        }
        echo json_encode(['ok' => false, 'error' => 'Requête invalide']);
    }

    public function liste_note()
    {
        $idDepartement = null;
        if (isset($_SESSION['id_departement'])) {
            $idDepartement = $_SESSION['id_departement'];
        }
        $filiereModel = new Filiere();
        $filieres = $filiereModel->listeFilieresParDepartement($idDepartement);
        $semestreModel = new Semestre();
        $listeSemestres = $semestreModel->SelectAllData("*", "semestre");
        $this->view('liste_notes', ['filieres' => $filieres, 'semestres' => $listeSemestres]);
    }

    public function get_moyenne_etudiant()
    {
        //Etpa 1: Vérification de la des idPromoton et idModule pour voir leur validité
        if (
            isset(
                $_POST['idPromotion'],
                $_POST['idSemestre']
            ) && !empty($_POST['idPromotion'])
            && !empty($_POST['idSemestre'])
        ) {


            $idPromotion = htmlspecialchars(trim($_POST['idPromotion']));
            $idSemestre = htmlspecialchars(trim($_POST['idSemestre']));


            $noteModel = new Note();
            $etudiantModel = new Etudiant();

            if (isset($_POST['idUe']) && !trim(empty($_POST['idUe']))) {


                if (isset($_POST['idModule']) && !trim(empty($_POST['idModule']))) {

                    $idModule = $_POST['idModule'];
                    $note_des_etudiants = $noteModel->getAllNotesEtudiant($idPromotion, $idSemestre, $idModule);
                    $infosModule = $noteModel->getInfoModule($idModule);
                    if (!empty($note_des_etudiants)) {

                        $this->view('post_liste_note_module', ['note_des_etudiants' => $note_des_etudiants, "infosModule" => $infosModule]);
                        return;
                    } else {
                        echo   "<h6 class='text-center text-bold-600 text-warning'>" .
                            "Aucun étudiant trouvé pour cette promotion !</h6>";
                        return;
                    }
                } else {

                    $idUe = htmlspecialchars(trim($_POST['idUe']));

                    $resultat = $noteModel->getAllMoyenneUeEtudiants($idPromotion, $idSemestre, $idUe);
                    $infosUe = $resultat['infosUe'];
                    $moyenne_des_etudiants = $resultat['moyenneUeEtudiants'];

                    if (!empty($moyenne_des_etudiants)) {

                        $this->view('post_liste_note_ue', ['moyenne_des_etudiants' => $moyenne_des_etudiants, "infosUe" => $infosUe]);
                        return;
                    } else {
                        echo "<h6 class='text-center text-bold-600 text-warning'>" .
                            "Aucun étudiant trouvé pour cette promotion !</h6>";
                        return;
                    }
                }
            } else {

                $resultat = $noteModel->getAllMoyenneSemestreEtudiants($idPromotion, $idSemestre);
                $moyennesSemestre = $resultat["moyennesSemestre"];
                $infosSemestre = $resultat['infosSemestre'];

                if (!empty($moyennesSemestre)) {

                    $this->view(
                        'post_liste_note_semestre',
                        [
                            'moyennesSemestre' => $moyennesSemestre,
                            'moyennesUe' => $resultat["moyennesUe"],
                            "infosSemestre" => $infosSemestre,
                            "promotion" => $resultat["promotion"],
                            "semestre" => $resultat["semestre"]
                        ]
                    );
                    return;
                } else {
                    echo "<h6 class='text-center text-bold-600 text-warning'>" .
                        "Aucun étudiant trouvé pour cette promotion!</h6>";
                    return;
                }
            }

            $this->view("set_flash");
            return;
        } else {
            echo "notfound";
            exit;
        }
    }

    public function get_moyenne_licence_etudiant()
    {

        //Etpa 1: Vérification de la des idPromoton et idModule pour voir leur validité
        if (
            isset($_POST['idPromotion'],) && !empty($_POST['idPromotion'])

        ) {
            $idPromotion = htmlspecialchars(trim($_POST['idPromotion']));
            $noteModel = new Note();
            $etudiantModel = new Etudiant();

            $resultat = $noteModel->getAllMoyenneLicenceEtudiants($idPromotion);
            $infosLicence = $resultat['infosLicence'];
            $moyennesLicence = $resultat['moyennesLicence'];
            $moyennesSemestre = $resultat['moyennesSemestre'];

            if (!empty($moyennesLicence)) {
                $this->view(
                    'post_liste_note_licence',
                    [
                        'infosLicence' => $infosLicence,
                        'moyennesLicence' => $moyennesLicence,
                        'moyennesSemestre' => $moyennesSemestre
                    ]
                );
                return;
            } else {
                echo "<h6 class='text-center text-bold-600 text-warning'>" .
                    "Aucun étudiant trouvé pour cette promotion!</h6>";
                return;
            }


            $this->view("set_flash");
            return;
        } else {
            echo "notfound";
            exit;
        }
    }


    public function get_classe_annee()
    {
        if (isset($_POST['anneeUniversitaire']) && !empty(trim(htmlspecialchars($_POST['anneeUniversitaire'])))) {
            $anneeUniversitaire = $_POST['anneeUniversitaire'];
            $noteModel = new Note();
            $requette = "SELECT id_promotion, annee_universitaire, promotion.statut, promotion.id_parcours, promotion.id_filiere, nom_filiere, 
            sigle_filiere, nom_semestre, sigle_semestre FROM promotion INNER JOIN filiere ON promotion.id_filiere=filiere.id_filiere
            INNER JOIN parcours ON promotion.id_parcours=parcours.id_parcours INNER JOIN semestre ON parcours.id_semestre=semestre.id_semestre
            WHERE promotion.annee_universitaire=?";

            $promotions = $noteModel->select_data_table_join_where($requette, [$anneeUniversitaire]);
            $classes = [];

            foreach ($promotions as $promotion) {

                // la recuperation des semestres de la promotion
                $requetteSemestre = "SELECT id_parcours, parcours.id_semestre, nom_semestre, sigle_semestre 
                FROM parcours  INNER JOIN semestre ON parcours.id_semestre=semestre.id_semestre WHERE id_filiere=? AND id_parcours >=? LIMIT 2";
                $semestres = $noteModel->select_data_table_join_where($requetteSemestre, [$promotion->id_filiere, $promotion->id_parcours]);

                // la deduction du niveau
                if (strtolower($promotion->sigle_semestre) == 's1' || strtolower($promotion->sigle_semestre) == 's2') {
                    $niveau = 'L1';
                } elseif (strtolower($promotion->sigle_semestre) == 's3' || strtolower($promotion->sigle_semestre) == 's4') {
                    $niveau = 'L2';
                } else {
                    $niveau = 'L3';
                }
                if (isset($_POST['action']) && $_POST['action'] === "liste_note") {
                    $classes[] = (object) [
                        "id_filiere" => $promotion->id_filiere,
                        "id_promotion" => $promotion->id_promotion,
                        "classe" => strtoupper(
                            $promotion->sigle_filiere . '-' . $niveau . '-' . $promotion->annee_universitaire . ''
                        ),
                        "id_parcours" => $promotion->id_parcours
                    ];
                }
                if (!isset($_POST['list'])) {
                    foreach ($semestres as $semestre) {
                        $classes[] = (object) [
                            "id_filiere" => $promotion->id_filiere,
                            "id_promotion" => $promotion->id_promotion,
                            "classe" => strtoupper(
                                $promotion->sigle_filiere . '-' . $niveau . '-' . $semestre->sigle_semestre . '-' . $promotion->annee_universitaire . ''
                            ),
                            "id_parcours" => $semestre->id_parcours
                        ];
                    }
                }
            }
            header("Content-Type:application/json");
            echo json_encode($classes);
        }
    }

    public function get_releves_notes()
    {

        if (
            isset(
                $_POST['idPromotion'],
                $_POST['idSemestre']
            ) && !empty($_POST['idPromotion'])
            && !empty($_POST['idSemestre'])
        ) {


            $idPromotion = htmlspecialchars(trim($_POST['idPromotion']));
            $idSemestre = htmlspecialchars(trim($_POST['idSemestre']));
            $noteModel = new Note();

            $resultat = $noteModel->getAllMoyenneSemestreEtudiants($idPromotion, $idSemestre);
            $moyennesSemestre = $resultat["moyennesSemestre"];
            $infosSemestre = $resultat['infosSemestre'];

            if (!empty($moyennesSemestre)) {

                $this->view(
                    'post_releve_etudiant',
                    [
                        'moyennesSemestre' => $moyennesSemestre,
                        'moyennesUe' => $resultat["moyennesUe"],
                        "infosSemestre" => $infosSemestre,
                        "promotion" => $resultat["promotion"],
                        "semestre" => $resultat["semestre"]
                    ]
                );
                return;
            } else {
                echo "notfound";
                exit;
            }
        }
    }

    // ===== Centre des relevés (fonctionnalité dédiée) =====
    public function releves()
    {
        $this->view('releves');
    }

    // Liste d'une classe avec décision (roster du centre des relevés).
    public function get_releves_roster()
    {
        if (!empty($_POST['idPromotion']) && !empty($_POST['idSemestre'])) {
            $idPromotion = htmlspecialchars(trim($_POST['idPromotion']));
            $idSemestre = htmlspecialchars(trim($_POST['idSemestre']));
            $resultat = (new Note())->getAllMoyenneSemestreEtudiants($idPromotion, $idSemestre);
            if (!empty($resultat['moyennesSemestre'])) {
                $this->view('post_releves_roster', [
                    'moyennesSemestre' => $resultat['moyennesSemestre'],
                    'moyennesUe' => $resultat['moyennesUe'],
                    'infosSemestre' => $resultat['infosSemestre'],
                    'promotion' => $resultat['promotion'],
                    'semestre' => $resultat['semestre'],
                ]);
                return;
            }
            echo '<div class="gu-empty"><i class="bx bx-user-x"></i>Aucun étudiant / aucune note pour cette classe.</div>';
            return;
        }
        echo '<div class="gu-empty"><i class="bx bx-error-circle"></i>Sélection invalide.</div>';
    }

    // Relevé d'UN seul étudiant (aperçu écran / PDF individuel).
    public function get_releve_etudiant()
    {
        if (!empty($_POST['idPromotion']) && !empty($_POST['idSemestre']) && !empty($_POST['idEtudiant'])) {
            $idPromotion = htmlspecialchars(trim($_POST['idPromotion']));
            $idSemestre = htmlspecialchars(trim($_POST['idSemestre']));
            $idEtudiant = (int) $_POST['idEtudiant'];
            $resultat = (new Note())->getAllMoyenneSemestreEtudiants($idPromotion, $idSemestre);
            $idx = null;
            foreach ($resultat['moyennesSemestre'] as $k => $ms) {
                if ((int) $ms['etudiant']->id_etudiant === $idEtudiant) { $idx = $k; break; }
            }
            if ($idx !== null) {
                $this->view('post_releve_etudiant', [
                    'moyennesSemestre' => [$resultat['moyennesSemestre'][$idx]],
                    'moyennesUe' => [$resultat['moyennesUe'][$idx]],
                    'infosSemestre' => $resultat['infosSemestre'],
                    'promotion' => $resultat['promotion'],
                    'semestre' => $resultat['semestre'],
                ]);
                return;
            }
            echo 'notfound';
            return;
        }
        echo 'notfound';
    }
}
