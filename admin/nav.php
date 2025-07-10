<?php require_once dirname(__DIR__).'/inc/init.php'; ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
        <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
        <link rel="stylesheet" href="<?php echo RACINE.'style/backoffice.css'?>">
        <title><?php echo $title; ?></title>
    </head>
    <body class="backoffice">
        <div class="header-content">
            <a href="<?php echo RACINE ?>" class="back"><i class="fas fa-hand-point-left"></i> Retour</a>
            <header>
                <nav>
                    <a href="<?php echo RACINE. 'admin'; ?>" id="clients" class="clients link"><i class="fas fa-users"></i>Client</a>
                    <div class="product link" id="products"><i class="fab fa-product-hunt"></i>Produits
                        <div class="dropmenu-content">
                            <a href="<?php echo RACINE."admin/product/add"; ?>" class="product-insert">Insérer un produit</a>
                            <a href="<?php echo RACINE."admin/product"; ?>" class="product-set">Modifier ou Supprimer un produit</a>
                            <a href="<?php echo RACINE."admin/extrat/add"; ?>" class="product-extrat">Ajouter des suppléments</a>
                            <a href="<?php echo RACINE."admin/extrat"; ?>" class="s-set">Modifier ou Supprimer un supplément</a>
                            <a href="<?php echo RACINE."admin/category/add"; ?>"" class="new-product">Créer une nouvelle catégorie de produit</a>
                            <a href="<?php echo RACINE."admin/category"; ?>" class="categorie">Modifier ou Supprimer une catégorie</a>
                        </div>
                    </div>
                    <a id="commandes" href="<?php echo RACINE.'admin/commande' ?>" class="commande link"><i class="fas fa-database"></i>Commandes</a>
                </nav>
            </header>
        </div>
        <main>