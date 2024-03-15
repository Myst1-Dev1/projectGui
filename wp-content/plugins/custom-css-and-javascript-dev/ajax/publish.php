<?php
function pp_custom_css_js_dev_publish() {
	if (empty($_POST['mode']) || (empty($_POST['file']) && empty($_POST['all'])))
		wp_send_json_error();
	$_POST['mode'] = strtolower($_POST['mode']);
	if ($_POST['mode'] != 'css' && $_POST['mode'] != 'javascript')
		wp_send_json_error();
	
	// Get latest revision
	$args = array(
		'post_type' => 'hm_custom_'.$_POST['mode'],
		'post_status' => 'any',
		'orderby' => 'none',
		'meta_key' => 'ppccjd_latest',
		'nopaging' => true
	);
	if (empty($_POST['all'])) {
		$args['title'] = $_POST['file'];
	}
	$posts = get_posts($args);
	if (empty($posts)) {
		wp_send_json_error();
	}
	
	$postsToPublish = array();
	foreach ($posts as $post) {
		if ($post->post_status != 'publish') {
			$postsToPublish[$post->post_title] = $post->post_content;
		}
	}
	
	// Compile first to catch any errors
	include_once(dirname(__FILE__).'/include/compile.php');
	$result = pp_custom_css_js_dev_compile($_POST['mode'], true, !empty($_POST['minify']), $postsToPublish);
	if ($result !== true) {
		wp_send_json_error($result);
	}
	
	foreach ($posts as $post) {
		// Unpublish previous revisions of this file
		$revisions = get_posts(array(
			'post_type' => 'hm_custom_'.$_POST['mode'],
			'post_status' => 'publish',
			'title' => $post->post_title,
			'fields' => 'ids',
			'nopaging' => true
		));
		foreach ($revisions as $revisionId) {
			if (!wp_update_post(array(
				'ID' => $revisionId,
				'post_status' => 'draft',
			)))
			wp_send_json_error();
		}
		
		// Publish latest revision
		if (!wp_update_post(array(
				'ID' => $post->ID,
				'post_status' => 'publish',
				'post_date' => current_time('Y-m-d H:i:s'),
			)))
			wp_send_json_error();
	}
	
	// Update minify setting
	update_option('hm_custom_'.$_POST['mode'].'_minify', !empty($_POST['minify']));
	
	update_option('hm_custom_'.$_POST['mode'].'_ver', time());
	
	// Send revisions
	include(dirname(__FILE__).'/include/get_revisions.php');
	$result = array();
	foreach ($postsToPublish as $name => $contents) {
		$result[$name] = pp_custom_css_js_dev_get_revisions($_POST['mode'], $name);
	}
	wp_send_json_success($result);
}
?>