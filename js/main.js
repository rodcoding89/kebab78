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
                let inputCheckCount = 0;
                $('body main .extrat .move .next').on('click',()=>{
                    $('body main .extrat .elm-content .active.show .bloc-item .card').each(function(index){
                        //console.log('card ',index);
                        let prix ='';
                        if($(this).find('.card-body .prix').length){
                            prix = '$(this).find(".card-body .prix").outerHeight(true)';
                        }
                        let innerHeight = $(this).find('.card-body .card-title').outerHeight(true)+$(this).find('.card-body #compo').outerHeight(true)+prix;
                        let margin = getMaxHeight($(this)) - innerHeight
                        console.log(innerHeight);
                        $(this).find('.card-body').css({paddingBottom:margin+'px'});
                        const input = $(this).find('.card-body input');
                        if(input){
                            const check = input.is(':checked');
                            console.log("input",input,"check",check);
                            if(check === true){
                                inputCheckCount += 1;
                            }
                        }
                        //let path = document.querySelectorAll('body main .extrat .elm-content .active.show .bloc-item .card');
                        //console.log('cardbodyH ',getMaxHeight(paht));
                        //console.log('path ',$(this).find('.card-body').parent());
                    });
                    if($('body main .extrat .elm-content').find('.active.show').next('.active.hide').length){
                        if(inputCheckCount > 0){
                            $('.extrat .movebtn .btnprev').remove();
                            $('body main .extrat .elm-content').find('.active.show').removeClass('show').addClass('hide').next('.active.hide').removeClass('hide').addClass('show');
                            if(document.querySelector('.extrat .moveLeft .btnprev') === null){
                                $('.extrat .moveLeft').append('<p class="btnprev"><i class="fas fa-chevron-left"></i> Précédent</p>');
                            }
                            if(!$('body main .extrat .elm-content').find('.active.show').next('.active.hide').length){
                                $('.extrat .next').css({display:'none'});
                                $('.extrat .move').append('<p class="cartbtn"><span class="next">AJOUTER AU PANIER <i class="fas fa-chevron-right"></i></span></p>');
                            }
                            inputCheckCount = 0;
                        }else{
                            $('.bloc-item').prepend('<div class="error" style="color:darkred;font-size:14px;margin-bottom:10px;">Veuillez cocher parmis les champs.</div>');
                            setTimeout(()=>{
                                $('.bloc-item .error').remove();
                            },2500);
                        }
                    }
                    console.log("inputCheckCount",inputCheckCount);
                });
            }else{
                $('.extrat .move').append('<p class="cartbtn"><span class="next">AJOUTER AU PANIER <i class="fas fa-chevron-right"></i></span></p>');
            }
            
            $('body main .extrat .move').on('click',".cartbtn",()=>{
                const productName = document.getElementById("productName").value;
                let inputCheckCount = 0;
                let canMakeAction = false;
                $('body main .extrat .elm-content .active.show .bloc-item .card').each(function(index){
                    const input = $(this).find('.card-body input');
                    if(input){
                        const check = input.is(':checked');
                        console.log("input",input,"check",check);
                        if(check === true){
                            inputCheckCount += 1;
                        }
                    }
                });
                if(inputCheckCount > 0){
                    const vCount = productName.split(" ")[0];
                    if(productName.includes('viande')){
                        if(inputCheckCount === parseInt(vCount)){
                            canMakeAction = true;
                        }else{
                            $('.bloc-item').prepend('<div class="error" style="color:darkred;font-size:14px;margin-bottom:10px;">Vous pouvez choisir uniquement '+vCount+' viande.</div>');
                            setTimeout(()=>{
                                $('.bloc-item .error').remove();
                            },3500);
                        }
                    }else{
                        if(productName.includes('Sandwich')){
                            if(inputCheckCount === 1){
                                canMakeAction = true;
                            }else{
                                $('.bloc-item').prepend('<div class="error" style="color:darkred;font-size:14px;margin-bottom:10px;">Vous pouvez choisir uniquement 1 viande.</div>');
                                setTimeout(()=>{
                                    $('.bloc-item .error').remove();
                                },3500);
                            }
                        }else{
                            canMakeAction = true;
                        }
                    }
                    if(canMakeAction === true){
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
                            productName:productName,
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
                                    poulet:poulet ? poulet.checked : false,
                                    steak:steak ? steak.checked : false,
                                    socisse:socisse ? socisse.checked : false,
                                    kefta:kefta ? kefta.checked : false,
                                    socisseCurry:socisseCury ? socisseCury.checked : false,
                                    bouletViande:bouletteViande ? bouletteViande.checked : false
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
                    }
                }else{
                    $('.bloc-item').prepend('<div class="error" style="color:darkred;font-size:14px;margin-bottom:10px;">Veuillez cocher parmis les champs.</div>');
                    setTimeout(()=>{
                        $('.bloc-item .error').remove();
                    },3500);
                }
            });
            
            $('body main .extrat .move').on('click','.btnprev',()=>{
                if($('.extrat .move .cartbtn').length){
                    $('.extrat .move .cartbtn').remove();
                    $('.extrat .move .next').css({display:'flex'});
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
