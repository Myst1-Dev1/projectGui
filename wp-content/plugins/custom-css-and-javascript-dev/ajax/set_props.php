<?php
function pp_custom_css_js_dev_set_props() {
	if (empty($_POST['mode']) || empty($_POST['file']) || empty($_POST['props']))
		wp_send_json_error();
	$_POST['mode'] = strtolower($_POST['mode']);
	if ($_POST['mode'] != 'css' && $_POST['mode'] != 'javascript')
		wp_send_json_error();
	
	// Check for invalid property names
	if (count(array_diff(array_keys($_POST['props']), array('tinymce')))) {
		wp_send_json_error();
	}
	
	// Check file name
	$files = get_option('hm_custom_'.$_POST['mode'].'_files', array());
	if (array_search($_POST['file'], $files) === false)
		wp_send_json_error();
	
	$props = get_option('hm_custom_'.$_POST['mode'].'_props', array());
	
	foreach ($_POST['props'] as $prop => $value) {
		if ($value === null || $value == 'null') {
			unset($props[$_POST['file']][$prop]);
			if (empty($props[$_POST['file']])) {
				unset($props[$_POST['file']]);
			}
		} else {
			if (!isset($props[$_POST['file']])) {
				$props[$_POST['file']] = array();
			}
			$props[$_POST['file']][$prop] = $value;
		}
	}
	
	if (empty($props)) {
		delete_option('hm_custom_'.$_POST['mode'].'_props');
	} else {
		update_option('hm_custom_'.$_POST['mode'].'_props', $props, false);
	}
	
	include_once(dirname(__FILE__).'/include/compile.php');
	$result = pp_custom_css_js_dev_compile($_POST['mode'], 'tinymce');
	if ($result !== true) {
		wp_send_json_error($result);
	}
	
	wp_send_json_success();
}
?>