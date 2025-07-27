<?php
class AiService {
    private string $pythonPath = "C:\\Users\\DELL\\AppData\\Local\\Programs\\Python\\Python313\\python.exe";
    private string $analyseScript = "C:\\xampp\\htdocs\\G_universite\\python\\analyse_ai.py";
    private string $chatScript = "C:\\xampp\\htdocs\\G_universite\\python\\chat_ai.py";

    // ✅ Pour la prévision (Stats)
    public function askAI(array $data, string $context = "stats"): string {
        try {
            $payload = json_encode([
                "context" => $context,
                "data" => $data
            ], JSON_UNESCAPED_UNICODE);

            if ($payload === false) {
                throw new Exception("Erreur encodage JSON");
            }

            $escapedPayload = escapeshellarg($payload);
            $cmd = "\"{$this->pythonPath}\" \"{$this->analyseScript}\" $escapedPayload 2>&1";

            error_log("Commande Python (analyse): $cmd");

            $output = shell_exec($cmd);

            if (!$output) {
                throw new Exception("Pas de réponse du script Python analyse");
            }

            return nl2br(trim($output));
        } catch (Exception $e) {
            error_log("Erreur AI analyse: " . $e->getMessage());
            return "⚠️ Service IA indisponible.";
        }
    }

// public function askChat(string $question): string {
//     try {
//         $payload = json_encode(["question" => $question], JSON_UNESCAPED_UNICODE);
//         if ($payload === false) {
//             throw new Exception("Erreur encodage JSON");
//         }

//         // ✅ Utiliser addslashes au lieu de escapeshellarg
//         $escapedPayload = '"' . addslashes($payload) . '"';

//         $cmd = "\"{$this->pythonPath}\" \"{$this->chatScript}\" $escapedPayload 2>&1";

//         error_log("Commande Python (chat): $cmd");

//         $output = shell_exec($cmd);

//         if (!$output) {
//             throw new Exception("Pas de réponse du script Python chat");
//         }

//         return trim($output); // ✅ Python renvoie un JSON valide
//     } catch (Exception $e) {
//         error_log("Erreur AI chat: " . $e->getMessage());
//         return json_encode(["response" => "⚠️ Service IA indisponible"], JSON_UNESCAPED_UNICODE);
//     }
// }

public function askChat(string $question): string {
    try {
        // Encoder la question en JSON
        $payload = json_encode(["question" => $question], JSON_UNESCAPED_UNICODE);
        if ($payload === false) {
            throw new Exception("Erreur encodage JSON");
        }

        // Préparer les pipes pour la communication
        $descriptorspec = [
            0 => ["pipe", "r"],  // stdin
            1 => ["pipe", "w"],  // stdout
            2 => ["pipe", "w"]   // stderr
        ];

        // Exécution du script Python
        $process = proc_open(
            "\"{$this->pythonPath}\" \"{$this->chatScript}\"",
            $descriptorspec,
            $pipes
        );

        if (!is_resource($process)) {
            throw new Exception("Impossible de lancer le processus Python");
        }

        // ✅ Envoyer la question en JSON dans stdin
        fwrite($pipes[0], $payload);
        fclose($pipes[0]);

        // ✅ Lire la sortie JSON du script
        $output = stream_get_contents($pipes[1]);
        fclose($pipes[1]);

        $errorOutput = stream_get_contents($pipes[2]);
        fclose($pipes[2]);

        $return_value = proc_close($process);

        if ($return_value !== 0) {
            throw new Exception("Erreur Python : $errorOutput");
        }

        if (!$output) {
            throw new Exception("Pas de réponse du script Python");
        }

        return trim($output); // ✅ Retour brut (JSON)
    } catch (Exception $e) {
        error_log("Erreur AI chat: " . $e->getMessage());
        return json_encode(["response" => "⚠️ Service IA indisponible"], JSON_UNESCAPED_UNICODE);
    }
}



}
