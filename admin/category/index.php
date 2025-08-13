<?php
	require_once dirname(dirname(__DIR__)).'/inc/init.php';
	$result = selectQuery("SELECT * FROM categorie");
    $resultats = $result->fetchAll(PDO::FETCH_ASSOC);
    $categories = '';

    if ($result->rowCount() > 0) {
        $table = '<table class="table table-striped"><thead><tr><th>Identifiant de la catégorie</th><th>Nom de la catégorie</th><th>Image</th><th>Action</th></tr></thead><tbody>';

		// Supposons que $resultats soit un tableau contenant les résultats
		foreach ($resultats as $el) {
		    $table .= '<tr>';
		    $table .= '<td>' . htmlspecialchars($el['categorie_id']) . '</td>';
		    $table .= '<td>' . htmlspecialchars($el['categorie_name']) . '</td>';
		    $table .= '<td><img src="' . RACINE . htmlspecialchars($el['img_url']) . '" style="width:80px;"/></td>';
		    $table .= '<td><div style="display:flex;gap:10px;"><a href="'.RACINE.'admin/category/manage?categoryId='.htmlspecialchars($el['categorie_id']).'"><i class="fas fa-cog csetting" style="cursor:pointer;"></i></a><span style="pointer-events:none;opacity:.5" class="cdelete"><i class="fas fa-trash-alt" style="cursor:pointer;"><input type="hidden" value="' . htmlspecialchars($el['categorie_id']) . '"></i></span></div></td>';
		    $table .= '</tr>';
		}

		$table .= '</tbody></table>';

		$categories = $table;
    } else {
        $categories = '<div>Vous n\'avez pour l\'instant inséré aucune catégorie</div>';
    }
    $title = "Liste de Catégories - Kebab78";
    require_once '../nav.php';            
?>

<div class="pbloc">
	<div id="manage-categ" class="product-content">
		<h3 class="mb-4">Liste des catégories</h3>
		<?php echo $categories; ?>
	</div>
</div>

<?php 
    require_once '../footer.php';