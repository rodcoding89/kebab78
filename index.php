<?php 
    include_once 'inc/init.php';
    $title = "Acceuil Kebab de la gare";
    include_once 'inc/header.php';
?>
<section class="kebab">
    <?php 
        if(!isOn()){
            ?>
            <div class="auth">
                <h1 class="mb-2 wordCarousel">
                    <span>KEBAB DE LA GARE</span>
                    <div>
                        <!-- Use classes 2,3, or 4 to match the number of words -->
                        <ul class="flip4">
                            <li>Bienvenu</li>
                            <li>A emporter</li>
                            <li>Sur place</li>
                            <li>Commendez</li>
                        </ul>
                    </div>
                </h1>
                <p class="mb-5">Pour gérer vos commandes veillez vous connecter</p>
                <form action="" method="post">
                    <div class="mb-3 id">
                        <label for="exampleFormControlInput1" class="form-label">Entrez votre identifiant</label>
                        <div class="input-group flex-nowrap">
                            <span class="input-group-text" id="addon-wrapping"><i class="fas fa-fingerprint"></i></span>
                            <input type="text" class="form-control idf" placeholder="Identifiant" aria-label="Identifiant" aria-describedby="addon-wrapping" value="kebab78-1" disabled="true">
                        </div>
                        <!--<div class="new-message-box">
                            <div class="new-message-box-danger">
                                <div class="info-tab tip-icon-danger" title="error"><i class="fas fa-times"></i><i></i></div>
                                <div class="tip-box-danger">
                                   <p>error</p>
                                </div>
                            </div>
                        </div>-->
                        <!--<div class="new-message-box">
                            <div class="new-message-box-info">
                                <div class="info-tab tip-icon-info" title="info"><i class="fas fa-info"></i><i></i></div>
                                <div class="tip-box-info">
                                   <p>info</p>
                                </div>
                            </div>
                        </div>
                        <div class="new-message-box">
                            <div class="new-message-box-success">
                                <div class="info-tab tip-icon-success" title="success"><i class="fas fa-check"></i><i></i></div>
                                <div class="tip-box-success">
                                   <p>success</p>
                                </div>
                            </div>
                        </div>-->
                    </div>
                    <div class="mb-3 mdp">
                        <label for="exampleFormControlInput1" class="form-label">Entrez votre mot de passe</label>
                        <div class="input-group flex-nowrap">
                            <span class="input-group-text" id="addon-wrapping"><i class="fas fa-key"></i></span>
                            <input type="password" class="form-control key" placeholder="Mot de passe" aria-label="Mot de passe" aria-describedby="addon-wrapping" value="kebab78" disabled="true">
                        </div>
                    </div>
                    <div class="submit">
                        <button type="submit" class="btn btn-primary w-100 mt-4 py-10 conn" id="load2" data-loading-text="Traitement en cours <i class='fa fa-spinner fa-spin '></i>">Connexion</button>
                    </div>
                </form>
            </div>
            <?php
        }else{
            ?>
            <div class="commande-mode">
                <h2>Choisissez votre mode de commande</h2>
                <div class="content">
                    <div class="surplace item">
                        <a href="categorie?surplace">
                            <i class="fas fa-utensils"></i>
                            <h4>Sur place</h4>
                        </a>
                    </div>
                    <div class="emporter item">
                        <a href="categorie?livraison">
                            <i></i>
                            <h4>Livraison</h4>
                        </a>
                    </div>
                </div>
                <?php 
                    if(isAdminOn()){
                        ?>
                        <div class="admin"><a href="<?php echo RACINE.'admin'?>">Gérez votre boutique<i class="far fa-arrow-alt-circle-right"></i></a></div>
                        <?php
                    }
                ?>
            </div>
            <?php
        }
    ?>
</section>


<?php
    include_once 'inc/footer.php';