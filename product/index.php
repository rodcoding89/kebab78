<?php 
    include_once '../inc/init.php';
    if(!isOn()){
        header('location:'.RACINE);
        exit;
    }
    $title = "Découvrez nos produits";
    include_once '../inc/header.php';
    $content = '';
    //$url = 'http://localhost/kebab/';
    $url = 'http://localhost/kebab-gare/';
    if (isset($_GET)) {
        $cid = $_GET['access'];
        $deliveryMode = $_GET['delivery'];
        if($deliveryMode == 'livraison'){
            $result = selectQuery("SELECT product_id,categorie,product_name,description,img_url,prix_avec_livraison FROM product WHERE categorie = :cid" ,array(
                ':cid' => $cid,
            ));
        }else{
            $result = selectQuery("SELECT product_id,categorie,product_name,description,img_url,prix_sans_livraison FROM product WHERE categorie = :cid" ,array(
                ':cid' => $cid
            ));
        }
        
        $content ='<div class="product-content"><div class="bloc-content in">';
        while ($product = $result->fetch(PDO::FETCH_ASSOC)) {
            $prix = 0;
            if (isset($product['prix_avec_livraison'])) {
                $prix = '<span class="prix">'.$product['prix_avec_livraison'].'€</span>';
            } else {
                $prix = '<span class="prix">'.$product['prix_sans_livraison'].'€</span>';
            }
            
            $content .= '<a href="'.RACINE.'extrat?p='.$product['product_id'].'&c='.$product['categorie'].'&delivery='.$deliveryMode.'"><div class="card h-100">';
            $content .='<img src="'.$url.$product['img_url'].'" class="card-img-top" alt="'.$product['product_name'].'">';
            $content .= '<div class="card-body"><h5 class="card-title">'.$product['product_name'].'</h5><p class="card-text">'.$product['description'].'</p>'.$prix.'</div>';
            $content .= '</div></a>';
        }
        $content .='</div></div>';
    }
?>

<div class="products"><a href="<?php echo RACINE.'categorie?'.$deliveryMode?>" class="back"><i class="fas fa-hand-point-left"></i> Retour</a><?php echo $content ?></div>

<?php
    include_once '../inc/footer.php';