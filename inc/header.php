<?php require_once 'init.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css">

    <link rel="stylesheet" href="<?php echo RACINE.'style/style.css'?>">
    <link rel="stylesheet" href="<?php echo RACINE.'style/animations.css'?>">
    <link
    rel="stylesheet"
    href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    />
    <link
    rel="stylesheet"
    href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css"
    />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons"
      rel="stylesheet">
    <title><?php echo $title; ?></title>
</head>
<body>
    <header>
        <a href="<?php echo RACINE; ?>">Accueil</a>
        
        <div class="cart-icon" onclick="openCart()">
            <i class="fas fa-cart-plus"></i>
            <span class="cart-count"><?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?></span>
        </div>
    </header>
   <main>
