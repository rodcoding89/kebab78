<?php 
	require_once dirname(dirname(dirname(__DIR__))).'/inc/init.php';
	$productId = $_GET['productId'];
    
	$resultat = selectQuery("SELECT * FROM product WHERE product_id =:productId",array(
		"productId" => $productId
	));
	$productItem = $resultat->fetch(PDO::FETCH_ASSOC);
	$result = selectQuery("SELECT * FROM categorie");
    $categories = $result->fetchAll(PDO::FETCH_ASSOC);
    //var_dump($categories);
	$option = '<div class="form-floating mb-3"><select class="form-select" id="p-categ" aria-label="Choisir la catégorie du supplément">';

	// Supposons que $categories soit un tableau contenant les catégories et $productCategorie soit l'ID de la catégorie du produit
	foreach ($categories as $category) {
	    if ($productItem == $category['categorie_id']) {
	        $option .= '<option value="' . htmlspecialchars($category['categorie_id']) . '" selected>' . htmlspecialchars(strtolower($category['categorie_name'])) . '</option>';
	    } else {
	        $option .= '<option value="' . htmlspecialchars($category['categorie_id']) . '">' . htmlspecialchars(strtolower($category['categorie_name'])) . '</option>';
	    }
	}

	$option .= '</select></div>';
    $title = "Gestion des Produits - Kebab78";
	require_once '../../nav.php';
?>
<div id="edit-product" class="product-content">
	<h3 class="mb-4">Modifier le produit</h3>
	<div class="setting-product">
		
		<form action="" method="post" enctype="multipart/form-data">
		    <div class="form-floating mb-3">
		        <input type="hidden" value="<?php echo $productItem['product_id']; ?>" id="pid">
		        <input type="text" class="form-control" id="p-name" placeholder="Nom du produit" value="<?php echo htmlspecialchars($productItem['product_name']); ?>">
		        <label for="p-name">Nom du produit</label>
		    </div>
		    <div class="form-floating mb-3">
		        <textarea class="form-control" placeholder="Description du produit" id="p-descrip" style="height: 100px"><?php echo htmlspecialchars($productItem['description']); ?></textarea>
		        <label for="p-descrip">Description du produit (facultative)</label>
		    </div>	
		    <?php echo $option; ?>
		    <div class="form-floating mb-3">
		        <input type="text" class="form-control" id="p-prixWith" placeholder="10.85" value="<?php echo htmlspecialchars($productItem['prix_avec_livraison']); ?>">
		        <label for="p-prixWith">Prix du produit plus livraison</label>
		    </div>
		    <div class="form-floating mb-3">
		        <input type="text" class="form-control" id="p-prixWithout" placeholder="8.50" value="<?php echo htmlspecialchars($productItem['prix_sans_livraison']); ?>">
		        <label for="p-prixWithout">Prix du produit sans livraison</label>
		    </div>
		    <div class="mb-3">
		        <div>
		        	<label for="formFileEditProduct" class="form-label">Choisir une image pour le produit</label>
			        <input class="form-control" type="file" id="formFileEditProduct" accept="image/*" name="file">
			        <input type="hidden" value="<?php echo htmlspecialchars($productItem['img_url']); ?>" id="img-url">
		        </div>
		        <img style="width:70px;height:auto;" src="<?php echo RACINE.htmlspecialchars($productItem['img_url']); ?>">
		    </div>
            <span style="font-style:italic;font-size:12px;">Button désactivé pour les tests</span>
		    <div class="submit mb-3">
		        <button style="pointer-events:none;opacity:.5" type="submit" class="btn btn-primary w-100 mt-4 py-10 p-setting" id="load2" data-loading-text="Traitement en cours <i class='fa fa-spinner fa-spin'></i>">Modifier ce produit</button>
		    </div>
		</form>
	</div>
</div>
<?php 
    require_once '../../footer.php';