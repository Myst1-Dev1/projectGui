jQuery(document).ready(function() {

  // Loading
  
  setTimeout(function() {
    jQuery("#loading").fadeOut(function() {
      jQuery("#loading").css( "display", "none" );
    });
  }, 1000);
  
});
//	funções de auxílio

function root_url(complemento){
  if( complemento ){
    var url = window.location.protocol+'//'+window.location.hostname+'/'+complemento;
  }else{
    var url = window.location.protocol+'//'+window.location.hostname+'/';
  }

  return url;
}

function full_url(complemento){
  if( complemento ){
    if( window.location.pathname == '/' ){
      var url = window.location.protocol+'//'+window.location.hostname+'/'+complemento;
    }else{
      var url = window.location.protocol+'//'+window.location.hostname+window.location.pathname+complemento;
    }
  }else{
    if( window.location.pathname == '/' ){
      var url = window.location.protocol+'//'+window.location.hostname+'/';
    }else{
      var url = window.location.protocol+'//'+window.location.hostname+window.location.pathname;
    }
  }

  return url;
}


//	funções para configurar e atualizar configurações dos campos do checkout

function configCheckoutField(this_dom, this_class, this_placeholder){
  jQuery('body.woocommerce-checkout '+this_dom).addClass(this_class);
  
  if(this_placeholder){
  	jQuery('body.woocommerce-checkout '+this_dom+' input').attr('placeholder', this_placeholder);
  }
} 

function setCheckoutFieldConfigs(){
	jQuery('body.woocommerce-checkout .woocommerce-billing-fields__field-wrapper > p.form-row').removeClass('form-row-wide form-row-first form-row-last');

    configCheckoutField('#billing_country_field', 'form-row-wide');
    configCheckoutField('#billing_first_name_field', 'form-row-first', 'Nome*');
    configCheckoutField('#billing_last_name_field', 'form-row-last', 'Sobrenome*');
    configCheckoutField('#billing_persontype_field', 'form-row-wide');
    configCheckoutField('#billing_cpf_field', 'form-row-wide', 'CPF*');
    configCheckoutField('#billing_company_field', 'form-row-wide', 'Nome da Empresa');
    configCheckoutField('#billing_cnpj_field', 'form-row-wide', 'CNPJ*');
    configCheckoutField('#billing_postcode_field', 'form-row-first', 'CEP*');
    configCheckoutField('#billing_address_1_field', 'form-row-last', 'Endereço*');
    configCheckoutField('#billing_number_field', 'form-row-first', 'Número*');
    configCheckoutField('#billing_address_2_field', 'form-row-last', 'Complemento (ex: apto, bloco, etc...)');
    configCheckoutField('#billing_neighborhood_field', 'form-row-first', 'Bairro*');
    configCheckoutField('#billing_city_field', 'form-row-last', 'Cidade*');
    configCheckoutField('#billing_state_field', 'form-row-wide');
  configCheckoutField('#billing_phone_field', 'form-row-first', 'Telefone*');
    configCheckoutField('#billing_cellphone_field', 'form-row-last', 'Telefone Secundário (opcional)');  	
    configCheckoutField('#billing_email_field', 'form-row-wide', 'E-mail*');
}


//	função que detecta scroll a partir do topo da página

function scrolledHeader(dom, cls, val){
  var lastScrollTop = val;
  var st = jQuery(this).scrollTop();

  if (st > lastScrollTop){
    jQuery(dom).addClass(cls);
  } else {
    if(st == 0){
      jQuery(dom).removeClass(cls);
    }
  }

  lastScrollTop = st;
}


//	função de exemplo de interação ajax

