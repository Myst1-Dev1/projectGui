<?php
function pp_custom_css_js_dev_delete_revisions() {
	if (empty($_POST['mode']) || empty($_POST['file']))
		wp_send_json_error();
	$_POST['mode'] = strtolower($_POST['mode']);
	if ($_POST['mode'] != 'css' && $_POST['mode'] != 'javascript')
		wp_send_json_error();
	
	$posts = get_posts(array(
		'post_type' => 'hm_custom_'.$_POST['mode'],
		'post_status' => 'draft',
		'fields' => 'ids',
		'nopaging' => true,
		'title' => $_POST['file']
	));
	if (!is_array($posts)) {
		wp_send_json_error();
	}
	foreach ($posts as $postId) {
		if (!wp_delete_post($postId, true))
			wp_send_json_error();
	}
	
	// Send revisions
	include(dirname(__FILE__).'/include/get_revisions.php');
	wp_send_json_success(array('file' => $_POST['file'], 'revisions' => pp_custom_css_js_dev_get_revisions($_POST['mode'], $_POST['file'])));
}
?>