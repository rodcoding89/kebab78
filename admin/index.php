<?php 
    require_once '../inc/init.php';
    if(!isAdminOn()){
        header('location:'.RACINE);
        exit;
    }
    $title = "Gestion du restaurant";

    require_once 'nav.php';

    $resultat = selectQuery("SELECT * FROM client");
    $table = '<table class="table table-striped"><head><tr><th>Nom/Prénom</th><th>Email</th><th>Tel</th><th>Addresse</th><th>Ville</th><th>Code postale</th><th>Country</th><thead><tbody>';
    while ($client = $resultat->fetch(PDO::FETCH_ASSOC)) {
        $table .= '<tr>';
        $table .= '<td>' . $client['first_name'] .' '.$client['last_name'] .'</td>';
        $table .= '<td>' . $client['email'] . '</td>';
        $table .= '<td>' . $client['tel'] . '</td>';
        $table .= '<td>' . $client['street'].'</td>';
        $table .= '<td>' . $client['city'] . '</td>';
        $table .= '<td>' . $client['postal_code'] . '</td>';
        $table .= '<td>' . $client['country'] . '</td>';
        $table .= '</tr>';
    }
    $table .= '</tbody></table>';
?>
    <div class="cbloc product-content">
        <h3>Liste client</h3>
        <?php echo $table; ?>
    </div>
<?php 
    require_once 'footer.php';