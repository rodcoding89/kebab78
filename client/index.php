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
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header('location:'.RACINE);
    exit;
}

// Calcul du total
$total = 0;
foreach ($_SESSION['cart'] as $item) {
    $total += $item['price'] * $item['quantity'];
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validation et traitement des données
    $errors = [];
    
    // Validation des champs
    $requiredFields = ['first_name', 'last_name', 'email', 'street', 'postal_code', 'city'];
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
        
        // Pour l'exemple, on stocke en session
        $_SESSION['order'] = [
            'customer' => [
                'first_name' => htmlspecialchars($_POST['first_name']),
                'last_name' => htmlspecialchars($_POST['last_name']),
                'email' => htmlspecialchars($_POST['email']),
                'phone' => htmlspecialchars($_POST['phone']),
                'street' => htmlspecialchars($_POST['street']),
                'postal_code' => htmlspecialchars($_POST['postal_code']),
                'city' => htmlspecialchars($_POST['city']),
            ],
            'cart' => $_SESSION['cart'],
            'total' => $total,
            'order_date' => date('Y-m-d H:i:s')
        ];
        
        // Vider le panier
        unset($_SESSION['cart']);
        
        // Rediriger vers la confirmation
        header('Location:'. RACINE.'confirmation');
        exit;
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
                        <?php foreach ($_SESSION['cart'] as $item): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <img src="images/<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>" class="cart-item-img me-3">
                                    <div>
                                        <h6 class="mb-0"><?php echo $item['name']; ?></h6>
                                        <small class="text-muted">Quantité: <?php echo $item['quantity']; ?></small>
                                    </div>
                                </div>
                                <span><?php echo number_format($item['price'] * $item['quantity'], 2); ?> €</span>
                            </li>
                        <?php endforeach; ?>
                    </ul>

                    <div class="d-flex justify-content-between fw-bold fs-5">
                        <span>Total</span>
                        <span><?php echo number_format($total, 2); ?> €</span>
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
                    <form id="checkoutForm" method="post">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="firstName" class="form-label">Prénom</label>
                                <input type="text" class="form-control <?php echo isset($errors['first_name']) ? 'is-invalid' : ''; ?>" 
                                       id="firstName" name="first_name" value="<?php echo $_POST['first_name'] ?? ''; ?>">
                                <?php if (isset($errors['first_name'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['first_name']; ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="lastName" class="form-label">Nom</label>
                                <input type="text" class="form-control <?php echo isset($errors['last_name']) ? 'is-invalid' : ''; ?>" 
                                       id="lastName" name="last_name" value="<?php echo $_POST['last_name'] ?? ''; ?>">
                                <?php if (isset($errors['last_name'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['last_name']; ?></div>
                                <?php endif; ?>
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
                            <input type="text" class="form-control <?php echo isset($errors['street']) ? 'is-invalid' : ''; ?>" 
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