<?php
function pp_custom_css_js_dev_delete_file() {
	global $wpdb;

	if (empty($_POST['mode']) || empty($_POST['file']))
		wp_send_json_error();
	$_POST['mode'] = strtolower($_POST['mode']);
	if ($_POST['mode'] != 'css' && $_POST['mode'] != 'javascript')
		wp_send_json_error();
	
	$_POST['file'] = pp_custom_css_js_dev_sanitize_filename($_POST['file']);
	
	$posts = $wpdb->get_col('SELECT ID FROM '.$wpdb->posts.'
								WHERE post_type=\'hm_custom_'.$_POST['mode'].'\'
									AND post_title=\''.$_POST['file'].'\'');
	
	if ($posts === false) {
		wp_send_json_error();
	}
	
	// First, try to compile without this file to catch any errors
	$fileArray = array($_POST['file'] => null);
	include_once(dirname(__FILE__).'/include/compile.php');
	$result = pp_custom_css_js_dev_compile($_POST['mode'], false, false, $fileArray);
	if ($result !== true) {
		wp_send_json_error($result);
	}
	if ($_POST['mode'] == 'css') {
		$result = pp_custom_css_js_dev_compile('css', 'tinymce', false, $fileArray);
		if ($result !== true) {
			wp_send_json_error($result);
		}
	}
	$result = pp_custom_css_js_dev_compile($_POST['mode'], true, !empty($_POST['minify']), $fileArray);
	if ($result !== true) {
		wp_send_json_error($result);
	}
	
	foreach ($posts as $postId) {
		if (!wp_delete_post($postId, true))
			wp_send_json_error();
	}
	
	$props = get_option('hm_custom_'.$_POST['mode'].'_props', array());
	if (isset($props[$_POST['file']])) {
		unset($props[$_POST['file']]);
		update_option('hm_custom_'.$_POST['mode'].'_props', $props, false);
	}
	
	wp_send_json_success();
}
?>