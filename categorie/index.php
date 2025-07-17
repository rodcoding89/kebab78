<?php 
    include_once '../inc/init.php';
    if(!isOn()){
        header('location:'.RACINE);
        exit;
    }
    $title = "Découvrez nos catégorie";
    include_once '../inc/header.php';
?>

<div class="categ"></div>

<?php
    include_once '../inc/footer.php';