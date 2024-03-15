<?php
function pp_custom_css_js_dev_upload() {
	if (!isset($_POST['mode']) || empty($_FILES['upload_file']) || !empty($_FILES['upload_file']['error']) || !is_uploaded_file($_FILES['upload_file']['tmp_name']))
		die();
	$_POST['mode'] = strtolower($_POST['mode']);
	if ($_POST['mode'] != 'css' && $_POST['mode'] != 'javascript')
		die();
	
	$files = get_option('hm_custom_'.$_POST['mode'].'_files', array());
	$allowedExtensions = ($_POST['mode'] == 'javascript' ? array('js') : array('css', 'scss'));
	
	$uploadedFileNameDotPos = strrpos($_FILES['upload_file']['name'], '.');
	if ($uploadedFileNameDotPos === false)
		die();
	$uploadedFileNameExt = strtolower(substr($_FILES['upload_file']['name'], $uploadedFileNameDotPos + 1));
	if ($uploadedFileNameExt == 'zip') {
		$isZip = true;
		
		$wpTempDir = get_temp_dir().'/';
		$i = 0;
		do {
			$tempDir = $wpTempDir.md5(microtime()).'/';
		} while (($tempDirResult = @mkdir($tempDir)) === false && ++$i < 100);
		if (!$tempDirResult)
			die();
		
		require_once('includes/class-pclzip.php');
		$zip = new PclZip($_FILES['upload_file']['tmp_name']);
		$extractedFiles = $zip->extract($tempDir);
		
		if (empty($extractedFiles) || $extractedFiles < 0)
			die();
		
		$realTempPath = realpath($tempDir).DIRECTORY_SEPARATOR;
		$realTempPathLen = strlen($realTempPath);
	} else if (in_array($uploadedFileNameExt, $allowedExtensions)) {
		$isZip = false;
		$extractedFiles = array(array('filename' => $_FILES['upload_file']['tmp_name']));
	} else {
		// Invalid file name extension
		die();
	}
	
	foreach ($extractedFiles as $file) {
		// Security check
		if (isset($realTempPath) && substr(realpath($file['filename']), 0, $realTempPathLen) != $realTempPath)
			continue;
		
		$fileName = basename($isZip ? $file['filename'] : $_FILES['upload_file']['name']);
		
		// Check for existing file
		$foundExistingFile = false;
		foreach ($files as $existingFile) {
			if (!strcasecmp($fileName, $existingFile)) {
				$fileName = $existingFile;
				$foundExistingFile = true;
				break;
			}
		}
		
		if (!$foundExistingFile) {
			$dotPos = strrpos($fileName, '.');
			if (empty($dotPos))
				continue;
			$ext = strtolower(substr($fileName, $dotPos + 1));
			if (!in_array($ext, $allowedExtensions))
				continue;
			$fileName = pp_custom_css_js_dev_sanitize_filename(substr($fileName, 0, $dotPos).'.'.$ext);
			$files[] = $fileName;
		}
		
		$rev_id = wp_insert_post(array(
			'post_title' => $fileName,
			'post_status' => 'draft',
			'post_type' => 'hm_custom_'.$_POST['mode'],
			'post_content' => file_get_contents($file['filename'])
		));
		if (!empty($rev_id)) {
			if ($foundExistingFile) {
				// Delete previous latest revision
				$oldLatest = get_posts(array(
					'post_status' => 'any',
					'post_type' => 'hm_custom_'.$_POST['mode'],
					'title' => $fileName,
					'meta_key' => 'ppccjd_latest',
					'fields' => 'ids'
				));
				/*if ($oldLatest === false) {
					wp_send_json_error();
				}*/
				if (!empty($oldLatest)) {
					foreach ($oldLatest as $post) {
						if ($post != $rev_id) {
							delete_post_meta($post, 'ppccjd_latest')/* or wp_send_json_error()*/;
						}
					}
				}
			}
			update_post_meta($rev_id, 'ppccjd_latest', 1)/* or wp_send_json_error()*/;
		}
		
		if (!isset($firstFileName))
			$firstFileName = $fileName;
		
	}
	
	update_option('hm_custom_'.$_POST['mode'].'_files', $files);
	
	if (isset($tempDir) && WP_Filesystem()) {
		global $wp_filesystem;
		$wp_filesystem->rmdir($tempDir);
	}
	unlink($_FILES['upload_file']['tmp_name']);
	
	echo('<html><body><script type="text/javascript">window.parent.location.href = window.parent.location.pathname + window.parent.location.search'.(empty($firstFileName) ? '' : ' + \'#'.$firstFileName.'\'').'; window.parent.location.reload();</script></body></html>');
	
	die();
	
}
?>