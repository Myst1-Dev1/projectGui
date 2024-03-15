<?php
/**
 * Origgami Tema V3 functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Origgami_Tema_V3
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

if ( ! function_exists( 'origgami_tema_v3_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function origgami_tema_v3_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Origgami Tema V3, use a find and replace
		 * to change 'origgami-tema-v3' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'origgami-tema-v3', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(
			array(
				'menu-1' => esc_html__( 'Primary', 'origgami-tema-v3' ),
			)
		);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);

		// Set up the WordPress core custom background feature.
		add_theme_support(
			'custom-background',
			apply_filters(
				'origgami_tema_v3_custom_background_args',
				array(
					'default-color' => 'ffffff',
					'default-image' => '',
				)
			)
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 250,
				'width'       => 250,
				'flex-width'  => true,
				'flex-height' => true,
			)
		);
	}
endif;
add_action( 'after_setup_theme', 'origgami_tema_v3_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function origgami_tema_v3_content_width() {
	// This variable is intended to be overruled from themes.
	// Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
	$GLOBALS['content_width'] = apply_filters( 'origgami_tema_v3_content_width', 640 );
}
add_action( 'after_setup_theme', 'origgami_tema_v3_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function origgami_tema_v3_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Blog', 'origgami-tema-v3' ),
			'id'            => 'sidebar-1',
			'description'   => 'Barra lateral das páginas de Blog',
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'origgami_tema_v3_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function origgami_tema_v3_scripts() {
	wp_enqueue_style( 'origgami-tema-v3-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_style_add_data( 'origgami-tema-v3-style', 'rtl', 'replace' );

	wp_enqueue_script( 'origgami-tema-v3-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'origgami_tema_v3_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}




/* ###################################################### */
/* ################ FUNÇÕES CUSTOMIZADAS ################ */
/* ###################################################### */




//	Remove Admin Bar para todos
	show_admin_bar(false);


//	Adiciona suporte ao Woocommerce	
	add_theme_support( 'woocommerce' );


//	Custom thumbnails
	add_image_size( 'post-archive-image', 600, 350, array( 'center', 'center') );


//	Adiciona CSS ou JS adicionais
	function origgami_tema_v3_custom_enqueue() {
		// Estilos
		wp_enqueue_style( 'bootstrap-4.5.0', get_template_directory_uri() . '/assets/bootstrap-4.5.0/css/bootstrap.min.css' );
		wp_enqueue_style( 'slick-carousel-1.8.1', get_template_directory_uri() . '/assets/slick-carousel-1.8.1/css/slick.min.css' );
		wp_enqueue_style( 'font-awesome-alt', get_template_directory_uri() . '/assets/font-awesome-kit/font-awesome/css/font-awesome.min.css');
		wp_enqueue_style( 'font-awesome-animations-alt', get_template_directory_uri() . '/assets/font-awesome-kit/font-awesome-animation/font-awesome-animation.min.css');

		// Scripts
		wp_enqueue_script('jquery');
		wp_enqueue_script('bootstrap-4.5.0', get_template_directory_uri() . '/assets/bootstrap-4.5.0/js/bootstrap.min.js', '', '', true );
		wp_enqueue_script('jquery-mask-1.14.15', get_template_directory_uri() . '/assets/jquery-mask-1.14.15/js/jquery.mask.min.js', '', '', true);
		wp_enqueue_script('slick-carousel-1.8.1', get_template_directory_uri() . '/assets/slick-carousel-1.8.1/js/slick.min.js', '', '', true);
	}

	add_action( 'wp_enqueue_scripts', 'origgami_tema_v3_custom_enqueue' );


