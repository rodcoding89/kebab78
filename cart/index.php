<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

include_once '../inc/init.php';
if(!isOn()){
    header('location:'.RACINE);
    exit;
}
$title = "Découvrez nos catégorie";
include_once '../inc/header.php';
$productExtrat = array();
function filterArrayWithoutPrice($item){
    return $item == 'true' ? true : false;
}
function handleExtrat($productId){
    $extratPrice = 0;
    global $productExtrat;
    if($_SESSION['cart'][$productId]){
        $extrat = $_SESSION['cart'][$productId]['extract'];
        $response = [];
        for($i = 0; $i < count($extrat); $i++){
            $item = $extrat[$i];
            if($i == 0 || $i == 1){
                $result = array_filter($item, "filterArrayWithoutPrice");
                //var_dump($result);
                $keys = array_keys($result);
                //var_dump($keys);
                $compo = implode(", ", $keys);
                //var_dump($compo);
                if(count($keys) > 0){
                    if($i == 0){
                        $response[$i] = 'Compsition : '.$compo;
                    }else{
                        $response[$i] = 'Sauce : '.$compo;
                    }
                }
            }else if($i == 2 || $i == 3){
                $extratKey = [];
                foreach ($item as $key => $value) {
                    if($value['check'] == 'true'){
                        //var_dump($value);
                        $price = floatval($value['price']);
                        $extratPrice += $price;
                        array_push($extratKey,$key);
                    }
                }
                $extratAdded = implode(", ", $extratKey);
                if($i == 2){
                    $response[$i] = 'Viande : '.$extratAdded;
                }else{
                    $response[$i] = 'Poison : '.$extratAdded;
                }
            }
        }
        array_push($productExtrat,$extratPrice);
        return $response;
    }
}
//debug($_SESSION["cart"]); 
?>

<div class="cartBox">
    <!-- Panier -->
    <div class="overlay" id="overlay"></div>
        <div class="cart-modal" id="cartModal">
            <div class="cart-header">
                <h2>Votre Panier</h2>
                <a class="movebtn" href="<?php echo RACINE.'product?access='.$_SESSION['cid'].'&delivery='.$_SESSION['mode']?>">
                    <i class="fas fa-times"></i>FERMER
                </a>
            </div>

            <div class="cart-items">
                <?php if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])): ?>
                    <?php foreach ($_SESSION['cart'] as $id => $item): ?>
                        <div class="cart-item">
                            <img src="<?php echo RACINE. $item['image']; ?>" alt="<?php echo $item['name']; ?>" class="cart-item-image">
                            <div class="cart-item-details">
                                <div class="cart-item-title"><?php echo $item['name']; ?>
                                    <ul>
                                        <?php foreach (handleExtrat($id) as $val){
                                            echo '<li style="font-size:12px;">'.$val.'</li>';
                                        } ?>
                                    </ul>
                                </div>
                                <div class="cart-item-quantity">
                                    <form action="<?php echo RACINE . 'inc/update_cart.php' ?>" method="post" style="display: flex; align-items: center;">
                                        <input type="hidden" name="product_id" value="<?php echo $id; ?>">
                                        <button type="submit" name="action" value="decrease" class="quantity-btn">-</button>
                                        <input type="text" name="quantity" value="<?php echo $item['quantity']; ?>" class="quantity-input" readonly>
                                        <button type="submit" name="action" value="increase" class="quantity-btn">+</button>
                                    </form>
                                    <div class="cart-item-price"><?php echo number_format($item['price'], 2); ?> €</div>
                                </div>
                                <form action="<?php echo RACINE . 'inc/remove_from_cart.php' ?>" method="post">
                                    <input type="hidden" name="product_id" value="<?php echo $id; ?>">
                                    <button type="submit" class="remove-item">Supprimer</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Votre panier est vide.</p>
                <?php endif; ?>
            </div>

            <?php if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])): ?>
            <?php 
                $total = 0;
                $subtotal = 0;
                $extract = 0;
                $index = 0;
                foreach ($_SESSION['cart'] as $item){
                    $extratPrice = $productExtrat[$index];
                    $extract = $extratPrice * $item['quantity'];
                    $subtotal += $item['price'] * $item['quantity'];
                    $index++;
                }
                $total = $subtotal + $extract;
            ?>
            <div class="cart-summary">
                    <div class="cart-total">
                        <span>Extrat:</span>
                        <em> <?php echo number_format($extract, 2); ?> €</em>
                    </div>
                    <div class="cart-total">
                        <span>Produit:</span>
                        <em> <?php echo number_format($subtotal, 2); ?> €</em>
                    </div>
                    <div class="cart-total">
                        <span>Total:</span>
                        <em> <?php echo number_format($total, 2); ?> €</em>
                    </div>
                    <a href="<?php echo RACINE.'client'; ?>" class="checkout-btn">Passer la commande</a>
                </div>
            <?php endif; ?>
    </div>
</div>
    
<?php
    include_once '../inc/footer.php';
