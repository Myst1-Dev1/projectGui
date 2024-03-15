<?php
function pp_custom_css_js_dev_download_file() {
	if (!isset($_GET['rev']) || !is_numeric($_GET['rev']))
		die();
	
	$post = get_post($_GET['rev']);
	if (empty($post) || ($post->post_type != 'hm_custom_css' && $post->post_type != 'hm_custom_javascript'))
		die();
	
	header('Content-Type: text/'.($post->post_type == 'hm_custom_javascript' ? 'javascript' : 'css'));
	header('Content-Disposition: attachment; filename="'.$post->post_title.'"');
	
	echo($post->post_content);
	die();
}
?>