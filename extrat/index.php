<?php 
    include_once '../inc/init.php';
    if(!isOn()){
        header('location:'.RACINE);
        exit;
    }
    $title = "Découvrez nos supplément";
    include_once '../inc/header.php';
    $content = '';
    $composition = '';
    $sauce = '';
    $burger = '';
    $viande = '';
    $sandwichs = '';
    $boisson = '';
    $dessert = '';
    $CategorieNameC = '';
    $CategorieNameB = '';
    $CategorieNameV = '';
    $CategorieNameP = '';
    $CategorieNameD = '';
    $CategorieNameS = '';
    $CategorieNameSW = '';
    $CategorieNameBG = '';
    $autre = '';
    $otherArr = [];
    if (isset($_GET)) {
        $cid = $_GET['c'];
        echo $cid;
        $deliveryMode = $_GET['delivery'];
        $pid = $_GET['p'];
        $cartPrice = 0;
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
            $cartPrice = $product['prix_avec_livraison'];
        } else {
            $pprix = '<span>'.$product['prix_sans_livraison'].'<em>€</em></span>';
            $cartPrice = $product['prix_avec_livraison'];
        }
        while ($extrat = $result->fetch(PDO::FETCH_ASSOC)) {
            //debug($extrat);
            if (isset($extrat['extrat_categ']) && $extrat['extrat_categ'] === 'composition') {
                $prix = '';
                if(floatval($extrat['prix']) > 0.0){
                    $prix = '<span>'.$extrat['prix'].'<em>€</em></span>';
                }
                $CategorieNameC = $extrat['extrat_categ'];
                $composition .= '<div class="card align-self-stretch h-100"><img src="'.RACINE.$extrat['img_url'].'" class="card-img-top" alt="'.$extrat['extrat_name'].'"><div class="card-body"><h5 class="card-title">'.$extrat['extrat_name'].'</h5>'.$prix.'<input type="checkbox" id='.str_replace(" ", "-", strtolower($extrat['extrat_name'])).'></div></div>';
            }
            if(isset($extrat['extrat_categ']) && $extrat['extrat_categ'] === 'sauce'){
                $prix = '';
                if(floatval($extrat['prix']) > 0.0){
                    $prix = '<span>'.$extrat['prix'].'<em>€</em></span>';
                }
                $CategorieNameS = $extrat['extrat_categ'];
                $sauce .= '<div class="card align-self-stretch h-100"><input type="hidden" value="'.$extrat['extrat_categ'].'" id="c-name"><img src="'.RACINE.$extrat['img_url'].'" class="card-img-top" alt="'.$extrat['extrat_name'].'"><div class="card-body"><h5 class="card-title">'.$extrat['extrat_name'].'</h5>'.$prix.'<input type="checkbox" id='.str_replace(" ", "-", strtolower($extrat['extrat_name'])).'></div></div>';
            }
            if(isset($extrat['extrat_categ']) && $extrat['extrat_categ'] === 'sandwichs'){
                $prix = '';
                if(floatval($extrat['prix']) > 0.0){
                    $prix = '<span>'.$extrat['prix'].'<em>€</em></span>';
                }
                $CategorieNameSW = $extrat['extrat_categ'];
                $sandwichs .= '<div class="card align-self-stretch h-100"><input type="hidden" value="'.$extrat['extrat_categ'].'" id="l-name"><img src="'.RACINE.$extrat['img_url'].'" class="card-img-top" alt="'.$extrat['extrat_name'].'"><div class="card-body"><h5 class="card-title">'.$extrat['extrat_name'].'</h5>'.$prix.'<input type="checkbox" id='.str_replace(" ", "-", strtolower($extrat['extrat_name'])).'></div></div>';
            }
            if(isset($extrat['extrat_categ']) && $extrat['extrat_categ'] === 'viande'){
                $prix = '';
                if(floatval($extrat['prix']) > 0.0){
                    $prix = '<span>'.$extrat['prix'].'<em>€</em></span>';
                }
                $CategorieNameV = $extrat['extrat_categ'];
                $viande .= '<div class="card align-self-stretch h-100"><input type="hidden" value="'.$extrat['prix'].'" id='.str_replace(" ", "-", strtolower($extrat['extrat_name']))."-price".'><input type="hidden" value="'.$extrat['extrat_categ'].'" id="v-name"><img src="'.RACINE.$extrat['img_url'].'" class="card-img-top" alt="'.$extrat['extrat_name'].'"><div class="card-body"><h5 class="card-title">'.$extrat['extrat_name'].'</h5>'.$prix.'<input type="checkbox" id='.str_replace(" ", "-", strtolower($extrat['extrat_name'])).'></div></div>';
            }
            if(isset($extrat['extrat_categ']) && $extrat['extrat_categ'] === 'burger'){
                $prix = '';
                if(floatval($extrat['prix']) > 0.0){
                    $prix = '<span>'.$extrat['prix'].'<em>€</em></span>';
                }
                $CategorieNameBG = $extrat['extrat_categ'];
                $burger .= '<div class="card align-self-stretch h-100"><input type="hidden" value="'.$extrat['extrat_categ'].'" id="p-name"><input type="hidden" value="'.$extrat['prix'].'" id='.str_replace(" ", "-", strtolower($extrat['extrat_name']))."-price".'><img src="'.RACINE.$extrat['img_url'].'" class="card-img-top" alt="'.$extrat['extrat_name'].'"><div class="card-body"><h5 class="card-title">'.$extrat['extrat_name'].'</h5>'.$prix.'<input type="checkbox" id='.str_replace(" ", "-", strtolower($extrat['extrat_name'])).'></div></div>';
            }
            if(isset($extrat['extrat_categ']) && $extrat['extrat_categ'] === 'boisson'){
                $prix = '';
                if(floatval($extrat['prix']) > 0.0){
                    $prix = '<span>'.$extrat['prix'].'<em>€</em></span>';
                }
                $CategorieNameB = $extrat['extrat_categ'];
                $boisson .= '<div class="card align-self-stretch h-100"><input type="hidden" value="'.$extrat['extrat_categ'].'" id="f-name"><img src="'.RACINE.$extrat['img_url'].'" class="card-img-top" alt="'.$extrat['extrat_name'].'"><div class="card-body"><h5 class="card-title">'.$extrat['extrat_name'].'</h5>'.$prix.'<input type="checkbox" id='.str_replace(" ", "-", strtolower($extrat['extrat_name'])).'></div></div>';
            }
            if(isset($extrat['extrat_categ']) && $extrat['extrat_categ'] === 'dessert'){
                $prix = '';
                if(floatval($extrat['prix']) > 0.0){
                    $prix = '<span>'.$extrat['prix'].'<em>€</em></span>';
                }
                $CategorieNameD = $extrat['extrat_categ'];
                $dessert .= '<div class="card align-self-stretch h-100"><input type="hidden" value="'.$extrat['extrat_categ'].'" id="f-name"><img src="'.RACINE.$extrat['img_url'].'" class="card-img-top" alt="'.$extrat['extrat_name'].'"><div class="card-body"><h5 class="card-title">'.$extrat['extrat_name'].'</h5>'.$prix.'<input type="checkbox" id='.str_replace(" ", "-", strtolower($extrat['extrat_name'])).'></div></div>';
            }
        }
        
    }
