<?php 
    require_once '../inc/init.php';
    if(!isAdminOn()){
        header('location:'.RACINE);
        exit;
    }
    $title = "Gestion du restaurant";

    require_once '../inc/header.php';
?>
    <div class="backoffice">
        <div class="header-content">
            <a href="<?php echo RACINE ?>" class="back"><i class="fas fa-hand-point-left"></i> Retour</a>
            <header>
                <nav>
                    <div class="active client link"><i class="fas fa-users"></i>Client</div>
                    <div class="product link"><i class="fab fa-product-hunt"></i>Produits
                        <div class="dropmenu-content">
                            <span class="product-insert">Insérer un produit</span>
                            <span class="product-set">Modifier ou supprimer un produit</span>
                            <span class="product-extrat">Ajouter des suppléments</span>
                            <span class="s-set">Modifier ou supprimer un supplément</span>
                            <span class="new-product">Créer une nouvelle catégorie de produit</span>
                            <span class="categorie">Gérer mes catégories</span>
                        </div>
                    </div>
                    <div class="commande link"><i class="fas fa-database"></i>Commandes</div>
                </nav>
            </header>
        </div>
        <div class="content">
            <div class="client-content"></div>
            <div class="product-content"></div>
            <div class="commande-content"></div>
        </div>
    </div>
<?php 
    require_once '../inc/footer.php';