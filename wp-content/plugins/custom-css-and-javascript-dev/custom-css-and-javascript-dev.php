<?php
/**
 * Plugin Name:       Custom CSS and JavaScript Developer Edition
 * Plugin URI:        https://aspengrovestudios.com/product/custom-css-and-javascript-developer-edition/
 * Description:       Easily add custom CSS and JavaScript code to your WordPress site.
 * Version:           1.0.18
 * Author:            Aspen Grove Studios
 * Author URI:        https://aspengrovestudios.com/?utm_source=custom-css-and-javascript-dev&utm_medium=link&utm_campaign=wp-plugin-author-uri
 * License:           GPLv3
 * License URI:       http://www.gnu.org/licenses/gpl.html

 * AGS Info:          ids.aspengrove 404432 ids.divispace 445651 legacy.key pp_ccjdev_license_key legacy.status pp_ccjdev_license_status adminPage themes.php?page=pp_custom_css_dev
 */

/*
 Custom CSS and JavaScript Developer Edition
Copyright (C) 2022 Aspen Grove Studios

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <https://www.gnu.org/licenses/>.

============

For the text of the GNU General Public License version 3, and licensing/copyright
information for third-party code used in this product, see ./license.txt.

*/



// Constants
define( 'PP_CCJDEV_BRAND_NAME', 'Aspen Grove Studios' );
define( 'PP_CCJDEV_VERSION', '1.0.18' );
define( 'PP_CCJDEV_ACTIVATED', get_option( 'pp_ccjdev_license_status' ) == 'valid' );

if ( PP_CCJDEV_ACTIVATED ) {
	// Frontend scripts
	add_action( 'wp_enqueue_scripts', 'pp_custom_css_js_dev_scripts', 999999 );

	// AJAX actions
	add_action( 'wp_ajax_pp_custom_css_js_dev_save', 'pp_custom_css_js_dev_ajax' );
	add_action( 'wp_ajax_pp_custom_css_js_dev_publish', 'pp_custom_css_js_dev_ajax' );
	add_action( 'wp_ajax_pp_custom_css_js_dev_sort', 'pp_custom_css_js_dev_ajax' );
	add_action( 'wp_ajax_pp_custom_css_js_dev_delete_revision', 'pp_custom_css_js_dev_ajax' );
	add_action( 'wp_ajax_pp_custom_css_js_dev_delete_revisions', 'pp_custom_css_js_dev_ajax' );
	add_action( 'wp_ajax_pp_custom_css_js_dev_load', 'pp_custom_css_js_dev_ajax' );
	add_action( 'wp_ajax_pp_custom_css_js_dev_get_revision', 'pp_custom_css_js_dev_ajax' );
	add_action( 'wp_ajax_pp_custom_css_js_dev_create_file', 'pp_custom_css_js_dev_ajax' );
	add_action( 'wp_ajax_pp_custom_css_js_dev_delete_file', 'pp_custom_css_js_dev_ajax' );
	add_action( 'wp_ajax_pp_custom_css_js_dev_download_file', 'pp_custom_css_js_dev_ajax' );
	add_action( 'wp_ajax_pp_custom_css_js_dev_download_all', 'pp_custom_css_js_dev_ajax' );
	add_action( 'wp_ajax_pp_custom_css_js_dev_upload', 'pp_custom_css_js_dev_ajax' );
	add_action( 'wp_ajax_pp_custom_css_js_dev_rename', 'pp_custom_css_js_dev_ajax' );
	add_action( 'wp_ajax_pp_custom_css_js_dev_set_props', 'pp_custom_css_js_dev_ajax' );

	// Miscellaneous
	add_filter( 'mce_css', 'pp_custom_css_js_dev_mce_css', 999999 );
}

