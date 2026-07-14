<?php
/*
Plugin Name: WC Multiple Email Recipients
Plugin URI: http://conschneider.de/plugin-woocommerce-multiple-email-recipients/
Description: Allows for multiple recipients for WooCommerce E-Mails. Supports WooCommerce Bookings and WooCommerce Subscriptions.
Author: Con Schneider
Author URI: http://conschneider.de/
Version: 1.5.0
Requires at least: 5.0
Requires PHP: 7.4
WC requires at least: 3.0
WC tested up to: 10.9
Text Domain: wc-multiple-email-recipients
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/******************************
* includes
******************************/

include plugin_dir_path( __FILE__ ) . 'includes/functions.php'; // mail sending functions
include plugin_dir_path( __FILE__ ) . 'includes/admin-page.php'; // the plugin options page HTML and save functions

/******************************
* WooCommerce HPOS compatibility
******************************/
add_action( 'before_woocommerce_init', function() {
	if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
	}
} );

/******************************
* useful settings link
******************************/
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'wcme_plugin_settings_link' );

function wcme_plugin_settings_link( $links ) {
	$links[] = '<a href="' . esc_url( get_admin_url( null, 'options-general.php?page=wcme-options' ) ) . '">' . __( 'Settings', 'wc-multiple-email-recipients' ) . '</a>';
	return $links;
}
