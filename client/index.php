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
// Vérifier si le panier existe
if (!isset($_SESSION['orders'])) {
    header('location:'.RACINE);
    exit;
}

$orders = $_SESSION['orders'];
debug($_SESSION['mode']);
$totalCount = 0;
foreach ($orders as $key => $value) {
    $totalCount += $value['total'];
}
$total = number_format($totalCount, 2);
//debug($orders);
function handleExtrat($orders){
    $orderExtrat = array();
    foreach ($orders as $key => $value) {
        $extrat = $value['extrat'];
        array_push($orderExtrat,$extrat);
    }
    return $orderExtrat;
}
$extratOrder = handleExtrat($orders);
// Vérifiez s'il y a des erreurs lors de l'encodage JSON
if (json_last_error() !== JSON_ERROR_NONE) {
    throw new Exception("Erreur d'encodage JSON : " . json_last_error_msg());
}

$orders_content = json_encode($extratOrder);
// Vérifiez que $orders_content est une chaîne JSON valide
if (!is_string($orders_content)) {
    throw new Exception("Les données ne sont pas une chaîne JSON valide.");
}
//debug($orders_content);
function initCart($orders){
    $carts = array();
    foreach ($orders as $key => $value) {
        $item = [
            "productId"=>$value["productId"],
            "name"=>$value["name"],
            "quantity"=>$value["quantity"],
            "price"=>$value["total"]
        ];
        array_push($carts, $item);
    }
    return $carts;
}
// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validation et traitement des données
    $errors = [];
    
    // Validation des champs
    $requiredFields = ['first_name', 'last_name', 'email', 'street', 'postal_code', 'city', 'country'];
    foreach ($requiredFields as $field) {
        if (empty($_POST[$field])) {
            $errors[$field] = 'Ce champ est obligatoire';
        }
    }
    
    // Validation email
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Email invalide';
    }
    
    // Si pas d'erreurs, traiter la commande
    if (empty($errors)) {
        // Ici vous pourriez enregistrer la commande en base de données
        // puis rediriger vers une page de confirmation
        global $pdo;
        // Pour l'exemple, on stocke en session
        $selectClient = selectQuery("SELECT client_id FROM client WHERE first_name =:first_name AND last_name =:last_name",array(
            "first_name"=>$_POST['first_name'],
            "last_name"=>$_POST['last_name']
        ));
        if ($selectClient->rowCount() > 0) {
            $result = $selectClient->fetch(PDO::FETCH_ASSOC);
            $client_id = $result['client_id'];
        }else{
            $insertSQL = "INSERT INTO `client` (first_name, last_name, email, tel, street, postal_code, city, country,lat,lon) VALUES (:first_name, :last_name, :email, :tel, :street, :postal_code, :city, :country,:lat,:lon)";
            $stmt = $pdo->prepare($insertSQL);

            // Liaison des paramètres
            $stmt->bindParam(':first_name', $first_name);
            $stmt->bindParam(':last_name', $last_name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':tel', $tel);
            $stmt->bindParam(':street', $street);
            $stmt->bindParam(':postal_code', $postal_code);
            $stmt->bindParam(':city', $city);
            $stmt->bindParam(':country', $country);
            $stmt->bindParam(':lat', $lat);
            $stmt->bindParam(':lon', $lon);

            // Valeurs à insérer
            $first_name = $_POST['first_name'];
            $last_name = $_POST['last_name'];
            $email = $_POST['email'];
            $tel = $_POST['phone'];
            $street = $_POST['street'];
            $postal_code = $_POST['postal_code'];
            $city = $_POST['city'];
            $country = $_POST['country'];
            $lat = $_POST['inputLat'];
            $lon = $_POST['inputLon'];

            // Exécution de la requête
            if (!$stmt->execute()) {
                throw new Exception("Erreur lors de l'insertion dans la table orders.");
            }

            // Récupération de l'ID inséré
            $client_id = $pdo->lastInsertId();
        }

        if(isset($client_id) && !empty($client_id)){
            $insertOrdersSQL = "INSERT INTO `orders` (orders_ref, client_id, orders_date, total_amount,orders_content, order_status) VALUES (:orders_ref, :client_id, :orders_date, :total_amount, :orders_content, :order_status)";
            $stmt1 = $pdo->prepare($insertOrdersSQL);
             $stmt1->bindParam(':orders_ref', $orders_ref);
            $stmt1->bindParam(':client_id', $client_id);
            $stmt1->bindParam(':orders_date', $orders_date);
            $stmt1->bindParam(':total_amount', $total);
            $stmt1->bindParam(':orders_content', $orders_content);
            $stmt1->bindParam(':order_status', $order_status);

            $orders_ref = 'REF-'.time();
            $client_id = $client_id;
            $total = $total;
            $orders_date = date("Y-m-d");
            $order_status = 'pending';
            $orders_content = $orders_content;
            // Exécution de la requête

            // Exécution de la deuxième requête
            if (!$stmt1->execute()) {
                throw new Exception("Erreur lors de l'insertion dans la table orders.");
            }

            $order_id = $pdo->lastInsertId();
            //echo 'order_id'.$order_id;
            if(isset($order_id) && !empty($order_id)){
                foreach ($orders as $key => $value) {
                    $insertOrdersProductSQL = "INSERT INTO `orders_product` (orders_id, product_id) VALUES (:orders_id, :product_id)";
                    $stmt2 = $pdo->prepare($insertOrdersProductSQL);
                    $stmt2->bindParam(':orders_id', $orders_id);
                    $stmt2->bindParam(':product_id', $product_id);

                    $orders_id = $order_id;
                    $product_id = $value['productId'];

                    // Exécution de la requête
                    if (!$stmt2->execute()) {
                        throw new Exception("Erreur lors de l'insertion dans la table orders_product.");
                    }
                }
                
                $_SESSION['confirmation'] = [
                    "customer"=>[
                        "first_name" =>$_POST['first_name'],
                        "last_name" =>$_POST['last_name'],
                        "email" =>$_POST['email'],
                        "phone" =>$_POST['phone'],
                        "street" =>$_POST['street'],
                        "postal_code" =>$_POST['postal_code'],
                        "city" =>$_POST['city'],
                        "country" =>$_POST['country']
                    ],
                    "mode"=>$orders["mode"],
                    "order_date"=> date("Y-m-d"),
                    "cart"=>initCart($orders),
                    "total"=>$total,
                    "ref"=>$orders_ref
                ];
                unset($_SESSION['cart']);
                unset($_SESSION['orders']);
                unset($_SESSION['id']);
                unset($_SESSION['cid']);
                header('Location:'.RACINE.'confirmation?delivery='.$_SESSION['mode']);
                exit;
            }
        }
    }
}
$title = "Finaliser la commande";
include_once '../inc/header.php';
?>
<div id="client" class="client">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Votre commande</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <span>Produits</span>
                        <span>Total</span>
                    </div>

                    <ul class="list-group mb-3">
                        <?php foreach ($orders as $item): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <img src="<?php echo RACINE.$item['image']; ?>" alt="<?php echo $item['name']; ?>" class="cart-item-img me-3">
                                    <div>
                                        <h6 style="font-size:17px;" class="mb-0"><?php echo $item['name']; ?></h6>
                                        <small class="text-muted">Quantité: <?php echo $item['quantity']; ?></small>
                                    </div>
                                </div>
                                <div>
                                    <span style="font-size:18px;font-weight:bold;color:cadetblue;">Sous Total: <em style=" font-size: 15px; color: #6f6f6f; font-style: normal;"><?php echo number_format($item['subTotal'], 2); ?> €</em></span>
                                    <span style="font-size:18px;color:cadetblue;">TVA <?php echo $item['tva']; ?>: <em style="font-weight:bold; font-size: 15px; color: #6f6f6f; font-style: normal;"><?php echo number_format($item['tvaPrice'],2); ?> €</em></span>
                                    <span style="font-size:18px;font-weight:bold;color:cadetblue;">Total: <em style=" font-size: 15px; color: #6f6f6f; font-style: normal;"><?php echo number_format($item['total'], 2); ?> €</em> </span>
                                </div> 
                                
                            </li>
                        <?php endforeach; ?>
                    </ul>

                    <div class="d-flex justify-content-between fw-bold fs-5">
                        <span style="font-size:40px;font-weight: bold;">Total</span>
                        <span style="font-size:18px;color: gray;"><?php echo $total; ?> €</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Informations client</h5>
                </div>
                <div class="card-body">
                    <form id="checkoutForm" method="post" autocomplete="off">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="firstName" class="form-label">Prénom</label>
                                <input type="text" class="form-control <?php echo isset($errors['first_name']) ? 'is-invalid' : ''; ?>" 
                                       id="firstName" name="first_name" value="<?php echo $_POST['first_name'] ?? ''; ?>">
                                <?php if (isset($errors['first_name'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['first_name']; ?></div>
                                <?php endif; ?>
                                <div id="firstNameResult" class="filter"></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="lastName" class="form-label">Nom</label>
                                <input type="text" class="form-control <?php echo isset($errors['last_name']) ? 'is-invalid' : ''; ?>" 
                                       id="lastName" name="last_name" value="<?php echo $_POST['last_name'] ?? ''; ?>">
                                <?php if (isset($errors['last_name'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['last_name']; ?></div>
                                <?php endif; ?>
                                <div id="lastNameResult" class="filter"></div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control <?php echo isset($errors['email']) ? 'is-invalid' : ''; ?>" 
                                   id="email" name="email" value="<?php echo $_POST['email'] ?? ''; ?>">
                            <?php if (isset($errors['email'])): ?>
                                <div class="invalid-feedback"><?php echo $errors['email']; ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Téléphone (optionnel)</label>
                            <input type="tel" class="form-control" id="phone" name="phone" value="<?php echo $_POST['phone'] ?? ''; ?>">
                        </div>

                        <hr class="my-4">

                        <h5 class="mb-3">Adresse de livraison</h5>

                        <div class="mb-3 position-relative">
                            <label for="street" class="form-label">Adresse</label>
                            <input type="text" autocomplete="nope" class="form-control <?php echo isset($errors['street']) ? 'is-invalid' : ''; ?>" 
                                   id="street" name="street" value="<?php echo $_POST['street'] ?? ''; ?>" 
                                   placeholder="Commencez à taper votre adresse...">
                            <div id="suggestions"></div>
                            <?php if (isset($errors['street'])): ?>
                                <div class="invalid-feedback"><?php echo $errors['street']; ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="postalCode" class="form-label">Code postal</label>
                                <input type="text" class="form-control <?php echo isset($errors['postal_code']) ? 'is-invalid' : ''; ?>" 
                                       id="postalCode" name="postal_code" value="<?php echo $_POST['postal_code'] ?? ''; ?>" readonly>
                                <?php if (isset($errors['postal_code'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['postal_code']; ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-8 mb-3">
                                <label for="city" class="form-label">Ville</label>
                                <input type="text" class="form-control <?php echo isset($errors['city']) ? 'is-invalid' : ''; ?>" 
                                       id="city" name="city" value="<?php echo $_POST['city'] ?? ''; ?>" readonly>
                                <?php if (isset($errors['city'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['city']; ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="city" class="form-label">Pays</label>
                            <input type="text" class="form-control <?php echo isset($errors['country']) ? 'is-invalid' : ''; ?>" 
                                   id="country" name="country" value="<?php echo $_POST['country'] ?? ''; ?>" readonly>
                            <?php if (isset($errors['country'])): ?>
                                <div class="invalid-feedback"><?php echo $errors['country']; ?></div>
                            <?php endif; ?>
                        </div>
                        <hr class="my-4">

                        <button type="submit" class="btn btn-primary btn-lg w-100">Confirmer la commande</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php 
    include_once '../inc/footer.php';