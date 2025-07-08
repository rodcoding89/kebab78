let env = 'dev';
let RACINE = env === 'prod' ? '' : '/Kebab78/';
console.log(RACINE);
$(function(){
    if($('main .kebab').length){
        /** we submit the form. Inside the submit event, we get the necessary parameter and send this to the server. In the callback function we check the status of the response */
        $('main .kebab .auth form').on('submit',function(e){
            let idf = $(this).find('.idf').val();
            let mdp = $(this).find('.key').val();
            e.preventDefault();

            $('main .kebab .auth .mdp .new-message-box').remove();
            $('main .kebab .auth .id .new-message-box').remove();
            if (idf == '' || idf == undefined) {
                $('main .kebab .auth .id').append('<div class="new-message-box"><div class="new-message-box-danger"><div class="info-tab tip-icon-danger" title="error"><i class="fas fa-times"></i><i></i></div><div class="tip-box-danger"><p>Veillez remplir le champs</p></div></div></div>');
            } if(mdp == '' || mdp == undefined) {
                $('main .kebab .auth .mdp').append('<div class="new-message-box"><div class="new-message-box-danger"><div class="info-tab tip-icon-danger" title="error"><i class="fas fa-times"></i><i></i></div><div class="tip-box-danger"><p>Veillez remplir le champs</p></div></div></div>');
            }
            if(idf != '' && mdp !=''){
                $('main .kebab .auth form .conn').text('').prepend($('main .kebab .auth form .conn').attr('data-loading-text'));
                    
                $.post(RACINE+'inc/controls.php',{'postType':'userAuth',idf:idf,mdp:mdp},(res) =>{
                    setTimeout(() => {
                        if (res.resultat && res.resultat == "success") {
                            window.location.reload();
                        } else if(res.mdpError) {
                            $('main .kebab .auth form .conn').text('Connexion').find('i').remove();
                            $('main .kebab .auth .mdp').append('<div class="new-message-box"><div class="new-message-box-danger"><div class="info-tab tip-icon-danger" title="error"><i class="fas fa-times"></i><i></i></div><div class="tip-box-danger"><p>'+res.mdpError+'</p></div></div></div>');
                        }else if(res.idfError){
                            $('main .kebab .auth form .conn').text('Connexion').find('i').remove();
                            $('main .kebab .auth .id').append('<div class="new-message-box"><div class="new-message-box-danger"><div class="info-tab tip-icon-danger" title="error"><i class="fas fa-times"></i><i></i></div><div class="tip-box-danger"><p>'+res.idfError+'</p></div></div></div>');
                        }
                    }, 2000);
                },'json');
            }
        });
    }
    /**heer we check if we are in the backoffice page */
    if($('main .backoffice').length){
        /**this click event hide some content and display another one */
        $('body').addClass('office');
        $(this).find('.client').on('click',() =>{
            $('.backoffice .content .client-content div').remove();
            $('.backoffice .content .commande-content div').remove();
            $('.backoffice .content .product-content div').remove();
            $('main .backoffice .client').addClass('active').siblings().removeClass('active');
        });

        /**this click event show the drop down menu */
        $(this).find('.product').on('click',() =>{
            $('main .backoffice .product').addClass('active').siblings().removeClass('active');
            $('.backoffice .dropmenu-content').toggleClass("openSubNav");
        });

        /**this click event hide some content and display another one */
        $(this).find('.commande').on('click',() =>{
            $('.backoffice .content .client-content div').remove();
            $('.backoffice .content .commande-content div').remove();
            $('.backoffice .content .product-content div').remove();
            $('main .backoffice .commande').addClass('active').siblings().removeClass('active');
            $('.backoffice .dropmenu').hide(300);
        });

       /**this click event hide some content and display another one. he call also the function getProductData */
        $(this).find('.product-set').on('click',() =>{
            $('.backoffice .content .client-content div').remove();
            $('.backoffice .content .commande-content div').remove();
            $('.backoffice .content .product-content div').remove();
            $('.backoffice .dropmenu').hide(300);
            getProductData();
        });

        /**this click event hide some content and display another one. he call also the function getExtratData */
        $(this).find('.s-set').on('click',() =>{
            $('.backoffice .content .client-content div').remove();
            $('.backoffice .content .commande-content div').remove();
            $('.backoffice .content .product-content div').remove();
            $('.backoffice .dropmenu').hide(300);
            getExtratData();
        });
        /**this click event hide some content and display another one. he call also the function getCategorieData */
        $(this).find('.categorie').on('click',() =>{
            $('.backoffice .content .client-content div').remove();
            $('.backoffice .content .commande-content div').remove();
            $('.backoffice .content .product-content div').remove();
            $('.backoffice .dropmenu').hide(300);
            getCategorieData();
        });

        /**loading */
        /**by clicking this event, it initialize the content with formular. This formular is for product setting */
        $('.backoffice .content .product-content').on('click','.psetting',function(){
            let pid = $(this).find('input').val();
            $('.backoffice .content .product-content .new-message-box').remove();
            $.post(RACINE+'inc/controls.php',{postType:'getSingleProduct',pid:pid},(res) => {
                console.log(res.resultat);
                setTimeout(() => {
                    if(res.resultat){
                        $('.backoffice .content .client-content div').remove();
                        $('.backoffice .content .commande-content div').remove();
                        $('.backoffice .content .product-content div').remove();
                        let option = '<div class="form-floating mb-3"><select class="form-select" id="p-categ" aria-label="Choisir la catégorie du supplément">';
                        res.categ.forEach(el => {
                            if(res.resultat.categorie == el.categorie_id){
                                option += '<option value='+el.categorie_id+' selected>'+el.categorie_name.toLowerCase()+'</option>';
                            }
                        });
                        option += '</select><label for="floatingSelect">Modifier la catégorie du produit</label></div>';
                        $('.backoffice .content .product-content').prepend(`<div class="setting-product"><form action="" method="post" enctype="multipart/form-data"><div class="form-floating mb-3"><input type="hidden" value="${res.resultat.product_id}" id="pid"><input type="text" class="form-control" id="p-name" placeholder="Nom du produit" value="${res.resultat.product_name}"><label for="p-name">Nom du produit</label></div><div class="form-floating mb-3"><textarea class="form-control" placeholder="Description du produit" id="p-descrip" style="height: 100px">${res.resultat.description}</textarea><label for="p-descrip">Description du produit (facultative)</label></div>${option}<div class="form-floating mb-3"><input type="text" class="form-control" id="p-prixWith" placeholder="10.85" value="${res.resultat.prix_avec_livraison}"><label for="p-name">Prix du produit plus livraison</label></div><div class="form-floating mb-3"><input type="text" class="form-control" id="p-prixWithout" placeholder="8.50" value="${res.resultat.prix_sans_livraison}"><label for="p-name">Prix du produit sans livraison</label></div><div class="mb-3"><label for="formFile" class="form-label">Choisir une image pour le produit</label><input class="form-control" type="file" id="formFile" accept="image/*" name="file"><input type="hidden" value="${res.resultat.img_url}" id="img-url"></div><div class="submit mb-3"><button type="submit" class="btn btn-primary w-100 mt-4 py-10 p-setting" id="load2" data-loading-text="Traitement en cours <i class='fa fa-spinner fa-spin'></i>">Modifier ce produit</button></div></form></div>`);
                    }
                },2000);
            },'json');
        });

        /**by clicking this event, it hold the formular content and send this to the server.*/
        $('.backoffice .content .product-content').on('submit','.setting-product form',(e) =>{
            e.preventDefault();
            $('.backoffice .content .product-content .new-message-box').remove();
            let pid = $(this).find("#pid").val();
            let pname = $(this).find("#p-name").val();
            let pdescrip = $(this).find("#p-descrip").val();
            let pcateg = $(this).find("#p-categ option:selected").val();
            let ppwith = $(this).find("#p-prixWith").val();
            let ppwithout = $(this).find("#p-prixWithout").val();
            let file = $(this).find('#formFile').get(0).files[0];
            
            if(pname == '' || pcateg == '' || ppwith == '' || ppwithout == ''){
                alert('Vos données saisis ne sont pas corrects, revérifiez vos champs et retentez la modification');
            }else{
                $('main .backoffice .content .product-content .p-setting').text('').prepend($('main .backoffice .content .product-content .p-setting').attr('data-loading-text'));
                
                var data = new FormData();
                data.append('pid',pid);
                data.append('postType','updateProduct');
                data.append('pname',pname);
                data.append('pdescrip',pdescrip);
                data.append('pcateg',pcateg);
                data.append('ppwith',ppwith);
                data.append('ppwithout',ppwithout);
                if(file == null || file == undefined){
                    data.append('img_url',$(this).find('#img-url').val());
                }else{
                    data.append('file',file);
                    data.append('img_url',$(this).find('#img-url').val());
                }
                $.ajax({
                    url: RACINE+'inc/controls.php', 
                    type: 'POST',
                    data: data,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success : function(res) {
                        setTimeout(() => {
                            if(res.resultat){
                                $('main .backoffice .content .product-content .p-setting').text('Modifier ce produit').find('i').remove();
                                $('main .backoffice .content .product-content').prepend('<div class="new-message-box"><div class="new-message-box-success"><div class="info-tab tip-icon-success" title="succes"><i class="fas fa-check"></i><i></i></div><div class="tip-box-success"><p>'+res.resultat+'</p></div></div></div>');
                                setTimeout(() => {
                                    $('main .backoffice .content .product-content .new-message-box').remove();
                                }, 30000);
                            }else if(res.sqlError){
                                $('main .backoffice .content .product-content .p-setting').text('Modifier ce produit').find('i').remove();
                                $('main .backoffice .content .product-content').prepend('<div class="new-message-box"><div class="new-message-box-danger"><div class="info-tab tip-icon-danger" title="error"><i class="fas fa-times"></i><i></i></div><div class="tip-box-danger"><p>'+res.sqlError+'</p></div></div></div>');
                                setTimeout(() => {
                                    $('main .backoffice .content .product-content .new-message-box').remove();
                                }, 30000);
                            }
                        }, 2000);
                    }
                });
            }
        });

        /**this click event send a request to the server for deleting a particular product */
        $('.backoffice .content .product-content').on('click','.pdelete',function(){
            if(window.confirm('Êtes vous certains de vouloir de vouloir supprimer ce produit?')){
                //console.log($(this).find('input').val());
                let pid = $(this).find('input').val();
                $.post(RACINE+'inc/controls.php',{postType:'deleteProduct','pid':pid},function(res){
                    setTimeout(() => {
                        if(res.resultat == 'success'){
                            $('.backoffice .content .client-content div').remove();
                            $('.backoffice .content .commande-content div').remove();
                            $('.backoffice .content .product-content div').remove();
                            getProductData();
                        }else if(res.sqlError){
                            $('main .backoffice .content .product-content').prepend('<div class="new-message-box"><div class="new-message-box-danger"><div class="info-tab tip-icon-danger" title="error"><i class="fas fa-times"></i><i></i></div><div class="tip-box-danger"><p>'+res.sqlError+'</p></div></div></div>');
                            setTimeout(() => {
                                $('main .backoffice .content .product-content .new-message-box').remove();
                            }, 30000);
                        }
                    }, 2000);
                },'json');
           }else{
               //console.log('annuler');
           }
        });

        /**this click event send a request to the server for deleting a particular categorie */
        $('.backoffice .content .product-content').on('click','.cdelete',function(){
            if(window.confirm('Êtes vous certains de vouloir supprimer cette catégorie? En la supprimant, tous les produits ou suppléments liés à cette catégorie seront également supprimés')){
                //console.log($(this).find('input').val());
                let cid = $(this).find('input').val();
                $.post(RACINE+'inc/controls.php',{postType:'deleteCategorie','cid':cid},function(res){
                    setTimeout(() => {
                        if(res.resultat == 'success'){
                            $('.backoffice .content .client-content div').remove();
                            $('.backoffice .content .commande-content div').remove();
                            $('.backoffice .content .product-content div').remove();
                            getCategorieData();
                        }else if(res.sqlError){
                            $('main .backoffice .content .product-content').prepend('<div class="new-message-box"><div class="new-message-box-danger"><div class="info-tab tip-icon-danger" title="error"><i class="fas fa-times"></i><i></i></div><div class="tip-box-danger"><p>'+res.sqlError+'</p></div></div></div>');
                            setTimeout(() => {
                                $('main .backoffice .content .product-content .new-message-box').remove();
                            }, 30000);
                        }
                    }, 2000);
                },'json');
           }else{
               //console.log('annuler');
           }
        })

        /*loading effect*/
        /**by clicking on the event the dom will be initialized with a formular. The formular had the gool to set a single extrat of a product */
        $('.backoffice .content .product-content').on('click','.setting',function(){
            let sid = $(this).find('input').val();
            $('.backoffice .content .product-content .new-message-box').remove();
            $.post(RACINE+'inc/controls.php',{postType:'getSingleExtrat',sid:sid},(res) => {
                setTimeout(() => {
                    if(res.resultat){
                        $('.backoffice .content .client-content div').remove();
                        $('.backoffice .content .commande-content div').remove();
                        $('.backoffice .content .product-content div').remove();
                        let option = '<div class="form-floating mb-3"><select class="form-select" id="p-categ" aria-label="Choisir la catégorie du supplément">';
                        res.categ.map(el => {
                            if(res.resultat.categ_ref == el.categorie_id){
                                option += '<option value='+el.categorie_id+' selected>'+el.categorie_name.toLowerCase()+'</option>';
                            }else{
                                option += '<option value='+el.categorie_id+'>'+el.categorie_name.toLowerCase()+'</option>';
                            }
                        });
                        option += '</select><label for="floatingSelect">Choisir la catégorie du produit</label></div>';
                        $('.backoffice .content .product-content').prepend(`<div class="setting-extrat"><form action="" method="post" enctype="multipart/form-data"><input type="hidden" id="sid" value="${res.resultat.extrat_id}"><div class="form-floating mb-3"><input type="text" value = "${res.resultat.extrat_name}" class="form-control" id="s-name" placeholder="Nom du supplément"><label for="s-name">Nom du supplément</label></div><div class="form-floating mb-3"><input type="text" class="form-control" value="${res.resultat.extrat_categ}" id="categ-s" placeholder="Légume,Poulet, ..."><label for="categ-s">Donnez une catégorie au supplément</label></div>${option}<div class="form-floating mb-3"><input type="text" value ="${res.resultat.prix}" class="form-control" id="s-prix" placeholder="10.85"><label for="s-prix">Prix du supplément (facultatif)</label></div><div class="mb-3"><label for="formFile" class="form-label">Choisir une image de remplacement (facultative)</label><input class="form-control" type="file" id="formFile" accept="image/*" name="file"><input type="hidden" class="img-url" value="${res.resultat.img_url}"/></div><div class="submit mb-3"><button type="submit" class="btn btn-primary w-100 mt-4 py-10 s-setting" id="load2" data-loading-text="Traitement en cours <i class='fa fa-spinner fa-spin'></i>">Modifier ce supplément</button></div></form></div>`);
                    }
                },2000);
            },'json');
           
        });

         /**by clicking on the event the dom will be initialized with a formular. The formular had the gool to set a categorie*/
        $('.backoffice .content .product-content').on('click','.csetting',function(){
            let cid = $(this).find('input').val();
            $('.backoffice .content .product-content .new-message-box').remove();
            $.post(RACINE+'inc/controls.php',{postType:'getSingleCategorie',cid:cid},(res) => {
                setTimeout(() => {
                    if(res.resultat){
                        $('.backoffice .content .client-content div').remove();
                        $('.backoffice .content .commande-content div').remove();
                        $('.backoffice .content .product-content div').remove();
                        $('.backoffice .content .product-content').prepend(`<div class="setting-categ"><h3 class="mb-4">Modifier cette catégorie</h3><form action="" method="post"><div class="form-floating mb-3 categ"><input type="text" class="form-control" id="c-name" placeholder="Saucisse" value="${res.resultat.categorie_name}"><input type="hidden" id="cid" value="${res.resultat.categorie_id}"><label for="p-name">Modifier le nom de votre nouveau type de produit</label></div><div class="mb-3"><label for="formFile" class="form-label">Modifier l'image de la catégorie</label><input class="form-control" type="file" id="formFile" accept="image/*" name="file"><input type="hidden" class="img-url" value="${res.resultat.img_url}"></div><div class="submit mb-3"><button type="submit" class="btn btn-primary w-100 mt-4 py-10 c-save1" id="load2" data-loading-text="Traitement en cours <i class='fa fa-spinner fa-spin'></i>">Modifier la catégorie du produit</button></div></form></div>`);
                    }
                },2000);
            },'json');
        });

        /**by clicking this event, it hold the formular content and send this to the server.*/
        $('.backoffice .content .product-content').on('submit','.setting-categ form',(e) =>{
            e.preventDefault();
            $('.backoffice .content .product-content .new-message-box').remove();
            let cname = $(this).find('#c-name').val();
            let cid = $(this).find('#cid').val();
            let file = $(this).find('#formFile').get(0).files[0];
            if(cname == ''){
                $(this).find('.categ').append('<div class="new-message-box"><div class="new-message-box-danger"><div class="info-tab tip-icon-danger" title="error"><i class="fas fa-times"></i><i></i></div><div class="tip-box-danger"><p>Veillez remplir le champs</p></div></div></div>');
            }else{
                $('main .backoffice .content .product-content .c-save1').text('').prepend($('main .backoffice .content .product-content .c-save1').attr('data-loading-text'));
                var data = new FormData();
                data.append('cid',cid);
                data.append('postType','updateCategorie');
                data.append('cname',cname);
                if(file == null || file == undefined){
                    data.append('img_url',$(this).find('.img-url').val());
                }else{
                    data.append('file',file);
                    data.append('img_url',$(this).find('.img-url').val());
                }
                $.ajax({
                    url: RACINE+'inc/controls.php', 
                    type: 'POST',
                    data: data,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success : function(res) {
                        setTimeout(() => {
                            if(res.resultat){
                                $('main .backoffice .content .product-content .c-save1').text('Modifier la catégorie du produit').find('i').remove();
                                $('main .backoffice .content .product-content').prepend('<div class="new-message-box"><div class="new-message-box-success"><div class="info-tab tip-icon-success" title="success"><i class="fas fa-check"></i><i></i></div><div class="tip-box-success"><p>'+res.resultat+'</p></div></div></div>');
                                setTimeout(() => {
                                    $('main .backoffice .content .product-content .new-message-box').remove();
                                }, 30000);
                            }else if(res.sqlError){
                                $('main .backoffice .content .product-content .c-save1').text('Modifier la catégorie du produit').find('i').remove();
                                $('main .backoffice .content .product-content').prepend('<div class="new-message-box"><div class="new-message-box-danger"><div class="info-tab tip-icon-danger" title="error"><i class="fas fa-times"></i><i></i></div><div class="tip-box-danger"><p>'+res.sqlError+'</p></div></div></div>');
                                setTimeout(() => {
                                    $('main .backoffice .content .product-content .new-message-box').remove();
                                }, 30000);
                            }
                        }, 2000);
                    }
                }); 
            }
        });

        /**by clicking this event, it hold the formular content and send this to the server.*/
        $('.backoffice .content .product-content').on('submit','.setting-extrat form',(e) =>{
            e.preventDefault();
            $('.backoffice .content .product-content .new-message-box').remove();
            let sname = $(this).find('#s-name').val();
            let sid = $(this).find('#sid').val();
            let scateg = $(this).find('#categ-s').val();
            let pcateg = $(this).find('#p-categ option:selected').val();
            let sprix = $(this).find('#s-prix').val();
            let file = $(this).find('#formFile').get(0).files[0];
            if(sname == '' || scateg == '' || pcateg == ''){
                alert('Vos données saisis ne sont pas corrects, revérifiez vos champs et retentez la modification');
            }else{
                $('main .backoffice .content .product-content .s-setting').text('').prepend($('main .backoffice .content .product-content .s-setting').attr('data-loading-text'));
                var data = new FormData();
                data.append('eid',sid);
                data.append('postType','updateExtrat');
                data.append('sname',sname);
                data.append('scateg',scateg);
                data.append('pcateg',pcateg);
                data.append('sprix',sprix);
                if(file == null || file == undefined){
                    data.append('img_url',$(this).find('.img-url').val());
                }else{
                    data.append('file',file);
                    data.append('img_url',$(this).find('.img-url').val());
                }
                $.ajax({
                    url: RACINE+'inc/controls.php', 
                    type: 'POST',
                    data: data,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success : function(res) {
                        setTimeout(() => {
                            if(res.resultat){
                                $('main .backoffice .content .product-content .s-setting').text('Modifier ce supplément').find('i').remove();
                                $('main .backoffice .content .product-content').prepend('<div class="new-message-box"><div class="new-message-box-success"><div class="info-tab tip-icon-success" title="succes"><i class="fas fa-check"></i><i></i></div><div class="tip-box-success"><p>'+res.resultat+'</p></div></div></div>');
                                $('.setting-extrat form')[0].reset();
                                setTimeout(() => {
                                    $('main .backoffice .content .product-content .new-message-box').remove();
                                }, 30000);
                            }else if(res.sqlError){
                                $('main .backoffice .content .product-content .s-setting').text('Modifier ce supplément').find('i').remove();
                                $('main .backoffice .content .product-content').prepend('<div class="new-message-box"><div class="new-message-box-danger"><div class="info-tab tip-icon-danger" title="error"><i class="fas fa-times"></i><i></i></div><div class="tip-box-danger"><p>'+res.sqlError+'</p></div></div></div>');
                                setTimeout(() => {
                                    $('main .backoffice .content .product-content .new-message-box').remove();
                                }, 30000);
                            }
                        }, 2000);
                    }
                });
            }
        });

         /**this click event send a request to the server for deleting a particular extrat of a product */
        $('.backoffice .content .product-content').on('click','.delete',function(){
            if(window.confirm('Êtes vous certains de vouloir de vouloir supprimer ce supplément?')){
                console.log($(this).find('input').val());
                let sid = $(this).find('input').val();
                $.post(RACINE+'inc/controls.php',{postType:'deleteExtrat','eid':sid},function(res){
                    setTimeout(() => {
                        if(res.resultat == 'success'){
                            $('.backoffice .content .client-content div').remove();
                            $('.backoffice .content .commande-content div').remove();
                            $('.backoffice .content .product-content div').remove();
                            getExtratData();
                        }else if(res.sqlError){
                            $('main .backoffice .content .product-content').prepend('<div class="new-message-box"><div class="new-message-box-danger"><div class="info-tab tip-icon-danger" title="error"><i class="fas fa-times"></i><i></i></div><div class="tip-box-danger"><p>'+res.sqlError+'</p></div></div></div>');
                            setTimeout(() => {
                                $('main .backoffice .content .product-content .new-message-box').remove();
                            }, 30000);
                        }
                    }, 2000);
                },'json');
           }else{
               //console.log('annuler');
           }
        });

         /**this click event send a request to the server to get all extrat of a product */
        $(this).find('.product-extrat').on('click',() =>{
            $('.backoffice .content .client-content div').remove();
            $('.backoffice .content .commande-content div').remove();
            $('.backoffice .content .product-content div').remove();
            $('.backoffice .dropmenu').hide(300);
            $.post(RACINE+'inc/controls.php',{postType:'getCategorie'},(res) => {
                if(res.resultat){
                    let option = '<div class="form-floating mb-3"><select class="form-select" id="p-categ" aria-label="Choisir la catégorie du supplément">';
                    res.resultat.map(el => {
                        option += '<option value='+el.categorie_id+'>'+el.categorie_name.toLowerCase()+'</option>';
                    });
                    
                    option += '</select><label for="floatingSelect">Choisir la catégorie du produit</label></div>';
                    $('.backoffice .content .product-content').prepend(`<div class="create-extrat"><form action="" method="post" enctype="multipart/form-data"><div class="form-floating mb-3"><input type="text" class="form-control" id="s-name" placeholder="Nom du supplément"><label for="s-name">Nom du supplément</label></div><div class="form-floating mb-3"><input type="text" class="form-control" id="categ-s" placeholder="Légume,Poulet, ..."><label for="categ-s">Donnez une catégorie au supplément</label></div>${option}<div class="form-floating mb-3"><input type="text" class="form-control" id="s-prix" placeholder="10.85"><label for="s-prix">Prix du supplément (facultatif)</label></div><div class="mb-3"><label for="formFile" class="form-label">Choisir une image pour le supplément</label><input class="form-control" type="file" id="formFile" accept="image/*" name="file"></div><div class="submit mb-3"><button type="submit" class="btn btn-primary w-100 mt-4 py-10 s-save" id="load2" data-loading-text="Traitement en cours <i class='fa fa-spinner fa-spin'></i>">Ajouter un supplément</button></div></form></div>`);
                }
            },'json');
        });

         /**by clicking this event a request will be send to the server for creating a new extrat of product */
        $('.backoffice .content .product-content').on('submit','.create-extrat form',(e) =>{
            e.preventDefault();
            $('.backoffice .content .product-content .new-message-box').remove();
            let sname = $(this).find('#s-name').val();
            let scateg = $(this).find('#categ-s').val();
            let pcateg = $(this).find('#p-categ option:selected').val();
            let sprix = $(this).find('#s-prix').val();
            let file = $(this).find('#formFile').get(0).files[0];
            //console.log(sname,scateg,pcateg,file);
            if(sname == '' || scateg == '' || pcateg == '' || file == null || file == undefined){
                alert('Vos données saisis ne sont pas corrects, revérifiez vos champs et retentez l\'insertion');
            }else{
                $('main .backoffice .content .product-content .s-save').text('').prepend($('main .backoffice .content .product-content .s-save').attr('data-loading-text'));
                var data = new FormData();
                data.append('file',file);
                data.append('postType','addExtrat');
                data.append('sname',sname);
                data.append('scateg',scateg);
                data.append('pcateg',pcateg);
                data.append('sprix',sprix);
                $.ajax({
                    url: RACINE+'inc/controls.php', 
                    type: 'POST',
                    data: data,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success : function(res) {
                        setTimeout(() => {
                            if(res.resultat){
                                $('main .backoffice .content .product-content .s-save').text('Ajouter un supplément').find('i').remove();
                                $('main .backoffice .content .product-content').prepend('<div class="new-message-box"><div class="new-message-box-success"><div class="info-tab tip-icon-success" title="succes"><i class="fas fa-check"></i><i></i></div><div class="tip-box-success"><p>'+res.resultat+'</p></div></div></div>');
                                $('.create-extrat form')[0].reset();
                                setTimeout(() => {
                                    $('main .backoffice .content .product-content .new-message-box').remove();
                                }, 30000);
                            }else if(res.sqlError){
                                $('main .backoffice .content .product-content .s-save').text('Ajouter un supplément').find('i').remove();
                                $('main .backoffice .content .product-content').prepend('<div class="new-message-box"><div class="new-message-box-danger"><div class="info-tab tip-icon-danger" title="error"><i class="fas fa-times"></i><i></i></div><div class="tip-box-danger"><p>'+res.sqlError+'</p></div></div></div>');
                                setTimeout(() => {
                                    $('main .backoffice .content .product-content .new-message-box').remove();
                                }, 30000);
                            }
                        }, 2000);
                    }
                });
            }
        });

        /**by clicking this event, it initialize the dom for creating a new categorie */
        $(this).find('.new-product').on('click',() =>{
            $('.backoffice .content .client-content div').remove();
            $('.backoffice .content .commande-content div').remove();
            $('.backoffice .content .product-content div').remove();
            $('.backoffice .dropmenu').hide(300);
            $('.backoffice .content .product-content').prepend(`<div class="new-categ"><h3 class="mb-4">Première étape donnez un nom à cette nouvelle catégorie</h3><form action="" method="post"><div class="form-floating mb-3 categ"><input type="text" class="form-control" id="c-name" placeholder="Saucisse"><label for="p-name">Donnez un nom a votre nouveau type de produit</label></div><div class="mb-3"><label for="formFile" class="form-label">Choisir une image pour la catégorie</label><input class="form-control" type="file" id="formFile" accept="image/*" name="file"></div><div class="submit mb-3"><button type="submit" class="btn btn-primary w-100 mt-4 py-10 c-save" id="load2" data-loading-text="Traitement en cours <i class='fa fa-spinner fa-spin'></i>">Insérer la catégorie du produit</button></div></form></div>`);
        });

        /**by clicking this event a request will be send to the server for creating a new categorie */
        $('.backoffice .content .product-content').on('submit','.new-categ form',(e) =>{
            e.preventDefault();
            $('.backoffice .content .product-content .new-message-box').remove();
            let cname = $(this).find('#c-name').val();
            let file = $(this).find('#formFile').get(0).files[0];
            if(cname == '' || file == null || file == undefined){
                $(this).find('.categ').append('<div class="new-message-box"><div class="new-message-box-danger"><div class="info-tab tip-icon-danger" title="error"><i class="fas fa-times"></i><i></i></div><div class="tip-box-danger"><p>Veillez indiquer tous les champs</p></div></div></div>');
            }else{
                $('main .backoffice .content .product-content .c-save').text('').prepend($('main .backoffice .content .product-content .c-save').attr('data-loading-text'));
                var data = new FormData();
                data.append('file',file);
                data.append('postType','createCategorie');
                data.append('cname',cname);
                $.ajax({
                    url: RACINE+'inc/controls.php', 
                    type: 'POST',
                    data: data,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success : function(res) {
                        setTimeout(() => {
                            if(res.resultat){
                                $('main .backoffice .content .product-content .c-save').text('Insérer la catégorie du produit').find('i').remove();
                                    $('main .backoffice .content .product-content').prepend('<div class="new-message-box"><div class="new-message-box-success"><div class="info-tab tip-icon-success" title="success"><i class="fas fa-check"></i><i></i></div><div class="tip-box-success"><p>'+res.resultat+'</p></div></div></div><div class="new-message-box"><div class="new-message-box-info"><div class="info-tab tip-icon-info" title="info"><i class="fas fa-info"></i><i></i></div><div class="tip-box-info"><p>L\'étape suivante est d\'ajouter des produits ou des suppléments à votre nouvelle catégorie de produit.</p></div></div></div>');
                                    $('.new-categ form')[0].reset();
                                    setTimeout(() => {
                                        $('main .backoffice .content .product-content .new-message-box').remove();
                                    }, 30000);
                            }else if(res.categExist){
                                $('main .backoffice .content .product-content .c-save').text('Insérer la catégorie du produit').find('i').remove();
                                $(this).find('.categ').append('<div class="new-message-box"><div class="new-message-box-danger"><div class="info-tab tip-icon-danger" title="error"><i class="fas fa-times"></i><i></i></div><div class="tip-box-danger"><p>'+res.categExist+'</p></div></div></div>');
                            }
                        }, 2000);
                    }
                });
            }
        });

        /**by clicking this event,it initialize the dom for creating a new product */
        $(this).find('.product-insert').on('click',() =>{
            $('.backoffice .content .client-content div').remove();
            $('.backoffice .content .commande-content div').remove();
            $('.backoffice .content .product-content div').remove();
            $('.backoffice .dropmenu').hide(300);
            $.post(RACINE+'inc/controls.php',{postType:'getCategorie'},(res) => {
                if(res.resultat){
                    let option = '<div class="form-floating mb-3"><select class="form-select" id="p-categ" aria-label="Choisir la catégorie du produit">';
                    res.resultat.map(el => {
                        option += '<option value='+el.categorie_id+'>'+el.categorie_name.toLowerCase()+'</option>';
                    });
                   
                    option += '</select><label for="floatingSelect">Choisir la catégorie du produit</label></div>';
                    $('.backoffice .content .product-content').prepend(`<div class="create-product"><form action="" method="post" enctype="multipart/form-data"><div class="form-floating mb-3"><input type="text" class="form-control" id="p-name" placeholder="Nom du produit"><label for="p-name">Nom du produit</label></div><div class="form-floating mb-3"><textarea class="form-control" placeholder="Description du produit" id="p-descrip" style="height: 100px"></textarea><label for="p-descrip">Description du produit (facultative)</label></div>${option}<div class="form-floating mb-3"><input type="text" class="form-control" id="p-prixWith" placeholder="10.85"><label for="p-name">Prix du produit plus livraison</label></div><div class="form-floating mb-3"><input type="text" class="form-control" id="p-prixWithout" placeholder="8.50"><label for="p-name">Prix du produit sans livraison</label></div><div class="mb-3"><label for="formFile" class="form-label">Choisir une image pour le produit</label><input class="form-control" type="file" id="formFile" accept="image/*" name="file"></div><div class="submit mb-3"><button type="submit" class="btn btn-primary w-100 mt-4 py-10 p-save" id="load2" data-loading-text="Traitement en cours <i class='fa fa-spinner fa-spin'></i>">Insérer le produit</button></div></form></div>`);
                }
            },'json');
        });

        /**by clicking this event a request will be send to the server for creating a new product */
        $('.backoffice .content .product-content').on('submit','.create-product form',(e) =>{
            e.preventDefault();
            let pname = $("#p-name").val();
            let pdescrip = $("#p-descrip").val();
            let pcateg = $("#p-categ option:selected").val();
            let ppwith = $("#p-prixWith").val();
            let ppwithout = $("#p-prixWithout").val();
            let file = $('#formFile').get(0).files[0];
            
            if(pname == '' || pcateg == '' || ppwith == '' || ppwithout == '' || file == null || file == undefined){
                alert('Vos données saisis ne sont pas corrects, revérifiez vos champs et retentez l\'insertion');
            }else{
                $('main .backoffice .content .product-content .p-save').text('').prepend($('main .backoffice .content .product-content .p-save').attr('data-loading-text'));
                
                var data = new FormData();
                data.append('file',file);
                data.append('postType','addProduct');
                data.append('pname',pname);
                data.append('pdescrip',pdescrip);
                data.append('pcateg',pcateg);
                data.append('ppwith',ppwith);
                data.append('ppwithout',ppwithout);
                $.ajax({
                    url: RACINE+'inc/controls.php', 
                    type: 'POST',
                    data: data,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success : function(res) {
                        setTimeout(() => {
                            if(res.resultat){
                                $('main .backoffice .content .product-content .p-save').text('Insérer le produit').find('i').remove();
                                $('main .backoffice .content .product-content').prepend('<div class="new-message-box"><div class="new-message-box-success"><div class="info-tab tip-icon-success" title="succes"><i class="fas fa-check"></i><i></i></div><div class="tip-box-success"><p>'+res.resultat+'</p></div></div></div>');
                                $('.create-product form')[0].reset();
                                setTimeout(() => {
                                    $('main .backoffice .content .product-content .new-message-box').remove();
                                }, 30000);
                            }else if(res.sqlError){
                                $('main .backoffice .content .product-content .p-save').text('Insérer le produit').find('i').remove();
                                $('main .backoffice .content .product-content').prepend('<div class="new-message-box"><div class="new-message-box-danger"><div class="info-tab tip-icon-danger" title="error"><i class="fas fa-times"></i><i></i></div><div class="tip-box-danger"><p>'+res.sqlError+'</p></div></div></div>');
                                setTimeout(() => {
                                    $('main .backoffice .content .product-content .new-message-box').remove();
                                }, 30000);
                            }
                        }, 2000);
                    }
                });
            }
        });
    }

    /**this function send a request to the server to get all extrat of products */
    function getExtratData(){
        $('.backoffice .content .product-content').prepend('<i class="fa-solid fa-circle-notch"></i>');
        $.post(RACINE+'inc/controls.php',{postType:'getExtrat'},(res) => {
            setTimeout(() => {
                if(res.resultat){
                    let table = '<div class="all-s"><h3>Liste de suppléments</h3><table class="table table-striped"><head><tr><th>Nom du supplément</th><th>Catégorie</th><th>prix</th><th>Image</th><th>Reférence</th><th>Action</th><thead><tbody>';
                    let index = 0;
                    for(let i = 0; i<res.resultat.length;i++){
                        table += '<tr>';
                        table += '<td>'+res.resultat[i].extrat_name+'</td>';
                        table += '<td>'+res.resultat[i].extrat_categ+'</td>';
                        table += '<td>'+res.resultat[i].prix+'</td>';
                        table += '<td><img src="'+RACINE+res.resultat[i].img_url+'" style="width:80px;"/></td>';
                        if(res.categ){
                            res.categ.forEach(categ => {
                                if(res.resultat[i].categ_ref == categ.categorie_id){
                                    table += '<td>'+categ.categorie_name+'</td>';
                                }
                            });
                        }
                        table += '<td><div style="display:flex;gap:10px;"><i class="fas fa-cog setting" style="cursor:pointer;"><input type="hidden" value="'+res.resultat[i].extrat_id+'"></i><i class="fas fa-trash-alt delete" style="cursor:pointer;"><input type="hidden" value="'+res.resultat[i].extrat_id+'"></i></div></td>';
                        table += '</tr>';
                    }
                    table += '</tbody></div>';
                    $('.backoffice .content .product-content').prepend(table);
                }else if(res.emptyTable){
                    $('.backoffice .content .product-content').prepend(`<div class="new-message-box"><div class="new-message-box-info"><div class="info-tab tip-icon-info" title="info"><i class="fas fa-info"></i><i></i></div><div class="tip-box-info"><p>${res.emptyTable}</p></div></div></div>`);
                }
            },2000);
        },'json')
    }

    /**this function send a request to the server to get all products */
    function getProductData(){
        $.post(RACINE+'inc/controls.php',{postType:'getProduct'},(res) => {
            setTimeout(() => {
                if(res.resultat){
                    let table = '<div class="all-s"><h3>Liste de produits</h3><table class="table table-striped"><head><tr><th>Nom du produit</th><th>Catégorie</th><th>Description</th><th>Prix sans livraison</th><th>Prix avec livraison</th><th>Image</th><th>Action</th><thead><tbody>';
                    res.resultat.map(el =>{
                        table += '<tr>';
                        table += '<td>'+el.product_name+'</td>';
                        if(res.categ){
                            res.categ.forEach(categ => {
                                if(el.categorie == categ.categorie_id){
                                    table += '<td>'+categ.categorie_name+'</td>';
                                }
                            });
                        }
                        table += '<td>'+el.description+'</td>';
                        table += '<td>'+el.prix_sans_livraison+'</td>';
                        table += '<td>'+el.prix_avec_livraison+'</td>';
                        table += '<td><img src="'+RACINE+el.img_url+'" style="width:80px;"/></td>';
                        table += '<td><div style="display:flex;gap:10px;"><i class="fas fa-cog psetting" style="cursor:pointer;"><input type="hidden" value="'+el.product_id+'"></i><i class="fas fa-trash-alt pdelete" style="cursor:pointer;"><input type="hidden" value="'+el.product_id+'"></i></div></td>';
                        table += '</tr>';
                    });
                    table += '</tbody></div>';
                    $('.backoffice .content .product-content').prepend(table);
                }else if(res.emptyTable){
                    $('.backoffice .content .product-content').prepend(`<div class="new-message-box"><div class="new-message-box-info"><div class="info-tab tip-icon-info" title="info"><i class="fas fa-info"></i><i></i></div><div class="tip-box-info"><p>${res.emptyTable}</p></div></div></div>`);
                }
            },2000);
        },'json')
    }
    /**this function send a request to the server to get all categorie*/
    function getCategorieData(){
        $.post(RACINE+'inc/controls.php',{postType:'getCategorie'},(res) => {
            setTimeout(() => {
                if(res.resultat){
                    let table = '<div class="all-s"><h3>Liste de catégorie</h3><table class="table table-striped"><head><tr><th>Identifiant de la catégorie</th><th>Nom de la catégorie</th><th>Image</th><th>Action</th><thead><tbody>';
                    res.resultat.map(el =>{
                        table += '<tr>';
                        table += '<td>'+el.categorie_id+'</td>';
                        table += '<td>'+el.categorie_name+'</td>';
                        table += '<td><img src="'+RACINE+el.img_url+'" style="width:80px;"/></td>';
                        table += '<td><div style="display:flex;gap:10px;"><i class="fas fa-cog csetting" style="cursor:pointer;"><input type="hidden" value="'+el.categorie_id+'"></i><i class="fas fa-trash-alt cdelete" style="cursor:pointer;"><input type="hidden" value="'+el.categorie_id+'"></i></div></td>';
                        table += '</tr>';
                    });
                    table += '</tbody></div>';
                    $('.backoffice .content .product-content').prepend(table);
                }else if(res.emptyTable){
                    $('.backoffice .content .product-content').prepend(`<div class="new-message-box"><div class="new-message-box-info"><div class="info-tab tip-icon-info" title="info"><i class="fas fa-info"></i><i></i></div><div class="tip-box-info"><p>${res.emptyTable}</p></div></div></div>`);
                }
            },2000);
        },'json')
    }
});

