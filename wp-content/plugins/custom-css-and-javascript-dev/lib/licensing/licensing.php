<?php
/**
 * This file contains modified code from and/or based on the Easy Digital Downloads Software Licensing plugin by Easy Digital Downloads
 * Copyright (c) Sandhills Development, LLC
 * Released under the GNU General Public License (GPL) version 2 or later.
 *
 * See the license.txt file in the root directory for more information and licenses
 *
 */

// this is the URL our updater / license checker pings. This should be the URL of the site with EDD installed
define('PP_CCJDEV_STORE_URL', 'https://aspengrovestudios.com'); // you should use your own CONSTANT name, and be sure to replace it throughout this file

// the name of your product. This should match the download name in EDD exactly
define( 'PP_CCJDEV_ITEM_NAME', 'Custom CSS and JavaScript Developer Edition' ); // you should use your own CONSTANT name, and be sure to replace it throughout this file


if (!class_exists('PP_CCJDEV_EDD_SL_Plugin_Updater')) {
    // load our custom updater
    include(dirname(__FILE__) . '/EDD_SL_Plugin_Updater.php');
}

function pp_ccjdev_plugin_updater()
{

    // retrieve our license key from the DB
    $license_key = trim(get_option('pp_ccjdev_license_key'));

    // setup the updater
    $edd_updater = new PP_CCJDEV_EDD_SL_Plugin_Updater(PP_CCJDEV_STORE_URL, dirname(dirname(dirname(__FILE__))) . '/custom-css-and-javascript-dev.php', array(
            'version' => PP_CCJDEV_VERSION,    // current version number
            'license' => $license_key,         // license key (used get_option above to retrieve from DB)
            'item_name' => PP_CCJDEV_ITEM_NAME, // name of this plugin
            'author' => 'Aspen Grove Studios'   // author of this plugin
        )
    );

}

add_action('admin_init', 'pp_ccjdev_plugin_updater', 0);

/*
function pp_ccjdev_sanitize_license( $new ) {
	$old = get_option( 'pp_ccjdev_license_key' );
	if( $old && $old != $new ) {
		delete_option( 'pp_ccjdev_license_status' ); // new license has been entered, so must reactivate
	}
	return $new;
}
*/


/************************************
 * this illustrates how to activate
 * a license key
 *************************************/

function pp_ccjdev_activate_license($license)
{

    $license = trim($license);


    // data to send in our API request
    $api_params = array(
        'edd_action' => 'activate_license',
        'license' => $license,
        'item_name' => urlencode(PP_CCJDEV_ITEM_NAME), // the name of our product in EDD
        'url' => home_url()
    );

    // Call the custom API.
    $response = wp_remote_post(PP_CCJDEV_STORE_URL, array('timeout' => 15, 'sslverify' => false, 'body' => $api_params));

    // make sure the response came back okay
    if (is_wp_error($response) || 200 !== wp_remote_retrieve_response_code($response)) {

        if (is_wp_error($response)) {
            $message = $response->get_error_message();
        } else {
            $message = __('An error occurred, please try again.');
        }

    } else {

        $license_data = json_decode(wp_remote_retrieve_body($response));

        if (false === $license_data->success) {

            switch ($license_data->error) {

                case 'expired' :

                    $message = sprintf(
                        __('Your license key expired on %s.'),
                        date_i18n(get_option('date_format'), strtotime($license_data->expires, current_time('timestamp')))
                    );
                    break;

                case 'revoked' :

                    $message = __('Your license key has been disabled.');
                    break;

                case 'missing' :

                    $message = __('Invalid license key.');
                    break;

                case 'invalid' :
                case 'site_inactive' :

                    $message = __('Your license key is not active for this URL.');
                    break;

                case 'item_name_mismatch' :

                    $message = sprintf(__('This appears to be an invalid license key for %s.'), PP_CCJDEV_ITEM_NAME);
                    break;

                case 'no_activations_left':

                    $message = __('Your license key has reached its activation limit.');
                    break;

                default :

                    $message = __('An error occurred; please try again or contact support.');
                    break;
            }

        }

    }

    if (!empty($message) || $license_data->license != 'valid') {
        delete_option('pp_ccjdev_license_key');
        delete_option('pp_ccjdev_license_status');
        return $message;
    }

    // $license_data->license will be either "valid" or "invalid"

    update_option('pp_ccjdev_license_key', $license, false);
    update_option('pp_ccjdev_license_status', $license_data->license);
    return true;
}


/***********************************************
 * Illustrates how to deactivate a license key.
 * This will decrease the site count
 ***********************************************/

function pp_ccjdev_deactivate_license()
{

    // retrieve the license from the database
    $license = trim(get_option('pp_ccjdev_license_key'));

    // data to send in our API request
    $api_params = array(
        'edd_action' => 'deactivate_license',
        'license' => $license,
        'item_name' => urlencode(PP_CCJDEV_ITEM_NAME), // the name of our product in EDD
        'url' => home_url()
    );

    // Call the custom API.
    $response = wp_remote_post(PP_CCJDEV_STORE_URL, array('timeout' => 15, 'sslverify' => false, 'body' => $api_params));

    // make sure the response came back okay
    if (is_wp_error($response) || 200 !== wp_remote_retrieve_response_code($response)) {

        if (is_wp_error($response)) {
            $message = $response->get_error_message();
        } else {
            $message = __('An error occurred, please try again.');
        }

        $base_url = admin_url('plugins.php?page=' . PP_CCJDEV_PLUGIN_LICENSE_PAGE);
        $redirect = add_query_arg(array('sl_activation' => 'false', 'message' => urlencode($message)), $base_url);

        wp_redirect($redirect);
        exit();
    }

    // decode the license data
    $license_data = json_decode(wp_remote_retrieve_body($response));

    // $license_data->license will be either "deactivated" or "failed"
    if ($license_data->license == 'deactivated') {
        delete_option('pp_ccjdev_license_status');
        return true;
    }

    return false;
}


/************************************
 * this illustrates how to check if
 * a license key is still valid
 * the updater does this for you,
 * so this is only needed if you
 * want to do something custom
 *************************************/
/*
function pp_ccjdev_check_license() {

	global $wp_version;

	$license = trim( get_option( 'pp_ccjdev_license_key' ) );

	$api_params = array(
		'edd_action' => 'check_license',
		'license' => $license,
		'item_name' => urlencode( PP_CCJDEV_ITEM_NAME ),
		'url'       => home_url()
	);

	// Call the custom API.
	$response = wp_remote_post( PP_CCJDEV_STORE_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

	if ( is_wp_error( $response ) )
		return false;

	$license_data = json_decode( wp_remote_retrieve_body( $response ) );

	if( $license_data->license == 'valid' ) {
		echo 'valid'; exit;
		// this license is still valid
	} else {
		echo 'invalid'; exit;
		// this license is no longer valid
	}
}
*/

?>