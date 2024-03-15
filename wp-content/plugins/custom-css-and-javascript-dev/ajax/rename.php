<?php
function pp_custom_css_js_dev_rename() {
	if (empty($_POST['mode']) || empty($_POST['file']) || empty($_POST['new_name']))
		wp_send_json_error();
	$_POST['mode'] = strtolower($_POST['mode']);
	if ($_POST['mode'] != 'css' && $_POST['mode'] != 'javascript')
		wp_send_json_error();
	
	$files = get_option('hm_custom_'.$_POST['mode'].'_files', array());
	if (($fileIndex = array_search($_POST['file'], $files)) === false)
		wp_send_json_error();
	$_POST['new_name'] = pp_custom_css_js_dev_sanitize_filename($_POST['new_name'].substr($_POST['file'], strrpos($_POST['file'], '.')));
	
	// Duplicate check
	foreach ($files as $file) {
		if (!strcasecmp($_POST['new_name'], $file)) {
			wp_send_json_error();
		}
	}
	$files[$fileIndex] = $_POST['new_name'];
	
	global $wpdb;
	if ($wpdb->update($wpdb->posts,
					array('post_title' => $_POST['new_name']),
					array('post_type' => 'hm_custom_'.$_POST['mode'], 'post_title' => $_POST['file'])) === false) {
		wp_send_json_error();
	}
	
	$files = update_option('hm_custom_'.$_POST['mode'].'_files', $files);
	wp_send_json_success($_POST['new_name']);
}
?>