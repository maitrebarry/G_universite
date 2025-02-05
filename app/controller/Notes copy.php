<?php

class Notes extends Controller {
    
   public function index() {
        echo 'Controleur Notes appelé.<br>';//message de debogage
        $noteModel = new Note();
        $filiereModel = new Filiere();
        $etudiantModel = new Etudiant();
        $etudiant=[];
        if(!isset($_GET['promotions'])){
            echo "Parametre promotion non définie dans l'URL.<br>";
            $this->view('error_page', ['message' => "promotion nom spécifiée on invalide."]);
            return;
        }
        if(! is_numeric($_GET['promotions'])){
            echo "Parametre promotion n'est pas un nombre.<br>";
            $this->view('error_page', ['message' => "promotion nom spécifiée on invalide."]);
            return;
        }
        if(intval($_GET['promotions']) <=0){
            echo "Parametre promotion n'est pas un nombre.<br>";
            $this->view('error_page', ['message' => "promotion nom spécifiée on invalide."]);
            return;
        }

        $promotion = intval($_GET['promotions']);
        echo "promotion : ". $promotion;
        //Flitrage des étudiants par la promotion
        $etudiants = $etudiantModel->trie_liste_etudiant($promotion);
        if($etudiants){
        // Rendu de la vue avec les étudiants
        var_dump($etudiants); exit;
        $this->view('ajouter_notes', ['etudiant' => $etudiants]);
        exit("Vue ajouter_notes appelée");
        }else {
        echo "Aucun étudiant trouvé pour cette promotions";
        $this->view('error_page',['message'=>"Aucun étudiant trouvé pour cette promotions"]);
        return;
        }
    }
}
// public function trie_liste_etudiant($promotion){
//     // Message de débogage pour verifier la requête
//     echo "Promotion reçue pour la trie_liste_etudiant: " . $promotion;
//     try{
//         // Requête SQL pour recuperer les etudiants par promotion
//         $query="SELECT * FROM etudiants WHERE id_promotion = ?";
//         $stmt = $this->bdd->prepare($query);
//         $stmt->execute([$promotion]);
//         $etudiants = $stmt->fetchAll(PDO::FETCH_ASSOC);
//         if(!$etudiants){
//             throw new Exception("Aucun étudiant trouvé pour la promotions" . $promotion)
//         }
//         // Mesage de débogage pour vérivier les resultat
//         //var_dump($etudiants); 
//         return $etudiants;
//     }catch(Exception $e){
//         echo "Erreur : ". $e->getMessage();
//         return[];
//     }
    
// }
    

    // public function store() {
    //     if ($_SERVER["REQUEST_METHOD"] === "POST") {
    //         // Vérifier si tous les champs obligatoires sont remplis
    //         if (!empty($_POST['id_etudiant']) && isset($_POST['note_devoir'], $_POST['note_evaluation'])) {
                
    //             $etudiant_id = intval($_POST['id_etudiant']);
    //             $devoir = floatval($_POST['note_devoir']);
    //             $evaluation = floatval($_POST['note_evaluation']);
    //             $note_session = isset($_POST['note_session']) ? floatval($_POST['note_session']) : null;

    //             // Vérification des notes (elles doivent être comprises entre 0 et 20)
    //             if ($devoir < 0 || $devoir > 20 || $evaluation < 0 || $evaluation > 20 || ($note_session !== null && ($note_session < 0 || $note_session > 20))) {
    //                 header("Location: /notes?error=invalid_note");
    //                 exit();
    //             }

    //             $noteModel = new Note();

    //             // Enregistrement de la note
    //             if ($noteModel->saveNote($etudiant_id, $devoir, $evaluation, $note_session)) {
    //                 header("Location: /notes?success=1");
    //                 exit();
    //             } else {
    //                 header("Location: /notes?error=1");
    //                 exit();
    //             }
    //         } else {
    //             header("Location: /notes?missing_fields=1");
    //             exit();
    //         }
    //     }
    // }
/*
    public function saveNotes() {
        // Vérifie si une requête POST a été envoyée et si des notes sont présentes
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['notes'])) {
            $noteModel = new Note();

            // Validation des données
            $notes = $_POST['notes'];
            foreach ($notes as $note) {
                if (!isset($note['id_etudiant'], $note['note_devoir'], $note['note_evaluation'])) {
                    echo json_encode(["status" => "error", "message" => "Données manquantes pour certains étudiants."]);
                    exit();
                }

                $note['id_etudiant'] = intval($note['id_etudiant']);
                $note['note_devoir'] = floatval($note['note_devoir']);
                $note['note_evaluation'] = floatval($note['note_evaluation']);
                $note['note_session'] = isset($note['note_session']) ? floatval($note['note_session']) : null;

                // Validation des notes
                if ($note['note_devoir'] < 0 || $note['note_devoir'] > 20 || 
                    $note['note_evaluation'] < 0 || $note['note_evaluation'] > 20 ||
                    ($note['note_session'] !== null && ($note['note_session'] < 0 || $note['note_session'] > 20))) {
                    
                    echo json_encode(["status" => "error", "message" => "Une ou plusieurs notes sont invalides."]);
                    exit();
                }
            }

            // Sauvegarde des notes en base de données
            if ($noteModel->saveNotes($notes)) {
                echo json_encode(["status" => "success", "message" => "Notes enregistrées avec succès !"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Une erreur s'est produite lors de l'enregistrement."]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "Aucune donnée reçue."]);
        }
    }*/
?>
