<?php
/**
 * Test suite for the Pantheon Geolocation Shortcodes.
 *
 * @package Pantheon/EdgeIntegrations
 */

use Pantheon\EI\WP\Shortcodes;
use Pantheon\EI\WP\Geo;
use PHPUnit\Framework\TestCase;

/**
 * Main test class for the Pantheon Geolocation Shortcodes plugin.
 */
class shortcodesTests extends TestCase {
	/**
	 * Setup the test suite.
	 */
	function setUp() : void {
		add_filter( 'pantheon.ei.parsed_geo_data', function() {
			return [
				'country' => 'US',
				'city' => 'Salt Lake City',
				'region' => 'UT',
				'continent' => 'NA',
				'conn-speed' => 'broadband',
				'conn-type' => 'wired',
				'lat' => '40.7',
				'lon' => '-111.9',
			];
		} );

		parent::setUp();
	}

	/**
	 * Test that the plugin is loaded.
	 */
	public function testBootstrap() {
		$this->assertTrue(
			function_exists( 'Pantheon\EI\WP\Shortcodes\bootstrap' ),
			'bootstrap() function does not exist.'
		 );
	}
}
