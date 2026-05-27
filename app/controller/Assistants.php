<?php

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;

class Assistants extends Controller
{
    public function chat()
    {
        header('Content-Type: application/json; charset=utf-8');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Methode non autorisee.']);
            return;
        }

        if (!isset($_SESSION['id_utilisateur'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Session expiree. Veuillez vous reconnecter.']);
            return;
        }

        if (!defined('GROQ_API_KEY') || trim(GROQ_API_KEY) === '') {
            http_response_code(503);
            echo json_encode([
                'error' => 'La cle Groq n est pas configuree. Ouvrez app/core/config.php et renseignez $groqApiKey.'
            ]);
            return;
        }

        $payload = json_decode(file_get_contents('php://input'), true);
        $message = trim($payload['message'] ?? '');
        $history = is_array($payload['history'] ?? null) ? $payload['history'] : [];
        $pageContext = is_array($payload['context'] ?? null) ? $payload['context'] : [];

        if ($message === '') {
            http_response_code(422);
            echo json_encode(['error' => 'Veuillez saisir une question.']);
            return;
        }

        $messages = array_merge(
            [['role' => 'system', 'content' => $this->buildSystemPrompt($pageContext)]],
            $this->sanitizeHistory($history),
            [['role' => 'user', 'content' => mb_substr($message, 0, 1200)]]
        );

        try {
            $client = new Client([
                'base_uri' => 'https://api.groq.com/openai/v1/',
                'timeout' => 30,
            ]);

            $response = $client->post('chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer ' . GROQ_API_KEY,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'model' => defined('GROQ_MODEL') ? GROQ_MODEL : 'llama3-8b-8192',
                    'messages' => $messages,
                    'temperature' => 0.35,
                    'max_tokens' => 700,
                ],
            ]);

            $data = json_decode((string) $response->getBody(), true);
            $answer = trim($data['choices'][0]['message']['content'] ?? '');

            echo json_encode([
                'answer' => $answer ?: 'Je n ai pas pu generer une reponse pour le moment.',
            ]);
        } catch (RequestException $e) {
            $message = 'Le service IA est momentanement indisponible.';
            if ($e->hasResponse()) {
                $status = $e->getResponse()->getStatusCode();
                $body = json_decode((string) $e->getResponse()->getBody(), true);
                $groqMessage = $body['error']['message'] ?? '';
                $message = trim("Groq a retourne une erreur {$status}. {$groqMessage}");
            }

            http_response_code(502);
            echo json_encode([
                'error' => $message ?: 'Le service IA est momentanement indisponible. Veuillez reessayer dans un instant.',
            ]);
        } catch (GuzzleException $e) {
            http_response_code(502);
            echo json_encode([
                'error' => 'Le service IA est momentanement indisponible. Veuillez reessayer dans un instant.',
            ]);
        }
    }

    private function sanitizeHistory(array $history): array
    {
        $clean = [];
        foreach (array_slice($history, -8) as $item) {
            $role = $item['role'] ?? '';
            $content = trim($item['content'] ?? '');

            if (!in_array($role, ['user', 'assistant'], true) || $content === '') {
                continue;
            }

            $clean[] = [
                'role' => $role,
                'content' => mb_substr($content, 0, 900),
            ];
        }

        return $clean;
    }

    private function buildSystemPrompt(array $pageContext): string
    {
        $role = $_SESSION['role'] ?? 'Utilisateur';
        $pageTitle = $pageContext['title'] ?? 'Page inconnue';
        $pagePath = $pageContext['path'] ?? '';

        return "Tu es G_universiteBot, l assistant conversationnel integre a G_UNIVERSITE.
Mission: aider les utilisateurs a comprendre et utiliser l application de gestion scolaire G_UNIVERSITE.
Style: professionnel, pedagogue, bienveillant, clair et concis. Reponds dans la langue de l utilisateur, en francais par defaut.
Contexte utilisateur: role actuel = {$role}. Page actuelle = {$pageTitle}. Chemin = {$pagePath}.
Fonctionnalites connues: tableau de bord, etudiants, inscriptions, reinscriptions, filieres, departements, modules, semestres, promotions, enseignants, salles, periodes, notes, releves, emplois du temps, apercus, exports PDF et listes.
Regles:
- Donne des etapes pratiques adaptees a la page et au role lorsque c est possible.
- Reste dans le contexte de G_UNIVERSITE et de la gestion scolaire.
- Si la question est hors sujet, refuse poliment et ramene vers l aide sur G_UNIVERSITE.
- Ne demande jamais de mot de passe, de cle API ou de donnee sensible.
- Si tu n es pas certain d une action exacte, propose une demarche prudente et invite l utilisateur a verifier les libelles visibles a l ecran.";
    }
}
