<?php 
    include_once '../inc/init.php';
    if(!isOn()){
        header('location:'.RACINE);
        exit;
    }
    $title = "Découvrez nos supplément";
    //$url = 'http://localhost/kebab/';
    $url = 'http://localhost/kebab-gare/';
    include_once '../inc/header.php';
    $content = '';
    $composition = '';
    $sauce = '';
    $legume = '';
    $viande = '';
    $poisson = '';
    $fromage = '';
    $CategorieNameC = '';
    $CategorieNameL = '';
    $CategorieNameV = '';
    $CategorieNameP = '';
    $CategorieNameF = '';
    $CategorieNameS = '';
    $autre = '';
    $otherArr = [];
    if (isset($_GET)) {
        $cid = $_GET['c'];
        $deliveryMode = $_GET['delivery'];
        $pid = $_GET['p'];
        $result = selectQuery("SELECT * FROM extrat WHERE categ_ref =:cid",array(
            ':cid' => $cid
        ));
        if($deliveryMode == 'livraison'){
            $resultat = selectQuery("SELECT product_id,categorie,product_name,description,img_url,prix_avec_livraison FROM product WHERE product_id = :pid" ,array(
                ':pid' => $pid
            ));
        }else{
            $resultat = selectQuery("SELECT product_id,categorie,product_name,description,img_url,prix_sans_livraison FROM product WHERE product_id = :pid" ,array(
                ':pid' => $pid
            ));
        }
        $product = $resultat->fetch(PDO::FETCH_ASSOC);
        $pprix = 0;
        $i = 0;
        if (isset($product['prix_avec_livraison'])) {
            $pprix = '<span>'.$product['prix_avec_livraison'].'<em>€</em></span>';
        } else {
            $pprix = '<span>'.$product['prix_sans_livraison'].'<em>€</em></span>';
        }
        while ($extrat = $result->fetch(PDO::FETCH_ASSOC)) {
           
            if (isset($extrat['extrat_categ']) && strpos(strtolower($extrat['extrat_categ']),'composition') !== false) {
                $prix = '';
                if(floatval($extrat['prix']) > 0.0){
                    $prix = '<span>'.$extrat['prix'].'<em>€</em></span>';
                }
                $CategorieNameC = $extrat['extrat_categ'];
                $composition .= '<div class="card h-100"><img src="'.$url.$extrat['img_url'].'" class="card-img-top" alt="'.$extrat['extrat_name'].'"><div class="card-body"><h5 class="card-title">'.$extrat['extrat_name'].'</h5>'.$prix.'<input type="radio" id="compo" checked></div></div>';
            }
            if(isset($extrat['extrat_categ']) && strpos(strtolower($extrat['extrat_categ']),'sauce') !== false){
                $prix = '';
                if(floatval($extrat['prix']) > 0.0){
                    $prix = '<span>'.$extrat['prix'].'<em>€</em></span>';
                }
                $CategorieNameS = $extrat['extrat_categ'];
                $sauce .= '<div class="card h-100"><input type="hidden" value="'.$extrat['extrat_categ'].'" id="c-name"><img src="'.$url.$extrat['img_url'].'" class="card-img-top" alt="'.$extrat['extrat_name'].'"><div class="card-body"><h5 class="card-title">'.$extrat['extrat_name'].'</h5>'.$prix.'<input type="radio" id="compo"></div></div>';
            }
            if(isset($extrat['extrat_categ']) && strpos(strtolower($extrat['extrat_categ']),'légume') !== false){
                $prix = '';
                if(floatval($extrat['prix']) > 0.0){
                    $prix = '<span>'.$extrat['prix'].'<em>€</em></span>';
                }
                $CategorieNameL = $extrat['extrat_categ'];
                $legume .= '<div class="card h-100"><input type="hidden" value="'.$extrat['extrat_categ'].'" id="l-name"><img src="'.$url.$extrat['img_url'].'" class="card-img-top" alt="'.$extrat['extrat_name'].'"><div class="card-body"><h5 class="card-title">'.$extrat['extrat_name'].'</h5>'.$prix.'<input type="radio" id="compo" checked></div></div>';
            }
            if(isset($extrat['extrat_categ']) && strpos(strtolower($extrat['extrat_categ']),'viande') !== false){
                $prix = '';
                if(floatval($extrat['prix']) > 0.0){
                    $prix = '<span>'.$extrat['prix'].'<em>€</em></span>';
                }
                $CategorieNameV = $extrat['extrat_categ'];
                $viande .= '<div class="card h-100"><input type="hidden" value="'.$extrat['extrat_categ'].'" id="v-name"><img src="'.$url.$extrat['img_url'].'" class="card-img-top" alt="'.$extrat['extrat_name'].'"><div class="card-body"><h5 class="card-title">'.$extrat['extrat_name'].'</h5>'.$prix.'<input type="radio" id="compo" checked></div></div>';
            }
            if(isset($extrat['extrat_categ']) && strpos(strtolower($extrat['extrat_categ']),'poisson') !== false){
                $prix = '';
                if(floatval($extrat['prix']) > 0.0){
                    $prix = '<span>'.$extrat['prix'].'<em>€</em></span>';
                }
                $CategorieNameP = $extrat['extrat_categ'];
                $poisson .= '<div class="card h-100"><input type="hidden" value="'.$extrat['extrat_categ'].'" id="p-name"><img src="'.$url.$extrat['img_url'].'" class="card-img-top" alt="'.$extrat['extrat_name'].'"><div class="card-body"><h5 class="card-title">'.$extrat['extrat_name'].'</h5>'.$prix.'<input type="radio" id="compo" checked></div></div>';
            }
            if(isset($extrat['extrat_categ']) && strpos(strtolower($extrat['extrat_categ']),'fromage') !== false){
                $prix = '';
                if(floatval($extrat['prix']) > 0.0){
                    $prix = '<span>'.$extrat['prix'].'<em>€</em></span>';
                }
                $CategorieNameF = $extrat['extrat_categ'];
                $fromage .= '<div class="card h-100"><input type="hidden" value="'.$extrat['extrat_categ'].'" id="f-name"><img src="'.$url.$extrat['img_url'].'" class="card-img-top" alt="'.$extrat['extrat_name'].'"><div class="card-body"><h5 class="card-title">'.$extrat['extrat_name'].'</h5>'.$prix.'<input type="radio" id="compo" checked></div></div>';
            }
            if(isset($extrat['extrat_categ']) && strpos(strtolower($extrat['extrat_categ']),'fromage') === false && strpos(strtolower($extrat['extrat_categ']),'poisson') === false && strpos(strtolower($extrat['extrat_categ']),'viande') === false && strpos(strtolower($extrat['extrat_categ']),'légume') === false && strpos(strtolower($extrat['extrat_categ']),'sauce') === false && strpos(strtolower($extrat['extrat_categ']),'composition') === false){
                $autre .= '<div class="card h-100"><input type="hidden" value="'.$extrat['extrat_categ'].'" id="f-name"><img src="'.$url.$extrat['img_url'].'" class="card-img-top" alt="'.$extrat['extrat_name'].'"><div class="card-body"><h5 class="card-title">'.$extrat['extrat_name'].'</h5>'.$prix.'<input type="radio" id="compo" checked></div></div>';
                $otherArr[i] = $autre;
                $i++;
            }
        }
        
    }
