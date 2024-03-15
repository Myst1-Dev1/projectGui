<?php get_header(); ?>

	<header class="page-header">
		<?php echo do_shortcode('[breadcrumb]'); ?>
		
		<?php if ( !is_product() ): ?>
			<h1 class="page-title"><?php woocommerce_page_title(); ?></h1>
		<?php endif ?>
	</header>

	<div id="primary_secondary">
		<?php if ( is_archive() ): ?>
			<div id="toggle-secondary" class="mobile-only">
				<svg viewBox="0 0 16 16" class="bi bi-arrow-down-circle-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
				  <path fill-rule="evenodd" d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8.5 5a.5.5 0 0 0-1 0v4.793L5.354 7.646a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 9.793V5z"/>
				</svg>

				Filtrar Produtos
			</div>

			<aside id="secondary" class="widget-area-woocommerce" role="complementary">
				<?php dynamic_sidebar( 'shop' ); ?>
			</aside><!-- #secondary --> 
		<?php endif ?>

		<div id="primary" class="content-area">
			<main id="main" class="site-main">

			<?php if ( have_posts() ): ?>
				<?php woocommerce_content(); ?>
			<?php else: ?>
				<div class="alert alert-warning" role="alert">
					Nenhum produto encontrado.
				</div>
			<?php endif ?>

			</main><!-- #main -->
		</div><!-- #primary -->
	</div>

<?php
get_footer();
