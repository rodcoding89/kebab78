<?php 
    require_once '../inc/init.php';
    if(!isAdminOn()){
        header('location:'.RACINE);
        exit;
    }
    $title = "Gestion du restaurant";

    require_once 'nav.php';
?>
    <div class="content">
        
    </div>
<?php 
    require_once 'footer.php';