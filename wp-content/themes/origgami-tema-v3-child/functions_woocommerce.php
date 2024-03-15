<?php

	
//	Adiciona a sidebar do Woocommerce
	function shop_sidebar() {
		register_sidebar( array(
			'name' => 'Shop',
			'id' => 'shop',
			'description' => 'Barra lateral das páginas de Loja',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'class'         => 'shop-sidebar',
		'after_widget'  => '</div>',
		'before_title'  => '<h2 class="widgettitle">',
		'after_title'   => '</h2>', 
		) );
	}

	add_action( 'widgets_init', 'shop_sidebar' );


//	Remove necessidade de senha forte ao cadastrar um conta
	function remove_senha_forte() {
		if ( wp_script_is( 'wc-password-strength-meter', 'enqueued' ) ) {
			wp_dequeue_script( 'wc-password-strength-meter' );
		}
	}
	
	add_action( 'wp_print_scripts', 'remove_senha_forte', 100 );


//	Ativa suporte a zoom, lightbox e slider na página do produto
	function custom_product_gallery() {
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );
	}

	add_action( 'after_setup_theme', 'custom_product_gallery' );


//	Define tamanho da imagem do produto na sua página interna
	function custom_product_img_size(){
		$size = array(
			'width' => 600,
			'height' => 600,
			'crop' => 1,
		);

		return $size;
	}

	add_filter( 'woocommerce_get_image_size_single', 'custom_product_img_size' );
	add_filter( 'woocommerce_get_image_size_shop_single', 'custom_product_img_size' );
	add_filter( 'woocommerce_get_image_size_woocommerce_single', 'custom_product_img_size' );


//	Altera a quantidade de produtos relacionados
	/*
	function related_products_args( $args ) {
		$args['posts_per_page'] = 8;
		$args['columns'] = 4;
		return $args;
	}

	add_filter( 'woocommerce_output_related_products_args', 'related_products_args', 20 );
	*/


//	Remove botão de comprar no loop de produtos
	/*
	remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
	*/


//	Informa um peso padrão mínimo aos produtos sem peso definido pelo vendedor
	if( !function_exists('product_default_weight') ) {
		function product_default_weight($weight) {
			$default_weight = 0.3; // Informe o peso mínimo (ex: 0.3 -> 300g)
			if( empty($weight) ) {
				return $default_weight;
			}
			else {
				return $weight;
			}
		}
	}

	add_filter( 'woocommerce_product_get_weight', 'product_default_weight' );
	add_filter( 'woocommerce_product_variation_get_weight', 'product_default_weight' );


//	Shortcode - Breadcrumb Shortcode
	function breadcrumb(){ ob_start(); ?>

	<?php if ( function_exists('bcn_display') ): ?>
		<div class="breadcrumbs" typeof="BreadcrumbList" vocab="https://schema.org/">
			<?php bcn_display();?>
		</div>
	<?php endif ?>

	<?php return ob_get_clean(); }

	add_shortcode('breadcrumb','breadcrumb');


//	Adicionar Wishlist no menu em Minha Conta	
	function wislist_my_account_link( $menu_links ){
		// we will hook "anyuniquetext123" later
		$new = array( 'yith_wishlist' => 'Lista de Desejos' );
	 
		// or in case you need 2 links
		// $new = array( 'link1' => 'Link 1', 'link2' => 'Link 2' );
	 
		// array_slice() is good when you want to add an element between the other ones
		$menu_links = array_slice( $menu_links, 0, 1, true ) 
		+ $new 
		+ array_slice( $menu_links, 1, NULL, true );
	 
		return $menu_links;	 
	}

	add_filter ( 'woocommerce_account_menu_items', 'wislist_my_account_link' );
	 
	function wislist_hook_endpoint( $url, $endpoint, $value, $permalink ){
		if( $endpoint === 'yith_wishlist' ) {
			// ok, here is the place for your custom URL, it could be external
			$url = get_permalink( get_page_by_title( 'Lista de Desejos' ) );
		}
		return $url;
	}

	add_filter( 'woocommerce_get_endpoint_url', 'wislist_hook_endpoint', 10, 4 );


//	Remove titulo da página de archive da loja
	add_filter('woocommerce_show_page_title', '__return_false');


//	Remove tabs da página do produto
	function woo_remove_product_tabs( $tabs ) {
		unset( $tabs['description'] );
		unset( $tabs['reviews'] );
		unset( $tabs['additional_information'] );
		return $tabs;
	}

	add_filter( 'woocommerce_product_tabs', 'woo_remove_product_tabs', 98 );


//	Remove metas do produto
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );


//	Muda texto dos produtos relacionados
	function my_text_strings( $translated_text, $text, $domain ) {
		switch ( $translated_text ) {
			case 'Produtos relacionados' :
				$translated_text = __( 'Relacionados', 'woocommerce' );
				break;
		}
		return $translated_text;
	}

	add_filter( 'gettext', 'my_text_strings', 20, 3 );


