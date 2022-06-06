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
				__( 'The %1$s plugin was not activated because it requires the %2$s plugin to be installed and active.', 'pantheon-geolocation-shortcodes' ),
				'<strong>' . esc_html__( 'Pantheon Geolocation Shortcodes', 'pantheon-geolocation-shortcodes' ) . '</strong>',
				'<strong>' . esc_html__( 'Pantheon WordPress Edge Integrations', 'pantheon-geolocation-shortcodes' ) . '</strong>'
			);
			printf(
				__( '<a href="%s">View the installation guide</a>.', 'pantheon-geolocation-shortcodes' ),
				'https://github.com/pantheon-systems/edge-integrations-wordpress-sdk'
			)
			?>
		</p>
	</div>
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
	return Geo\get_geo( 'continent-code' );
}

/**
 * Get the geolocated country.
 *
 * @return string The two-letter country code.
 */
function get_country() : string {
	return Geo\get_geo( 'country-code' );
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

/**
 * Output the current continent
 *
 * @return string The continent.
 */
function do_shortcode_continent() : string {
	$continent = get_continent();
	if ( ! empty( $continent ) ) {
		return $continent;
	}

	return '';
}

/**
 * Output the current country
 *
 * @return string The country.
 */
function do_shortcode_country() : string {
	$country = get_country();
	if ( ! empty( $country ) ) {
		return $country;
	}

	return '';
}

/**
 * Output the current region
 *
 * @return string The region.
 */
function do_shortcode_region() : string {
	$region = get_region();
	if ( ! empty( $region ) ) {
		return $region;
	}

	return '';
}

/**
 * Output the current city
 *
 * @return string The city.
 */
function do_shortcode_city() : string {
	$city = get_city();
	if ( ! empty( $city ) ) {
		return $city;
	}

	return '';
}

/**
 * Output a current human-readable location
 *
 * @return string The postal code.
 */
function do_shortcode_location() : string {
	$city = get_city();
	$region = get_region();
	$country = get_country();

	// Check permutations of City/Region/Country.
	if (
		! empty( $city ) &&
		! empty( $region ) &&
		! empty( $country )
	) {
		$location = "$city, $region, $country";
	} elseif (
		! empty( $city ) &&
		! empty( $region )
	) {
		$location = "$city, $region";
	} elseif (
		! empty( $city ) &&
		! empty( $country )
	) {
		$location = "$city, $country";
	} elseif (
		! empty( $region ) &&
		! empty( $country )
	) {
		$location = "$region, $country";
	} elseif (
		! empty( $city )
	) {
		$location = $city;
	} elseif (
		! empty( $region )
	) {
		$location = $region;
	} elseif (
		! empty( $country )
	) {
		$location = $country;
	} else {
		$location = '';
	}

	return $location;
}

/**
 * Output the content filtered by the current location.
 *
 * @param string|array $atts The shortcode attributes.
 * @param string $content The content that comes between the shortcode tags.
 * @return string The HTML content.
 */
function do_shortcode_content( $atts, string $content = '' ) : string {
	$keep = true;
	$test_parameters = [];
	$geos = get_all();

	// If we don't have any geolocation content is empty, just bail and return an empty string.
	if ( empty( $geos ) || empty( $atts ) ) {
		return '';
	}

	foreach ( $atts as $label => $value ) {
		// Set up initial negation parameters.
		$negate = 0;
		$inline_negate = 0;
		$label = $label === 'country' ? 'country-code' : $label;
		$label = $label === 'continent' ? 'continent-code' : $label;

		// Check to see if the attribute has not- or not_ in it.
		$negate = preg_match( '/not?[-_]?(.*)/', $label, $matches );

		// WordPress doesn't like a dash in shortcode parameter labels.
		// Check to see if the value has "not-" in it.
		if ( ! $negate ) {
			$negate = preg_match( '/not?\-([^=]+)\=\"?([^"]+)\"?/', $value, $matches );
			$inline_negate = $negate;
		}

		$label = $negate ? $matches[1] : $label;
		$value = $inline_negate ? $matches[2] : $value;

		if ( ! isset( $geos[ $label ] ) ) {
			continue;
		}

		$test_values = (array) explode( ',', strtolower( $value ) );
		$test_parameters[ $label ] = [
			'test_values' => $test_values,
			'negate' => $negate,
		];
	}

	// Sort the parameters by region type, largest to smallest.
	uksort( $test_parameters, __NAMESPACE__ . '\\compare_location_types' );

	// Loop through the parameters to see if we have a match.
	foreach ( $test_parameters as $label => $parameter ) {
		$test_values = $parameter['test_values'];
		$negate = $parameter['negate'];
		$match_value = strtolower( $geos[ $label ] );

		foreach ( $test_values as &$test_value ) {
			$test_value = strtolower( trim( $test_value, " \t\"." ) );
		}

		$is_match = in_array( $match_value, $test_values, true );
		$is_match = ! $negate ? $is_match : ! $is_match;

		if ( ! $is_match ) {
			$keep = false;
		}
	}

	if ( ! $keep || empty( $test_parameters ) ) {
		return '';
	}

	// Handle any other shortcodes in the content.
	$content = do_shortcode( $content );

	return apply_filters( 'pantheon.ei.geo_content', $content, $atts );
}

/**
 * Compare the location types.
 *
 * Used for sorting location types from largest area to smallest.
 *
 * @param string $a The first location type.
 * @param string $b The second location type.
 * @return int The comparison result.
 */
function compare_location_types( string $a, string $b ) : int {
	$location_types = [
		'continent' => 0,
		'continent-code' => 0,
		'country' => 1,
		'country-code' => 1,
		'region' => 2,
		'city' => 3,
	];

	if ( isset( $location_types[ $a ] ) && isset( $location_types[ $b ] ) ) {
		return $location_types[ $a ] - $location_types[ $b ];
	} else {
		return 0;
	}
}
