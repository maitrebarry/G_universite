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
    {       //Etpa 1: Vérification de la des idPromoton et idModule pour voir leur validité
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
            // var_dump($infosModule); exit;
            //Etape 5 : Debogagevar_dump($etudiants); //post_liste_note est un fichier qui gère l'affichage qui se trouve dans le view
            if (!empty($note_des_etudiants)) {
                # cod //Etape 6: Passage des donnée à la view depuis la le fichier post_liste_note.php qui est responsable de l'affichage dynamique dans notre cas
                $this->view('post_ajouter_notes', ['note_des_etudiants' => $note_des_etudiants, "infosModule" => $infosModule]); //Pour afficher la listes des étudiant en arriere plan par ajax
                return;
            } else {
                echo   "<h6 class='text-center text-bold-600 text-warning'>" .
                    "Aucun étudiant trouvé pour cette promotion !</h6>";
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
    {   //ACTION EST UNE DONNEE PRISE COMME CA PAR NOUS DEPUIS AJAX POUR SAVOIR SI LES DONNEES SONT BIEN TRANSMIS AU CONTROLLER OU PAS
        if (isset($_POST['action']) && $_POST['action'] === 'noterecuee') {
            //Récupération des données envoyée par l'ajax
            @$idNote = htmlspecialchars($_POST['idNote']);
            @$devoir = $_POST['devoir'];
            @$evaluation = htmlspecialchars($_POST['evaluation']);
            @$session = htmlspecialchars($_POST['session']);
            @$moyenne = htmlspecialchars($_POST['moyenne']);
            //echo"Les données sont bien reçues";

            //Appel de la fonction de modification de la note
            $noteModel = new Note();
            $note_modifiee = $noteModel->save_note_etudiant($idNote, $devoir, $evaluation, $session, $moyenne);
            if (!$note_modifiee) {
                $this->view("set_flash");
            }
        } else {
            echo "Les données sont en attente";
        }
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
                $etudiants = $etudiantModel->trie_liste_etudiant($idPromotion);
                if (!empty($moyennesSemestre)) {

                    $this->view(
                        'post_liste_note_semestre',
                        [
                            'moyennesSemestre' => $moyennesSemestre,
                            "infosSemestre" => $infosSemestre,
                            "etudiants" => $etudiants
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
            isset(
                $_POST['idPromotion'],
                $_POST['licence']
            ) && !empty($_POST['idPromotion'])
            && !empty($_POST['licence'])
        ) {
            $idPromotion = htmlspecialchars(trim($_POST['idPromotion']));
            $licence = htmlspecialchars(trim($_POST['licence']));
            $noteModel = new Note();
            $etudiantModel = new Etudiant();

            $resultat = $noteModel->getAllMoyenneLicenceEtudiants($idPromotion, $licence);
            $infosLicence = $resultat['infosLicence'];
            $moyennesLicence = $resultat['moyennesLicence'];
            $etudiants = $etudiantModel->trie_liste_etudiant($idPromotion);
            if (!empty($moyennesLicence)) {
                $this->view(
                    'post_liste_note_licence',
                    [
                        'infosLicence' => $infosLicence,
                        'moyennesLicence' => $moyennesLicence,
                        "etudiants" => $etudiants
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
}