//	Função para substituir paginação padrão por páginação numérica
	function custom_page_navigation() {
	    if( is_singular() )
	        return;
	 
	    global $wp_query;
	 
	    /** Stop execution if there's only 1 page */
	    if( $wp_query->max_num_pages <= 1 )
	        return;
	 
	    $paged = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;
	    $max   = intval( $wp_query->max_num_pages );
	 
	    /** Add current page to the array */
	    if ( $paged >= 1 )
	        $links[] = $paged;
	 
	    /** Add the pages around the current page to the array */
	    if ( $paged >= 3 ) {
	        $links[] = $paged - 1;
	        $links[] = $paged - 2;
	    }
	 
	    if ( ( $paged + 2 ) <= $max ) {
	        $links[] = $paged + 2;
	        $links[] = $paged + 1;
	    }
	 
	    echo '<div class="custom-page-navigation"><ul>' . "\n";
	 
	    /** Previous Post Link */
	    if ( get_previous_posts_link() )
	        printf( '<li class="previous-page">%s</li>' . "\n", get_previous_posts_link('<i class="fa fa-angle-left" aria-hidden="true"></i>') );
	 
	    /** Link to first page, plus ellipses if necessary */
	    if ( ! in_array( 1, $links ) ) {
	        $class = 1 == $paged ? ' class="active"' : '';
	 
	        printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( 1 ) ), '1' );
	 
	        if ( ! in_array( 2, $links ) )
	            echo '<li>…</li>';
	    }
	 
	    /** Link to current page, plus 2 pages in either direction if necessary */
	    sort( $links );
	    foreach ( (array) $links as $link ) {
	        $class = $paged == $link ? ' class="active"' : '';
	        printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $link ) ), $link );
	    }
	 
	    /** Link to last page, plus ellipses if necessary */
	    if ( ! in_array( $max, $links ) ) {
	        if ( ! in_array( $max - 1, $links ) )
	            echo '<li>…</li>' . "\n";
	 
	        $class = $paged == $max ? ' class="active"' : '';
	        printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $max ) ), $max );
	    }
	 
	    /** Next Post Link */
	    if ( get_next_posts_link() )
	        printf( '<li class="next-page">%s</li>' . "\n", get_next_posts_link('<i class="fa fa-angle-right" aria-hidden="true"></i>') );
	 
	    echo '</ul></div>' . "\n";
	}


//	Função para buscar post/page feitas no visual composer
	function get_vc_page($cpt_name, $post_name){ ?>

		<?php if ($cpt_name && $post_name): ?>

			<?php
				$args = array( 
					'post_type' => $cpt_name,
					'name' => $post_name,
				);

				$wp_query = new WP_Query( $args );
			?>

			<?php if ($wp_query->have_posts()): ?>
				
				<?php while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>
					
					<?php
					global $post;
					$post_id = get_the_ID();
					?>

					<?php if ( $post_id ): ?>
						<?php $shortcodes_custom_css = get_post_meta( $post_id, '_wpb_shortcodes_custom_css', true ); ?>

						<?php if ( ! empty( $shortcodes_custom_css ) ): ?>
							<?php
								echo '<style type="text/css" data-type="vc_shortcodes-custom-css">';
								echo $shortcodes_custom_css;
								echo '</style>';
							?>
						<?php endif ?>

						<?php echo do_shortcode($post->post_content); ?>
					<?php endif ?>					

				<?php endwhile; // end while ?>

				<?php wp_reset_postdata(); ?>

			<?php endif ?>

		<?php else: ?>

			<pre>CPT Name e/ou Post Name não informado(s)!</pre>

		<?php endif ?>

	<?php }


//	Função para renomear slug de paginação (page -> pagina)
	/*function custom_page_slug() {
		global $wp_rewrite;
		$wp_rewrite->pagination_base = "pagina";
	}

	add_action( 'init', 'custom_page_slug' );*/


//	Adiciona novas configurações ao editor do plugin TinyMCE
	if ( ! function_exists( 'wpex_mce_text_sizes' ) ) {
		function wpex_mce_text_sizes( $initArray ){
			$initArray['fontsize_formats'] = "1px 2px 3px 4px 5px 6px 7px 8px 9px 10px 11px 12px 13px 14px 15px 16px 17px 18px 19px 20px 21px 22px 23px 24px 25px 26px 27px 28px 29px 30px 31px 32px 33px 34px 35px 36px 37px 38px 39px 40px 42px 44px 46px 48px 50px 52px 54px 56px 58px 60px 62px 64px 66px 68px 70px 72px 74px 78px 80px 90px 100px 120px 140px 150px 160px 170px 180px 200px";
			return $initArray;
		}
	}

	add_filter( 'tiny_mce_before_init', 'wpex_mce_text_sizes' );


