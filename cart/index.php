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
//debug($_SESSION["cart"]); 
?>

<div class="cartBox">
    <!-- Panier -->
    <div class="overlay" id="overlay"></div>
        <div class="cart-modal" id="cartModal">
            <div class="cart-header">
                <h2>Votre Panier</h2>
                <a class="movebtn" href="<?php echo RACINE.'extrat?p='.$_SESSION['id'].'&c='.$_SESSION['cid'].'&delivery='.$_SESSION['mode']?>">
                    <i class="fas fa-times"></i>FERMER
                </a>
            </div>

            <div class="cart-items">
                <?php if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])): ?>
                    <?php foreach ($_SESSION['cart'] as $id => $item): ?>
                        <div class="cart-item">
                            <img src="<?php echo RACINE. $item['image']; ?>" alt="<?php echo $item['name']; ?>" class="cart-item-image">
                            <div class="cart-item-details">
                                <div class="cart-item-title"><?php echo $item['name']; ?></div>
                                <div class="cart-item-quantity">
                                    <form action="update_cart.php" method="post" style="display: flex; align-items: center;">
                                        <input type="hidden" name="product_id" value="<?php echo $id; ?>">
                                        <button type="submit" name="action" value="decrease" class="quantity-btn">-</button>
                                        <input type="text" name="quantity" value="<?php echo $item['quantity']; ?>" class="quantity-input" readonly>
                                        <button type="submit" name="action" value="increase" class="quantity-btn">+</button>
                                    </form>
                                    <div class="cart-item-price"><?php echo number_format($item['price'], 2); ?> €</div>
                                </div>
                                <form action="remove_from_cart.php" method="post">
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
                <div class="cart-summary">
                    <div class="cart-total">
                        <span>Total:</span>
                        <span><?php 
                            $total = 0;
                            foreach ($_SESSION['cart'] as $item) {
                                $total += $item['price'] * $item['quantity'];
                            }
                            echo number_format($total, 2);
                        ?> €</span>
                    </div>
                    <button class="checkout-btn">Passer la commande</button>
                </div>
            <?php endif; ?>
    </div>
</div>
    
<?php
    include_once '../inc/footer.php';
