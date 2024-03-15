<?php
function pp_custom_css_js_dev_delete_revision() {
	if (empty($_POST['mode']) || !isset($_POST['rev']) || !is_numeric($_POST['rev']))
		wp_send_json_error();
	$_POST['mode'] = strtolower($_POST['mode']);
	if ($_POST['mode'] != 'css' && $_POST['mode'] != 'javascript')
		wp_send_json_error();
	
	$post = get_post($_POST['rev']);
	if ($post->post_type != 'hm_custom_'.$_POST['mode'] || $post->post_status == 'publish')
		wp_send_json_error();
	
	
	if (!wp_delete_post($post->ID, true))
		wp_send_json_error();
	
	// Send revisions
	include(dirname(__FILE__).'/include/get_revisions.php');
	wp_send_json_success(array('file' => $post->post_title, 'revisions' => pp_custom_css_js_dev_get_revisions($_POST['mode'], $post->post_title)));
}
?>