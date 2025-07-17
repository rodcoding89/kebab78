<?php
	require_once dirname(dirname(__DIR__)).'/inc/init.php';
	
    require_once '../nav.php';
    function extratFromAssociativeArray($data){
        $li = '';
        foreach($data as $value){
            $li .= '<li>'.$value.'</li>';
        }
        return $li;
    }
    function filterAction ($item,$action){
        //var_dump($action);
        $result = [];
        if ($item == "pending") {
            $result = array_filter($action,function($value){
                return $value != "pending";
            });
        }else if($item == "cancelled"){
            $result = [];
        }else if($item == "delivered"){
            $result = [];
        }else{
            $result = ["delivered","cancelled"];
        }

        return $result;
    }
    function initStatus ($status,$from,$orderId){
        $statusText = '';
        $spanStyle = 'font-size:11px; padding:5px 10px; border-radius:.5rem; color:white;display:block;width:max-content;'.($from != 'status' ? 'cursor:pointer;':';');

        if ($status == 'pending') {
            $statusText = '<span style="' . $spanStyle . 'background-color:yellowgreen;">En attente</span>';
        } elseif ($status == 'delivered') {
            $statusText = $from == 'status' ? '<span style="' . $spanStyle . 'background-color:blue;">Livré</span>' : '<form action="'.RACINE.'inc/make_as_delivered.php" method="POST"><input type="hidden" value="'.$orderId.'" name="orderId"> <button style="'.$spanStyle.'background-color:blue;border:none;" type="submit">Livrer</button></form>';
        } elseif ($status == 'cancelled') {
            $statusText = $from == 'status' ? '<span style="' . $spanStyle . 'background-color:red;">Annulé</span>' : '<form action="'.RACINE.'inc/make_as_cancelled.php" method="POST"><input type="hidden" value="'.$orderId.'" name="orderId"> <button style="'.$spanStyle.'background-color:red;border:none;" type="submit">Annuler</button></form>';
        } else {
            $statusText = $from == 'status' ? '<span style="' . $spanStyle . 'background-color:orange;">Expédié</span>' : '<form action="'.RACINE.'inc/make_as_shipped.php" method="POST"><input type="hidden" value="'.$orderId.'" name="orderId"> <button style="'.$spanStyle.'background-color:orange;border:none;" type="submit">Expedier</button></form>';
        }
        return $statusText;
    }
    $resultat = selectQuery("SELECT o.*, c.first_name, c.last_name, GROUP_CONCAT(p.product_name SEPARATOR ', ') AS products FROM orders o INNER JOIN client c ON c.client_id = o.client_id INNER JOIN orders_product op ON op.orders_id = o.orders_id INNER JOIN product p ON op.product_id = p.product_id GROUP BY o.orders_id");
    $table = '<table class="table table-striped"><head><tr><th>Réference de commande</th><th>Client</th><th>Date de commande</th><th>Total (€)</th><th>Contenu commande</th><th>Statut</th><th>Mette a jour le statut</th><thead><tbody>';
    //$orders = $resultat->fetchAll(PDO::FETCH_ASSOC);
    //debug($orders);
    while ($orders = $resultat->fetch(PDO::FETCH_ASSOC)) {
    	$extrat = json_decode($orders['orders_content']);
    	//debug($extrat);
        $productNames = explode(",", $orders['products']);
        $index = 0;
        $content = '<div><ol>';
    	foreach ($extrat as $key => $value) {
    		//debug($value);
            $content .= '<li>'.str_replace(" ", "", $productNames[$index]).'<ul>'. extratFromAssociativeArray($value) . '</ul></li>';
            $index ++;
    	}
        $content .= '</ol></div>';

        $actionList = ['pending','cancelled','delivered','shipped'];
        $possibleAction = filterAction($orders['order_status'],$actionList);

        $actions = '<div style="display: flex;flex-wrap: wrap;gap: 5px;">';
        foreach ($possibleAction as $key => $value) {
            $actions .= initStatus($value,'action',$orders['orders_id']);
        }
        $actions .= '</div>';

        $table .= '<tr>';
        $table .= '<td>' . $orders['orders_ref'] .'</td>';
        $table .= '<td>' . $orders['first_name'] .' '. $orders['last_name']. '</td>';
        $table .= '<td>' . $orders['orders_date'] . '</td>';
        $table .= '<td>' . $orders['total_amount'].'€</td>';
        $table .= '<td>' . $content . '</td>';
        $table .= '<td>' . initStatus($orders['order_status'],'status','0') . '</td>';
        $table .= '<td>' . $actions . '</td>';
        $table .= '</tr>';
    }

    $table .= '</tbody></table>';
    $success = '';
    $error = '';

    if (isset($_GET['success'])) {
        $success = '<div class="alert alert-success">Modification éffectué avec succès</div>';
    } else if (isset($_GET['error'])) {
        $error = '<div class="alert alert-danger">Erreur survenu lors de la modification du statut de la commande</div>';
    }
            
?>

<div class="product-content">
    <?php echo $success; ?>
    <?php echo $error; ?>
	<?php echo $table; ?>
</div>

<?php 
    require_once '../footer.php';