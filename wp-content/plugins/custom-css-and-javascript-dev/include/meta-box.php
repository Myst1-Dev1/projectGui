<?php
global $post;
?>
<h3 id="pp_custom_css_js_dev_tabs" class="nav-tab-wrapper">
	<a href="javascript:void(0);" class="nav-tab">CSS</a>
	<a href="javascript:void(0);" class="nav-tab">Javascript</a>
</h3>
<textarea id="ppccjd_post_css"></textarea>
<textarea id="ppccjd_post_javascript"></textarea>
<script>
jQuery(document).ready(function($) {
	var ppccjd_post_editor;
	$('#pp_custom_css_js_dev_tabs').on('click', ':not(.nav-tab-active)', function() {
		var $this = $(this);
		$this.siblings('.nav-tab-active').removeClass('nav-tab-active');
		$this.addClass('nav-tab-active');
		var mode = $this.html().toLowerCase();
		if (ppccjd_post_editor) {
			ppccjd_post_editor.toTextArea();
		}
		ppccjd_post_editor = CodeMirror.fromTextArea(document.getElementById('ppccjd_post_' + mode), {
			lineNumbers: true,
			mode: mode,
			matchBrackets: true
		});
	});
	$('#pp_custom_css_js_dev_tabs a:first').click();
});
</script>