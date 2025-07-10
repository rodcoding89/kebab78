<?php 
	require_once dirname(dirname(dirname(__DIR__))).'/inc/init.php';
	$result = selectQuery("SELECT * FROM categorie");
    $categories = $result->fetchAll(PDO::FETCH_ASSOC);
    //var_dump($categories);
	$option = '<div class="form-floating mb-3"><select class="form-select" id="p-categ" aria-label="Choisir la catégorie du supplément">';

	// Supposons que $categories soit un tableau contenant les catégories et $productCategorie soit l'ID de la catégorie du produit
	foreach ($categories as $category) {
	    $option .= '<option value="' . htmlspecialchars($category['categorie_id']) . '">' . htmlspecialchars(strtolower($category['categorie_name'])) . '</option>';
	}
	$option .= '</select></div>';
	require_once '../../nav.php';
?>
<div id="add-categ" class="product-content new-categ">
	<h3 class="mb-4">Première étape donnez un nom à cette nouvelle catégorie</h3>
	<form action="" method="post">
		<div class="form-floating mb-3 categ">
			<input type="text" class="form-control" id="c-name" placeholder="Saucisse">
			<label for="p-name">Donnez un nom a votre nouveau type de produit</label>
		</div>
		<div class="mb-3">
			<label for="formFileCateg" class="form-label">Choisir une image pour la catégorie</label>
			<input class="form-control" type="file" id="formFileCateg" accept="image/*" name="file">
		</div>
		<div class="submit mb-3">
			<button type="submit" class="btn btn-primary w-100 mt-4 py-10 c-save" id="load2" data-loading-text="Traitement en cours <i class='fa fa-spinner fa-spin'></i>">Insérer la catégorie du produit</button>
		</div>
	</form>
</div>
<?php 
	require_once '../../footer.php';