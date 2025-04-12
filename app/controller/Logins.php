<?php

class Logins extends Controller {
    public function index() {
         $perso = new Login();
        if (isset($_POST["submit"])) {
            //echo "ok";exit;
            $perso->connecter([ "pwd", "email"]);
            
        }
        $this->view("login");
    } 
}
?>
