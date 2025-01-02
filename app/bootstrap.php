<?php
    //Charger le fichier de configuration
    require_once "config/config.php";
    require_once "libraries/Utils.php";
    require_once "libraries/SMTP.php";
    require_once 'libraries/PHPMailer.php';
    //charger le librairies
    // require_once "libraries/Core.php";
    // require_once "libraries/Controller.php";
    // require_once "libraries/Database.php";

    //Autoload Core libraries
    spl_autoload_register(function($className){
        require_once "libraries/"  .$className. ".php";
    });