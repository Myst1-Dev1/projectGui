<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Origgami_Tema_V3
 */

?>

<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="content">
		
		<?php
			$post_id = get_the_ID();
			$title = get_the_title();
			$image = get_the_post_thumbnail_url( $post_id, 'post-archive-image' );
			$url = get_the_permalink();
		?>

		<div class="post-image">
			<a href="<?php echo $url; ?>">
				<?php if ( $image ): ?>
					<img src="<?php echo $image; ?>">
				<?php else: ?>
					<pre>Sem Imagem do Post.</pre>
				<?php endif ?>
			</a>
		</div>

		<div class="post-title">
			<h2><a href="<?php echo $url; ?>"><?php echo $title; ?></a></h2>
		</div>

	</div>
</div>