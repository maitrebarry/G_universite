<?php
/*
|--------------------------------------------------------------------------
| Configuration de l'application
|--------------------------------------------------------------------------
| En LOCAL (XAMPP) : rien à modifier, les valeurs par défaut suffisent.
|
| En PRODUCTION : ne modifiez PAS ce fichier. Définissez les identifiants
| soit via des VARIABLES D'ENVIRONNEMENT, soit (plus simple sur un
| hébergement mutualisé) via le fichier  app/core/config.local.php
| (non versionné). Exemple de config.local.php :
|
|     <?php
|     $dbHost = "localhost";
|     $dbName = "mon_compte_db";
|     $dbUser = "mon_compte_user";
|     $dbPass = "motdepasse";
|     $appUrl = "https://mon-domaine.com";   // optionnel (sinon détection auto)
|     $groqApiKey = "gsk_xxx";               // optionnel
|
| Astuce déploiement : faites pointer la racine web (DocumentRoot) sur le
| dossier /public pour que app/ et vendor/ restent hors du web.
*/

// ----- Gestion des erreurs : masquées en production, visibles si GU_APP_DEBUG=1 -----
$appDebug = (getenv('GU_APP_DEBUG') === '1');
ini_set('display_errors', $appDebug ? '1' : '0');
ini_set('display_startup_errors', $appDebug ? '1' : '0');
ini_set('log_errors', '1');
error_reporting(E_ALL);

// ----- Valeurs par défaut (XAMPP local) -----
$appUrl   = "";              // vide = détection automatique (recommandé)
$dbHost   = "localhost";
$dbName   = "db_universite";
$dbUser   = "root";
$dbPass   = "";
$dbDriver = "mysql";
$groqApiKey = "";
$groqModel  = "llama-3.1-8b-instant";

// ----- Surcharges par variables d'environnement (hébergeurs type plateforme) -----
// IMPORTANT : préfixe GU_ spécifique à l'app pour ÉVITER toute collision avec
// d'autres applications de la machine (ex. un autre projet qui définit DB_PASSWORD).
$appUrl   = getenv('GU_APP_URL')  ?: $appUrl;
$dbHost   = getenv('GU_DB_HOST')  ?: $dbHost;
$dbName   = getenv('GU_DB_NAME')  ?: $dbName;
$dbUser   = getenv('GU_DB_USER')  ?: $dbUser;
if (getenv('GU_DB_PASSWORD') !== false) { $dbPass = getenv('GU_DB_PASSWORD'); }
$groqApiKey = getenv('GU_GROQ_API_KEY') ?: $groqApiKey;
$groqModel  = getenv('GU_GROQ_MODEL')   ?: $groqModel;

// ----- Surcharges par fichier local (NON versionné, PRIORITAIRE) -----
$localConfig = __DIR__ . "/config.local.php";
if (is_readable($localConfig)) {
    require $localConfig; // peut redéfinir $dbHost, $dbName, $dbUser, $dbPass, $appUrl, $groqApiKey…
}

// ----- Détection automatique de l'URL de base (si $appUrl est vide) -----
function detect_app_url()
{
    $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
    $scheme = $https ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
    $scriptDir = trim($scriptDir, '/');

    if ($scriptDir === '') {
        // DocumentRoot pointe directement sur /public (ou domaine racine)
        return $scheme . '://' . $host;
    }

    // Si on est servi depuis le dossier projet (…/G_universite), les assets
    // et le routage vivent sous /public.
    if (substr($scriptDir, -6) !== 'public') {
        $scriptDir .= '/public';
    }

    return $scheme . '://' . $host . '/' . trim($scriptDir, '/');
}

// ----- Constantes -----
define("ROOT", rtrim($appUrl ?: detect_app_url(), '/'));
define("DB_NAME", $dbName);
define("DBHOST", $dbHost);
define("DB_USERNAME", $dbUser);
define("DB_PASSWORD", $dbPass);
define("DBDRIVER", $dbDriver);
define("PUBLIC_PATH", realpath(__DIR__ . '/../../public') ?: __DIR__ . '/../../public');
define("GROQ_API_KEY", $groqApiKey ?: "");
define("GROQ_MODEL", $groqModel);
