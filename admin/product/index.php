<?php
	require_once dirname(dirname(__DIR__)).'/inc/init.php';
	$result = selectQuery("SELECT * FROM product");
    $resultat = selectQuery("SELECT * FROM categorie");
    $productData = '';
    if ($result->rowCount() > 0) {
        
        $res['categ'] = $resultat->fetchAll(PDO::FETCH_ASSOC);
        $table = '<table class="table table-striped"><head><tr><th>Nom du produit</th><th>Catégorie</th><th>Description</th><th>Prix sans livraison</th><th>Prix avec livraison</th><th>Image</th><th>Action</th><thead><tbody>';
        // Récupérer tous les produits
		while ($product = $result->fetch(PDO::FETCH_ASSOC)) {
		    $table .= '<tr>';
		    $table .= '<td>' . $product['product_name'] . '</td>';

		    // Remettre le pointeur de résultat au début pour la recherche de catégorie
		    $resultat->execute();

		    // Rechercher la catégorie correspondante
		    while ($categ = $resultat->fetch(PDO::FETCH_ASSOC)) {
		        if ($product['categorie'] == $categ['categorie_id']) {
		            $table .= '<td>' . $categ['categorie_name'] . '</td>';
		            break; // Sortir de la boucle une fois la catégorie trouvée
		        }
		    }

		    $table .= '<td>' . $product['description'] . '</td>';
		    $table .= '<td>' . $product['prix_sans_livraison'] . '</td>';
		    $table .= '<td>' . $product['prix_avec_livraison'] . '</td>';
		    $table .= '<td><img src="' . RACINE . $product['img_url'] . '" style="width:80px;"/></td>';
		    $table .= '<td><div style="display:flex;gap:10px;"><a  href="'.RACINE.'admin/product/manage?productId='. $product['product_id'].'"><i class="fas fa-cog psetting" style="cursor:pointer;"></i><a/><span style="pointer-events:none;opacity:.5" class="delProduct"><i class="fas fa-trash-alt" style="cursor:pointer;"><input type="hidden" value="' . $product['product_id'] . '"></i></span></div></td>';
		    $table .= '</tr>';
		}

		$table .= '</tbody></table>'; // J'ai supposé que vous vouliez fermer une table, pas un div

		$productData = $table;
    } else {
        $productData = '<div>Vous n\'avez pour l\'instant inséré aucun produit</div>';
    }
    $title = "Liste Produit - Kebab78";
    require_once '../nav.php';            
?>

<div class="pbloc">
	<div id="manage" class="product-content">
		<h3 class="mb-4">Liste de produits</h3>
		<?php echo $productData; ?>
	</div>
</div>

<?php 
    require_once '../footer.php';