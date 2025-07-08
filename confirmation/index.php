<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */
include_once '../inc/init.php';
if(!isOn()){
    header('location:'.RACINE);
    exit;
}
if (!isset($_SESSION['order'])) {
    header('Location: index.php');
    exit;
}

$order = $_SESSION['order'];
$title = "Confirmation de commande";
include_once '../inc/header.php';
?>
<div class="container confirmation-container">
    <div class="card confirmation-card">
        <div class="card-header confirmation-header text-center py-4">
            <h2>Merci pour votre commande !</h2>
            <p class="mb-0">Votre commande #<?php echo time(); ?> a bien été enregistrée</p>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="mb-3">Informations client</h5>
                    <p>
                        <strong><?php echo $order['customer']['first_name'] . ' ' . $order['customer']['last_name']; ?></strong><br>
                        <?php echo $order['customer']['email']; ?><br>
                        <?php if (!empty($order['customer']['phone'])): ?>
                            <?php echo $order['customer']['phone']; ?><br>
                        <?php endif; ?>
                    </p>

                    <h5 class="mb-3 mt-4">Adresse de livraison</h5>
                    <p>
                        <?php echo $order['customer']['street']; ?><br>
                        <?php echo $order['customer']['postal_code'] . ' ' . $order['customer']['city']; ?>
                    </p>
                </div>

                <div class="col-md-6 order-details">
                    <h5 class="mb-3">Détails de la commande</h5>
                    <p>
                        <strong>Date:</strong> <?php echo date('d/m/Y H:i', strtotime($order['order_date'])); ?><br>
                        <strong>Statut:</strong> En traitement
                    </p>

                    <h5 class="mb-3 mt-4">Articles commandés</h5>
                    <ul class="list-group mb-3">
                        <?php foreach ($order['cart'] as $item): ?>
                            <li class="list-group-item d-flex justify-content-between">
                                <span><?php echo $item['name']; ?> × <?php echo $item['quantity']; ?></span>
                                <span><?php echo number_format($item['price'] * $item['quantity'], 2); ?> €</span>
                            </li>
                        <?php endforeach; ?>
                    </ul>

                    <div class="d-flex justify-content-between fw-bold fs-5">
                        <span>Total</span>
                        <span><?php echo number_format($order['total'], 2); ?> €</span>
                    </div>
                </div>
            </div>

            <div class="text-center mt-5">
                <a href="index.php" class="btn btn-primary">Retour à la boutique</a>
            </div>
        </div>
    </div>
</div>

<?php 
    include_once '../inc/footer.php';