<?php 
	require_once dirname(dirname(dirname(__DIR__))).'/inc/init.php';
	$extratId = $_GET['extratId'];
    $result = selectQuery("SELECT * FROM extrat WHERE extrat_id = :extratId", array(
        ':extratId' => $extratId
    ));
    $resultat = $result->fetch(PDO::FETCH_ASSOC);
    $categ = selectQuery("SELECT * FROM categorie");
    $categories = $categ->fetchAll(PDO::FETCH_ASSOC);
	
	$option = '<div class="form-floating mb-3"><select class="form-select" id="p-categ" aria-label="Choisir la catégorie du supplément">';

	// Supposons que $categories soit un tableau contenant les catégories et $productCategorie soit l'ID de la catégorie du produit
	foreach ($categories as $category) {
	    if ($resultat['categ_ref'] == $category['categorie_id']) {
	        $option .= '<option value="' . htmlspecialchars($category['categorie_id']) . '" selected>' . htmlspecialchars(strtolower($category['categorie_name'])) . '</option>';
	    } else {
	        $option .= '<option value="' . htmlspecialchars($category['categorie_id']) . '">' . htmlspecialchars(strtolower($category['categorie_name'])) . '</option>';
	    }
	}

	$option .= '</select></div>';
    $title = "Gestion des suppléments - Kebab78";
	require_once '../../nav.php';
?>
<div id="edit-extrat" class="product-content setting-extrat">
	<h3 class="mb-4">Modifier la catégorie</h3>
	<form action="" method="post" enctype="multipart/form-data">
	    <input type="hidden" id="sid" value="<?php echo htmlspecialchars($resultat['extrat_id']); ?>">
	    <div class="form-floating mb-3">
	        <input type="text" value="<?php echo htmlspecialchars($resultat['extrat_name']); ?>" class="form-control" id="s-name" placeholder="Nom du supplément">
	        <label for="s-name">Nom du supplément</label>
	    </div>
	    <div class="form-floating mb-3">
	        <input type="text" class="form-control" value="<?php echo htmlspecialchars($resultat['extrat_categ']); ?>" id="categ-s" placeholder="Légume, Poulet, ...">
	        <label for="categ-s">Donnez une catégorie au supplément</label>
	    </div>
	    <?php echo $option; ?>
	    <div class="form-floating mb-3">
	        <input type="text" value="<?php echo htmlspecialchars($resultat['prix']); ?>" class="form-control" id="s-prix" placeholder="10.85">
	        <label for="s-prix">Prix du supplément (facultatif)</label>
	    </div>
	    <div class="mb-3">
	        <label for="formFileEditExtrat" class="form-label">Choisir une image de remplacement (facultative)</label>
	        <input class="form-control" type="file" id="formFileEditExtrat" accept="image/*" name="file">
	        <input type="hidden" class="img-url" value="<?php echo htmlspecialchars($resultat['img_url']); ?>"/>
	        <img style="width:70px;height:auto;" src="<?php echo RACINE.htmlspecialchars($resultat['img_url']); ?>">
	    </div>
        <span style="font-style:italic;font-size:12px;">Button désactivé pour les tests</span>
	    <div class="submit mb-3">
	        <button style="pointer-events:none;opacity:.5" type="submit" class="btn btn-primary w-100 mt-4 py-10 s-setting" id="load2" data-loading-text="Traitement en cours <i class='fa fa-spinner fa-spin'></i>">Modifier ce supplément</button>
	    </div>
	</form>

</div>
<?php 
    require_once '../../footer.php';