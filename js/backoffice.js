/**heer we check if we are in the backoffice page */

/**this click event hide some content and display another one */
let env = 'dev';
let RACINE = env === 'prod' ? '' : '/Kebab78/';

const url = window.location.href;
const newUrl = new URL(url);
const segment = newUrl.pathname.split('/');
const client = document.getElementById("clients");
const commande = document.getElementById("commandes");
const product = document.getElementById("products");

if(env === 'dev'){
    if(segment[3] === ''){
        const siblings = client.parentElement.children;
        for (var i = 0; i < siblings.length; i++) {
            siblings[i].classList.remove("active");
        }
        client.classList.add("active");
    }else if(segment[3] === 'commande'){
        const siblings = commande.parentElement.children;
        for (var i = 0; i < siblings.length; i++) {
            siblings[i].classList.remove("active");
        }
        commande.classList.add("active");
    }else{
        const siblings = product.parentElement.children;
        for (var i = 0; i < siblings.length; i++) {
            siblings[i].classList.remove("active");
        }
        product.classList.add("active");
    }
}

$('.product').on('click',() =>{
    $('.backoffice main .product').addClass('active').siblings().removeClass('active');
    $('.backoffice .dropmenu-content').toggleClass("openSubNav");
});

/**loading */

/**by clicking this event, it hold the formular content and send this to the server.*/
$('.backoffice #edit-product form').on('submit',(e) =>{
    e.preventDefault();
    $('.backoffice #edit-product .new-message-box').remove();
    let pid = $('.backoffice #edit-product form').find("#pid").val();
    let pname = $('.backoffice #edit-product form').find("#p-name").val();
    let pdescrip = $('.backoffice #edit-product form').find("#p-descrip").val();
    let pcateg = $('.backoffice #edit-product form').find("#p-categ option:selected").val();
    let ppwith = $('.backoffice #edit-product form').find("#p-prixWith").val();
    let ppwithout = $('.backoffice #edit-product form').find("#p-prixWithout").val();
    let file = $('.backoffice #edit-product form').find('#formFileEditProduct').get(0).files[0];
    
    if(pname == '' || pcateg == '' || ppwith == '' || ppwithout == ''){
        alert('Vos données saisis ne sont pas corrects, revérifiez vos champs et retentez la modification');
    }else{
        $('.backoffice main #edit-product .p-setting').text('').prepend($('.backoffice main #edit-product .p-setting').attr('data-loading-text'));
        
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
                        window.location.href = RACINE + "admin/product";
                    }else if(res.sqlError){
                        $('.backoffice main #edit-product .p-setting').text('Modifier ce produit').find('i').remove();
                        $('.backoffice main #edit-product').prepend('<div class="new-message-box"><div class="new-message-box-danger"><div class="info-tab tip-icon-danger" title="error"><i class="fas fa-times"></i><i></i></div><div class="tip-box-danger"><p>'+res.sqlError+'</p></div></div></div>');
                        setTimeout(() => {
                            $('.backoffice main #edit-product .new-message-box').remove();
                        }, 30000);
                    }
                }, 2000);
            }
        });
    }
});

/**this click event send a request to the server for deleting a particular product */
$('#manage .delProduct').on('click',function(){
    if(window.confirm('Êtes vous certains de vouloir supprimer ce produit?')){
        //console.log($(this).find('input').val());
        let pid = $(this).find('input').val();
        $.post(RACINE+'inc/controls.php',{postType:'deleteProduct','pid':pid},function(res){
            setTimeout(() => {
                if(res.resultat == 'success'){
                    window.location.reload();
                }else if(res.sqlError){
                    $('.backoffice main .content .product-content').prepend('<div class="new-message-box"><div class="new-message-box-danger"><div class="info-tab tip-icon-danger" title="error"><i class="fas fa-times"></i><i></i></div><div class="tip-box-danger"><p>'+res.sqlError+'</p></div></div></div>');
                    setTimeout(() => {
                        $('.backoffice main .content .product-content .new-message-box').remove();
                    }, 30000);
                }
            }, 2000);
        },'json');
   }else{
       //console.log('annuler');
   }
});

