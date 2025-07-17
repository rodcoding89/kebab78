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

function filterArrayWithoutPrice($item){
    //var_dump($item['check']);
    return $item['check'] == 'true' ? true : false;
}

//debug($_SESSION['cart']);

function handleExtrat($productId){
    $extratPrice = 0;
    global $productExtrat;
    if($_SESSION['cart'][$productId]){
        $extrat = $_SESSION['cart'][$productId]['extract'];
        $response = [];
        if ($extrat != null) {
            foreach ($extrat as $key => $value) {
                //debug($value);
                $result = array_filter($value, "filterArrayWithoutPrice");
                $item = array_column($result, 'name');
                //var_dump($item);
                $name = implode(", ", $item);
                array_push($response, ucfirst($key).' - '.$name);
            }
        }
        array_push($response,"Mode de reception - ".$_SESSION['cart'][$productId]['deliveryMode']);
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
                <a class="movebtn" href="<?php echo isset($_SESSION['mode']) && isset($_SESSION['cid']) ? RACINE.'product?access='.$_SESSION['cid'].'&delivery='.$_SESSION['mode'] : RACINE; ?>">
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
                $index = 0;

                foreach ($_SESSION['cart'] as $item){
                    $subtotal += $item['price'] * $item['quantity'];
                    $index++;
                }
                $tva = $subtotal * 0.10;
                $total = $subtotal + $tva;
            ?>
            <div class="cart-summary">
                <div class="summary-sec">
                    <div class="cart-total">
                        <span>Sous Total:</span>
                        <em> <?php echo number_format($subtotal, 2); ?> €</em>
                    </div>
                    <div class="cart-total">
                        <span>TVA: 10%</span>
                        <em> <?php echo number_format($tva, 2); ?> €</em>
                    </div>
                    <div class="cart-total">
                        <span>Total:</span>
                        <em> <?php echo number_format($total, 2); ?> €</em>
                    </div>
                </div>
                <form action="<?php echo RACINE . 'inc/order_from_cart.php' ?>" method="post">
                    <button type="submit" class="checkout-btn">Passer la commande</button>
                </form>
            <?php endif; ?>
    </div>
</div>
    
<?php
    include_once '../inc/footer.php';
