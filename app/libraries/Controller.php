<?php
    /**
     * Controleur de base, qui charge les modeles et les vues
     */
    class Controller{
        //Charger le model
        public function model($model){
            # Charger le fichier du modèle
            require_once '../app/models/' . $model . '.php';
            return new $model(); 
        }

        //Charger la vue
        public function view($view, $data = []){
            # tester si le fichier existe
            if (file_exists('../app/views/' . $view . '.php')) {
                if(isset($data)){
                    extract($data);
                }
                require_once '../app/views/' . $view . '.php';
            }else{
                //Le fichier de la vue n'existe pas
                die('La vue n\'existe pas');
            }
        }

        public function redirectToMethod($controller, $method="index",$params=""){
            if (empty($params))
                header("Location:".URLROOT."/".$controller.'/'.$method);
            else
                header("Location:".URLROOT."/".$controller.'/'.$method."/".$params);
        }
    }