//	Muda ícone de paginação do woocommerce
	function custom_woo_pagination( $args ) {
		$args['prev_text'] = '<i class="fa fa-angle-left"></i>';
		$args['next_text'] = '<i class="fa fa-angle-right"></i>';
		return $args;
	}

	add_filter( 'woocommerce_pagination_args', 	'custom_woo_pagination' );


//	Move descrição curta do produto
	function product_full_description(){ ?>
		<div class="product-description">
			<div class="descricao_compartilhe">
				<span class="descricao">DESCRIÇÃO</span>

				<span class="compartilhe"><?php echo do_shortcode('[addtoany]'); ?></span>
			</div>

			<?php the_content(); ?>
		</div>

		<?php echo do_shortcode('[yith_wcwl_add_to_wishlist]'); ?>
	<?php }

	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
	add_action( 'woocommerce_after_add_to_cart_form', 'product_full_description', 10 );


//	Ao ir para "Finalizar Conta", redireciona para "Minha Conta" caso não esteja registrado ou logado.
	function check_if_logged_in(){
		$pageid = 64; // your checkout page id

		if(!is_user_logged_in() && is_page($pageid)){
			$url = add_query_arg(
				'redirect_to',
				get_permalink($pagid),
				site_url('/minha-conta/') // your my acount url
			);
			wp_redirect($url);
			exit;
		}

		if(is_user_logged_in()){
			if(is_page(65)){ //my-account page id
				$redirect = $_GET['redirect_to'];
				if (isset($redirect)) {
				echo '<script>window.location.href = "'.$redirect.'";</script>';
				}

			}
		}
	}

	add_action('template_redirect','check_if_logged_in');


//	Shortcode - Shop Widgets
	function shop_widgets($atts){ ob_start(); ?>

		<?php
			if ( class_exists( 'woocommerce' ) ) {
				?>

				<?php
					$alinhamento = $atts['alinhamento'];

					if ( is_null( WC()->cart ) ) {
						$qtd = 0;
					} else { 
						$qtd = WC()->cart->get_cart_contents_count();
					}

					$account_url = get_permalink( get_page_by_path( 'Minha conta' ) );
					$cart_url = wc_get_cart_url();
				?>

				<div class="shop_widgets">
					<div class="content <?php echo sanitize_title($alinhamento); ?>">
						<div class="icone busca">
							<a href="javascript:void(0);" class="abre-buscador">
								<i class="fa fa-search" aria-hidden="true"></i>
							</a>
						</div>

						<div class="icone minha-conta">
							<a href="<?php echo $account_url; ?>">
								<i class="fa fa-user-circle-o" aria-hidden="true"></i>
							</a>
						</div>

						<div class="icone sacola-de-compras">
							<a href="<?php echo $cart_url; ?>">
								<i class="fa fa-shopping-basket" aria-hidden="true"></i><span>0<?php echo $qtd; ?> ITEN(S)</span>
							</a>
						</div>
					</div>
				</div>

				<?php
			} else {
				?>

				<div class="alert alert-danger" role="alert">
					<strong>Woocommerce</strong> não instalado.
				</div>

				<?php
			}
		?>

	<?php return ob_get_clean(); }

	add_shortcode('shop_widgets','shop_widgets');


//	Adiciona informações de parcelamento abaixo do preço do produto
	function info_parcelamento( $price ) { ?>

		<?php if ( !is_cart() ): ?>

			<?php global $product; ?>

			<!-- Produto Simples -->
			<?php if ( $product->is_type( 'simple' ) ): ?>
				<?php if ( $product->price <= 5 ): ?>
					<?php $price .= '<p class="info-preco">ou 1x de '.wc_price($product->price).'</p>'; ?>
				<?php else: ?>
					<?php
						$parcelas = get_field('numero_de_parcelas', get_option( 'woocommerce_shop_page_id' ));

						$preco_parcelado = $product->price / $parcelas;
						$preco_desconto = $product->price - ($product->price * 0.1);

						$price .= '<p class="info-preco">ou '.$parcelas.'x de '.wc_price($preco_parcelado).' s/ juros</p>';
					?>
				<?php endif ?>
			<?php endif ?>

			<!-- Produto Variável -->
			<?php if ( $product->is_type( 'variable' ) ): ?>
				<?php
					$parcelas = get_field('numero_de_parcelas', get_option( 'woocommerce_shop_page_id' ));

					$preco_parcelado = $product->price / $parcelas;
					$preco_desconto = $product->price - ($product->price * 0.1);

					$price .= '<p class="info-preco">ou em '.$parcelas.'x s/ juros</p>';
				?>
			<?php endif ?>
			
		<?php endif ?>		

		<?php return $price; ?>
	<?php }

	add_filter( 'woocommerce_get_price_html', 'info_parcelamento' );
	add_filter( 'woocommerce_cart_item_price', 'info_parcelamento' );


//	Muda texto e link do botão de compra
	function custom_add_to_cart_button( $button, $product  ) {
	    $button_text = "Comprar";
	    $button = '<a class="button add_to_cart_button" href="' . $product->get_permalink() . '">' . $button_text . '</a>';

	    return $button;
	}

	add_filter( 'woocommerce_loop_add_to_cart_link', 'custom_add_to_cart_button', 10, 2 );


