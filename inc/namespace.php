<?php
/**
 * Pantheon Geolocation Shortcodes main namespace file.
 *
 * @package Pantheon\EdgeIntegrations
 */

namespace Pantheon\EI\WP\Shortcodes;

use Pantheon\EI\WP\Geo;

/**
 * Kick off the plugin.
 */
function bootstrap() {
	// Check if the WordPress Edge Integrations plugin exists. If it doesn't, we can't use this plugin.
	add_action( 'admin_init', __NAMESPACE__ . '\\check_ei_plugin' );

	// Register the shortcodes.
	add_action( 'init', __NAMESPACE__ . '\\register_shortcodes' );
}

/**
 * Check if the WordPress Edge Integrations plugin exists.
 */
function check_ei_plugin() {
	if (
		is_admin() &&
		current_user_can( 'activate_plugins' ) &&
		! is_plugin_active( 'pantheon-wordpress-edge-integrations/pantheon-wordpress-edge-integrations.php' )
	) {
		add_action( 'admin_notices', __NAMESPACE__ . '\\ei_plugin_notice' );
		deactivate_plugins( plugin_basename( __FILE__ ) );

		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}
	}
}

/**
 * Display a notice if the WordPress Edge Integrations plugin is not installed and active.
 */
function ei_plugin_notice() {
	?>
	<div class="error">
		<p>
			<?php
			printf(
				__( 'The %1$s plugin requires the %2$s plugin to be installed and active.', 'pantheon-geolocation-shortcodes' ),
				'<strong>' . esc_html__( 'Pantheon Geolocation Shortcodes', 'pantheon-geolocation-shortcodes' ) . '</strong>',
				'<strong>' . esc_html__( 'Pantheon WordPress Edge Integrations', 'pantheon-geolocation-shortcodes' ) . '</strong>'
			);
			?>
		</p>
	<?php
}

/**
 * Get all geolocation data.
 *
 * @return array The JSON-decoded geolocation data from the Edge Integrations plugin.
 */
function get_all() : array {
	return json_decode( Geo\get_geo(), true );
}

/**
 * Get the geolocated continent.
 *
 * @return string The two-letter continent code.
 */
function get_continent() : string {
	return Geo\get_geo( 'continent' );
}

/**
 * Get the geolocated country.
 *
 * @return string The two-letter country code.
 */
function get_country() : string {
	return Geo\get_geo( 'country' );
}

/**
 * Get the geolocated region.
 *
 * @return string The two-letter region code.
 */
function get_region() : string {
	return Geo\get_geo( 'region' );
}

/**
 * Get the geolocated city.
 *
 * @return string The city name.
 */
function get_city() : string {
	return Geo\get_geo( 'city' );
}

/**
 * Get the geolocated postal code.
 *
 * Note: As of WordPress Edge Integrations 0.2.x, this parameter is not available.
 *
 * @return string The postal code.
 */
function get_postal_code() : string {
	return Geo\get_geo( 'postal-code' );
}

/**
 * Get the geolocated latitude.
 *
 * Note: As of WordPress Edge Integrations 0.2.x, this parameter is not supported.
 *
 * @return string The latitude.
 */
function get_latitude() : string {
	return Geo\get_geo( 'latitude' );
}

/**
 * Get the geolocated longitude.
 *
 * Note: As of WordPress Edge Integrations 0.2.x, this parameter is not supported.
 *
 * @return string The longitude.
 */
function get_longitude() : string {
	return Geo\get_geo( 'longitude' );
}

/**
 * Get an array of all the shortcodes.
 *
 * @return array An array of all the shortcodes.
 */
function get_shortcodes() : array {
	return [
		'continent' => 'geoip-continent',
		'country' => 'geoip-country',
		'region' => 'geoip-region',
		'city' => 'geoip-city',
		// 'postalcode' => 'geoip-postalcode',
		// 'latitude' => 'geoip-latitude',
		// 'longitude' => 'geoip-longitude',
		'location' => 'geoip-location',
		'content' => 'geoip-content',
	];
}

/**
 * Register the shortcodes.
 */
function register_shortcodes() {
	foreach ( get_shortcodes() as $name => $shortcode ) {
		if ( ! shortcode_exists( $shortcode ) ) {
			add_shortcode( $shortcode, __NAMESPACE__ . "\\do_shortcode_$name" );
		}
	}
}
	}
}
