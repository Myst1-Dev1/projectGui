<?php
function pp_custom_css_js_dev_load() {
	global $wpdb;
	
	if (empty($_POST['mode']))
		wp_send_json_error();
	$_POST['mode'] = strtolower($_POST['mode']);
	if ($_POST['mode'] != 'css' && $_POST['mode'] != 'javascript')
		wp_send_json_error();
	
	$files = get_option('hm_custom_'.$_POST['mode'].'_files', array());
	if (!is_array($files))
		$files = array();
	
	// Migrate data from free plugin
	if (empty($files)) {
		$migratedFile = 'main.'.($_POST['mode'] == 'javascript' ? 'js' : 'css');
		$result = $wpdb->update($wpdb->posts, array('post_title' => $migratedFile), array('post_type' => 'hm_custom_'.$_POST['mode'], 'post_title' => ''));
		if (!empty($result)) {
			$files = array($migratedFile);
			update_option('hm_custom_'.$_POST['mode'].'_files', $files);
			
			// Set ppccjd_latest on most recent revision
			$posts = get_posts(array(
				'post_type' => 'hm_custom_'.$_POST['mode'],
				'post_status' => 'any',
				'posts_per_page' => 1,
				'fields' => 'ids'
			));
			if (!empty($posts[0])) {
				update_post_meta($posts[0], 'ppccjd_latest', 1);
			}
		}
	}
	
	// Automatically recover files missing from $files array
	$missingFiles = $wpdb->get_col('SELECT DISTINCT post_title FROM '.$wpdb->posts.' WHERE post_type="hm_custom_'.$_POST['mode'].'"'.(empty($files) ? '' : ' AND post_title NOT IN ("'.implode('","', $files).'")'));
	if ($missingFiles === false) {
		wp_send_json_error();
	}
	if (!empty($missingFiles)) {
		$files = array_merge($files, $missingFiles);
		update_option('hm_custom_'.$_POST['mode'].'_files', $files);
	}
	
	$returnData = array();
	$returnFiles = array();
	$props = get_option('hm_custom_'.$_POST['mode'].'_props', array());
	foreach ($files as $file) {
		$returnData[$file] = array('content' => '', 'revisions' => array(), 'props' => isset($props[$file]) ? $props[$file] : array());
		$returnFiles[] = $file;
	}
	
	$posts = get_posts(array(
		'post_type' => 'hm_custom_'.$_POST['mode'],
		'post_status' => 'any',
		'nopaging' => true,
		'orderby' => 'none',
		'meta_key' => 'ppccjd_latest'
	));
	foreach($posts as $post) {
		if (isset($returnData[$post->post_title])) {
			$returnData[$post->post_title]['content'] = $post->post_content;
		}
	}
	
	$revisions = $wpdb->get_results('SELECT ID, post_title, post_date, post_status
										FROM '.$wpdb->posts.'
										WHERE post_type=\'hm_custom_'.$_POST['mode'].'\' ORDER BY post_date DESC');
	
	if ($revisions === false) {
		wp_send_json_error();
	}
	
	foreach ($revisions as $revision) {
		if (isset($returnData[$revision->post_title])) {
			$returnData[$revision->post_title]['revisions'][] = array(
				'id' => $revision->ID,
				'date' => $revision->post_date,
				'published' => ($revision->post_status == 'publish')
			);
		}
	}
	
	
	wp_send_json_success(array('files' => $returnFiles, 'data' => $returnData));
}
?>