/**this click event send a request to the server for deleting a particular categorie */
$('.backoffice #manage-categ .cdelete').on('click',function(){
    if(window.confirm('Êtes vous certains de vouloir supprimer cette catégorie? En la supprimant, tous les produits ou suppléments liés à cette catégorie seront également supprimés')){
        //console.log($(this).find('input').val());
        let cid = $(this).find('input').val();
        $.post(RACINE+'inc/controls.php',{postType:'deleteCategorie','cid':cid},function(res){
            setTimeout(() => {
                if(res.resultat == 'success'){
                   window.location.reload();
                }else if(res.sqlError){
                    $('.backoffice main #manage-categ').prepend('<div class="new-message-box"><div class="new-message-box-danger"><div class="info-tab tip-icon-danger" title="error"><i class="fas fa-times"></i><i></i></div><div class="tip-box-danger"><p>'+res.sqlError+'</p></div></div></div>');
                    setTimeout(() => {
                        $('.backoffice main #manage-categ .new-message-box').remove();
                    }, 30000);
                }
            }, 2000);
        },'json');
   }else{
       //console.log('annuler');
   }
})


/**by clicking this event, it hold the formular content and send this to the server.*/
$('.backoffice #edit-categ form').on('submit',(e) =>{
    e.preventDefault();
    $('.backoffice #edit-categ .new-message-box').remove();
    let cname = $('.backoffice #edit-categ form').find('#c-name').val();
    let cid = $('.backoffice #edit-categ form').find('#cid').val();
    let file = $('.backoffice #edit-categ form').find('#formFileEditCateg').get(0).files[0];
    if(cname == ''){
        $(this).find('.categ').append('<div class="new-message-box"><div class="new-message-box-danger"><div class="info-tab tip-icon-danger" title="error"><i class="fas fa-times"></i><i></i></div><div class="tip-box-danger"><p>Veillez remplir le champs</p></div></div></div>');
    }else{
        $('.backoffice main #edit-categ .c-save1').text('').prepend($('.backoffice main #edit-categ .c-save1').attr('data-loading-text'));
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
                        window.location.href = RACINE + "admin/category";
                    }else if(res.sqlError){
                        $('.backoffice main #edit-categ .c-save1').text('Modifier la catégorie du produit').find('i').remove();
                        $('.backoffice main #edit-categ').prepend('<div class="new-message-box"><div class="new-message-box-danger"><div class="info-tab tip-icon-danger" title="error"><i class="fas fa-times"></i><i></i></div><div class="tip-box-danger"><p>'+res.sqlError+'</p></div></div></div>');
                        setTimeout(() => {
                            $('.backoffice main #edit-categ .new-message-box').remove();
                        }, 30000);
                    }
                }, 2000);
            }
        }); 
    }
});

/**by clicking this event, it hold the formular content and send this to the server.*/
$('.backoffice #edit-extrat form').on('submit',(e) =>{
    e.preventDefault();
    $('.backoffice #edit-extrat .new-message-box').remove();
    let sname = $('.backoffice #edit-extrat form').find('#s-name').val();
    let sid = $('.backoffice #edit-extrat form').find('#sid').val();
    let scateg = $('.backoffice #edit-extrat form').find('#categ-s').val();
    let pcateg = $('.backoffice #edit-extrat form').find('#p-categ option:selected').val();
    let sprix = $('.backoffice #edit-extrat form').find('#s-prix').val();
    let file = $('.backoffice #edit-extrat form').find('#formFileEditExtrat').get(0).files[0];
    if(sname == '' || scateg == '' || pcateg == ''){
        alert('Vos données saisis ne sont pas corrects, revérifiez vos champs et retentez la modification');
    }else{
        $('.backoffice main  #edit-extrat .s-setting').text('').prepend($('.backoffice main  #edit-extrat .s-setting').attr('data-loading-text'));
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
                       window.location.href = RACINE + "admin/extrat";
                    }else if(res.sqlError){
                        $('.backoffice main #edit-extrat .s-setting').text('Modifier ce supplément').find('i').remove();
                        $('.backoffice main #edit-extrat').prepend('<div class="new-message-box"><div class="new-message-box-danger"><div class="info-tab tip-icon-danger" title="error"><i class="fas fa-times"></i><i></i></div><div class="tip-box-danger"><p>'+res.sqlError+'</p></div></div></div>');
                        setTimeout(() => {
                            $('.backoffice main .content .product-content .new-message-box').remove();
                        }, 30000);
                    }
                }, 2000);
            }
        });
    }
});

 /**this click event send a request to the server for deleting a particular extrat of a product */
