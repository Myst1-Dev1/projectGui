/**
 * Author:      Aspen Grove Studios
 * License:     GNU General Public License version 2 or later
 * License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html
 */

var pp_custom_code_editor_dev, pp_custom_css_js_dev_data = null, pp_custom_css_js_dev_files = [], pp_custom_css_js_dev_file = '', pp_custom_css_js_dev_rev = 0, pp_custom_css_js_dev_published_rev = 0, pp_custom_css_js_dev_liveview = false, pp_custom_code_editor_dev_localstorage_dirty = false;
window.localStorage.removeItem('pp_custom_css_liveview_files');
jQuery(document).ready(function($) {
	$.post(pp_custom_css_js_dev_config.api_url, {action: "pp_custom_css_js_dev_load", mode: pp_custom_css_js_dev_mode})
		.done(function(data) {
			if (data.success) {
				pp_custom_css_js_dev_data = data.data.data;
				pp_custom_css_js_dev_files = data.data.files;
				for (var i = 0; i < data.data.files.length; ++i) {
					$('<a class="nav-tab nav-tab-file">')
						.attr('href', '#' + data.data.files[i])
						.text(data.data.files[i])
						.appendTo($('#pp_custom_css_js_dev_page .nav-tab-wrapper'));
					
					// Initialize localStorage
					window.localStorage.setItem('pp_custom_' + pp_custom_css_js_dev_mode.toLowerCase() + '-' + data.data.files[i], data.data.data[data.data.files[i]].content);
				}
				
				$('#pp_custom_css_js_dev_page').show();
				$(window).resize();
				$('#pp_custom_css_js_dev_loader').fadeOut();
				
				if ($('.nav-tab-file').length) {
					if (location.hash.length > 1) {
						$(window).trigger('hashchange');
					} else {
						$('.nav-tab-file')[0].click();
					}
				} else {
					// If there are no files, create a new file
					$('.nav-tab-new-file:first').click();
				}
				
				setInterval(pp_custom_css_js_dev_update_localstorage, 2000);
			} else {
				alert('An error occurred while loading data. Please try again or contact support if this problem persists.');
			}
		})
		.fail(function() {
			alert('An error occurred while loading data. Please try again or contact support if this problem persists.');
		});

	$('#pp_custom_css_js_dev_page .nav-tab-wrapper').sortable({
		axis: 'x',
		containment: 'parent',
		items: '> .nav-tab-file'
	}).on('sortupdate', function() {
		var order = [];
		$('#pp_custom_css_js_dev_page .nav-tab-file').each(function() { order.push($(this).html()); });
		pp_custom_css_js_dev_files = order;
		if (pp_custom_css_js_dev_liveview == true)
			pp_custom_css_js_dev_update_liveview_files();
		
		$.post(pp_custom_css_js_dev_config.api_url, {action: "pp_custom_css_js_dev_sort", mode: pp_custom_css_js_dev_mode, 'order': order})
			.fail(function() {
				alert("Error while saving the file order. Please try again.");
			});
	});

	pp_custom_code_editor_dev = CodeMirror(document.getElementById("pp_custom_code_editor_dev"), {
		lineNumbers: true,
		mode: pp_custom_css_js_dev_mode.toLowerCase(),
		matchBrackets: true
	});
	$("#pp-custom-css-js-dev-save-btn").click(function() {
		$(this).prop("disabled", true).html("Saving...");
		$("#pp-custom-css-js-dev-publish-btn").prop("disabled", true);
		pp_custom_css_js_dev_save(pp_custom_css_js_dev_file, pp_custom_css_js_dev_update_buttons, pp_custom_css_js_dev_update_buttons);
	});
	$("#pp-custom-css-js-dev-publish-btn").click(function() {
		$(this).prop("disabled", true).html("Publishing...");
		if (pp_custom_css_js_dev_data[pp_custom_css_js_dev_file].unsaved) {
			$("#pp-custom-css-js-dev-save-btn").prop("disabled", true).html("Saving...");
			pp_custom_css_js_dev_save(pp_custom_css_js_dev_file, function() {
				pp_custom_css_js_dev_publish(pp_custom_css_js_dev_file, pp_custom_css_js_dev_update_buttons, pp_custom_css_js_dev_update_buttons);
			}, pp_custom_css_js_dev_update_buttons);
		} else {
			pp_custom_css_js_dev_publish(pp_custom_css_js_dev_file, pp_custom_css_js_dev_update_buttons, pp_custom_css_js_dev_update_buttons);
		}
	});
	$("#pp-custom-css-js-dev-save-all-btn").click(function() {
		$(this).prop("disabled", true).html("Saving...");
		$("#pp-custom-css-js-dev-save-btn, #pp-custom-css-js-dev-publish-btn, #pp-custom-css-js-dev-publish-all-btn").prop("disabled", true);
		pp_custom_css_js_dev_save_multiple(pp_custom_css_js_dev_files.slice(), pp_custom_css_js_dev_update_buttons, pp_custom_css_js_dev_update_buttons);
	});
	
	$("#pp-custom-css-js-dev-publish-all-btn").click(function() {
		$(this).prop("disabled", true).html("Publishing...");
		console.log($('#pp-custom-css-js-dev-save-all-btn:enabled').length)
		if ($('#pp-custom-css-js-dev-save-all-btn:enabled').length) {
			// We need to save first
			$('#pp-custom-css-js-dev-save-all-btn').html("Saving...").prop('disabled', true);
			$("#pp-custom-css-js-dev-save-btn, #pp-custom-css-js-dev-publish-btn").prop("disabled", true);
			pp_custom_css_js_dev_save_multiple(pp_custom_css_js_dev_files.slice(), function() {
				pp_custom_css_js_dev_publish(null, pp_custom_css_js_dev_update_buttons, pp_custom_css_js_dev_update_buttons);
			}, pp_custom_css_js_dev_update_buttons);
			return;
		}
		$("#pp-custom-css-js-dev-save-btn, #pp-custom-css-js-dev-publish-btn, #pp-custom-css-js-dev-save-all-btn").prop("disabled", true);
		pp_custom_css_js_dev_publish(null, pp_custom_css_js_dev_update_buttons, pp_custom_css_js_dev_update_buttons);
	});
	$("#pp-custom-css-js-dev-delete-revisions-btn").click(function() {
		$(this).prop('disabled', true).html('Deleting...');
		
		$.post(pp_custom_css_js_dev_config.api_url, {action: "pp_custom_css_js_dev_delete_revisions", mode: pp_custom_css_js_dev_mode, file: pp_custom_css_js_dev_file})
			.done(function(data) {
				if (data.success) {
					pp_custom_css_js_dev_data[data.data.file].revisions = data.data.revisions;
					pp_custom_css_js_dev_update_revisions();
					$("#pp-custom-css-js-dev-delete-revisions-btn").html('Delete Draft Revisions').prop('disabled', false);	
				} else {
					alert("Error while deleting. Please try again.");
					$("#pp-custom-css-js-dev-delete-revisions-btn").html('Delete Draft Revisions').prop('disabled', false);
				}
			})
			.fail(function() {
				alert("Error while deleting. Please try again.");
				$("#pp-custom-css-js-dev-delete-revisions-btn").html('Delete Draft Revisions').prop('disabled', false);
			});
	});
	
	
	$(window).resize(function() {
		$("#pp_custom_code_editor_dev, #pp_custom_code_editor_dev .CodeMirror").height(Math.max(150,
														$(window).height()
														- $("#pp_custom_code_editor_dev").offset().top
														- $("#pp-custom-css-js-dev-save-btn").height()
														- 50));
		pp_custom_code_editor_dev.refresh();
	});
	$(window).on("beforeunload", function(ev) {
		for (file in pp_custom_css_js_dev_data) {
			if (pp_custom_css_js_dev_data[file].unsaved) {
				ev.returnValue = "You have unsaved changes that will be lost if you leave this page!";
				return ev.returnValue;
			}
		}
	});
	
	$("#pp_custom_css_js_dev_revisions").on("click", "li > a.view-rev", function(ev) {
		
		if (pp_custom_css_js_dev_data[pp_custom_css_js_dev_file].unsaved &&
				!confirm("You have unsaved changes that will be lost if you view this revision!"))
			return;
		
		pp_custom_code_editor_dev.doc.setValue('');
		var revId = $(this).parent().attr("id").substring(24);
		
		$.post(pp_custom_css_js_dev_config.api_url, {action: "pp_custom_css_js_dev_get_revision", mode: pp_custom_css_js_dev_mode, rev: revId})
			.done(function(data) {
				if (data.success) {
					pp_custom_code_editor_dev.doc.setValue(data.data.content);
					pp_custom_css_js_dev_update_buttons();
					//pp_custom_css_js_dev_editor_change();
					pp_custom_css_js_dev_rev = revId;
					$('#pp-custom-css-js-dev-download-btn').prop('disabled', false);
				} else {
					alert("Error while loading. Please try again.");
				}
			})
			.fail(function() {
				alert("Error while loading. Please try again.");
			});
	});
	
	$("#pp_custom_css_js_dev_revisions").on("click", "li > a.del-rev", function(ev) {
		
		var revId = $(this).parent().attr("id").substring(24);
		
		$.post(pp_custom_css_js_dev_config.api_url, {action: "pp_custom_css_js_dev_delete_revision", mode: pp_custom_css_js_dev_mode, rev: revId})
			.done(function(data) {
				if (data.success) {
					pp_custom_css_js_dev_data[data.data.file].revisions = data.data.revisions;
					pp_custom_css_js_dev_update_revisions();
				} else {
					alert("Error while deleting. Please try again.");
				}
			})
			.fail(function() {
				alert("Error while deleting. Please try again.");
			});
	});
	
	$(window).on('hashchange', function() {
		pp_custom_css_js_dev_update_localstorage();
	
		var fileName = location.hash.substr(1);
		if (!/[a-z0-9_\-\.]+/i.test(fileName))
			return;
		pp_custom_css_js_dev_published_rev = 0;
		pp_custom_css_js_dev_file = fileName;
		$('#pp_custom_css_js_dev_page .nav-tab').removeClass('nav-tab-active').filter('[href="' + location.hash + '"]').addClass('nav-tab-active');
		
		var fileDotPos = pp_custom_css_js_dev_file.lastIndexOf('.');
		$('#pp-custom-css-js-dev-rename-field').val(pp_custom_css_js_dev_file.substr(0, fileDotPos));
		$('#pp-custom-css-js-dev-rename-ext').html(pp_custom_css_js_dev_file.substr(fileDotPos));
		
		pp_custom_css_js_dev_update_revisions();
		
		pp_custom_code_editor_dev.off('change', pp_custom_css_js_dev_editor_change);
		pp_custom_code_editor_dev.doc.setValue(pp_custom_css_js_dev_data[pp_custom_css_js_dev_file].content);
		pp_custom_code_editor_dev.on('change', pp_custom_css_js_dev_editor_change);
		
		$('#pp-custom-css-js-dev-tinymce-cb').prop('checked', (pp_custom_css_js_dev_data[pp_custom_css_js_dev_file].props.tinymce == true));
		
		pp_custom_css_js_dev_update_buttons();
	});
	
	$('#pp_custom_css_js_dev_page .nav-tab-new-file').click(function() {
		$.post(pp_custom_css_js_dev_config.api_url, {action: "pp_custom_css_js_dev_create_file", type: $(this).find('.new-file-type').html().toLowerCase()})
			.done(function(data) {
				if (data.success) {
					var newFileTab = $('#pp_custom_css_js_dev_page .nav-tab-new-file:first');
					newFileTab.clone().html(data.data).removeClass('nav-tab-new-file').addClass('nav-tab-file')
								.attr('href', '#' + data.data).appendTo(newFileTab.parent());
					pp_custom_css_js_dev_data[data.data] = { content: '', revisions: [], props: [] };
					window.localStorage.setItem('pp_custom_' + pp_custom_css_js_dev_mode.toLowerCase() + '-' + data.data, '');
					$('#pp_custom_css_js_dev_page .nav-tab-wrapper').trigger('sortupdate');
					location.hash = data.data;
				} else {
					alert("Error while creating a new file. Please try again.");
				}
			})
			.fail(function() {
				alert("Error while creating a new file. Please try again.");
			});
	});
	
	$('#pp-custom-css-js-dev-delete-btn').click(function() {
		if (typeof 'pp_custom_css_js_dev_file' == 'undefined' || !confirm('Are you sure that you want to delete "' + pp_custom_css_js_dev_file + '"?'))
			return;
		$.post(pp_custom_css_js_dev_config.api_url, {action: "pp_custom_css_js_dev_delete_file", mode: pp_custom_css_js_dev_mode, file: pp_custom_css_js_dev_file})
			.done(function(data) {
				if (data.success) {
					var deletedFile = pp_custom_css_js_dev_file;
					var fileTab = $('#pp_custom_css_js_dev_page .nav-tab[href="#' + deletedFile + '"]');
					if (fileTab.length) {
						if (fileTab.next('.nav-tab-file').length) {
							location.hash = fileTab.next('.nav-tab-file').attr('href').substr(1);
						} else if (fileTab.prev('.nav-tab-file').length) {
							location.hash = fileTab.prev('.nav-tab-file').attr('href').substr(1);
						} else {
							$('#pp_custom_css_js_dev_page .nav-tab-new-file:first').click();
							var createdNewFile = true;
						}
						fileTab.remove();
						delete pp_custom_css_js_dev_data[deletedFile];
						window.localStorage.removeItem('pp_custom_' + pp_custom_css_js_dev_mode.toLowerCase() + '-' + deletedFile);
						if (!createdNewFile) {
							$('#pp_custom_css_js_dev_page .nav-tab-wrapper').trigger('sortupdate');
						}
					}
				} else {
					alert("Error while deleting the file. Please try again.");
				}
			})
			.fail(function() {
				alert("Error while deleting the file. Please try again.");
			});
	});
	
	$('#pp-custom-css-js-dev-download-btn').click(function() {
		$('#pp-custom-css-js-dev-iframe').attr('src', pp_custom_css_js_dev_config.api_url + '&action=pp_custom_css_js_dev_download_file&rev=' + pp_custom_css_js_dev_rev);
	});
	
	$('#pp-custom-css-js-dev-download-all-btn').click(function() {
		$('#pp-custom-css-js-dev-iframe').attr('src', pp_custom_css_js_dev_config.api_url + '&action=pp_custom_css_js_dev_download_all&mode=' + pp_custom_css_js_dev_mode);
	});
	
	$('#pp-custom-css-js-dev-upload-btn').click(function() {
		$(this).parent().attr('action', pp_custom_css_js_dev_config.api_url);
		for (file in pp_custom_css_js_dev_data) {
			if (pp_custom_css_js_dev_data[file].unsaved) {
				return confirm('You have unsaved changes that will be lost if you upload a file. Please click Cancel and save your changes before uploading.');
			}
		}
		return true;
	});
	
	$('#pp-custom-css-js-dev-liveview-btn').click(function() {
		
		if (pp_custom_css_js_dev_liveview) {
			pp_custom_css_js_dev_liveview = false;
			window.localStorage.removeItem('pp_custom_css_liveview_files');
			
			$(this).html('Start Live View');
		} else {
			// Test for live view support
			if (!$('#pp-custom-css-liveview-test-style').length) {
				$('head').append('<style id="pp-custom-css-liveview-test-style" type="text/css">#pp-custom-css-js-dev-liveview-btn{position:relative;}</style>');
				if ($('#pp-custom-css-js-dev-liveview-btn').css('position') != 'relative') {
					alert('Your browser does not seem to support CSS Live View. Please use the current version of Google Chrome or Mozilla Firefox instead.');
					$('#pp-custom-css-liveview-test-style').remove();
					return;
				}
			}
			pp_custom_css_js_dev_liveview = true;
			pp_custom_css_js_dev_update_liveview_files();
			$(this).html('Stop Live View');
			window.open(pp_custom_css_js_dev_siteurl);
		}
		
	});
	
	$('#pp-custom-css-js-dev-rename-btn').click(function() {
		var newName = $('#pp-custom-css-js-dev-rename-field').val();
		if (!/[a-z0-9_\-\.]+/i.test(newName)) {
			alert('The filename you entered contains invalid characters or is empty. Your filename may only contain the following characters: a-z 0-9 _ - .');
			return;
		}
		
		var tabs = $('.nav-tab-file');
		var testName = newName.toLowerCase() + $('#pp-custom-css-js-dev-rename-ext').html();
		for (var i = 0; i < tabs.length; ++i) {
			if ($(tabs[i]).html().toLowerCase() == testName) {
				alert('The filename you entered already exists.');
				return;
			}
		}
		$.post(pp_custom_css_js_dev_config.api_url, {action: "pp_custom_css_js_dev_rename", file: pp_custom_css_js_dev_file, mode: pp_custom_css_js_dev_mode, new_name: $('#pp-custom-css-js-dev-rename-field').val()})
			.done(function(data) {
				if (data.success) {
					$('.nav-tab-file[href="#' + pp_custom_css_js_dev_file + '"]').attr('href', '#' + data.data).html(data.data);
					$('#pp-custom-css-js-dev-rename-field').val(data.data);
					pp_custom_css_js_dev_data[data.data] = pp_custom_css_js_dev_data[pp_custom_css_js_dev_file];
					window.localStorage.setItem('pp_custom_' + pp_custom_css_js_dev_mode.toLowerCase() + '-' + data.data, pp_custom_code_editor_dev.getValue());
					var oldFile = pp_custom_css_js_dev_file;
					location.hash = data.data;
					delete pp_custom_css_js_dev_data[oldFile];
					if (pp_custom_css_js_dev_liveview)
						pp_custom_css_js_dev_update_liveview_files();
					window.localStorage.removeItem('pp_custom_' + pp_custom_css_js_dev_mode.toLowerCase() + '-' + oldFile);
				} else {
					alert('An error occurred while renaming the file. Please try again.');
				}
			})
			.fail(function() {
				alert('An error occurred while renaming the file. Please try again.');
			});
	});
	
	$('#pp-custom-css-js-dev-editor-expand-btn').click(function() {
		if ($('#pp_custom_css_js_dev_page').hasClass('editor-expanded')) {
			$('#pp_custom_css_js_dev_page').removeClass('editor-expanded').css('background-color', '');
			$('body').css('overflow', '');
			$(this).html('Expand Editor');
		} else {
			$('#pp_custom_css_js_dev_page').addClass('editor-expanded').css('background-color', $('body').css('background-color'));
			$('body').css('overflow', 'hidden');
			$(this).html('Contract Editor');
		}
		$(window).trigger('resize');
	});
	
	$('#pp-custom-css-js-dev-tinymce-cb').change(function() {
		if (typeof 'pp_custom_css_js_dev_file' == 'undefined')
			return;
		$.post(pp_custom_css_js_dev_config.api_url, {action: 'pp_custom_css_js_dev_set_props', mode: pp_custom_css_js_dev_mode, file: pp_custom_css_js_dev_file, props: {'tinymce': ($(this).is(':checked') ? 1 : 'null')}})
			.done(function(data) {
				if (!data.success) {
					alert("Error while setting file properties. Please try again.");
				}
			})
			.fail(function() {
				alert("Error while setting file properties. Please try again.");
			});
	});
	
	function pp_custom_css_js_dev_update_liveview_files() {
		window.localStorage.setItem('pp_custom_css_liveview_files', pp_custom_css_js_dev_files);
	}
	
	function pp_custom_css_js_dev_update_revisions() {
		$("#pp_custom_css_js_dev_revisions").empty();
		
		var revs = pp_custom_css_js_dev_data[pp_custom_css_js_dev_file] ? pp_custom_css_js_dev_data[pp_custom_css_js_dev_file].revisions : [];
		
		if (revs.length == 0) {
			$("#pp_custom_css_js_dev_revisions").append("<li>None</li>");
			pp_custom_css_js_dev_rev = 0;
			pp_custom_css_js_dev_published_rev = 0;
			$('#pp-custom-css-js-dev-download-btn').prop('disabled', true);
		} else {
			pp_custom_css_js_dev_rev = revs[0].id;
			$('#pp-custom-css-js-dev-download-btn').prop('disabled', false);
			for (var i = 0; i < revs.length; ++i) {
				$("#pp_custom_css_js_dev_revisions").append("<li id=\"pp_custom_css_js_dev_rev" + revs[i].id + "\"><a class=\"view-rev\" href=\"javascript:void(0);\">" + revs[i].date + "</a> " + (revs[i].published ? "[published]" : (i == 0 ? '[current]' : "<a class=\"del-rev\" href=\"javascript:void(0);\">[delete]</a>")) + "</li>");
				if (revs[i].published)
					pp_custom_css_js_dev_published_rev = revs[i].id;
			}
		}
	}
	
	function pp_custom_css_js_dev_editor_change(ev) {
		pp_custom_css_js_dev_data[pp_custom_css_js_dev_file].content = pp_custom_code_editor_dev.getValue();
		pp_custom_code_editor_dev_localstorage_dirty = true;
		if (!pp_custom_css_js_dev_data[pp_custom_css_js_dev_file].unsaved) {
			pp_custom_css_js_dev_data[pp_custom_css_js_dev_file].unsaved = true;
			$('.nav-tab-file[href="#' + pp_custom_css_js_dev_file + '"]').addClass('unsaved');
			pp_custom_css_js_dev_update_buttons();
		}
	}
	
	function pp_custom_css_js_dev_update_buttons() {
		var allSaved = true;
		if (pp_custom_css_js_dev_data[pp_custom_css_js_dev_file].unsaved) {
			$("#pp-custom-css-js-dev-save-btn").html("Save").prop("disabled", false);
			allSaved = false;
		} else {
			$("#pp-custom-css-js-dev-save-btn").html("Saved").prop("disabled", true);
		}
		if (allSaved) {
			for (file in pp_custom_css_js_dev_data) {
				if (pp_custom_css_js_dev_data[file].unsaved) {
					allSaved = false;
					break;
				}
			}
		}
		$("#pp-custom-css-js-dev-save-all-btn").html('Save All').prop("disabled", allSaved);
		
		var allPublished = true;
		if (!pp_custom_css_js_dev_data[pp_custom_css_js_dev_file].unsaved
				&& (!pp_custom_css_js_dev_data[pp_custom_css_js_dev_file].revisions.length
					|| pp_custom_css_js_dev_data[pp_custom_css_js_dev_file].revisions[0].published)) {
			$("#pp-custom-css-js-dev-publish-btn").html("Published").prop("disabled", true);
		} else {
			$("#pp-custom-css-js-dev-publish-btn").html("Publish").prop("disabled", false);
			allPublished = false;
		}
		
		if (allPublished) {
			for (file in pp_custom_css_js_dev_data) {
				if (pp_custom_css_js_dev_data[file].unsaved || (pp_custom_css_js_dev_data[file].revisions.length && !pp_custom_css_js_dev_data[file].revisions[0].published)) {
					allPublished = false;
					break;
				}
			}
		}
		$("#pp-custom-css-js-dev-publish-all-btn").html('Publish All').prop("disabled", allPublished);
	}
	
	function pp_custom_css_js_dev_save(theFile, successAction, failAction) {
		$.post(pp_custom_css_js_dev_config.api_url, {action: "pp_custom_css_js_dev_save", file: theFile, mode: pp_custom_css_js_dev_mode, code: pp_custom_css_js_dev_data[theFile].content})
			.done(function(data) {
				if (data.success) {
					pp_custom_css_js_dev_data[theFile].unsaved = false;
					$('.nav-tab-file[href="#' + theFile + '"]').removeClass('unsaved');
					pp_custom_css_js_dev_data[data.data.file].revisions = data.data.revisions;
					if (data.data.file == pp_custom_css_js_dev_file)
						pp_custom_css_js_dev_update_revisions();
					
					if (typeof successAction == 'function')
						successAction();
				} else {
					alert("Error while saving. Please try again." + (typeof data.data == 'undefined' ? '' : '\n\n' + data.data));
					
					if (typeof failAction == 'function')
						failAction();
				}
			})
			.fail(function() {
				alert("Error while saving. Please try again.");
				if (typeof failAction == 'function')
					failAction();
			});
	}
	
	function pp_custom_css_js_dev_publish(theFile, successAction, failAction) {
		var request = {
			action: "pp_custom_css_js_dev_publish",
			mode: pp_custom_css_js_dev_mode,
			minify: ($('.pp-custom-css-js-dev-minify-cb').prop('checked') ? 1 : 0)
		};
		if (theFile === null) {
			request.all = 1;
		} else {
			request.file = theFile;
		}
		$.post(pp_custom_css_js_dev_config.api_url, request)
			.done(function(data) {
				if (data.success) {
					for (file in data.data) {
						pp_custom_css_js_dev_data[file].revisions = data.data[file];
						if (file == pp_custom_css_js_dev_file)
							pp_custom_css_js_dev_update_revisions();
					}
					
					if (typeof successAction == 'function')
						successAction();
				} else {
					alert("Error while publishing. Please try again." + (typeof data.data == 'undefined' ? '' : '\n\n' + data.data));
					if (typeof failAction == 'function')
						failAction();
				}
			})
			.fail(function() {
				alert("Error while publishing. Please try again.");
				if (typeof failAction == 'function')
					failAction();
			});
	}
	
	function pp_custom_css_js_dev_save_multiple(filesToSave, successAction, failAction) {
		do {
			if (!filesToSave.length) {
				successAction();
				return;
			}
			var file = filesToSave.shift();
		} while (!pp_custom_css_js_dev_data[file].unsaved);
		
		pp_custom_css_js_dev_save(file, function() {
			pp_custom_css_js_dev_save_multiple(filesToSave, successAction, failAction);
		}, failAction);
	}
	
	function pp_custom_css_js_dev_update_localstorage() {
		if (pp_custom_code_editor_dev_localstorage_dirty) {
			window.localStorage.setItem('pp_custom_' + pp_custom_css_js_dev_mode.toLowerCase() + '-' + pp_custom_css_js_dev_file, pp_custom_code_editor_dev.getValue());
			pp_custom_code_editor_dev_localstorage_dirty = false;
		}
	}
	
});