function interacaoAjax(param1, param2){
  jQuery.post(
    root_url('wp-content/themes/origgami-tema-v3-child/ajax/[arquivo.php]'),
    {
      param1: param1,
      param2: param2,
    },
    function(response){
      jQuery('#ajax-response').html(response);
    }
  ).fail(function() {
    alert('Ocorreu um erro.\n\nTente novamente.');
  });
}
jQuery(document).ready(function() {
  
  // Toggle do Shiftnav
  
  jQuery('.shiftnav-nav .menu-item-has-children > a').on('click', function(e){
    e.preventDefault();
    jQuery(this).next('ul').slideToggle('fast');
  });
  
  
  // Configurações dos Campos do Checkout
  
  setTimeout(function(){
  	setCheckoutFieldConfigs();
  },500);

  jQuery('body').on('updated_checkout', function(){
    setCheckoutFieldConfigs();
  });
  

  // Chama função "scrolledHeader()" assim que carrega o site

  /*scrolledHeader('body', 'scroll-ativo' , 200);*/

  
  // Chama função "scrolledHeader()" sempre que houver um evento de "scroll"

  /*
  jQuery(window).scroll(function(event){
    scrolledHeader('body', 'scroll-ativo' , 200);
  });
  */
  
  
  // Slick Carousels (info: https://kenwheeler.github.io/slick/)
  
  /*
  // Exemplo de uso
  
  jQuery('#depoimentos > .wpb_column > .vc_column-inner > .wpb_wrapper').slick({
    infinite: true,
    slidesToShow: 3,
    slidesToScroll: 1,
    prevArrow: '<button type="button" class="slick-prev"><i class="fa fa-angle-left" aria-hidden="true"></i></button>',
    nextArrow: '<button type="button" class="slick-next"><i class="fa fa-angle-right" aria-hidden="true"></i></button>',
  });
  */
    
  
  // Abre/Fecha modal de busca do site

  jQuery(".abre-buscador").on('click', function(){
    jQuery(".busca-modal").fadeIn(250);
  });

  jQuery(".fecha-buscador").on('click', function(){
    jQuery(".busca-modal").fadeOut(250);
  });
  
  
  // Abre/Fecha sidebar da loja no mobile
    
  jQuery('#toggle-secondary').on('click', function(){
    jQuery(this).toggleClass('ativo');
    jQuery("#secondary").toggle('fast');
  });
  
  
  // Move caixa de cupom na página de checkout

  if( jQuery('body').hasClass('woocommerce-checkout') == true ){
    var coupon_toggle = jQuery(".woocommerce-form-coupon-toggle");
    var coupon_field = jQuery(".checkout_coupon");    
    
    coupon_field.insertAfter('.shop_table.woocommerce-checkout-review-order-table');
    coupon_toggle.insertAfter('.shop_table.woocommerce-checkout-review-order-table');    
  }
  
  
  // Aplica máscaras de input
  
  var SPMaskBehavior = function (val) {
    return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
  }, spOptions = {
    onKeyPress: function(val, e, field, options) {
      field.mask(SPMaskBehavior.apply({}, arguments), options);
    }
  };

  jQuery('input.telefone').mask(SPMaskBehavior, spOptions);
  jQuery('input.data').mask('00/00/0000');
  jQuery('input.cep').mask('00000-000');
  jQuery('input.cpf').mask('000.000.000-00');
  jQuery('input.cnpj').mask('00.000.000/0000-00');
  jQuery('input.rg').mask('00.000.000-0');
  jQuery('input.dinheiro').mask('#.##0,00', {reverse: true});
  jQuery('input.1-99').mask('#0');
  jQuery('input.passaporte').mask('AA000000', {'translation': {A: {pattern: /[A-Za-z]/}} });
  jQuery('input.titulo-eleitor').mask('0000 0000 0000');
  jQuery('input.uf').mask('AA');
  jQuery('input.pis-pasep').mask('000.00000.00-0');
  jQuery('input.ctps').mask('0000000');
  jQuery('input.ctps-serie').mask('000-0');
  jQuery('input.cnh').mask('000000000000');
  jQuery('input.zona-eleitoral').mask('000');
  jQuery('input.secao-eleitoral').mask('0000');

});
