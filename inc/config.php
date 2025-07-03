<?php
    // with this code line, we create a database object. With the object we can user some sql request like insert,select,update or delete
    $pdo = new PDO('mysql:host=localhost;dbname=restokebab','root', '', 
    array( 
        PDO::ATTR_ERRMODE => PDO:: ERRMODE_WARNING,
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
    ));
?>