/**this function check if a element is in viewport */
function isInViewport(el) {
    const rect = el.getBoundingClientRect();
    return (rect.top < window.innerHeight && rect.bottom >= 0);
}
/** by loading of the page, the javascript check the existent of some page. if this pages exist, some fonctionnality will be applayed */
window.addEventListener('load',() =>{
    let kebab = document.querySelector('body .kebab');
    if (typeof(kebab) != 'undefined' && kebab != null){
        let title = document.querySelector('.commande-mode h2');
        let item = document.querySelectorAll('.commande-mode .content .item');

        if (typeof(item) != 'undefined' && item != null && typeof(title) != 'undefined' && title != null){
            title.style['opacity'] = 1;
            title.style['visibility'] = 'visible';
            title.style['transform'] = "translateY(-10+'px')";
            title.style['transform'] = "translateY(0)";

            item.forEach(el => {
                el.style['opacity'] = 1;
                el.style['visibility'] = 'visible';
                el.style['transform'] = "translateY(10+'px')";
                el.style['transform'] = "translateY(0)";
            });
        }

        let adminlink = document.querySelector('.commande-mode .admin');
        if (typeof(adminlink) != 'undefined' && adminlink != null){
            adminlink.style['transform'] = "translateX(0)";
        }
    }
    let product = document.querySelector('body main .products');
    if(typeof(product) != 'undefined' && product != null){
        $('.products .product-content .card').each(function(index){
            let innerHeight = $(this).find('.card-body .card-title').outerHeight(true)+$(this).find('.card-body .card-text').outerHeight(true)+$(this).find('.card-body .prix').outerHeight(true);

            let margin = getMaxHeight($('.products .product-content .card .card-body')) - innerHeight

            $(this).find('.card-body').css({paddingBottom:margin+'px'});

        });
    }
    let categorie = document.querySelector('body main .categ');
    if(typeof(categorie) != 'undefined' && categorie != null){
        //alert(categorie);
        let currentUrl = window.location.href;
        let commandeType = currentUrl.toString().split('?')[1];
        $(function(){
            $.post(RACINE+'inc/controls.php',{postType:'getCategorie'},(res) => {
                console.log(res);
                setTimeout(() => {
                    if(res.resultat){
                        let content = `<div class="categ-content"><div class="bloc-content">`;
                        res.resultat.map(el =>{
                            content += `<a class="item-link" href="${RACINE}product?access=${el.categorie_id}&delivery=${commandeType}"><div class="item" style="background-image:url(${RACINE+el.img_url})"><img src="${RACINE+el.img_url}" alt="${el.categorie_name}" style="display:none"><div class="text"></div></div><h4><i class="fas fa-plus"></i>${el.categorie_name.toUpperCase()}</h4></a>`;
                        });
                        content += '</div></div>';
                        $('main .categ').append(content);
                        $('.categ .bloc-content').addClass('in');
                    }else if(res.emptyTable){
                        $('main .categ').append(`<div class="new-message-box"><div class="new-message-box-info"><div class="info-tab tip-icon-info" title="info"><i class="fas fa-info"></i><i></i></div><div class="tip-box-info"><p>${res.emptyTable}</p></div></div></div>`);
                    }
                },2000);
            },'json')
        });
    }
    let extrat = document.querySelector('body main .extrat');
    if(typeof(extrat) != 'undefined' && extrat != null){
        $(function(){
            
            let bloc = document.querySelectorAll('body main .extrat .elm-content .bloc');
            //console.log(bloc);
            
            bloc.forEach((el,i) => {
                console.log(el);
                //this.css({backgroundColor:'red'});
                if(el.offsetHeight > 0){
                    if(i == 0){
                        el.classList.remove('hide');
                        el.classList.add('show');
                    }else{
                        el.classList.remove('show');
                        el.classList.add('hide');
                    }
                }
                el.classList.add('active');
            });
            $('body main .extrat .elm-content .active.show .bloc-item').find('.card').each(function(index){
                //console.log('card ',index);
                let prix ='';
                if($(this).find('.card-body .prix').length){
                    prix = '$(this).find(".card-body .prix").outerHeight(true)';
                }
                let innerHeight = $(this).find('.card-body .card-title').outerHeight(true)+$(this).find('.card-body #compo').outerHeight(true)+prix;
                let margin = getMaxHeight($(this).find('.card-body')) - innerHeight
                console.log(innerHeight);
                $(this).find('.card-body').css({paddingBottom:margin+'px'});
                console.log('cardbodyH ',getMaxHeight($(this).find('.card-body')));
                //console.log('path ',$(this).find('.card-body').parent());
    
            });
            
            if(document.querySelector('body main .extrat .move .next')){
                $('body main .extrat .move .next').on('click',()=>{
                    if($('body main .extrat .elm-content').find('.active.show').next('.active.hide').length){
                        $('.extrat .movebtn .btnprev').remove();
                        $('body main .extrat .elm-content').find('.active.show').removeClass('show').addClass('hide').next('.active.hide').removeClass('hide').addClass('show');
                        if(document.querySelector('.extrat .moveLeft .btnprev') === null){
                            $('.extrat .moveLeft').append('<p class="btnprev"><i class="fas fa-chevron-left"></i> Précédent</p>');
                        }
                        if(!$('body main .extrat .elm-content').find('.active.show').next('.active.hide').length){
                            $('.extrat .next').css({display:'none'});
                            $('.extrat .move').append('<p class="cartbtn"><span class="next">AJOUTER AU PANIER <i class="fas fa-chevron-right"></i></span></p>');
                        }
                    }
                    $('body main .extrat .elm-content .active.show .bloc-item .card').each(function(index){
                        console.log('card ',index);
                        let prix ='';
                        if($(this).find('.card-body .prix').length){
                            prix = '$(this).find(".card-body .prix").outerHeight(true)';
                        }
                        let innerHeight = $(this).find('.card-body .card-title').outerHeight(true)+$(this).find('.card-body #compo').outerHeight(true)+prix;
                        let margin = getMaxHeight($(this)) - innerHeight
                        console.log(innerHeight);
                        $(this).find('.card-body').css({paddingBottom:margin+'px'});
                        //let path = document.querySelectorAll('body main .extrat .elm-content .active.show .bloc-item .card');
                        //console.log('cardbodyH ',getMaxHeight(paht));
                        //console.log('path ',$(this).find('.card-body').parent());
                    });
                });
            }else{
                $('.extrat .move').append('<p class="cartbtn"><span class="next">AJOUTER AU PANIER <i class="fas fa-chevron-right"></i></span></p>');
            }
            
            $('body main .extrat .move').on('click',".cartbtn",()=>{
                const salade = document.getElementById("salades");
                const tomate = document.getElementById("tomates");
                const oignon = document.getElementById("oignons");
                const sauceB = document.getElementById("sauce-barbequie");
                const mayonaise = document.getElementById("moyonaise");
                const ketchop = document.getElementById("sauce-ketchop");
                const sauceBlanche = document.getElementById("sauce-blanche");
                const thon = document.getElementById("thon");
                const poulet = document.getElementById("poulet");
                const steak = document.getElementById("steak");
                const socisse = document.getElementById("socisse");
                const kefta = document.getElementById("kefta");
                const socisseCury = document.getElementById("socisse-au-curry");
                const bouletteViande = document.getElementById("boulette-de-viande");
                const thonPrice = document.getElementById("thon-price");
                const pouletPrice = document.getElementById("poulet-price");
                const steakPrice = document.getElementById("steak-price");
                const socissePrice = document.getElementById("socisse-price");
                const keftaPrice = document.getElementById("kefta-price");
                const socisseCuryPrice = document.getElementById("socisse-au-curry-price");
                const bouletteViandePrice = document.getElementById("boulette-de-viande-price");
                const productId = document.getElementById("productId").value;
                const deliveryMode = document.getElementById("deliveryMode").value;
                const cid = document.getElementById("catId").value;
                const product = {
                    productName:document.getElementById("productName").value,
                    productPrice:document.getElementById("productPrice").value,
                    productImg:document.getElementById("productImg").value,
                    productId:document.getElementById("productId").value,
                    deliveryMode:document.getElementById("deliveryMode").value,
                    cid:document.getElementById("catId").value,
                    extract:[
                        {
                            oignon:oignon ? oignon.checked : false,
                            tomate:tomate ? tomate.checked : false,
                            salade:salade ? salade.checked : false
                        },
                        {
                            sauceBarbequie:sauceB ? sauceB.checked : false,
                            sauceBlanche:sauceBlanche ? sauceBlanche.checked : false,
                            ketchop:ketchop ? ketchop.checked : false,
                            mayonaise:mayonaise ? mayonaise.checked : false
                        },
                        {
                            poulet:{check:poulet ? poulet.checked : false,price:pouletPrice ? pouletPrice.value : 0},
                            steak:{check:steak ? steak.checked : false,price:steakPrice ? steakPrice.value : 0},
                            socisse:{check:socisse ? socisse.checked : false,price:socissePrice ? socissePrice.value : 0},
                            kefta:{check:kefta ? kefta.checked : false,price:keftaPrice ? keftaPrice.value : 0},
                            socisseCurry:{check:socisseCury ? socisseCury.checked : false,price:socisseCuryPrice ? socisseCuryPrice.value : 0},
                            bouletViande:{check:bouletteViande ? bouletteViande.checked : false,price:bouletteViandePrice ? bouletteViandePrice.value : 0}
                        },
                        {
                            thon:{check:thon ? thon.checked : false,price:thonPrice ? thonPrice.value : 0}
                        },
                        {},
                        {}
                    ]
                };
                console.log("product",product);
                const data = {
                    postType:"addCart",
                    product:product
                };
                $.post(RACINE + 'inc/controls.php', data, (res) => {
                    if (res.result === 'product_added') {
                        // Traitez ici la réponse réussie, par exemple :
                        console.log("res",res);
                        window.location.href = RACINE+'product?access='+cid+'&delivery='+deliveryMode;
                        //console.log("cartCount",cartCount);
                        // Mettez à jour l'interface utilisateur si nécessaire
                    } else {
                        alert("Erreur lors de l'ajout du produit");
                    }
                }, 'json')
                .fail(function(jqXHR, textStatus, errorThrown) {
                    console.error("Erreur lors de la requête AJAX :", textStatus, errorThrown);
                    alert("Erreur lors de la requête AJAX. Veuillez consulter la console pour plus de détails.");
                });
            });
            
            $('body main .extrat .move').on('click','.btnprev',()=>{
                if($('.cart .cartbtn').length){
                    $('.cart .cartbtn').remove();
                    $('.cart .next').css({display:'flex'});
                }
                if($('body main .extrat .elm-content').find('.active.show').prev('.active.hide').length){
                    $('body main .extrat .elm-content').find('.active.show').removeClass('show').addClass('hide').prev('.active.hide').removeClass('hide').addClass('show');
                    //$('.extrat .movebtn').append('<a class="btnprev"><p class="prev"><i class="fas fa-chevron-left"></i> Précédent</p></a>');
                    if(!$('body main .extrat .elm-content').find('.active.show').prev('.active.hide').length){
                        $('.extrat .btnprev').remove();
                    }
                }
            });
        });
    }
});
function openCart(){
    window.location.href = RACINE+'cart';
}
function getRandomNumber(min, max) {
    return Math.random() * (max - min) + min;
}
function getMaxHeight(sel){
    let tmp1 = 0;
    sel.each(function(i){
        let tmp = $(this).find('.card-body').height();
        if($(this).find('.card-body').height() > tmp1){
            tmp = $(this).find('.card-body').height();
            tmp1 = tmp;
        }else{

        }
    });
    return tmp1;
}
