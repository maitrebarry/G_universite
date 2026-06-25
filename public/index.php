<?php
/*
|--------------------------------------------------------------------------
| Point d'entrée PUBLIC (front controller)
|--------------------------------------------------------------------------
| Permet de faire pointer la racine web (DocumentRoot) directement sur le
| dossier /public — configuration RECOMMANDÉE en production : les dossiers
| app/ et vendor/ restent alors hors de la racine web (non accessibles).
|
| Fonctionne aussi quand on accède à l'app via .../G_universite/public/
| (le .htaccess réécrit toutes les routes vers ce fichier).
*/
date_default_timezone_set('Africa/Abidjan');

// Les chemins internes de l'application sont relatifs à la RACINE du projet.
chdir(dirname(__DIR__));

require_once dirname(__DIR__) . '/app/core/autoload.php';

$app = new App();
