<?php 
    include_once '../inc/init.php';
    if(!isOn()){
        header('location:'.RACINE);
        exit;
    }
    $title = "Découvrez nos produits";
    include_once '../inc/header.php';
    $content = '';
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
        $categ = selectQuery("SELECT categorie_name FROM categorie WHERE categorie_id =:cid",array(
            ":cid" => $cid
        ));
        $categResult = $categ->fetch(PDO::FETCH_ASSOC);
        
        $products = $result->fetchAll(PDO::FETCH_ASSOC);
        if (count($products) > 0) {
            $content ='<div class="product-content"><h2 style="text-align:center;margin-bottom:20px;">'.ucfirst($categResult['categorie_name']).'</h2><div class="bloc-content in">';
            foreach ($products as $product) {
                $prix = 0;
                if (isset($product['prix_avec_livraison'])) {
                    $prix = '<span class="prix">'.$product['prix_avec_livraison'].'€</span>';
                } else {
                    $prix = '<span class="prix">'.$product['prix_sans_livraison'].'€</span>';
                }
                
                $content .= '<a href="'.RACINE.'extrat?p='.$product['product_id'].'&c='.$product['categorie'].'&delivery='.$deliveryMode.'"><div class="card h-100">';
                $content .='<img src="'.RACINE.$product['img_url'].'" class="card-img-top" alt="'.$product['product_name'].'">';
                $content .= '<div class="card-body"><h5 class="card-title">'.$product['product_name'].'</h5><p class="card-text">'.$product['description'].'</p>'.$prix.'</div>';
                $content .= '</div></a>';
            }
            $content .='</div></div>';
        }else{
            $content = "<div style='min-height:250px;width:100%;display:flex;align-items:center;justify-content:center;font-size:23px;font-weight:bold;'>Aucun produit correspondant à cette catégorie n'est enregistré pour le moment.</div>";
        }
    }
?>

<div class="products"><?php echo $content ?></div>

<?php
    include_once '../inc/footer.php';