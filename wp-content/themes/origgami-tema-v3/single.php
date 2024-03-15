<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Origgami_Tema_V3
 */

get_header();
?>

	<div id="primary_secondary">
		<div id="primary" class="content-area">
			<main id="main" class="site-main">

				<div class="post">
					<div class="content">
						<?php
						if ( have_posts() ) :

							/* Start the Loop */
							while ( have_posts() ) :
								the_post();

								/*
								 * Include the Post-Type-specific template for the content.
								 * If you want to override this in a child theme, then include a file
								 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
								 */
								get_template_part( 'template-parts/content-single', get_post_type() );

							endwhile;

							the_posts_navigation();

						else :

							get_template_part( 'template-parts/content', 'none' );

						endif;
						?>
					</div>
				</div>

			</main><!-- #main -->
		</div><!-- #primary -->
	</div>

<?php
get_footer();
