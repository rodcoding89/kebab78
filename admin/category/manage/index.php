<?php 
	require_once dirname(dirname(dirname(__DIR__))).'/inc/init.php';
	$categoryId = $_GET['categoryId'];
    $result = selectQuery("SELECT * FROM categorie WHERE categorie_id = :cid", array(
        ':cid' => $categoryId
    ));
    $resultat = $result->fetch(PDO::FETCH_ASSOC);
    $title = "Gestion des Catégories - Kebab78";
	require_once '../../nav.php';
?>
<div id="edit-categ" class="product-content">
	<h3 class="mb-4">Modifier cette catégorie</h3>
	<div class="setting-categ">
	    <form action="" method="post">
	        <div class="form-floating mb-3 categ">
	            <input type="text" class="form-control" id="c-name" placeholder="Saucisse" value="<?php echo htmlspecialchars($resultat['categorie_name']); ?>">
	            <input type="hidden" id="cid" value="<?php echo htmlspecialchars($resultat['categorie_id']); ?>">
	            <label for="c-name">Modifier le nom de votre nouveau type de produit</label>
	        </div>
	        <div class="mb-3">
	            <label for="formFileEditCateg" class="form-label">Modifier l'image de la catégorie</label>
	            <input class="form-control" type="file" id="formFileEditCateg" accept="image/*" name="file">
	            <input type="hidden" class="img-url" value="<?php echo htmlspecialchars($resultat['img_url']); ?>">
	            <img style="width:70px;height:auto;" src="<?php echo RACINE.htmlspecialchars($resultat['img_url']); ?>">
	        </div>
            <span style="font-style:italic;font-size:12px;">Button désactivé pour les tests</span>
	        <div class="submit mb-3">
	            <button style="pointer-events:none;opacity:.5" type="submit" class="btn btn-primary w-100 mt-4 py-10 c-save1" id="load2" data-loading-text="Traitement en cours <i class='fa fa-spinner fa-spin'></i>">Modifier la catégorie du produit</button>
	        </div>
	    </form>
	</div>
</div>
<?php 
    require_once '../../footer.php';