function pp_custom_css_js_dev_scripts() {
	$uploadDir = wp_upload_dir();
	if ( is_ssl() ) {
		$uploadDir['baseurl'] = set_url_scheme( $uploadDir['baseurl'], 'https' );
	}
	if ( current_user_can( 'edit_theme_options' ) ) {
		if ( file_exists( $uploadDir['basedir'] . '/pp-css-js-dev/custom.draft.js' ) ) {
			wp_enqueue_script( 'pp_custom_css_dev_js', $uploadDir['baseurl'] . '/pp-css-js-dev/custom.draft.js', array(), time() );
		}
		wp_enqueue_script( 'pp_custom_js_dev_liveview', plugins_url( 'js/liveview.js', __FILE__ ), array( 'jquery' ), false, true );
		add_action( 'wp_head', 'pp_custom_css_js_dev_wp_head' );
		if ( file_exists( $uploadDir['basedir'] . '/pp-css-js-dev/custom.draft.css' ) ) {
			wp_enqueue_style( 'pp_custom_css_dev_css', $uploadDir['baseurl'] . '/pp-css-js-dev/custom.draft.css', array(), time() );
		}
	} else {
		if ( file_exists( $uploadDir['basedir'] . '/pp-css-js-dev/custom.js' ) ) {
			wp_enqueue_script( 'pp_custom_css_dev_js', $uploadDir['baseurl'] . '/pp-css-js-dev/custom.js', array(), get_option( 'hm_custom_javascript_ver', 1 ) );
		}
		if ( file_exists( $uploadDir['basedir'] . '/pp-css-js-dev/custom.css' ) ) {
			wp_enqueue_style( 'pp_custom_css_dev_css', $uploadDir['baseurl'] . '/pp-css-js-dev/custom.css', array(), get_option( 'hm_custom_css_ver', 1 ) );
		}
	}
}

// This function is only run on wp_had if the current user has edit_theme_options capability
function pp_custom_css_js_dev_wp_head() {
	echo( '<script type="text/javascript">var pp_custom_css_js_dev_sassjs_url = \'' . plugins_url( 'js/sass.js/dist/sass.js', __FILE__ ) . '\';</script>' );
}

add_action( 'admin_menu', 'pp_custom_css_js_dev_admin_menu' );
function pp_custom_css_js_dev_admin_menu() {
	add_theme_page( 'Custom CSS', 'Custom CSS', 'edit_theme_options', 'pp_custom_css_dev', 'pp_custom_css_page' );
	add_theme_page( 'Custom JavaScript', 'Custom JavaScript', 'edit_theme_options', 'pp_custom_js_dev', 'pp_custom_js_page' );

	// Temporarily disabled - work in progress
	//add_options_page('Custom CSS &amp; JS', 'Custom CSS &amp; JS', 'edit_theme_options', 'pp_custom_css_js_dev_settings', 'pp_custom_css_js_dev_settings_page');
}

