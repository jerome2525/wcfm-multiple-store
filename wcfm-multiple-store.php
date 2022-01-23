<?php
/**
 * Plugin Name: WCFM Multiple Store 
 * Plugin URI: https://www.eigital.com
 * Description: WCFM Extension that helps to create multiple store from WCFM Plugin.
 * Author: Eigital
 * Version: 1.0.0
 * Author URI: https://www.eigital.com
 *
 * Text Domain: wcfm-multiple-store
 * Domain Path: /lang/
 *
 * WC requires at least: 3.0.0
 * WC tested up to: 3.2.0
 *
 */

if(!defined('ABSPATH')) exit; // Exit if accessed directly

if(!class_exists('WCFM')) return; // Exit if WCFM not installed

/**
 * WCFM - Custom Menus Query Var
 */
function wcfmms_query_vars( $query_vars ) {
	$wcfm_modified_endpoints = (array) get_option( 'wcfm_endpoints' );
	
	$query_custom_menus_vars = array(
		'wcfm-ms'               => ! empty( $wcfm_modified_endpoints['wcfm-ms'] ) ? $wcfm_modified_endpoints['wcfm-ms'] : 'ms',
	);
	
	$query_vars = array_merge( $query_vars, $query_custom_menus_vars );
	
	return $query_vars;
}
add_filter( 'wcfm_query_vars', 'wcfmms_query_vars', 50 );

/**
 * WCFM - Custom Menus End Point Title
 */
function wcfmms_endpoint_title( $title, $endpoint ) {
	global $wp;
	switch ( $endpoint ) {
		case 'wcfm-ms' :
			$title = __( 'Multi Store settings', 'wcfm-custom-menus' );
		break;
		
	}
	
	return $title;
}
add_filter( 'wcfm_endpoint_title', 'wcfmms_endpoint_title', 50, 2 );

/**
 * WCFM - Custom Menus Endpoint Intialize
 */
function wcfmms_init() {
	global $WCFM_Query;

	// Intialize WCFM End points
	$WCFM_Query->init_query_vars();
	$WCFM_Query->add_endpoints();
	
	if( !get_option( 'wcfm_updated_end_point_cms' ) ) {
		// Flush rules after endpoint update
		flush_rewrite_rules();
		update_option( 'wcfm_updated_end_point_cms', 1 );
	}
}
add_action( 'init', 'wcfmms_init', 50 );

/**
 * WCFM - Custom Menus Endpoiint Edit
 */
function wcfmms_custom_menus_endpoints_slug( $endpoints ) {
	
	$custom_menus_endpoints = array(
								'wcfm-ms'        => 'ms',
							);
	
	$endpoints = array_merge( $endpoints, $custom_menus_endpoints );
	
	return $endpoints;
}
add_filter( 'wcfm_endpoints_slug', 'wcfmms_custom_menus_endpoints_slug' );

if(!function_exists('get_wcfmms_custom_menus_url')) {
	function get_wcfmms_custom_menus_url( $endpoint ) {
		global $WCFM;
		$wcfm_page = get_wcfm_page();
		$wcfm_custom_menus_url = wcfm_get_endpoint_url( $endpoint, '', $wcfm_page );
		return $wcfm_custom_menus_url;
	}
}

/**
 * WCFM - Custom Menus
 */
function wcfmms_wcfm_menus( $menus ) {
	global $WCFM;
	
	$custom_menus = array( 'wcfm-ms' => array(   'label'  => __( 'Multi Store', 'wcfm-custom-menus'),
		'url'       => get_wcfmms_custom_menus_url( 'wcfm-ms' ),
		'icon'      => 'store',
		'priority'  => 5.1
	));
	
	$menus = array_merge( $menus, $custom_menus );
		
	return $menus;
}
add_filter( 'wcfm_menus', 'wcfmms_wcfm_menus', 20 );

/**
 *  WCFM - Custom Menus Views
 */
function wcfmms_csm_load_views( $end_point ) {
	global $WCFM, $WCFMu;
	$plugin_path = trailingslashit( dirname( __FILE__  ) );
	
	switch( $end_point ) {
		case 'wcfm-ms':
			require_once( $plugin_path . 'views/wcfm-views-ms.php' );
		break;
		
	}
}
add_action( 'wcfm_load_views', 'wcfmms_csm_load_views', 50 );
add_action( 'before_wcfm_load_views', 'wcfmms_csm_load_views', 50 );

// Custom Load WCFM Scripts
function wcfmms_csm_load_scripts( $end_point ) {
	global $WCFM;
	$plugin_url = trailingslashit( plugins_url( '', __FILE__ ) );
	
	switch( $end_point ) {
		case 'wcfm-ms':
			wp_enqueue_script( 'wcfm_ms_js', $plugin_url . 'js/wcfm-script-ms.js', array( 'jquery' ), $WCFM->version, true );
		break;
	}
}

add_action( 'wcfm_load_scripts', 'wcfmms_csm_load_scripts' );
add_action( 'after_wcfm_load_scripts', 'wcfmms_csm_load_scripts' );

// Custom Load WCFM Styles
function wcfmms_csm_load_styles( $end_point ) {
	global $WCFM, $WCFMu;
	$plugin_url = trailingslashit( plugins_url( '', __FILE__ ) );
	
	switch( $end_point ) {
		case 'wcfm-ms':
			wp_enqueue_style( 'wcfm_ms_css', $plugin_url . 'css/wcfm-style-ms.css', array(), $WCFM->version );
		break;
	}
}
add_action( 'wcfm_load_styles', 'wcfmms_csm_load_styles' );
add_action( 'after_wcfm_load_styles', 'wcfmms_csm_load_styles' );

/**
 *  WCFM - Custom Menus Ajax Controllers
 */
function wcfmms_csm_ajax_controller() {
	global $WCFM, $WCFMu;
	
	$plugin_path = trailingslashit( dirname( __FILE__  ) );
	
	$controller = '';
	if( isset( $_POST['controller'] ) ) {
		$controller = $_POST['controller'];
		
		switch( $controller ) {
			case 'wcfm-ms':
				require_once( $plugin_path . 'controllers/wcfm-controller-ms.php' );
				new WCFM_MS_Controller();
			break;
		}
	}
}
add_action( 'after_wcfm_ajax_controller', 'wcfmms_csm_ajax_controller' );

/**
 *  WCFM - auto login
 */

function wcfmms_auto_login() {
	if( isset( $_GET['ms_store_switch'] ) ) {
		if( !empty( $_GET['ms_store_switch'] ) ) {
			// @TODO: change these 2 items
			$loginpageid   = get_the_ID(); //Page ID of your login page
			$loginusername = $_GET['ms_store_switch']; //username of the WordPress user account to impersonate

			// get this username's ID
			$user = get_user_by( 'login', $loginusername );

			// only attempt to auto-login if at www.site.com/auto-login/ (i.e. www.site.com/?p=1234 ) and a user by that username was found
			if ( ! is_page( $loginpageid ) || ! $user instanceof WP_User ) {
				return;
			}

			$user_id = $user->ID;

			// login as this user
			wp_set_current_user( $user_id, $loginusername );
			wp_set_auth_cookie( $user_id );
			do_action( 'wp_login', $loginusername, $user );

			// redirect to home page after logging in (i.e. don't show content of www.site.com/?p=1234 )
			wp_redirect( get_permalink( get_the_ID() ) );
			exit;
		}
	}
}

add_action( 'wp', 'wcfmms_auto_login', 1 );