?>

<div class="extrat">
    <a href="<?php echo RACINE.'product?access='.$cid.'&delivery='.$deliveryMode?>" class="back"><i class="fas fa-hand-point-left"></i> Retour</a>
    <div class="extrat-content">
        <div class="bloc-content">
            <div class="top">
                <h4><?php echo $product['product_name'] ?></h4>
                <?php echo $pprix ?>
            </div>
            <div class="move">
                <div class="movebtn"><a href="<?php echo RACINE.'product?access='.$cid.'&delivery='.$deliveryMode?>"><p class="close"><i class="fas fa-times"></i>FERMER</p></a></div>
                <div class="cart"><a><p class="next">SUIVANT <i class="fas fa-chevron-right"></i></p></a></div>
            </div>
            <div class="elm">
                <div class="elm-content">
                    <div class="bloc-compo bloc">
                        <div class="bloc-item">
                            <h4 class="c-title"><?php echo $CategorieNameC;?></h4>
                            <div class="elm-bloc">
                                <?php  echo $composition?>
                            </div>
                        </div>
                    </div>
                    <div class="bloc-sauce bloc">
                        <div class="bloc-item">
                            <h4 class="s-title"><?php echo $CategorieNameS;?></h4>
                            <div class="elm-bloc1">
                                <?php  echo $sauce?>
                            </div>
                        </div>
                    </div>
                    <div class="elm-bloc2 bloc">
                        <div class="bloc-item">
                            <h4 class="l-title"><?php echo $CategorieNameL;?></h4>
                            <div class="bloc-item-content">
                                <?php  echo $legume?>
                            </div>
                        </div>
                        <div class="bloc-item">
                            <h4 class="v-title"><?php echo $CategorieNameV;?></h4>
                            <div class="bloc-item-content">
                                <?php  echo $viande?>
                            </div>
                        </div>
                        <div class="bloc-item">
                            <h4 class="p-title"><?php echo $CategorieNameP;?></h4>
                            <div class="bloc-item-content">
                                <?php  echo $poisson?>
                            </div>
                        </div>
                        <div class="bloc-item">
                            <h4 class="f-title"><?php echo $CategorieNameF;?></h4>
                            <div class="bloc-item-content">
                                <?php  echo $fromage?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
    include_once '../inc/footer.php';