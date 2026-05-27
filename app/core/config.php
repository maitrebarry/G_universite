<?php
/*
|--------------------------------------------------------------------------
| Configuration d'hebergement
|--------------------------------------------------------------------------
| En production, configurez uniquement APP_URL.
|
| Exemples:
| - Local XAMPP:     http://localhost:8080/G_universite/public
| - Domaine /public: https://monsite.com/G_universite/public
| - Domaine pointe directement vers public: https://monsite.com
|
| Laissez vide pour une detection automatique raisonnable.
*/
$appUrl = "";

function detect_app_url()
{
    $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
    $scheme = $https ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
    $scriptDir = trim($scriptDir, '/');

    if ($scriptDir === '') {
        return $scheme . '://' . $host;
    }

    if (substr($scriptDir, -6) !== 'public') {
        $scriptDir .= '/public';
    }

    return $scheme . '://' . $host . '/' . trim($scriptDir, '/');
}

define("ROOT", rtrim($appUrl ?: detect_app_url(), '/'));
define("DB_NAME","db_universite");
define("DBHOST", "localhost");
define("DB_USERNAME", "root");
define("DB_PASSWORD", '');
define("DBDRIVER", 'mysql');
define("PUBLIC_PATH", realpath(__DIR__ . '/../../public') ?: __DIR__ . '/../../public');

// Collez votre cle Groq ici pour l'utilisation locale avec XAMPP.
// Exemple: $groqApiKey = "gsk_xxxxxxxxxxxxxxxxx";
$groqApiKey = "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";

define("GROQ_API_KEY", $groqApiKey ?: getenv("GROQ_API_KEY") ?: "");
define("GROQ_MODEL", getenv("GROQ_MODEL") ?: "llama-3.1-8b-instant");
