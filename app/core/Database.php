<?php
/**
* Created by PhpStorm.
* User: SNT
* Date: 21/11/2022
* Time: 12:24
*/
session_start();

class Database {
    private ?PDO $database = null;

    public function connect () {
        if ( !$bdd = new PDO( 'mysql:host=' . DBHOST . ';dbname=' . DB_NAME, DB_USERNAME, DB_PASSWORD ) ) {

            die( 'echec de connection a la bas de données' );
        }
        ;
        return $bdd;
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