add_action( 'admin_enqueue_scripts', 'pp_custom_css_js_dev_admin_scripts' );
function pp_custom_css_js_dev_admin_scripts( $hook ) {
	global $pagenow, $post;
	$isPostMode = ( $pagenow == 'post.php' && pp_custom_css_js_dev_post_type_enabled( $post->post_type ) );
	if ( ! $isPostMode && $hook != 'appearance_page_pp_custom_css_dev' && $hook != 'appearance_page_pp_custom_js_dev' ) {
		return;
	}
	if ( PP_CCJDEV_ACTIVATED ) {
		wp_enqueue_script( 'pp_custom_css_js_dev_codemirror', plugins_url( 'codemirror/codemirror.js', __FILE__ ) );
		if ( $hook == 'appearance_page_pp_custom_css_dev' ) {
			wp_enqueue_script( 'pp_custom_css_js_dev_codemirror_mode_css', plugins_url( 'codemirror/mode/css.js', __FILE__ ) );
		} else {
			wp_enqueue_script( 'pp_custom_css_js_dev_codemirror_mode_js', plugins_url( 'codemirror/mode/javascript.js', __FILE__ ) );
		}
		wp_enqueue_script( 'hm_custom_css_js_codemirror_dialog', plugins_url( 'codemirror/addon/dialog/dialog.js', __FILE__ ) );
		wp_enqueue_script( 'hm_custom_css_js_codemirror_matchbrackets', plugins_url( 'codemirror/addon/edit/matchbrackets.js', __FILE__ ) );
		wp_enqueue_script( 'hm_custom_css_js_codemirror_search', plugins_url( 'codemirror/addon/search/search.js', __FILE__ ) );
		wp_enqueue_script( 'hm_custom_css_js_codemirror_searchcursor', plugins_url( 'codemirror/addon/search/searchcursor.js', __FILE__ ) );
		wp_enqueue_script( 'hm_custom_css_js_codemirror_matchhighlighter', plugins_url( 'codemirror/addon/search/match-highlighter.js', __FILE__ ) );
		wp_enqueue_script( 'hm_custom_css_js_codemirror_annotatescrollbar', plugins_url( 'codemirror/addon/scroll/annotatescrollbar.js', __FILE__ ) );
		wp_enqueue_script( 'hm_custom_css_js_codemirror_matchesonscrollbar', plugins_url( 'codemirror/addon/search/matchesonscrollbar.js', __FILE__ ) );

		wp_enqueue_style( 'pp_custom_css_js_dev_codemirror', plugins_url( 'codemirror/codemirror.css', __FILE__ ) );
		wp_enqueue_style( 'hm_custom_css_js_codemirror_dialog', plugins_url( 'codemirror/addon/dialog/dialog.css', __FILE__ ) );
		wp_enqueue_style( 'hm_custom_css_js_codemirror_matchesonscrollbar', plugins_url( 'codemirror/addon/search/matchesonscrollbar.css', __FILE__ ) );

		if ( ! $isPostMode ) {
			wp_enqueue_script( 'jquery-ui-sortable' );
			wp_enqueue_script( 'pp_custom_css_js_dev', plugins_url( 'js/custom-css-and-javascript-dev.js', __FILE__ ) );
			wp_localize_script( 'pp_custom_css_js_dev', 'pp_custom_css_js_dev_config', array(
				'api_url' => esc_url( admin_url( 'admin-ajax.php?ppccj_nonce=' . wp_create_nonce( 'ppccj_ajax' ) ) )
			) );
		}
	}
	wp_enqueue_style( 'pp_custom_css_js_dev', plugins_url( 'css/admin.min.css', __FILE__ ) );
}

function pp_custom_css_js_dev_ajax() {

	ini_set( 'display_errors', 1 );
	if (
		strpos( $_REQUEST['action'], '.' ) === false
		&& current_user_can( 'edit_theme_options' )
		&& isset( $_REQUEST['ppccj_nonce'] )
		&& wp_verify_nonce( $_REQUEST['ppccj_nonce'], 'ppccj_ajax' )
		&& @include_once( dirname( __FILE__ ) . '/ajax/' . substr( $_REQUEST['action'], 21 ) . '.php' )
	) {
		$_REQUEST['action']();
	} else {
		wp_send_json_error();
	}
}

add_action( 'init', 'pp_custom_css_js_dev_init' );
function pp_custom_css_js_dev_init() {
	register_post_type( 'hm_custom_css' );
	register_post_type( 'hm_custom_javascript' );
	/*
	if (!empty($_GET['pp_custom_css_dev_draft'])) {
		$files = get_option('hm_custom_css_files', array());
		$wp_query = new WP_Query(array(
			'post_type' => 'hm_custom_css',
			'post_status' => 'any',
			'nopaging' => true,
			'orderby' => 'none',
			'meta_key' => 'ppccjd_latest'
		));
		$posts = $wp_query->get_posts();
		header('Content-Type: text/css');
		$css = array();
		foreach($posts as $post)
			$css[$post->post_title] = $post->post_content;
		foreach ($files as $file) {
			if (isset($css[$file])) {
				echo($css[$file]."\n");
			}
		}
		exit;
	}
	if (!empty($_GET['pp_custom_js_dev_draft'])) {
		$files = get_option('hm_custom_js_files', array());
		$wp_query = new WP_Query(array(
			'post_type' => 'hm_custom_javascript',
			'post_status' => 'any',
			'nopaging' => true,
			'orderby' => 'none',
			'meta_key' => 'ppccjd_latest'
		));
		$posts = $wp_query->get_posts();
		header('Content-Type: text/javascript');
		$js = array();
		foreach($posts as $post)
			$js[$post->post_title] = $post->post_content;
		foreach ($files as $file) {
			if (isset($js[$file])) {
				echo($js[$file]."\n");
			}
		}
		exit;
	}
	*/
}

