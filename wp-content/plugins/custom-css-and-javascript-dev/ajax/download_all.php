<?php
function pp_custom_css_js_dev_download_all() {
	if (!isset($_GET['mode']))
		die();
	$_GET['mode'] = strtolower($_GET['mode']);
	if ($_GET['mode'] != 'css' && $_GET['mode'] != 'javascript')
		die();
	
	$files = get_option('hm_custom_'.$_GET['mode'].'_files', array());
	$posts = get_posts(array(
		'post_type' => 'hm_custom_'.$_GET['mode'],
		'post_status' => 'any',
		'nopaging' => true,
		'orderby' => 'none',
		'meta_key' => 'ppccjd_latest'
	));
	if (empty($posts))
		die();
	
	$wpTempDir = get_temp_dir().'/';
	$i = 0;
	do {
		$tempDir = $wpTempDir.md5(microtime()).'/';
	} while (($tempDirResult = @mkdir($tempDir)) === false && ++$i < 100);
	if (!$tempDirResult)
		die();
	
	require_once('includes/class-pclzip.php');
	$zip = new PclZip($tempDir.'download.zip');
	
	$savedFiles = array();
	foreach ($posts as $post) {
		if (($i = array_search($post->post_title, $files)) !== false) {
			$tempFileName = $tempDir.$post->post_title;
			if (file_put_contents($tempFileName, $post->post_content) !== false) {
				$savedFiles[] = $tempFileName;
				unset($files[$i]);
			} else {
				die();
			}
		}
	}
	
	foreach ($files as $emptyFile) {
		$tempFileName = $tempDir.$emptyFile;
		if (!file_put_contents($tempFileName, '') !== false) {
			$savedFiles[] = $tempFileName;
		} else {
			die();
		}
	}
	
	if (empty($savedFiles))
		die();
	
	$result = $zip->create($savedFiles, PCLZIP_OPT_REMOVE_ALL_PATH);
	if (empty($result) || $result < 0)
		die();
	
	foreach ($savedFiles as $file) {
		unlink($file);
	}
	
	header('Content-Type: application/zip');
	header('Content-Disposition: attachment; filename="Custom '.($_GET['mode'] == 'javascript' ? 'Javascript' : 'CSS').'.zip"');
	
	readfile($tempDir.'/download.zip');
	unlink($tempDir.'/download.zip');
	unlink($tempDir);
	
	die();
}
?>