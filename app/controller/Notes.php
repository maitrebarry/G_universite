<?php
class Notes extends Controller
{
    public function index()
    {
        //$etudianttModel = new Note();  
        // $etudiants = $etudianttModel->listeEtudiant();
        $filiereModel = new Filiere();
        $filieres = $filiereModel->SelectAllData("*", "filiere");
        $this->view('ajouter_notes', ['filieres' => $filieres]);
    }
    //Function pour obtenir toutes les notes
    public function get_note_etudiant()
    {       //Etpa 1: Vérification de la des idPromoton et idModule pour voir leur validité
        if (isset($_POST['idPromotion'], $_POST['idModule']) && !empty($_POST['idPromotion']) && !empty($_POST['idModule'])) {
            //Etape 2: Récupération des idPromotion et idModule
            $idPromotion = $_POST['idPromotion'];
            $idModule = $_POST['idModule'];

            //Etape 3: instantiation de la classe Note
            $noteModel = new Note();
            //Etape 4: La récupération de toutes les notes selon idPromotion et l'idModule
            $note_des_etudiants = $noteModel->getAllNotesEtudiant($idPromotion, $idModule);
            //Les informations du module sélectionner
            $infosModule = $noteModel->getInfoModule($idModule);
            // var_dump($infosModule); exit;
            //Etape 5 : Debogagevar_dump($etudiants); //post_liste_note est un fichier qui gère l'affichage qui se trouve dans le view
            if (!empty($note_des_etudiants)) {
                # cod //Etape 6: Passage des donnée à la view depuis la le fichier post_liste_note.php qui est responsable de l'affichage dynamique dans notre cas
                $this->view('post_liste_note', ['note_des_etudiants' => $note_des_etudiants, "infosModule" => $infosModule]); //Pour afficher la listes des étudiant en arriere plan par ajax
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
            //echo"Les données sont bien reçues";


            //Appel de la fonction de modification de la note
            $noteModel = new Note();
            $note_modifiee = $noteModel->save_note_etudiant($idNote, $devoir, $evaluation, $session);
            if (!$note_modifiee) {
                $this->view("set_flash");
            }
        } else {
            echo "Les données sont en attente";
        }
    }

    public function infosModule() {}
}