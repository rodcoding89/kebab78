<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

require_once 'init.php';

if (isset($_POST['product_id']) && isset($_SESSION['cart'][$_POST['product_id']])) {
    unset($_SESSION['cart'][$_POST['product_id']]);
    unset($_SESSION['id']);
    unset($_SESSION['mode']);
    unset($_SESSION['cid']);
    
    // Supprimer le panier s'il est vide
    if (empty($_SESSION['cart'])) {
        unset($_SESSION['cart']);
    }
}

// Rediriger vers la page précédente
header('Location: ' . $_SERVER['HTTP_REFERER']);
exit;
?>

