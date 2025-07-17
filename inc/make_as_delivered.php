<?php 
	require_once 'init.php';
	if(isset($_POST) && isset($_POST['orderId'])){
		$resultat = actionQuery('UPDATE orders SET order_status = :new_status WHERE orders_id = :ordersId',array(
			"new_status"=> "delivered",
			"ordersId" => $_POST['orderId']
		));

		if ($resultat) {
			header("Location:".RACINE.'admin/commande?success=true');
		} else {
			header("Location:".RACINE.'admin/commande?error=delivered');
		}
		
	}else{
		echo 'no post';
	}
?>