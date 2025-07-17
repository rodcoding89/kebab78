<?php
	require_once dirname(dirname(__DIR__)).'/inc/init.php';
	$result = selectQuery("SELECT * FROM extrat");
    $categ = selectQuery("SELECT * FROM categorie");
    $extrat = '';

    if ($result->rowCount() > 0) {
        $table = '<table class="table table-striped"><thead><tr><th>Nom du supplément</th><th>Catégorie</th><th>Prix</th><th>Image</th><th>Référence</th><th>Action</th></tr></thead><tbody>';

		// Supposons que $resultats soit un tableau contenant les résultats et $categories soit un tableau contenant les catégories
        $resultats = $result->fetchAll(PDO::FETCH_ASSOC);
        $categories = $categ->fetchAll(PDO::FETCH_ASSOC);
        //debug($resultats);
        //debug($categories);
		foreach ($resultats as $resultat) {
		    $table .= '<tr>';
		    $table .= '<td>' . htmlspecialchars($resultat['extrat_name']) . '</td>';
		    $table .= '<td>' . htmlspecialchars($resultat['extrat_categ']) . '</td>';
		    $table .= '<td>' . htmlspecialchars($resultat['prix']) . '</td>';
		    $table .= '<td><img src="' . RACINE . htmlspecialchars($resultat['img_url']) . '" style="width:80px;"/></td>';

		    if (!empty($categories)) {
		        foreach ($categories as $categ) {
		            if ($resultat['categ_ref'] == $categ['categorie_id']) {
		                $table .= '<td>' . htmlspecialchars($categ['categorie_name']) . '</td>';
		                break; // Sortir de la boucle une fois la catégorie trouvée
		            }
		        }
		    }

		    $table .= '<td><div style="display:flex;gap:10px;"><a href="'.RACINE.'admin/extrat/manage?extratId='.htmlspecialchars($resultat['extrat_id']).'"><i class="fas fa-cog setting" style="cursor:pointer;"></i></a><span class="delete"><i class="fas fa-trash-alt" style="cursor:pointer;"><input type="hidden" value="' . htmlspecialchars($resultat['extrat_id']) . '"></i></span></div></td>';
		    $table .= '</tr>';
		}

		$table .= '</tbody></table>';
		

		$extrat = $table;
    } else {
        $extrat = '<div>Vous n\'avez pour l\'instant inséré aucun supplément</div>';
    }
    require_once '../nav.php';            
?>

<div class="pbloc">
	<div id="manage-extrat" class="product-content">
		<h3 class="mb-4">Liste de suppléments</h3>
		<?php echo $extrat; ?>
	</div>
</div>

<?php 
    require_once '../footer.php';