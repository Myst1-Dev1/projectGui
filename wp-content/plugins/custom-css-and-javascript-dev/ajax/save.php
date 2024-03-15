<?php
function pp_custom_css_js_dev_save() {
	if (empty($_POST['mode']) || empty($_POST['file']) || !isset($_POST['code']))
		wp_send_json_error();
	$_POST['mode'] = strtolower($_POST['mode']);
	if ($_POST['mode'] != 'css' && $_POST['mode'] != 'javascript')
		wp_send_json_error();
	
	$_POST['file'] = pp_custom_css_js_dev_sanitize_filename($_POST['file']);
	$slashedCode = $_POST['code'];
	$_POST['code'] = wp_unslash($_POST['code']);
	
	// Compile
	include_once(dirname(__FILE__).'/include/compile.php');
	$result = pp_custom_css_js_dev_compile($_POST['mode'], false, false, array($_POST['file'] => $_POST['code']));
	if ($result !== true) {
		wp_send_json_error($result);
	}
	if ($_POST['mode'] == 'css') {
		$result = pp_custom_css_js_dev_compile('css', 'tinymce', false, array($_POST['file'] => $_POST['code']));
		if ($result !== true) {
			wp_send_json_error($result);
		}
	}
	
	$rev_id = wp_insert_post(array(
		'post_title' => $_POST['file'],
		'post_content' => $slashedCode,
		'post_status' => 'draft',
		'post_type' => 'hm_custom_'.$_POST['mode'],
	));
	if ($rev_id === false)
		wp_send_json_error();
	update_post_meta($rev_id, 'ppccjd_latest', 1) or wp_send_json_error();
	
	// Delete previous latest revision
	$oldLatest = get_posts(array(
		'post_status' => 'any',
		'post_type' => 'hm_custom_'.$_POST['mode'],
		'title' => $_POST['file'],
		'meta_key' => 'ppccjd_latest',
		'fields' => 'ids'
	));
	if (!is_array($oldLatest)) {
		wp_send_json_error();
	}
	foreach ($oldLatest as $post) {
		if ($post != $rev_id) {
			delete_post_meta($post, 'ppccjd_latest') or wp_send_json_error();
		}
	}
	
	// Send revisions
	include(dirname(__FILE__).'/include/get_revisions.php');
	wp_send_json_success(array('file' => $_POST['file'], 'revisions' => pp_custom_css_js_dev_get_revisions($_POST['mode'], $_POST['file'])));
}
?>