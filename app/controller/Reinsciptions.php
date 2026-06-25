<?php
class Reinsciptions extends Controller
{
    public function index()
    {
        $promotions = (new Reinscription())->toutesPromotions();
        $this->view('reinscription', ['promotions' => $promotions]);
    }

    public function get_etudiants()
    {
        if (isset($_POST["id_promotion"]) && !empty($_POST["id_promotion"])) {
            $idPromotion = (int) $_POST["id_promotion"];
            $reinscription = new Reinscription();
            $idParcours = $reinscription->getParcours($idPromotion);
            // getAllMoyenneSemestreEtudiants fournit l'étudiant + son statut de validation (isValidate) + moyenne.
            $resultat = (new Note())->getAllMoyenneSemestreEtudiants($idPromotion, $idParcours);
            $this->view('post_reinscription', [
                'moyennesSemestre' => $resultat['moyennesSemestre']
            ]);
            return;
        }
    }

    // Réinscrit les étudiants sélectionnés dans la classe suivante choisie.
    public function reinscrire()
    {
        header('Content-Type: application/json');
        $etudiants = $_POST['etudiants'] ?? [];
        $idNewPromotion = $_POST['id_new_promotion'] ?? null;
        if (!is_array($etudiants)) $etudiants = array_filter([$etudiants]);

        if (empty($etudiants) || empty($idNewPromotion)) {
            echo json_encode(['ok' => false, 'error' => 'Veuillez sélectionner au moins un étudiant et la classe suivante.']);
            return;
        }

        $idOldPromotion = $_POST['id_old_promotion'] ?? null;
        $res = (new Reinscription())->reinscrire($etudiants, $idNewPromotion, $idOldPromotion);
        if (isset($res['erreur'])) {
            echo json_encode(['ok' => false, 'error' => $res['erreur']]);
            return;
        }
        echo json_encode(['ok' => true, 'faits' => $res['faits'], 'deja' => $res['deja'], 'refuses' => $res['refuses'] ?? 0]);
    }

    // Renvoie les IDs déjà inscrits dans une promotion (pour marquer la liste).
    public function deja_inscrits()
    {
        header('Content-Type: application/json');
        if (empty($_POST['id_promotion'])) { echo json_encode([]); return; }
        echo json_encode((new Reinscription())->dejaInscrits($_POST['id_promotion']));
    }
}