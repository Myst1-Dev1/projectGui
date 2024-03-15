<?php
function pp_custom_css_js_dev_sort() {
	
	if (empty($_POST['mode']) || empty($_POST['order']))
		wp_send_json_error();
	$_POST['mode'] = strtolower($_POST['mode']);
	if ($_POST['mode'] != 'css' && $_POST['mode'] != 'javascript')
		wp_send_json_error();
		
	update_option('hm_custom_'.$_POST['mode'].'_files', $_POST['order']) or wp_send_json_error();
	
	wp_send_json_success();
}
?>