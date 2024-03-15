<?php
function pp_custom_css_js_dev_create_file() {
	if (empty($_POST['type']) || !in_array($_POST['type'], array('css', 'scss', 'js'))) {
		wp_send_json_error();
	}
	$mode = ($_POST['type'] == 'js' ? 'javascript' : 'css');
	$postType = 'hm_custom_'.$mode;
	
	$files = get_option('hm_custom_'.$mode.'_files', array());
	
	$i = 0;
	do {
		$newFile = 'new'.++$i.'.'.$_POST['type'];
	} while (count(preg_grep('/^'.$newFile.'$/i', $files)));
	/*if (wp_insert_post(array(
		'post_title' => $newFile,
		'post_status' => 'draft',
		'post_type' => $postType
	))) {*/
		wp_send_json_success($newFile);
	/*} else {
		wp_send_json_error();
	}*/
}
?>
