<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

require_once 'init.php';

function filterArrayWithoutPrice($item){
    //var_dump($item['check']);
    return $item['check'] == 'true' ? true : false;
}

function prepareExtratProduct($productId){
    $response = [];
    $product = $_SESSION['cart'][$productId];
    $extrat = $product['extract'];
    $mode = $product['deliveryMode'];
    
    if ($extrat != null) {
        foreach ($extrat as $key => $val) {
            //debug($value);
            $result = array_filter($val, "filterArrayWithoutPrice");
            $item = array_column($result, 'name');
            //var_dump($item);
            $name = implode(", ", $item);
            $compo = ucfirst($key).' - '.$name;
            //debug($compo);
            array_push($response, $compo);
        }
    }
   
    array_push($response,"Mode de reception - ".$mode);
    return $response;
}


if (isset($_POST) && isset($_SESSION['cart'])) {
    $orders = [];
    $carts = $_SESSION['cart'];
    
    foreach ($carts as $key => $value) {
       $extrat = prepareExtratProduct($key);

       $product = [
            'productId' => $value['id'],
            'name'=> $value['name'],
            'quantity' =>$value['quantity'],
            'image' =>$value['image'],
            'tva' => '10%',
            'tvaPrice' => $value['price'] * $value['quantity'] * 0.10,
            'subTotal' => $value['price'] * $value['quantity'],
            'total' => ($value['price'] * $value['quantity'] * 0.10) + ($value['price'] * $value['quantity']),
            "extrat"=> $extrat,
            "mode"=> $value['deliveryMode']
       ];
       array_push($orders, $product);
    }
    $_SESSION['orders'] = $orders;
    //debug($orders);
    header('Location:' . RACINE. 'client');
    exit;
}

// Rediriger vers la page précédente
//header('Location: ' . $_SERVER['HTTP_REFERER']);
//exit;
?>

