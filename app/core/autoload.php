<?php
// require_once ("config.php");
// require_once ("Database.php");
// require_once ("Controller.php");
// require_once ("Model.php");
// require_once ("App.php");
// require_once("AiService.php");
// spl_autoload_register(function ($class) {
//     require_once("app/models/".ucfirst($class).".php");
   

// });

require_once __DIR__ . "/config.php";
require_once __DIR__ . "/Database.php";
require_once __DIR__ . "/Controller.php";
require_once __DIR__ . "/Model.php";
require_once __DIR__ . "/App.php";
require_once __DIR__ . "/statuts.php";
// require_once __DIR__ . "/AiService.php";


// ✅ Ajout pour Guzzle et autres libs via Composer
require_once __DIR__ . '/../../vendor/autoload.php';

spl_autoload_register(function ($class) {
   $path = __DIR__ . "/../models/" . ucfirst($class) . ".php";

    if (file_exists($path)) {
        require_once($path);
    }
});