//	Adiciona o slug da página como classe no <body>
	function add_slug_body_class( $classes ) {
		global $post;
			if ( isset( $post ) ) {
				$classes[] = $post->post_type . '-' . $post->post_name;
			}
		return $classes;
	}

	add_filter( 'body_class', 'add_slug_body_class' );


//	Adiciona o slug da página pai como classe no <body>
	function body_class_section($classes) {
	    global $wpdb, $post;
	    if (is_page()) {
	        if ($post->post_parent) {
	            $parent  = end(get_post_ancestors($current_page_id));
	        } else {
	            $parent = $post->ID;
	        }
	        $post_data = get_post($parent, ARRAY_A);
	        $classes[] = 'parent-' . $post_data['post_name'];
	    }
	    return $classes;
	}

	add_filter('body_class','body_class_section');


//	Logotipo
	function logotipo($atts){ ob_start(); ?>

		<?php
			$url = get_field('logotipo', 14);
			$texto = $atts['texto'];
			$largura_maxima = $atts['largura_maxima'];
		?>

		<?php if ( $url ): ?>
			<?php if ( is_front_page() ): ?>
				<h1 id="custom-logotipo">
					<a href="<?php echo site_url(); ?>">
						<img src="<?php echo $url['sizes']['medium']; ?>"  <?php if ($largura_maxima): ?>style="max-width: <?php echo $largura_maxima; ?>;"<?php endif ?>>

						<?php if ($texto): ?>
							<span class="site-name"><?php echo $texto; ?></span>
						<?php else: ?>
							<span class="site-name"><?php echo get_bloginfo('name'); ?></span>
						<?php endif ?>						
					</a>
				</h1>
			<?php else: ?>
				<div id="custom-logotipo">
					<a href="<?php echo site_url(); ?>">
						<img src="<?php echo $url['sizes']['medium']; ?>"  <?php if ($largura_maxima): ?>style="max-width: <?php echo $largura_maxima; ?>;"<?php endif ?>>
						
						<?php if ($texto): ?>
							<span class="site-name"><?php echo $texto; ?></span>
						<?php else: ?>
							<span class="site-name"><?php echo get_bloginfo('name'); ?></span>
						<?php endif ?>	
					</a>
				</div>
			<?php endif ?>
		<?php else: ?>
			<div class="alert alert-info" role="alert">
				<strong>Logotipo não configurado!</strong>				
				<?php if( current_user_can('editor') || current_user_can('administrator') ) {  ?>
				    <br><a href="<?php echo site_url().'/wp-admin/post.php?post=14&action=edit'; ?>" target="_blank" class="btn btn-outline-info btn-sm" style="display: inline-block; margin-top: 5px;">Editar Logotipo</a>
				<?php } ?>
			</div>
		<?php endif ?>

	<?php return ob_get_clean(); }

	add_shortcode('logotipo','logotipo');


