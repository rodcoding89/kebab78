<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

require_once 'init.php';

if (isset($_POST['product_id']) && isset($_POST['action']) && isset($_SESSION['cart'][$_POST['product_id']])) {
    $productId = $_POST['product_id'];
    $action = $_POST['action'];
    
    if ($action === 'increase') {
        $_SESSION['cart'][$productId]['quantity'] += 1;
    } elseif ($action === 'decrease') {
        if ($_SESSION['cart'][$productId]['quantity'] > 1) {
            $_SESSION['cart'][$productId]['quantity'] -= 1;
        } else {
            // Si la quantité est 1 et on veut diminuer, supprimer l'article
            unset($_SESSION['cart'][$productId]);
        }
    }
}

// Rediriger vers la page précédente
header('Location: ' . $_SERVER['HTTP_REFERER']);
exit;
?>
