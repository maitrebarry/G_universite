<?php

// class Controller
// {
//    function load_model($model){

//       if(file_exists("app/models/".ucfirst($model).".php")){
//           require_once("app/models/".ucfirst($model).".php");
//           return  new $model();
//       }
//       return  false;
//    }
//    function view($view,$data=[]){
//        if(file_exists("app/views/".ucfirst($view).".view.php")){
//            extract($data);
//            require_once("app/views/".ucfirst($view).".view.php");
//        }else{
//            require_once("app/views/404.view.php");
//        }
//    }
//    public  function redirect($page)
//    {
//        header("Location:".ROOT."/".trim($page,"/"));
//        exit();
//    }

// }





class Controller
{
    function load_model($model)
    {
        if (file_exists("app/models/" . ucfirst($model) . ".php")) {
            require_once("app/models/" . ucfirst($model) . ".php");
            return new $model();
        }
        return false;
    }

    public function view($view, $data = [])
    {
        // notifications dispo dans toutes les vues
        $data['notifications'] = $this->getNotifications();

        $filename = "app/views/" . $view . ".view.php";
        if (file_exists($filename)) {
            extract($data);
            require $filename;
        } else {
            var_dump("Vue introuvable : " . $filename);
            exit;
        }
    }

    public function redirect($page)
    {
        header("Location:" . ROOT . "/" . trim($page, "/"));
        exit();
    }

    private function getNotifications()
    {
        if (!isset($_SESSION['enseignant_id'])) {
            return [];
        }

        $model = $this->load_model('Emploi_du_temp');
        $sql = "SELECT e.id_edt, e.date_debut, e.date_fin, m.nom_module, s.nom_salle
                FROM edt e
                JOIN module m ON m.id_module = e.id_module
                JOIN salle s ON s.id_salle = e.id_salle
                JOIN enseignant_edt ee ON ee.id_edt = e.id_edt
                WHERE ee.id_enseignant = ? AND e.date_debut >= ?
                ORDER BY e.date_debut ASC
                LIMIT 5";

        $params = [
            $_SESSION['enseignant_id'],
            date('Y-m-d')
        ];

        return $model->select_data_table_join_where($sql, $params);
    }
}