//	Redes Sociais
	function redes_sociais($atts){ ob_start(); ?>

		<?php
			$tipo_exibicao = $atts['tipo'];
			$alinhamento = $atts['alinhamento'];
			$modo_unico = $atts['modo_unico'];

			if ( $modo_unico ) {
				$args = array(
					'post_type' => 'redes_sociais',
					'name' => sanitize_title($modo_unico),
					'post_status' => 'publish',
					'posts_per_page' => -1,
				);
			}else{
				$args = array(
					'post_type' => 'redes_sociais',
					'post_status' => 'publish',
					'posts_per_page' => -1,
				);
			}			

			$the_query = new WP_Query( $args );
		?>

		<?php if ( $the_query->have_posts() ): ?>
			
			<div class="redes_sociais">
				<div class="content <?php echo sanitize_title($alinhamento); ?>">
					
					<?php
						while ($the_query->have_posts()) {
							$the_query->the_post();

							$id = get_the_ID();
							$titulo = get_the_title();
							$icone = get_field('icone');
							$texto = get_field('texto_alternativo');
							$link = get_field('url');

							// pause while ?>

							<div class="rede-social <?php echo sanitize_title($titulo); ?>">
								<a href="<?php if( $link ){ echo $link; }else{ echo 'javascript:void(0);'; } ?>" target="_blank" class="<?php echo sanitize_title($tipo_exibicao); ?>">

									<?php if ( $tipo_exibicao == 'Apenas Ícone' ): ?>
										<?php if ( $icone ): ?>
											<div class="icone">
												<?php echo $icone; ?>
											</div>
										<?php else: ?>
											<div class="icone">
												Nenhum <strong>ícone</strong> encontrado.
											</div>
										<?php endif ?>
									<?php endif ?>


									<?php if ( $tipo_exibicao == 'Ícone + Texto' ): ?>
										<?php if ( $icone ): ?>
											<div class="icone">
												<?php echo $icone; ?>
											</div>
										<?php else: ?>
											<div class="icone">
												Ícone Desconhecido
											</div>
										<?php endif ?>

										<?php if ( $texto ): ?>
											<div class="texto">
												<?php echo $texto; ?>
											</div>
										<?php else: ?>
											<div class="texto">
												Nenhum <strong>texto</strong> encontrado.
											</div>
										<?php endif ?>
									<?php endif ?>


									<?php if ( $tipo_exibicao == 'Apenas Texto' ): ?>
										<?php if ( $texto ): ?>
											<div class="texto">
												<?php echo $texto; ?>
											</div>
										<?php else: ?>
											<div class="texto">
												Nenhum <strong>texto</strong> encontrado.
											</div>
										<?php endif ?>
									<?php endif ?>
								</a>
							</div>

							<?php // resume while
						}
					?>

					<?php wp_reset_postdata(); ?>

				</div>
			</div>

		<?php else: ?>
			<div class="alert alert-warning" role="alert">
				Nenhuma <strong>rede social</strong> encontrada.
			</div>
		<?php endif ?>

	<?php return ob_get_clean(); }

	add_shortcode('redes_sociais','redes_sociais');


//	SHORTCODE - copyright
	function copyright(){ ob_start(); ?>

		<p><span class="copyright-year">© <?php echo date('Y'); ?></span> <span class="site-name"><?php echo get_bloginfo('name'); ?>.</span> <span class="all-rights-reserved">Todos os direitos reservados.</span></p>

	<?php return ob_get_clean(); }

	add_shortcode('copyright','copyright');


//	Custom Admin CSS
	function custom_admin_css() {
	  echo '<style>
	    table.pages tbody#the-list tr.level-0 th.check-column {
	        border-left: 4px solid #d1d1d1 !important;
	    }
	    table.pages tbody#the-list tr.level-1 th.check-column {
	        border-left: 4px solid #e02323 !important;
	    }

	    body.appearance_page_pp_custom_css_dev #wpbody-content {
	        padding: 0;
	    }
	    body.appearance_page_pp_custom_css_dev #wpfooter {
	        display: none;
	    }

	    #pp_custom_css_js_dev_page .nav-tab {
	        margin: 0 4px 4px 0;
	    }
	    #pp_custom_css_js_dev_page .nav-tab.nav-tab-new-file:first-child {
	        margin: 0;
	    }
	    #ags-ccjdev-settings{
	    	font-size: 14px;
	    	line-height: 16px;
	    	letter-spacing: 0.3px;
		}
		#ags-ccjdev-settings-container {
		    height: 100vh;
		    margin: 0 0 0 -20px;
		    position: relative;
		    z-index: 999;
		}
		.cm-s-default .cm-comment {
		    color: #03a9f4;
		    font-weight: bolder;
		}
		#pp_custom_css_js_dev_revisions{
			max-height: 235px;
			overflow: auto;
		}
		#pp_custom_code_editor_dev {
		    //height: 100vh !important;
		    overflow: hidden;
		}
		#pp_custom_code_editor_dev > .CodeMirror {
		    //height: 100vh !important;
		}
		@media(min-width: 961px){
			#pp-custom-css-js-dev-editor-buttons-bottom{
				//background-color: #fff;
			    //position: fixed;
			    //right: 9px;
			    //bottom: 0;
			    //left: 170px;
			    //z-index: 9;
			    //box-shadow: 0px 0px 10px rgb(0 0 0 / 25%);
			}
		}		
	  </style>';
	}

	add_action('admin_head', 'custom_admin_css');


