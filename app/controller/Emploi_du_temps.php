<?php
class Emploi_du_temps extends Controller
{
    public function index()
    {

        $this->view('liste_EDT');
    }



    public function ajouter_EDT()
    {
        $this->view("ajouter_EDT");
    }
}
