<?php
/*
Template Name: Sem Título / Com Sidebar
*/
get_header(); ?>

	<div id="primary_secondary">
		<?php get_sidebar(); ?>

		<div id="primary" class="content-area">
			<main id="main" class="site-main">

				<?php
				while ( have_posts() ) : the_post();

					the_content();

				endwhile; // End of the loop.
				?>

			</main><!-- #main -->
		</div><!-- #primary -->
	</div>

<?php
get_footer();
