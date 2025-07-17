<?php
include_once 'init.php';
$res = array();

if (isset($_POST)) {
    if ($_POST['postType'] == 'userAuth') {
        $result = selectQuery("SELECT * FROM user WHERE identifiant = :idf", array(
            ':idf' => $_POST['idf']
        ));

        if ($result->rowCount() > 0) {
            $user = $result->fetch(PDO::FETCH_ASSOC);
            if (password_verify($_POST['mdp'], $user['mdp'])) {
                session_regenerate_id(true); // Regenerate session ID to prevent session fixation
                $_SESSION['user'] = $user;
                $res['resultat'] = 'success';
            } else {
                $res['mdpError'] = 'Votre mot de passe n\'est pas correct';
            }
        } else {
            $res['idfError'] = 'Votre identifiant n\'est pas valide';
        }
        echo json_encode($res);
    } elseif ($_POST['postType'] == 'addProduct') {
        $imgUrl = uniqid() . '_' . basename($_FILES['file']['name']); // Use basename to prevent directory traversal
        $path = '../assets/images/product-images/' . $imgUrl;
        $check = selectQuery("SELECT * FROM product WHERE product_name = :pname AND categorie = :pcateg", array(
            ':pname' => $_POST['pname'],
            ':pcateg' => $_POST['pcateg']
        ));

        if ($check->rowCount() > 0) {
            $res['sqlError'] = 'Le Produit que vous souhaitez ajouter existe déjà.';
        } else {
            if (!file_exists($path)) {
                $dburl = 'assets/images/product-images/' . $imgUrl;
                if (move_uploaded_file($_FILES['file']['tmp_name'], $path)) { // Use move_uploaded_file instead of copy for security
                    $result = actionQuery("INSERT INTO product (product_name, categorie, description, prix_sans_livraison, prix_avec_livraison, img_url, product_create_date) VALUES(:pname, :pcateg, :pdescrip, :ppwith, :ppwithout, :purl, NOW())", array(
                        ':pname' => $_POST['pname'],
                        ':pcateg' => $_POST['pcateg'],
                        ':pdescrip' => $_POST['pdescrip'],
                        ':ppwith' => $_POST['ppwith'],
                        ':ppwithout' => $_POST['ppwithout'],
                        ':purl' => $dburl
                    ));
                    if ($result) {
                        $res['resultat'] = 'Le produit a été inséré avec succès';
                    } else {
                        $res['sqlError'] = 'Erreur produite lors de l\'insertion du produit';
                    }
                } else {
                    $res['imgError'] = 'Erreur produite lors de l\'insertion de l\'image';
                }
            }
        }
        echo json_encode($res);
    } elseif ($_POST['postType'] == 'getCategorie') {
        $result = selectQuery("SELECT * FROM categorie");
        $res['resultat'] = $result->fetchAll(PDO::FETCH_ASSOC); // Fetch all results at once
        echo json_encode($res);
    } elseif ($_POST['postType'] == 'createCategorie') {
        $result = selectQuery("SELECT * FROM categorie WHERE categorie_name = :cname", array(
            ':cname' => $_POST['cname']
        ));

        if ($result->rowCount() > 0) {
            $res['categExist'] = 'La catégorie que vous voulez créer existe déjà.';
        } else {
            $imgUrl = uniqid() . '_' . basename($_FILES['file']['name']);
            $path = '../assets/images/product-images/' . $imgUrl;
            if (!file_exists($path)) {
                $dburl = 'assets/images/product-images/' . $imgUrl;
                if (move_uploaded_file($_FILES['file']['tmp_name'], $path)) {
                    $result = actionQuery("INSERT INTO categorie (categorie_name, img_url) VALUES(:cname, :img_url)", array(
                        ':cname' => $_POST['cname'],
                        ':img_url' => $dburl
                    ));
                    if ($result) {
                        $res['resultat'] = 'Votre nouvelle catégorie de produit a été créée avec succès.';
                    } else {
                        $res['categExist'] = 'Erreur produite lors de la création de la catégorie';
                    }
                } else {
                    $res['imgError'] = 'Erreur produite lors de l\'insertion de l\'image';
                }
            }
        }
        echo json_encode($res);
    } elseif ($_POST['postType'] == 'addExtrat') {
        $imgUrl = uniqid() . '_' . basename($_FILES['file']['name']);
        $path = '../assets/images/product-images/' . $imgUrl;
        $check = selectQuery("SELECT * FROM extrat WHERE extrat_name = :sname AND categ_ref = :pcateg", array(
            ':sname' => $_POST['sname'],
            ':pcateg' => $_POST['pcateg']
        ));

        if ($check->rowCount() > 0) {
            $res['sqlError'] = 'Le supplément que vous souhaitez ajouter existe déjà.';
        } else {
            $prix = 0.0;
            if ($_POST['sprix'] != '') {
                $prix = $_POST['sprix'];
            }
            if (!file_exists($path)) {
                $dburl = 'assets/images/product-images/' . $imgUrl;
                if (move_uploaded_file($_FILES['file']['tmp_name'], $path)) {
                    $result = actionQuery("INSERT INTO extrat (extrat_name, extrat_categ, prix, img_url, categ_ref, s_create_date) VALUES(:sname, :scateg, :sprix, :surl, :pcateg, NOW())", array(
                        ':sname' => $_POST['sname'],
                        ':scateg' => $_POST['scateg'],
                        ':sprix' => $prix,
                        ':surl' => $dburl,
                        ':pcateg' => $_POST['pcateg']
                    ));
                    if ($result) {
                        $res['resultat'] = 'Le Supplément a été inséré avec succès';
                    } else {
                        $res['sqlError'] = 'Erreur produite lors de l\'insertion du supplément';
                    }
                } else {
                    $res['imgError'] = 'Erreur produite lors de l\'insertion de l\'image';
                }
            }
        }
        echo json_encode($res);
    } elseif ($_POST['postType'] == 'getExtrat') {
        $result = selectQuery("SELECT * FROM extrat");
        $resultat = selectQuery("SELECT * FROM categorie");
        if ($result->rowCount() > 0) {
            $res['resultat'] = $result->fetchAll(PDO::FETCH_ASSOC);
            $res['categ'] = $resultat->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $res['emptyTable'] = 'Vous n\'avez pour l\'instant inséré aucun supplément';
        }
        echo json_encode($res);
    } elseif ($_POST['postType'] == 'getSingleExtrat') {
        $result = selectQuery("SELECT * FROM extrat WHERE extrat_id = :id", array(
            ':id' => $_POST['sid']
        ));
        $res['resultat'] = $result->fetch(PDO::FETCH_ASSOC);
        $resultat = selectQuery("SELECT * FROM categorie");
        $res['categ'] = $resultat->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($res);
    } elseif ($_POST['postType'] == 'updateExtrat') {
        if (isset($_FILES['file'])) {
            $path = '../' . $_POST['img_url'];
            if (file_exists($path) && !empty($_POST['img_url'])) {
                unlink($path);
            }
            $img = uniqid() . '_' . basename($_FILES['file']['name']);
            $folder = '../assets/images/product-images/';
            if (move_uploaded_file($_FILES['file']['tmp_name'], $folder . $img)) {
                $dburl = 'assets/images/product-images/' . $img;
                $result = actionQuery("UPDATE extrat SET extrat_name = :ename, extrat_categ = :ecateg, prix = :eprix, img_url = :img_url, categ_ref = :categ_ref WHERE extrat_id = :eid", array(
                    ':ename' => $_POST['sname'],
                    ':ecateg' => $_POST['scateg'],
                    ':eprix' => $_POST['sprix'],
                    ':img_url' => $dburl,
                    ':categ_ref' => $_POST['pcateg'],
                    ':eid' => $_POST['eid']
                ));
                if ($result) {
                    $res['resultat'] = "Le supplément a été modifié avec succès";
                } else {
                    $res['sqlError'] = "Une erreur s'est produite. Veuillez réessayer plus tard";
                }
            }
        } else {
            $result = actionQuery("UPDATE extrat SET extrat_name = :ename, extrat_categ = :ecateg, prix = :eprix, img_url = :img_url, categ_ref = :categ_ref WHERE extrat_id = :eid", array(
                ':ename' => $_POST['sname'],
                ':ecateg' => $_POST['scateg'],
                ':eprix' => $_POST['sprix'],
                ':img_url' => $_POST['img_url'],
                ':categ_ref' => $_POST['pcateg'],
                ':eid' => $_POST['eid']
            ));
            if ($result) {
                $res['resultat'] = "Le supplément a été modifié avec succès";
            } else {
                $res['sqlError'] = "Une erreur s'est produite. Veuillez réessayer plus tard";
            }
        }
        echo json_encode($res);
    } elseif ($_POST['postType'] == 'deleteExtrat') {
        $result = actionQuery("DELETE FROM extrat WHERE extrat_id = :eid", array(
            ':eid' => $_POST['eid']
        ));
        if ($result) {
            $res['resultat'] = "success";
        } else {
            $res['sqlError'] = "Une erreur s'est produite. Veuillez réessayer plus tard";
        }
        echo json_encode($res);
    } elseif ($_POST['postType'] == 'getProduct') {
        $result = selectQuery("SELECT * FROM product");
        $resultat = selectQuery("SELECT * FROM categorie");
        if ($result->rowCount() > 0) {
            $res['resultat'] = $result->fetchAll(PDO::FETCH_ASSOC);
            $res['categ'] = $resultat->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $res['emptyTable'] = 'Vous n\'avez pour l\'instant inséré aucun produit';
        }
        echo json_encode($res);
    } elseif ($_POST['postType'] == 'getSingleProduct') {
        $result = selectQuery("SELECT * FROM product WHERE product_id = :pid", array(
            ':pid' => $_POST['pid']
        ));
        $res['resultat'] = $result->fetch(PDO::FETCH_ASSOC);
        $resultat = selectQuery("SELECT * FROM categorie");
        $res['categ'] = $resultat->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($res);
    } elseif ($_POST['postType'] == 'updateProduct') {
        if (isset($_FILES['file'])) {
            $path = '../' . $_POST['img_url'];
            if (file_exists($path) && !empty($_POST['img_url'])) {
                unlink($path);
            }
            $img = uniqid() . '_' . basename($_FILES['file']['name']);
            $folder = '../assets/images/product-images/';
            if (move_uploaded_file($_FILES['file']['tmp_name'], $folder . $img)) {
                $dburl = 'assets/images/product-images/' . $img;
                $result = actionQuery("UPDATE product SET product_name = :pname, categorie = :pcateg, description = :pdescrip, prix_sans_livraison = :ppwithout, prix_avec_livraison = :ppwith, img_url = :img_url WHERE product_id = :pid", array(
                    ':pname' => $_POST['pname'],
                    ':pcateg' => $_POST['pcateg'],
                    ':pdescrip' => $_POST['pdescrip'],
                    ':img_url' => $dburl,
                    ':ppwithout' => $_POST['ppwithout'],
                    ':ppwith' => $_POST['ppwith'],
                    ':pid' => $_POST['pid']
                ));
                if ($result) {
                    $res['resultat'] = "Le produit a été modifié avec succès";
                } else {
                    $res['sqlError'] = "Une erreur s'est produite. Veuillez réessayer plus tard";
                }
            }
        } else {
            $result = actionQuery("UPDATE product SET product_name = :pname, categorie = :pcateg, description = :pdescrip, prix_sans_livraison = :ppwithout, prix_avec_livraison = :ppwith, img_url = :img_url WHERE product_id = :pid", array(
                ':pname' => $_POST['pname'],
                ':pcateg' => $_POST['pcateg'],
                ':pdescrip' => $_POST['pdescrip'],
                ':img_url' => $_POST['img_url'],
                ':ppwithout' => $_POST['ppwithout'],
                ':ppwith' => $_POST['ppwith'],
                ':pid' => $_POST['pid']
            ));
            if ($result) {
                $res['resultat'] = "Le produit a été modifié avec succès";
            } else {
                $res['sqlError'] = "Une erreur s'est produite. Veuillez réessayer plus tard";
            }
        }
        echo json_encode($res);
    } elseif ($_POST['postType'] == 'deleteProduct') {
        $result = actionQuery("DELETE FROM product WHERE product_id = :pid", array(
            ':pid' => $_POST['pid']
        ));
        if ($result) {
            $res['resultat'] = "success";
        } else {
            $res['sqlError'] = "Une erreur s'est produite. Veuillez réessayer plus tard";
        }
        echo json_encode($res);
    } elseif ($_POST['postType'] == 'updateCategorie') {
        if (isset($_FILES['file'])) {
            $parentPaht = dirname(__DIR__);
            $uploadDir = $parentPaht.'/assets/images/product-images/';
            // Vérifiez si le répertoire existe, sinon créez-le
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true); // Le troisième paramètre true permet de créer des répertoires imbriqués
            }
            $img = $uploadDir . basename($_FILES['file']['name']);
            if (file_exists($img) && !empty($_POST['img_url'])) {
                unlink($img);
            }
            
            if (move_uploaded_file($_FILES['file']['tmp_name'], $img)) {
                $dburl = 'assets/images/product-images/' . basename($_FILES['file']['name']);
                $result = actionQuery("UPDATE categorie SET categorie_name = :cname, img_url = :imgurl WHERE categorie_id = :cid", array(
                    ':cid' => $_POST['cid'],
                    ':cname' => $_POST['cname'],
                    ':imgurl' => $dburl
                ));
                if ($result) {
                    $res['resultat'] = "La catégorie a été modifiée avec succès";
                } else {
                    $res['sqlError'] = "Une erreur s'est produite. Veuillez réessayer plus tard";
                }
            }
        } else {
            $result = actionQuery("UPDATE categorie SET categorie_name = :cname, img_url = :imgurl WHERE categorie_id = :cid", array(
                ':cid' => $_POST['cid'],
                ':cname' => $_POST['cname'],
                ':imgurl' => $_POST['img_url']
            ));
            if ($result) {
                $res['resultat'] = "La catégorie a été modifiée avec succès";
            } else {
                $res['sqlError'] = "Une erreur s'est produite. Veuillez réessayer plus tard";
            }
        }
        echo json_encode($res);
    } elseif ($_POST['postType'] == 'getSingleCategorie') {
        $result = selectQuery("SELECT * FROM categorie WHERE categorie_id = :cid", array(
            ':cid' => $_POST['cid']
        ));
        $res['resultat'] = $result->fetch(PDO::FETCH_ASSOC);
        echo json_encode($res);
    } elseif ($_POST['postType'] == 'deleteCategorie') {
        $result = actionQuery("DELETE FROM categorie WHERE categorie_id = :cid", array(
            ':cid' => $_POST['cid']
        ));
        if ($result) {
            $res['resultat'] = "success";
        } else {
            $res['sqlError'] = "Une erreur s'est produite. Veuillez réessayer plus tard";
        }
        echo json_encode($res);
    } elseif($_POST['postType'] == 'filterClient'){
        $searchTerm = '%'.$_POST['searchTerm'].'%';
        $result = selectQuery("SELECT * FROM client WHERE first_name LIKE :term OR last_name LIKE :term",array(
            "term"=>$searchTerm
        ));
        if ($result->rowCount() > 0) {
            $res['client'] = $result->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $res['noclient'] = null;
        }
        echo json_encode($res);
    } elseif($_POST['postType'] == 'addCart'){
        $product = $_POST['product'];
        $productId = $product['productId'];
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId]['quantity'] += 1;
        }else {
            $_SESSION['cart'][$productId] = [
                'id' => $productId,
                'name' => $product['productName'],
                'price' => $product['productPrice'],
                'image' => $product['productImg'],
                'quantity' => 1,
                'extract'=>isset($product['extract']) ? $product['extract'] : null,
                'deliveryMode'=>$product['deliveryMode']
            ];
            $_SESSION['id'] = $productId;
            $_SESSION['mode'] = $product['deliveryMode'];
            $_SESSION['cid'] = $product['cid'];
        }
        $res["result"] = "product_added";
        $res["count"] = count($_SESSION["cart"]);
        echo json_encode($res);
    }
}
?>
