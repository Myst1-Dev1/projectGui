<?php
function pp_custom_css_js_dev_get_revisions($mode, $file) {
	$posts = get_posts(array(
		'post_type' => 'hm_custom_'.$mode,
		'post_status' => 'any',
		'nopaging' => true,
		'title' => $file
	));
	if (!is_array($posts)) {
		wp_send_json_error();
	}
	$revisions = array();
	foreach ($posts as $post) {
		$revisions[] = array('id' => $post->ID, 'date' => $post->post_date, 'published' => ($post->post_status == 'publish'));
	}
	
	return $revisions;
}
?>