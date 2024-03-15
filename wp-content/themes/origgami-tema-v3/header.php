<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Origgami_Tema_V3
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
	<div class="busca-modal" style="display: none;">
		<div class="content">

			<div class="fechar">
				<span class="fecha-buscador"><i class="fa fa-times" aria-hidden="true"></i> Fechar</span>
			</div>
			
			<div class="formulario-busca">
				<form id="searchform" method="get" action="<?php echo site_url(); ?>">
				    <input type="text" class="search-field" name="s" placeholder="Buscar por..." value="<?php the_search_query(); ?>">
				    <input type="hidden" name="post_type" value="product">
				    <button type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
				</form>
			</div>
		</div>
	</div>

	<div id="loading">
		<div class="content">
			<i class="fa fa-circle-o-notch fa-spin"></i>
		</div>
	</div>

	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'origgami-tema-v3' ); ?></a>

	<header id="main-header">
		<div class="container">

			<?php get_vc_page('header_e_footer', 'header'); ?>

		</div>
	</header><!-- #main-header -->

	<div id="main-content">
		<div class="container">

		