$('.backoffice #manage-extrat .delete').on('click',function(){
    if(window.confirm('Êtes vous certains de vouloir de vouloir supprimer ce supplément?')){
        console.log($(this).find('input').val());
        let sid = $(this).find('input').val();
        $.post(RACINE+'inc/controls.php',{postType:'deleteExtrat','eid':sid},function(res){
            setTimeout(() => {
                if(res.resultat == 'success'){
                    window.location.reload();
                }else if(res.sqlError){
                    $('.backoffice main .content .product-content').prepend('<div class="new-message-box"><div class="new-message-box-danger"><div class="info-tab tip-icon-danger" title="error"><i class="fas fa-times"></i><i></i></div><div class="tip-box-danger"><p>'+res.sqlError+'</p></div></div></div>');
                    setTimeout(() => {
                        $('.backoffice main .content .product-content .new-message-box').remove();
                    }, 30000);
                }
            }, 2000);
        },'json');
   }else{
       //console.log('annuler');
   }
});


 /**by clicking this event a request will be send to the server for creating a new extrat of product */
$('.backoffice #add-extrat form').on('submit',(e) =>{
    e.preventDefault();
    $('.backoffice #add-extrat .new-message-box').remove();

    let sname = $('.backoffice #add-extrat form').find('#s-name').val();
    let scateg = $('.backoffice #add-extrat form').find('#categ-s').val();
    let pcateg = $('.backoffice #add-extrat form').find('#p-categ option:selected').val();
    let sprix = $('.backoffice #add-extrat form').find('#s-prix').val();

    let file = $('.backoffice #add-extrat form').find('#formFileExtrat').get(0)?.files[0];
    
    if(sname == '' || scateg == '' || pcateg == '' || file == null || file == undefined){
        alert('Vos données saisis ne sont pas corrects, revérifiez vos champs et retentez l\'insertion');
    }else{
        $('.backoffice main #add-extrat .s-save').text('').prepend($('.backoffice main #add-extrat .s-save').attr('data-loading-text'));
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
                        $('.backoffice main #add-extrat .s-save').text('Ajouter un supplément').find('i').remove();
                        $('.backoffice main #add-extrat').prepend('<div class="new-message-box"><div class="new-message-box-success"><div class="info-tab tip-icon-success" title="succes"><i class="fas fa-check"></i><i></i></div><div class="tip-box-success"><p>'+res.resultat+'</p></div></div></div>');
                        $('.backoffice main #add-extrat form')[0].reset();
                        setTimeout(() => {
                            $('.backoffice main #add-extrat .new-message-box').remove();
                        }, 30000);
                    }else if(res.sqlError){
                        $('.backoffice main #add-extrat .s-save').text('Ajouter un supplément').find('i').remove();
                        $('.backoffice main #add-extrat').prepend('<div class="new-message-box"><div class="new-message-box-danger"><div class="info-tab tip-icon-danger" title="error"><i class="fas fa-times"></i><i></i></div><div class="tip-box-danger"><p>'+res.sqlError+'</p></div></div></div>');
                        setTimeout(() => {
                            $('.backoffice main #add-extrat .new-message-box').remove();
                        }, 30000);
                    }
                }, 2000);
            }
        });
    }
});


