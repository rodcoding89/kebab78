<?php 
	require_once dirname(dirname(dirname(__DIR__))).'/inc/init.php';
	$result = selectQuery("SELECT * FROM categorie");
    $categories = $result->fetchAll(PDO::FETCH_ASSOC);
    //var_dump($categories);
	$option = '<div class="form-floating mb-3"><select class="form-select" id="p-categ" aria-label="Choisir la catégorie du produit">';

	// Supposons que $categories soit un tableau contenant les catégories et $productCategorie soit l'ID de la catégorie du produit
	foreach ($categories as $category) {
	    $option .= '<option value="' . htmlspecialchars($category['categorie_id']) . '">' . htmlspecialchars(strtolower($category['categorie_name'])) . '</option>';
	}
	$option .= '</select></div>';
	require_once '../../nav.php';
?>
<div id="add" class="product-content create-product">
	<form action="" method="post" enctype="multipart/form-data">
		<div class="form-floating mb-3">
			<input type="text" class="form-control" id="p-name" placeholder="Nom du produit"><label for="p-name">Nom du produit</label>
		</div>
		<div class="form-floating mb-3">
			<textarea class="form-control" placeholder="Description du produit" id="p-descrip" style="height: 100px">
			</textarea>
			<label for="p-descrip">Description du produit (facultative)</label>
		</div>
		<?php echo $option; ?>
		<div class="form-floating mb-3">
			<input type="text" class="form-control" id="p-prixWith" placeholder="10.85"><label for="p-name">Prix du produit plus livraison</label>
		</div>
		<div class="form-floating mb-3">
			<input type="text" class="form-control" id="p-prixWithout" placeholder="8.50"><label for="p-name">Prix du produit sans livraison</label>
		</div>
		<div class="mb-3">
			<label for="formFile" class="form-label">Choisir une image pour le produit</label>
			<input class="form-control" type="file" id="formFile" accept="image/*" name="file">
		</div>
		<div class="submit mb-3">
				<button type="submit" class="btn btn-primary w-100 mt-4 py-10 p-save" id="load2" data-loading-text="Traitement en cours <i class='fa fa-spinner fa-spin'></i>">Insérer le produit</button>
			</div>
		</form>
	</div>
<?php 
	require_once '../../footer.php';