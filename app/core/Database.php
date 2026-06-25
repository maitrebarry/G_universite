<?php
/**
* Created by PhpStorm.
* User: SNT
* Date: 21/11/2022
* Time: 12:24
*/

// Session durcie : cookie httpOnly (anti-vol via XSS) + SameSite=Lax (anti-CSRF)
// + Secure uniquement en HTTPS (sinon le cookie ne passerait pas en local http).
if (session_status() === PHP_SESSION_NONE) {
    $guHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'httponly' => true,
        'samesite' => 'Lax',
        'secure'   => $guHttps,
    ]);
    session_start();
}

class Database {
    private ?PDO $database = null;

    public function connect () {
        $dsn = DBDRIVER . ':host=' . DBHOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
        try {
            return new PDO($dsn, DB_USERNAME, DB_PASSWORD, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]);
        } catch (PDOException $e) {
            error_log('Echec de connexion BDD: ' . $e->getMessage());
            die('Echec de connexion a la base de donnees.');
        }
    }

    /*Parties
    sur les insertions
    * */

    public function bdd() {

        if ( $this->database == null ) {
            $this->database = $this->connect();
        }
        return $this->database;
    }
    // fin des fonction de sseclect
    //function pour verification de l'utilisateur

}