/**by clicking this event a request will be send to the server for creating a new categorie */
$('.backoffice #add-categ form').on('submit',(e) =>{
    e.preventDefault();
    $('.backoffice #add-categ .new-message-box').remove();
    let cname = $('.backoffice #add-categ form').find('#c-name').val();
    let file = $('.backoffice #add-categ form').find('#formFileCateg').get(0).files[0];
    if(cname == '' || file == null || file == undefined){
        $('.backoffice #add-categ form').append('<div class="new-message-box"><div class="new-message-box-danger"><div class="info-tab tip-icon-danger" title="error"><i class="fas fa-times"></i><i></i></div><div class="tip-box-danger"><p>Veillez indiquer tous les champs</p></div></div></div>');
    }else{
        $('.backoffice main #add-categ .c-save').text('').prepend($('.backoffice main #add-categ .c-save').attr('data-loading-text'));
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
                        $('.backoffice main #add-categ .c-save').text('Insérer la catégorie du produit').find('i').remove();
                            $('.backoffice main #add-categ').prepend('<div class="new-message-box"><div class="new-message-box-success"><div class="info-tab tip-icon-success" title="success"><i class="fas fa-check"></i><i></i></div><div class="tip-box-success"><p>'+res.resultat+'</p></div></div></div><div class="new-message-box"><div class="new-message-box-info"><div class="info-tab tip-icon-info" title="info"><i class="fas fa-info"></i><i></i></div><div class="tip-box-info"><p>L\'étape suivante est d\'ajouter des produits ou des suppléments à votre nouvelle catégorie de produit.</p></div></div></div>');
                            $('.backoffice main #add-categ form')[0].reset();
                            setTimeout(() => {
                                $('.backoffice main #add-categ .new-message-box').remove();
                            }, 30000);
                    }else if(res.categExist){
                        $('.backoffice main #add-categ .c-save').text('Insérer la catégorie du produit').find('i').remove();
                        $('.backoffice main #add-categ').append('<div class="new-message-box"><div class="new-message-box-danger"><div class="info-tab tip-icon-danger" title="error"><i class="fas fa-times"></i><i></i></div><div class="tip-box-danger"><p>'+res.categExist+'</p></div></div></div>');
                    }
                }, 2000);
            }
        });
    }
});

/**by clicking this event a request will be send to the server for creating a new product */
$('.backoffice main #add form').on('submit',(e) =>{
    e.preventDefault();
    let pname = $("#p-name").val();
    let pdescrip = $("#p-descrip").val();
    let pcateg = $("#p-categ option:selected").val();
    let ppwith = $("#p-prixWith").val();
    let ppwithout = $("#p-prixWithout").val();
    let file = $('#formFile').get(0)?.files[0];
    
    if(pname == '' || pcateg == '' || ppwith == '' || ppwithout == '' || file == null || file == undefined){
        alert('Vos données saisis ne sont pas corrects, revérifiez vos champs et retentez l\'insertion');
    }else{
        $('.backoffice main #add .p-save').text('').prepend($('.backoffice main #add .p-save').attr('data-loading-text'));
        
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
                        $('.backoffice main #add .p-save').text('Insérer le produit').find('i').remove();
                        $('.backoffice main #add').prepend('<div class="new-message-box"><div class="new-message-box-success"><div class="info-tab tip-icon-success" title="succes"><i class="fas fa-check"></i><i></i></div><div class="tip-box-success"><p>'+res.resultat+'</p></div></div></div>');
                        $('.backoffice main #add form')[0].reset();
                        setTimeout(() => {
                            $('.backoffice main #add .new-message-box').remove();
                        }, 30000);
                    }else if(res.sqlError){
                        $('.backoffice main #add .p-save').text('Insérer le produit').find('i').remove();
                        $('.backoffice main #add').prepend('<div class="new-message-box"><div class="new-message-box-danger"><div class="info-tab tip-icon-danger" title="error"><i class="fas fa-times"></i><i></i></div><div class="tip-box-danger"><p>'+res.sqlError+'</p></div></div></div>');
                        setTimeout(() => {
                            $('.backoffice main #add .new-message-box').remove();
                        }, 30000);
                    }
                }, 2000);
            }
        });
    }
});