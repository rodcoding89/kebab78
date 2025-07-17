<?php
    function debug($variable){
        echo '<div style = "border: 1px solid orange">';
                    echo '<pre>';
                    print_r($variable);
                    echo '</pre>';
            echo '</div>';
    }
    /**this function check if a giving email respect the nomenclature about the email (check if a giving text is a email or not) */
    function verifyEmail($email){
        if (filter_var($email,FILTER_VALIDATE_EMAIL)) {
            return true;
        } else {
            return false;
        }
    }
    /**this function run select sql request.It give also back true if the request was successfuly executed or false if it meet a error  */
    function selectQuery($query, $marqueur = array()) {
        global $pdo;

        try {
            $resultat = $pdo->prepare($query);
            $resultat->execute($marqueur);
            return $resultat;
        } catch (PDOException $e) {
            die('Erreur lors de l\'exécution de la requête : ' . $e->getMessage());
        }
    }

    /**this function run sql request like insert,delete,update. It give also back true if the request was successfuly executed or false if it meet a error */
    function actionQuery($query,$marqueur = array()){
        foreach ($marqueur as $key => $value) {
            $marqueur[$key] = htmlspecialchars($value,ENT_QUOTES);
        }

        global $pdo;

        $resultat = $pdo->prepare($query);
        $success = $resultat->execute($marqueur);
        if ($success) {
            return $success;
        }else {
            die('Erreur produit lors de l\'execution de la requête');
        }
    }
    /**this function check if a user is connected */
    function isOn(){
        if (!empty($_SESSION['user'])) {
            return true;
        } else {
            return false;
        }
        
    }
    /**this function check that a particular user is connected. in this fall "admin" */
    function isAdminOn(){
        if (isOn() && $_SESSION['user']['statut'] == 'admin') {
            return true;
        } else {
            return false;
        }
        
    }

?>