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
<div id="add-extrat" class="product-content create-extrat">
	<form action="" method="post" enctype="multipart/form-data">
		<div class="form-floating mb-3">
			<input type="text" class="form-control" id="s-name" placeholder="Nom du supplément">
			<label for="s-name">Nom du supplément</label>
		</div>
		<div class="form-floating mb-3">
			<input type="text" class="form-control" id="categ-s" placeholder="Légume,Poulet, ...">
			<label for="categ-s">Donnez une catégorie au supplément</label>
		</div>
		<?php echo $option; ?>
		<div class="form-floating mb-3">
			<input type="text" class="form-control" id="s-prix" placeholder="10.85">
			<label for="s-prix">Prix du supplément (facultatif)</label>
		</div>
		<div class="mb-3">
			<label for="formFileExtrat" class="form-label">Choisir une image pour le supplément</label>
			<input class="form-control" type="file" id="formFileExtrat" accept="image/*" name="file">
		</div>
		<div class="submit mb-3">
			<button type="submit" class="btn btn-primary w-100 mt-4 py-10 s-save" id="load2" data-loading-text="Traitement en cours <i class='fa fa-spinner fa-spin'></i>">Ajouter un supplément</button>
		</div>
	</form>
</div>
<?php 
	require_once '../../footer.php';