//	Adiciona opções "Somente leitura" e "Desativado" ao ACF Fields
	function add_readonly_and_disabled_to_fields($field) {
		acf_render_field_setting( $field, array(
			'label'      => __('Somente leitura?','acf'),
			'instructions'  => '',
			'type'      => 'radio',
			'name'      => 'readonly',
			'choices'    => array(
				0        => __("Não",'acf'),
				1        => __("Sim",'acf'),
			),
			'layout'  =>  'horizontal',
		));
		acf_render_field_setting( $field, array(
			'label'      => __('Desativado?','acf'),
			'instructions'  => '',
			'type'      => 'radio',
			'name'      => 'disabled',
			'choices'    => array(
				0        => __("Não",'acf'),
				1        => __("Sim",'acf'),			
			),
			'layout'  =>  'horizontal',
		));
	}

	add_action('acf/render_field_settings/type=textarea', 'add_readonly_and_disabled_to_fields');
	add_action('acf/render_field_settings/type=text', 'add_readonly_and_disabled_to_fields');
	add_action('acf/render_field_settings/type=number', 'add_readonly_and_disabled_to_fields');
	add_action('acf/render_field_settings/type=email', 'add_readonly_and_disabled_to_fields');
	add_action('acf/render_field_settings/type=tel', 'add_readonly_and_disabled_to_fields');
	add_action('acf/render_field_settings/type=url', 'add_readonly_and_disabled_to_fields');
	add_action('acf/render_field_settings/type=date', 'add_readonly_and_disabled_to_fields');


//	SHORTCODE - header_mobile
	function header_mobile(){ ob_start(); ?>

		<div id="header-mobile">
			<div class="content">
				
				<div class="logotipo-mobile">
					<a href="<?php echo site_url(); ?>">
						<img src="<?php echo site_url(); ?>/wp-content/uploads/2022/02/origgami-site-base-v3-origgami-site-base-v3-header-mobile-origgami.png">
					</a>
				</div>

				<div class="busca_outros-itens">
					<a href="javascript:void(0)" class="abre-buscador">
						<i class="fa fa-search" aria-hidden="true"></i>
					</a>

					<?php echo do_shortcode('[redes_sociais tipo="Apenas Ícone" alinhamento="direta"]'); ?>
				</div>

			</div>
		</div>

	<?php return ob_get_clean(); }

	add_shortcode('header_mobile','header_mobile');


//	SHORTCODE - dados_razao_social
	function dados_razao_social(){ ob_start(); ?>

		<?php echo get_field('razao_social', 3) ?>

	<?php return ob_get_clean(); }

	add_shortcode('dados_razao_social','dados_razao_social');


//	SHORTCODE - dados_cnpj
	function dados_cnpj(){ ob_start(); ?>

		<?php echo get_field('cnpj', 3) ?>

	<?php return ob_get_clean(); }

	add_shortcode('dados_cnpj','dados_cnpj');


//	SHORTCODE - dados_endereco
	function dados_endereco(){ ob_start(); ?>

		<?php echo get_field('endereco', 3) ?>

	<?php return ob_get_clean(); }

	add_shortcode('dados_endereco','dados_endereco');


//	SHORTCODE - dados_email
	function dados_email(){ ob_start(); ?>

		<?php echo get_field('e-mail', 3) ?>

	<?php return ob_get_clean(); }

	add_shortcode('dados_email','dados_email');