/**
 * Author:      Aspen Grove Studios
 * License:     GNU General Public License version 2 or later
 * License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html
 */

var pp_custom_css_js_dev_liveview_css = { active: false, loadedSassJs: false, sass: null, files: [], data: [] };
jQuery(document).ready(function($) {
	pp_custom_css_js_dev_liveview_init();
	
	$(window).on('storage', function(ev) {
		if (ev.originalEvent.key == 'pp_custom_css_liveview_files') {
			if (ev.originalEvent.newValue === null) {
				location.reload();
				return;
			} else if (ev.originalEvent.oldValue === null) {
				pp_custom_css_js_dev_liveview_init();
				$('<div style="position: fixed; top: 0; left: 0; width: 100%; margin-top: 50px; z-index: 999999;"><div style="width: 300px; font-family: sans-serif; font-weight: bold; font-size: 24px; text-align: center; color: #fff; background-color: #339933; padding: 50px 0; margin: 0 auto; white-space: no-wrap;">CSS Live View On</div></div>')
					.hide().appendTo('body').fadeIn(500).delay(1000).fadeOut(1000, function() {$(this).remove();});
			} else {
				pp_custom_css_js_dev_liveview_css.files = ev.originalEvent.newValue.split(',');
				pp_custom_css_js_dev_liveview_update();
			}
		} else if (pp_custom_css_js_dev_liveview_css.active && ev.originalEvent.key.length > 14 && ev.originalEvent.key.substr(0, 14) == 'pp_custom_css-') {
			pp_custom_css_js_dev_liveview_css.data[ev.originalEvent.key.substr(14)] = ev.originalEvent.newValue;
			pp_custom_css_js_dev_liveview_update();
		}
	});
	
	function pp_custom_css_js_dev_liveview_init() {
		var files = window.localStorage.getItem('pp_custom_css_liveview_files');
		if (files == null) {
			return;
		}
		if (!pp_custom_css_js_dev_liveview_css.loadedSassJs) {
			$.getScript(pp_custom_css_js_dev_sassjs_url, function() {
				pp_custom_css_js_dev_liveview_css.loadedSassJs = true;
				Sass.setWorkerUrl(pp_custom_css_js_dev_sassjs_url.substring(0, pp_custom_css_js_dev_sassjs_url.lastIndexOf('/')) + '/sass.worker.js');
				pp_custom_css_js_dev_liveview_css.sass = new Sass();
				pp_custom_css_js_dev_liveview_init();
			});
			return;
		}
		
		$('<style id="pp_custom_css_js_dev_liveview_css" type="text/css">').appendTo('head');
		pp_custom_css_js_dev_liveview_css.files = files.split(',');
		for (var i = 0; i < pp_custom_css_js_dev_liveview_css.files.length; ++i) {
			var fileContent = window.localStorage.getItem('pp_custom_css-' + pp_custom_css_js_dev_liveview_css.files[i]);
			pp_custom_css_js_dev_liveview_css.data[pp_custom_css_js_dev_liveview_css.files[i]] = (fileContent === null ? '' : fileContent);
		}
		pp_custom_css_js_dev_liveview_css.active = true;
		pp_custom_css_js_dev_liveview_update();
		$('#pp_custom_css_dev_css-css').remove();
	}
	
	function pp_custom_css_js_dev_liveview_update() {
		console.log('Updating liveview');
		var css = '';
		for (var i = 0; i < pp_custom_css_js_dev_liveview_css.files.length; ++i) {
			css += pp_custom_css_js_dev_liveview_css.data[pp_custom_css_js_dev_liveview_css.files[i]] + '\n';
		}
		pp_custom_css_js_dev_liveview_css.sass.compile(css, function(result) {
			if (!result.status) {
				console.log(result.text);
				$('#pp_custom_css_js_dev_liveview_css').text(result.text);
			}
		});
	}
});