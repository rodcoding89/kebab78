<?php
    // we start the session as globaly
    session_start();
    // we define a root path for the application
    //define("RACINE","/kebab/");
    define("RACINE","/Kebab78/");
    // we include the functions file. Inside the file, we can find all function usualy for the project
    include_once 'functions.php';
    // we include the config file. this handle the database connection
    include_once 'config.php';
    Header('Access-Control-Allow-Origin: *'); //for allow any domain, insecure
    Header('Access-Control-Allow-Headers: *'); //for allow any headers, insecure
    Header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');
?>