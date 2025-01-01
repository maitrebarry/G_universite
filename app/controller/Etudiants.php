<?php
class Etudiants extends Controller
{
    public function index()
    {
        $this->view('liste_incrit');
    }

    public function incrit_etudiant() {
        $this->view('incription');
    }

}

