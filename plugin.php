<?php
/**
 * Plugin Name: Pantheon Geolocation Shortcodes
 * Description: Used with the Pantheon WordPress Edge Integrations SDK, allows sites to use shortcodes to display geolocated content.
 * Author: Pantheon
 * Author URI: https://pantheon.io
 * Version: 0.2.3-dev
 *
 * @package Pantheon/EdgeIntegrations
 */

namespace Pantheon\EI\WP\Shortcodes;

// Check if the bootstrap function exists. If it doesn't, it means we're not using the Composer autoloader, so load the namespace manually.
if ( ! function_exists( __NAMESPACE__ . '\\bootstrap' ) ) {
	require_once __DIR__ . '/inc/namespace.php';
}

add_action( 'plugins_loaded', __NAMESPACE__ . '\\bootstrap' );
