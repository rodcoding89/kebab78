<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    // we start the session as globaly
    session_start();
    // we define a root path for the application
    // we include the functions file. Inside the file, we can find all function usualy for the project
    include_once 'functions.php';
    // we include the config file. this handle the database connection
    include_once 'config.php';
    define('RACINE',$env == 'dev' ? '/Kebab78/' : '/');
    define('NODE_ENV',$env);
    Header('Access-Control-Allow-Origin: *'); //for allow any domain, insecure
    Header('Access-Control-Allow-Headers: *'); //for allow any headers, insecure
    Header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');
?>