<?php
/*
|--------------------------------------------------------------------------
| Redirection vers le front controller public/
|--------------------------------------------------------------------------
| Le routage, les assets et la PWA vivent sous /public (le périmètre du
| service worker = /public/). En redirigeant .../G_universite/ vers
| .../G_universite/public/, on garantit que l'app est toujours "installable"
| (le bouton d'installation PWA n'apparaît que dans le périmètre du SW).
|
| En production, faites plutôt pointer le DocumentRoot directement sur /public
| (ce fichier ne sera alors jamais utilisé).
*/
$base = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
$rawUrl = isset($_GET['url']) ? preg_replace('/[\x00-\x1F]/', '', (string) $_GET['url']) : '';
$path = $rawUrl !== '' ? '/' . ltrim($rawUrl, '/') : '/';

header('Location: ' . $base . '/public' . $path, true, 302);
exit;
