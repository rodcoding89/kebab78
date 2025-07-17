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
if (!isset($_SESSION['confirmation'])) {
    header('Location:'.RACINE);
    exit;
}
unset($_SESSION['mode']);
$orders = $_SESSION['confirmation'];
//debug($orders);
$delivery = $_GET['delivery'];
$title = "Confirmation de commande";
include_once '../inc/header.php';
?>
<div id="confirmation" class="container confirmation-container">
    <div class="card confirmation-card">
        <div class="card-header confirmation-header text-center py-4">
            <h2>Merci pour votre commande !</h2>
            <p class="mb-0">Votre commande #<?php echo $orders['ref']; ?> a bien été enregistrée</p>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="mb-3">Informations client</h5>
                    <p>
                        <strong><?php echo $orders['customer']['first_name'] . ' ' . $orders['customer']['last_name']; ?></strong><br>
                        <?php echo $orders['customer']['email']; ?><br>
                        <?php if (!empty($orders['customer']['phone'])): ?>
                            <?php echo $orders['customer']['phone']; ?><br>
                        <?php endif; ?>
                    </p>
                    <?php
                        if($delivery == 'livraison'){
                            ?>
                            <h5 class="mb-3 mt-4">Adresse de livraison</h5>
                            <p>
                                <?php echo $orders['customer']['street']; ?><br>
                                <?php echo $orders['customer']['postal_code'] . ' ' . $orders['customer']['city']; ?>
                            </p>
                            <div id="distance">
                                <h3>Livraison / Distance / Trajet en KM</h3>
                                <div id="map">
                                    
                                </div>
                            </div>
                            <?php
                        }
                     ?>
                </div>

                <div class="col-md-6 order-details">
                    <h5 class="mb-3">Détails de la commande</h5>
                    <p>
                        <strong>Date:</strong> <?php echo date('d/m/Y', strtotime($orders['order_date'])); ?><br>
                        <strong>Statut:</strong> En traitement
                    </p>

                    <h5 class="mb-3 mt-4">Articles commandés</h5>
                    <ul class="list-group mb-3">
                        <?php foreach ($orders['cart'] as $item): ?>
                            <li class="list-group-item d-flex justify-content-between">
                                <span><?php echo $item['name']; ?> × <?php echo $item['quantity']; ?></span>
                                <span><?php echo number_format($item['price'], 2); ?> €</span>
                            </li>
                        <?php endforeach; ?>
                    </ul>

                    <div class="d-flex justify-content-between fw-bold fs-5">
                        <span>Total Commande</span>
                        <input type="hidden" name="odersTotal" value="<?php echo number_format($orders['total'], 2); ?>" id="odersTotal">
                        <p><?php echo number_format($orders['total'], 2); ?> €</p>
                    </div>
                    <?php 
                        if($delivery == 'livraison'){
                            ?>
                                <div id="totalDelivery" class="d-flex justify-content-between fw-bold fs-5">
                                    <span>Total Livraison</span>
                                </div>
                                <div id="ordersPlusDelivery" class="d-flex justify-content-between fw-bold fs-5">
                                    <span>Total Commande + Livraison</span>
                                </div>
                            <?php
                        }
                    ?>
                </div>
            </div>

            <div class="text-center mt-5">
                <a href="<?php echo RACINE; ?>" class="btn btn-primary">Retour à la boutique</a>
            </div>
        </div>
    </div>
</div>

<?php 
    include_once '../inc/footer.php';