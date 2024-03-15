<?php
function pp_custom_css_js_dev_get_revision() {
	if (empty($_POST['mode']) || !isset($_POST['rev']) || !is_numeric($_POST['rev']))
		wp_send_json_error();
	$_POST['mode'] = strtolower($_POST['mode']);
	if ($_POST['mode'] != 'css' && $_POST['mode'] != 'javascript')
		wp_send_json_error();
	
	$post = get_post($_POST['rev']);
	if ($post->post_type != 'hm_custom_'.$_POST['mode'])
		wp_send_json_error();
	
	wp_send_json_success(array(
		'id' => $post->ID,
		'file' => $post->post_title,
		'content' => $post->post_content
	));
}
?>