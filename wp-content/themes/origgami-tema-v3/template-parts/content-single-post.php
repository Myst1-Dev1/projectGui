<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Origgami_Tema_V3
 */

?>

<?php
	$post_id = get_the_ID();
	$title = get_the_title();
	//$image = get_the_post_thumbnail_url( $post_id, 'medium' );
	//$url = get_the_permalink();
?>

<header class="post-header">
	<h1 class="post-title"><?php echo $title; ?></h1>
	<span class="data-postagem">Postado em <?php echo get_the_date('d/m/Y'); ?></span>
</header>

<div class="post-content">
	<?php the_content(); ?>
</div>

<footer class="post-footer">
	<?php origgami_tema_v3_entry_footer(); ?>
</footer>