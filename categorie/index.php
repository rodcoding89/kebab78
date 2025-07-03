<?php 
    include_once '../inc/init.php';
    if(!isOn()){
        header('location:'.RACINE);
        exit;
    }
    $title = "Découvrez nos catégorie";
    include_once '../inc/header.php';
?>

<div class="categ"><a href="<?php echo RACINE?>" class="back"><i class="fas fa-hand-point-left"></i> Retour</a></div>

<?php
    include_once '../inc/footer.php';