function pp_custom_css_page() {
	pp_custom_css_js_dev_page( 'CSS' );
}

function pp_custom_js_page() {
	pp_custom_css_js_dev_page( 'Javascript' );
}

function pp_custom_css_js_dev_page( $mode )
{
if ( isset( $_POST['pp_ccjdev_license_deactivate'] ) ) {
	$result = pp_ccjdev_deactivate_license();
	if ( $result === true ) {
		echo( '<div id="pp_custom_css_js_dev_license_key_success">License key deactivated successfully. The page is reloading.</div>
		<script type="text/javascript">location.reload();</script>' );
		return;
	}
	$licenseKeyDeactivateError = empty( $result ) ? __( 'An unknown error has occurred. Please try again.', 'aspengrove-updater' ) : $result;
} else if ( ! empty( $_POST['license_key'] ) ) {
	check_admin_referer( 'pp_ccjdev_activate' );
	if ( ( $result = pp_ccjdev_activate_license( $_POST['license_key'] ) ) === true ) {
		echo( '<div id="pp_custom_css_js_dev_license_key_success">License key activated successfully. The page is reloading.</div>
		<script type="text/javascript">location.reload();</script>' );
		return;
	}
	$licenseKeyActivateError = empty( $result ) ? __('An error occurred; please try again or contact support.', 'aspengrove-updater') : $result;
}

global $wpdb;
$postType = 'hm_custom_' . strtolower( $mode );
//$files = get_option($postType.'_files', array());

$defaultExt = ( $mode == 'CSS' ? '.css' : '.js' ); ?>

<div id="ags-ccjdev-settings-container" class="wrap">
    <div id="ags-ccjdev-settings">

        <div id="ags-ccjdev-settings-header">
            <div class="ags-ccjdev-settings-logo">
                <h3><?php echo( esc_html( PP_CCJDEV_ITEM_NAME ) ); ?></h3>
            </div>
            <div id="ags-ccjdev-settings-header-links">
                <a id="ags-ccjdev-settings-header-link-support" href="https://support.aspengrovestudios.com/"
                   target="_blank">Documentation</a>
            </div>
        </div>

		<?php if ( PP_CCJDEV_ACTIVATED ) { ?>
            <ul id="ags-ccjdev-settings-tabs">
				<?php if ( $mode === 'CSS' ) : ?>
                    <li class="ags-ccjdev-settings-active"><a href="#editor">Custom CSS</a></li>
				<?php endif;
				if ( $mode === 'Javascript' ) : ?>
                    <li class="ags-ccjdev-settings-active"><a href="#editor">Custom Javascript</a></li>
				<?php endif; ?>
                <li><a href="#license">License</a></li>
            </ul>
		<?php } ?>

        <div id="ags-ccjdev-settings-tabs-content">
			<?php if ( PP_CCJDEV_ACTIVATED ) { ?>
                <div id="pp_custom_css_js_dev_loader">
                    <div id="pp_custom_css_js_dev_loader_inner">
                        <div id="pp-ccjdev-loader-title">
                            <img src="<?php echo plugins_url( 'images/loader.svg', __FILE__ ); ?>" alt="Loading..."
                                 class="loader"/> Loading...
                        </div>
                    </div>
                </div>

                <div id="ags-ccjdev-settings-editor" class="ags-ccjdev-settings-active">
                    <div id="pp_custom_css_js_dev_page" style="display: none;">
                        <script>var pp_custom_css_js_dev_mode = '<?php echo $mode;?>';</script>
                        <div class="nav-tab-wrapper">
							<?php
							/*foreach($files as $file) {
								echo('<a class="nav-tab nav-tab-file" href="#'.urlencode($file).'">'.htmlspecialchars($file).'</a>');
							}*/
							echo( $mode == 'CSS' ?
								'<a class="nav-tab nav-tab-new-file" href="javascript:void(0);">+ <span class="new-file-type">SCSS</span></a>
                                     <a class="nav-tab nav-tab-new-file" href="javascript:void(0);">+ <span class="new-file-type">CSS</span></a>' :
								'<a class="nav-tab nav-tab-new-file" href="javascript:void(0);">+ <span class="new-file-type">JS</span></a>'
							);
							?>
                        </div>
                        <div id="pp-custom-css-js-dev-editor">
                            <div id="pp-custom-css-js-dev-editor-sidebar">
                                <div id="pp-custom-css-js-dev-rename-wrapper">
                                    <input id="pp-custom-css-js-dev-rename-field" type="text"/>
                                    <span id="pp-custom-css-js-dev-rename-ext"></span>
                                </div>
                                <button id="pp-custom-css-js-dev-rename-btn" class="button-secondary">Rename</button>
								<?php if ( $mode == 'CSS' ) { ?>
                                    <hr>
                                    <label id="">
                                        <input id="pp-custom-css-js-dev-tinymce-cb" type="checkbox"/> Add to visual
                                        editor (TinyMCE)
                                    </label>
								<?php } ?>
                                <hr>
                                <button id="pp-custom-css-js-dev-delete-revisions-btn" class="button-secondary">Delete
                                    Draft Revisions
                                </button>
                                <h4>Revisions:</h4>
                                <ul id="pp_custom_css_js_dev_revisions"></ul>
                            </div>
                            <div id="pp_custom_code_editor_dev"></div>
                        </div>

                        <div id="pp-custom-css-js-dev-editor-buttons-bottom">
                            <div>
                                <button type="button" id="pp-custom-css-js-dev-save-btn" class="button-primary"
                                        disabled="disabled">Saved
                                </button>
                                <button type="button" id="pp-custom-css-js-dev-publish-btn" class="button-primary"
                                        disabled="disabled">Publish
                                </button>
                                <button type="button" id="pp-custom-css-js-dev-save-all-btn" class="button-primary"
                                        disabled="disabled">Save All
                                </button>
                                <button type="button" id="pp-custom-css-js-dev-publish-all-btn" class="button-primary"
                                        disabled="disabled">Publish All
                                </button>
                            </div>

                            <label>
								<?php echo '<input type="checkbox" class="pp-custom-css-js-dev-minify-cb"' . ( get_option( $postType . '_minify', true ) ? ' checked="checked"' : '' ) . ' /> Minify output'; ?>
                            </label>

                            <div>
                                <script type="text/javascript">var pp_custom_css_js_dev_siteurl = '<?php echo get_option( 'siteurl' );?>';</script>
                                <button type="button" id="pp-custom-css-js-dev-delete-btn" class="button-secondary">
                                    Delete
                                </button>
                                <span>
                                        <button type="button" id="pp-custom-css-js-dev-download-btn"
                                                class="button-secondary">Download</button>
                                        <button type="button" id="pp-custom-css-js-dev-download-all-btn"
                                                class="button-secondary">Download All</button>
                                    </span>
                                <iframe id="pp-custom-css-js-dev-iframe" name="pp-custom-css-js-dev-iframe"
                                        src="about:blank"></iframe>
                            </div>

                            <form id="pp-custom-css-js-dev-upload-form" action="admin-ajax.php" method="post"
                                  target="pp-custom-css-js-dev-iframe" enctype="multipart/form-data">
                                <input type="hidden" name="action" value="pp_custom_css_js_dev_upload"/>
                                <input type="hidden" name="mode" value="<?php echo $mode; ?>"/>
                                <input type="file" name="upload_file"/>
                                <button type="submit" id="pp-custom-css-js-dev-upload-btn" class="button-secondary">
                                    Upload
                                </button>
                            </form>

                            <div>
								<?php if ( $mode == 'CSS' ) { ?>
                                    <button type="button" id="pp-custom-css-js-dev-liveview-btn"
                                            class="button-secondary">Start Live View
                                    </button>
								<?php } ?>
                                <button type="button" id="pp-custom-css-js-dev-editor-expand-btn"
                                        class="button-primary">Expand Editor
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

			<?php
			$accepted_license = get_option( 'pp_ccjdev_license_key' );
			$display_license  = str_repeat( '*', strlen( $accepted_license ) - 4 ) . substr( $accepted_license, - 4 );
			?>

                <div id="ags-ccjdev-settings-license">
                    <div id="ags-ccjdev_license_key_box">
                        <form action="" method="post" id="ags-ccjdev_license_key_form">
                            <div id="ags-ccjdev_license_key_form_logo_container">
                                <a href="https://aspengrovestudios.com/?utm_source=custom-css-and-javascript-dev&amp;utm_medium=link&amp;utm_campaign=wp-plugin-credit-link"
                                   target="_blank">
                                    <img src="<?php echo( esc_url( plugins_url( 'images/ags-logo.png', __FILE__ ) ) ); ?>"
                                         alt="Aspen Grove Studios"/>
                                </a>
                            </div>
                            <div id="ags-ccjdev_license_key_form_body">
                                <h3>
									<?php echo( esc_html( PP_CCJDEV_ITEM_NAME ) ); ?>
                                    <small>v<?php echo( PP_CCJDEV_VERSION ); ?></small>
                                </h3>
                                <label for="pp_ccjdev_license_activate">
                                    <span><?php esc_html_e( 'License key:', 'aspengrove-updater' ); ?></span>
                                    <input type="text" readonly="readonly"
                                           value="<?php echo( esc_html( $display_license ) ); ?>"/>
                                </label>
								<?php
								if (!empty($licenseKeyDeactivateError)) {
									echo( '<div id="pp_custom_css_js_dev_license_key_error">' . esc_html($licenseKeyDeactivateError) . '</div>' );
								}
								?>
								<?php wp_nonce_field( 'pp_ccjdev_license_deactivate', 'pp_ccjdev_license_deactivate' );
								submit_button( esc_html__( 'Deactivate License Key', 'aspengrove-updater' ), 'button-secondary', 'submit' ); ?>
                            </div>
                        </form>
                    </div>
                </div>

                <script>
                    var ags_ccjdev_tabs_navigate = function () {
                        var tabs = [
                                {
                                    tabsContainerId: 'ags-ccjdev-settings-tabs',
                                    contentIdPrefix: 'ags-ccjdev-settings-'
                                }
                            ],
                            activeClass = 'ags-ccjdev-settings-active';
                        for (var i = 0; i < tabs.length; ++i) {
                            var $tabContent = jQuery('#' + tabs[i].contentIdPrefix + location.hash.substr(1));
                            if ($tabContent.length) {
                                var $tabs = jQuery('#' + tabs[i].tabsContainerId + ' > li');
                                $tabContent
                                    .siblings()
                                    .add($tabs)
                                    .removeClass(activeClass);
                                $tabContent.addClass(activeClass);
                                $tabs
                                    .filter(':has(a[href="' + location.hash + '"])')
                                    .addClass(activeClass);
                                break;
                            }
                        }
                    };
                    if (location.hash) {
                        ags_ccjdev_tabs_navigate();
                    }

                    jQuery(window).on('hashchange', ags_ccjdev_tabs_navigate);
                </script>

			<?php } else {
			?>
                <div id="ags-ccjdev_license_key_box">
                    <form action="" method="post" id="ags-ccjdev_license_key_form">
                        <div id="ags-ccjdev_license_key_form_logo_container">
                            <a href="https://aspengrovestudios.com/?utm_source=custom-css-and-javascript-dev&amp;utm_medium=link&amp;utm_campaign=wp-plugin-credit-link"
                               target="_blank">
                                <img src="<?php echo( esc_url( plugins_url( 'images/ags-logo.png', __FILE__ ) ) ); ?>"
                                     alt="Aspen Grove Studios"/>
                            </a>
                        </div>
                        <div id="ags-ccjdev_license_key_form_body">
                            <h3>
								<?php echo( esc_html( PP_CCJDEV_ITEM_NAME ) ); ?>
                                <small>v<?php echo( PP_CCJDEV_VERSION ); ?></small>
                            </h3>
                            <p><?php esc_html_e( 'Please enter the license key provided when you purchased the plugin:', 'aspengrove-updater' ); ?></p>
                            <label for="pp_ccjdev_license_activate">
                                <span><?php esc_html_e( 'License key:', 'aspengrove-updater' ); ?></span>
                                <input type="text" id="pp_ccjdev_license_key" name="license_key"/>
                            </label>
							<?php
							if (!empty($licenseKeyActivateError)) {
								echo( '<div id="pp_custom_css_js_dev_license_key_error">' . esc_html($licenseKeyActivateError) . '</div>' );
							}
							?>
                            <p class="submit">
                                <button type="submit"><?php esc_html_e( 'Continue', 'aspengrove-updater' ) ?></button>
								<?php wp_nonce_field( 'pp_ccjdev_activate' ); ?>
                            </p>
                        </div>
                    </form>
                </div>

			<?php }
			echo( '</div></div></div>' ); // settings container
			}

			function pp_custom_css_js_dev_sanitize_filename( $filename ) {
				return preg_replace( '/[^a-z0-9_\-\.]/i', '', $filename );
			}

			function pp_custom_css_js_dev_mce_css( $cssFiles ) {
				$uploadDir = wp_upload_dir();
				if ( file_exists( $uploadDir['basedir'] . '/pp-css-js-dev/custom.tinymce.css' ) ) {
					$cssFiles .= ( empty( $cssFiles ) ? '' : ',' ) . $uploadDir['baseurl'] . '/pp-css-js-dev/custom.tinymce.css?t=' . time();
				}

				return $cssFiles;
			}

			function pp_custom_css_js_dev_post_type_enabled( $postType ) {
				$postTypes = get_option( 'pp_ccjdev_post_types', array( 'page' ) );

				return in_array( $postType, $postTypes );
			}

			function pp_custom_css_js_dev_settings_page() {
				$postTypes    = get_option( 'pp_ccjdev_post_types', array( 'page' ) );
				$allPostTypes = get_post_types();
				sort( $allPostTypes );
				?>
                <div class="wrap">
                    <h2>Custom CSS &amp; Javascript Settings</h2>
                    <table class="form-table">
                        <tr>
                            <th scope="row">
                                Post-Specific CSS/JS:
                            </th>
                            <td>
                                <ul>
									<?php foreach ( $allPostTypes as $postType ) { ?>
                                        <li><label>
                                                <input type="checkbox" name="pp_ccjdev[post_types][]"
                                                       value="<?php echo( esc_attr( $postType ) ); ?>"<?php if ( in_array( $postType, $postTypes ) ) {
													echo( ' checked="checked"' );
												} ?>>
												<?php echo( esc_html( $postType ) ); ?>
                                            </label></li>
									<?php } ?>
                                </ul>
                            </td>
                        </tr>
                    </table>
                </div>
				<?php
			}

			// The following code is temporarily disabled - work in progress
			/*
			function pp_custom_css_js_dev_meta_boxes($postType) {
				if (pp_custom_css_js_dev_post_type_enabled($postType)) {
					add_meta_box('pp_custom_css_js_dev', 'Custom CSS &amp; Javascript', 'pp_custom_css_js_dev_meta_box');
				}
			}
			add_action('add_meta_boxes', 'pp_custom_css_js_dev_meta_boxes');

			function pp_custom_css_js_dev_meta_box() {
				include(dirname(__FILE__).'/include/meta-box.php');
			}
			*/
			include_once( dirname( __FILE__ ) . '/lib/licensing/licensing.php' );
			?>