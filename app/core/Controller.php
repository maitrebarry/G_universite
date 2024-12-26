<?php

class Controller
{
   function load_model($model){

      if(file_exists("app/models/".ucfirst($model).".php")){
          require_once("app/models/".ucfirst($model).".php");
          return  new $model();
      }
      return  false;
   }
   function view($view,$data=[]){
       if(file_exists("app/views/".ucfirst($view).".view.php")){
           extract($data);
           require_once("app/views/".ucfirst($view).".view.php");
       }else{
           require_once("app/views/404.view.php");
       }
   }


}