?>

<div class="extrat">
    <a href="<?php echo RACINE.'product?access='.$cid.'&delivery='.$deliveryMode?>" class="back"><i class="fas fa-hand-point-left"></i> Retour</a>
    <div class="extrat-content">
        <div class="bloc-content">
            <input type="hidden" value="<?php echo $product['product_name'] ?>" id="productName">
            <input type="hidden" value="<?php echo $cartPrice ?>" id="productPrice">
            <input type="hidden" value="<?php echo $product['img_url'] ?>" id="productImg">
            <input type="hidden" value="<?php echo $pid ?>" id="productId">
            <input type="hidden" value="<?php echo $deliveryMode ?>" id="deliveryMode">
            <input type="hidden" value="<?php echo $cid ?>" id="catId">
            <div class="top">
                <h4><?php echo $product['product_name'] ?></h4>
                <?php echo $pprix ?>
            </div>
            <div class="move">
                <div class="moveLeft">
                    <a class="close" href="<?php echo RACINE.'product?access='.$cid.'&delivery='.$deliveryMode?>"><i class="fas fa-times"></i>FERMER</a>
                </div>
                <?php echo !empty($composition) || !empty($legume) || !empty($sauce) || !empty($viande) || !empty($poison) || !empty($poison) ? '<div class="cart"><p class="next">SUIVANT <i class="fas fa-chevron-right"></i></p></div>' : ''; ?>
            </div>
            <div class="elm">
                <div class="elm-content">
                    <?php
                        if($composition !== ''){
                            ?>
                                <div class="bloc-compo bloc">
                                    <div class="bloc-item">
                                        <h4 class="c-title"><?php echo $CategorieNameC;?></h4>
                                        <div class="elm-bloc d-flex gap-4">
                                            <?php  echo $composition?>
                                        </div>
                                    </div>
                                </div>
                            <?php
                        }
                        if($sauce !== ''){
                            ?>
                                <div class="bloc-sauce bloc">
                                    <div class="bloc-item">
                                        <h4 class="s-title"><?php echo $CategorieNameS;?></h4>
                                        <div class="elm-bloc d-flex gap-4">
                                            <?php  echo $sauce?>
                                        </div>
                                    </div>
                                </div>
                            <?php
                        }
                    ?>
                    <?php
                        if($boisson !== ''){
                            ?>
                                <div class="bloc-legume bloc">
                                    <div class="bloc-item">
                                        <h4 class="l-title"><?php echo $CategorieNameB;?></h4>
                                        <div class="elm-bloc d-flex gap-4">
                                            <?php  echo $boisson?>
                                        </div>
                                    </div>
                                </div>
                            <?php
                        }
                        if($burger !== ''){
                            ?>
                                <div class="bloc-legume bloc">
                                    <div class="bloc-item">
                                        <h4 class="p-title"><?php echo $CategorieNameBG;?></h4>
                                        <div class="elm-bloc d-flex gap-4">
                                            <?php  echo $burger?>
                                        </div>
                                    </div>
                                </div>
                            <?php
                        }
                        if($sandwichs !== ''){
                            ?>
                                <div class="bloc-legume bloc">
                                    <div class="bloc-item">
                                        <h4 class="f-title"><?php echo $CategorieNameSW;?></h4>
                                        <div class="elm-bloc d-flex gap-4">
                                            <?php  echo $sandwichs?>
                                        </div>
                                    </div>
                                </div>
                            <?php
                        }
                        if($dessert !== ''){
                            ?>
                                <div class="bloc-legume bloc">
                                    <div class="bloc-item">
                                        <h4 class="f-title"><?php echo $CategorieNameD;?></h4>
                                        <div class="elm-bloc d-flex gap-4">
                                            <?php  echo $dessert?>
                                        </div>
                                    </div>
                                </div>
                            <?php
                        }
                        if($viande !== ''){
                            ?>
                                <div class="bloc-legume bloc">
                                    <div class="bloc-item">
                                        <h4 class="v-title"><?php echo $CategorieNameV;?></h4>
                                        <div class="elm-bloc d-flex gap-4">
                                            <?php  echo $viande?>
                                        </div>
                                    </div>
                                </div>
                            <?php
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
    include_once '../inc/footer.php';