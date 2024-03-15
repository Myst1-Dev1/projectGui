<?php
function pp_custom_css_js_dev_compile($mode, $published=false, $minify=false, $overrideContent = array()) {
	$files = get_option('hm_custom_'.$mode.'_files', array());
	$postsParams = array(
		'post_type' => 'hm_custom_'.$mode,
		'post_status' => ($published === true ? 'publish' : 'any'),
		'nopaging' => true,
		'orderby' => 'none',
	);
	if ($published !== true) {
		$postsParams['meta_key'] = 'ppccjd_latest';
	}
	$posts = get_posts($postsParams);
	if ($published === 'tinymce') {
		$props = get_option('hm_custom_'.$_POST['mode'].'_props', array());
	}
	if ($published === 'tinymce') {
		foreach ($overrideContent as $file => $content) {
			if (empty($props[$file]['tinymce'])) {
				unset($overrideContent[$file]);
			}
		}
	}
	$fileContents = $overrideContent;
	$hasSCSS = false;
	$compiled = '';
	foreach($posts as $post) {
		if (($published !== 'tinymce' || !empty($props[$post->post_title]['tinymce'])) && !isset($fileContents[$post->post_title]))
			$fileContents[$post->post_title] = $post->post_content;
	}
	foreach ($files as $file) {
		if (!empty($fileContents[$file])) {
			$compiled .= $fileContents[$file]."\n";
		}
		$hasSCSS = $hasSCSS || ($mode == 'css' && $file[strlen($file) - 4] == 's');
	}
	if ($hasSCSS && !empty($compiled)) {
		if (!class_exists('ScssPhp\ScssPhp\Compiler')) {
			include_once(dirname(__FILE__).'/../../lib/scssphp/scss.inc.php');
		}
		$scss = new ScssPhp\ScssPhp\Compiler();
		try {
			$compiled = $scss->compile($compiled);
		} catch (Exception $ex) {
			return $ex->getMessage();
		}
		if (empty($compiled) && $published !== 'tinymce') {
			return false;
		}
	}
	
	$uploadDir = wp_upload_dir();
	if (!is_dir($uploadDir['basedir'].'/pp-css-js-dev'))
		mkdir($uploadDir['basedir'].'/pp-css-js-dev');
	if ($published === 'tinymce') {
		$outputFilename = 'custom.tinymce';
	} else if ($published == true) {
		$outputFilename = 'custom';
	} else {
		$outputFilename = 'custom.draft';
	}
	$outputFile = $uploadDir['basedir'].'/pp-css-js-dev/'.$outputFilename.($mode == 'css' ? '.css' : '.js');
	if ($published === 'tinymce' && empty($compiled)) {
		@unlink($outputFile);
	} else if (file_put_contents($outputFile, $compiled) === false) {
		return false;
	}
	
	// Temporary - cleanup from bug in v1.0.4
	@unlink($uploadDir['basedir'].'/pp-css-js-dev/custom.tinymce.js');
	
	// TODO: minify error handling
	if ($minify) {
		require_once(dirname(__FILE__).'/../../lib/minify/src/Minify.php');
		require_once(dirname(__FILE__).'/../../lib/minify/src/Exception.php');
		if ($_POST['mode'] == 'css') {
			require_once(dirname(__FILE__).'/../../lib/minify/src/CSS.php');
			require_once(dirname(__FILE__).'/../../lib/minify/src/Converter.php');
			$minifier = new MatthiasMullie\Minify\CSS;
		} else {
			require_once(dirname(__FILE__).'/../../lib/minify/src/JS.php');
			$minifier = new MatthiasMullie\Minify\JS;
		}
		$minifier->add($outputFile);
		$minifier->minify($outputFile);
	}
	
	return true;
}
?>